<?php

require 'vendor/autoload.php';
include 'database.php';

// Events
include 'Models/File.php';
include 'Models/Colloque.php';
include 'Models/Price.php';

// Shop
include 'Models/Product.php';

// Rjn
include 'Models/Rjn_abo.php';
include 'Models/Rjn_payement.php';

// JWT encoder
include 'Service/Jwt.php';

// Abo and event helper
include 'Service/Abo.php';
include 'Service/Event.php';

// Users
include 'Models/User.php';
include 'Models/Address.php';
include 'Models/Auth.php';

// App instance
$app = new Slim\Slim(array('mode' => 'development'));

// Load json middleware
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());
//$app->config('debug', false); // avoid render error in html

$ipAuth = function() use ($app)
{
    $granted = array('194.126.200.59','127.0.0.1','130.125.41.184','178.192.238.140');

    if(!in_array($_SERVER['REMOTE_ADDR'],$granted))
    {
        return $app->redirect('/denied');
    }
};

/* *************************************
 * Routes
 ************************************** */

/*
 * Denied access page
 * */
$app->get('/denied', function() use ($app) {
    $app->render(403, array('error' => TRUE,'msg' => 'Vous n\'avez pas access'));
});

/*
 * Events
 * Filters by archive, name, organisateurs
 * */
$app->get('/event', $ipAuth ,function() use ($app) {

    $actif    = (isset($_GET['archive']) ? false : true);
    $name     = (isset($_GET['name']) ? $_GET['name'] : null);
    $centres  = (isset($_GET['centres']) ? $_GET['centres'] : array());

    $event  = new Event();
    $events = $event->getAllEvents($actif,$name);

    $actifs = $event->dispatchEvents($events->toArray(), $centres);

    $data['data'] = (!empty($actifs) ? $actifs : array());

    $app->render(200, $data);

});

/*
 * Event
 * Get event with infos
 * */
$app->get('/event/:id', $ipAuth ,function($id) use ($app) {

    $event    = new Event();
    $colloque = $event->getEvent($id);

    $data['data'] = $colloque->toArray();
    $app->render(200, $data);

});

/*
 * Abonnement numero
 * Test if user has an abo
 * */
$app->get('/abonnement/:numero', $ipAuth ,function($numero) use ($app) {

    $abo   = new Abo();
    $data  = array();

    $user = $abo->getUser($numero);
    $user = $user->first();

    if($abo->aboIsActive($user))
    {
        if (!empty($user->user)) {
            $name = $user->user->first_name . ' ' . $user->user->last_name;
        }

        if (!empty($user->address)) {
            $name = $user->address->first_name . ' ' . $user->address->last_name;
        }

        $data = $user->toArray();
        $data = array_merge($data, array('name' => $name));
    }

    $app->render(200, array('data' => $data));

});

/*
 * Abo Users
 * Broadcast users to see if new ones exist
 * */
$app->get('/users', $ipAuth ,function() use ($app) {

    $abo   = new Abo();

    $users = $abo->getAllUsers();
    $all   = $abo->usersHaveEmail($users);

    $data['data'] = $all->toArray();

    $app->render(200, $data);

});

/*
 * Abo User
 * Get one user by numero
 * */
$app->get('/user/:numero', $ipAuth ,function($numero) use ($app) {

    $abo  = new Abo();

    $user = $abo->getUser($numero);

    $data['data'] = $user->toArray();

    $app->render(200, $data);

});

/*
 * Authenticate User
 * By email and password
 * */
$app->get('/auth/:email/:password', $ipAuth ,function($email,$password) use ($app) {

    $auth  = new Auth();

    $password = $auth->simple_decrypt($password);

    $user     = $auth->authUser($email,$password);

    if(!$user->isEmpty())
    {
        $user = $user->first();
    }

    $data['data'] = $user->toArray();

    $app->render(200, $data);

});

$app->run();
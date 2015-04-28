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

// App instance
$app = new Slim\Slim(array('mode' => 'development'));

// Load json middleware
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());
//$app->config('debug', false); // avoid render error in html

$ipAuth = function() use ($app)
{
    $granted = array('194.126.200.59','127.0.0.1','130.125.41.184');

    if(!in_array($_SERVER['REMOTE_ADDR'],$granted))
    {
        return $app->redirect('/denied');
    }
};

/*
 * Routes
 */

$app->get('/denied', function() use ($app) {

    $app->render(403, array('error' => TRUE,'msg' => 'Vous n\'avez pas access'));

});

$app->get('/abonnement/:numero', $ipAuth ,function($numero) use ($app) {

    $abo   = new Abo();
    $last  = $abo->aboIspayedForUser($numero);
    $payed = ($last ? $last->toArray() : array());

    $app->render(200, array('data' => $payed));

});

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

$app->get('/event/:id', $ipAuth ,function($id) use ($app) {

    $event    = new Event();
    $colloque = $event->getEvent($id);

    $data['data'] = $colloque->toArray();
    $app->render(200, $data);

});

$app->run();
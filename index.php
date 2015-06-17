<?php

require 'vendor/autoload.php';
include 'database.php';
/*
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
include 'Models/Auth.php';*/

// Upload and reader
//include 'Service/Upload.php';
//include 'Service/Reader.php';

use Service\Upload;
use Service\Reader;
use Service\Event;
use Service\Abo;
use Models\Auth;
use Models\User;

// App instance
$app = new Slim\Slim(
    array(
        'mode' => 'development',
        'templates.path' => './views',
        'view'  => new \Slim\Views\Blade()
    )
);

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache'
);

function APIrequest(){
    $app = \Slim\Slim::getInstance();
    $app->view(new \JsonApiView());
    $app->add(new \JsonApiMiddleware());
}

$ipAuth = function() use ($app)
{
    $granted = array('194.126.200.59','127.0.0.1','130.125.41.184');

    if(!in_array($_SERVER['REMOTE_ADDR'],$granted))
    {
        return $app->redirect('/denied');
    }
};

/* *************************************
 * Routes simple
 ************************************** */

// and define the engine used for the view @see http://twig.sensiolabs.org
/*
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("views");

// Twig configuration
$view = $app->view();
$view->parserOptions = array('debug' => true);
$view->parserExtensions = array(new \Slim\Views\TwigExtension());*/

/*
 * Error upload
 * */
$app->get('/error/:message', function($message) use ($app) {
    echo $message;
});

$app->get('/add', function() use ($app) {

/*    $user   = new Models\User();
    $search = new Service\Search();
    $search->addSpecialisation(710, 8);
    $thea = $user->where('uid','=',710)->with(array('specialisation'))->get()->first();
    echo '<pre>';
    print_r($thea->specialisation->lists('id_Specialisation'));
    echo '</pre>';exit;*/
    $reader  = new Service\Reader();

    $data = array(
        array(1,2,34,5),
        array(62,34,75,76)
    );

    $reader->createExcel($data);
});

$app->map('/upload', function() use ($app) {

    $results = array();
    $users   = array();

    $reader  = new Service\Reader();
    $search  = new Service\Search();

    $specialisation = new Models\Specialisation();
    $membre         = new Models\Membre();

    $specialisations = $specialisation->all()->lists('TitreSpecialisation','id_Specialisation');
    $membres         = $membre->all()->lists('TitreMembre','id_Membre');

    if(isset($_POST['specialisation']) && !empty($_POST['specialisation']))
    {
        $search->setSpecialisation($_POST['specialisation']);
    }

    if(isset($_POST['membre']) && !empty($_POST['membre']))
    {
        $search->setMembre($_POST['membre']);
    }

    if(isset($_FILES['file']))
    {
        $results = $reader->uploadFile()->readFile();
        $users   = $search->search($results);
    }

    $data = array(
        'request_uri'     => $_SERVER['REQUEST_URI'],
        'upload_uri'      => 'doUpload',
        'results'         => $users,
        'specialisations' => $specialisations,
        'membres'         => $membres
    );

    $app->render('upload',$data);

})->via('GET', 'POST');


$app->get('/read', function() use ($app) {

    $data = array();

    $app->render('read.php',$data);

});

$app->post('/doUpload', function() use ($app) {

    $upload  = new Upload();

    if(isset($_POST['file'])){

    }

    return $app->redirect('/');

});

/* *************************************
 * Routes API JSON
 ************************************** */

/*
 * Denied access page
 * */
$app->get('/denied', 'APIrequest', function() use ($app) {
    $app->render(403, array('error' => TRUE,'msg' => 'Vous n\'avez pas access'));
});

/*
 * Events
 * Filters by archive, name, organisateurs
 * */
$app->get('/event', 'APIrequest', $ipAuth ,function() use ($app) {

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
$app->get('/event/:id', 'APIrequest',  $ipAuth ,function($id) use ($app) {

    $event    = new Event();
    $colloque = $event->getEvent($id);

    $data['data'] = $colloque->toArray();
    $app->render(200, $data);

});

/*
 * Abonnement numero
 * Test if user has an abo
 * */
$app->get('/abonnement/:numero', 'APIrequest', $ipAuth ,function($numero) use ($app) {

    $abo   = new Abo();
    $last  = $abo->aboIspayedForUser($numero);
    $payed = ($last ? $last->toArray() : array());
    $data  = array();

    if($payed)
    {
        $user = $abo->getUser($numero);
        $user = $user->first();

        if(!empty($user->user)){
            $name =  $user->user->first_name.' '.$user->user->last_name;
        }

        if(!empty($user->address)){
            $name =  $user->address->first_name.' '.$user->address->last_name;
        }

        $data = array_merge($payed,array('name' => $name));
    }

    $app->render(200, array('data' => $data));

});

/*
 * Abo Users
 * Broadcast users to see if new ones exist
 * */
$app->get('/users', 'APIrequest', $ipAuth ,function() use ($app) {

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
$app->get('/user/:numero', 'APIrequest', $ipAuth ,function($numero) use ($app) {

    $abo  = new Service\Abo();

    $user = $abo->getUser($numero);

    $data['data'] = $user->toArray();

    $app->render(200, $data);

});

/*
 * Authenticate User
 * By email and password
 * */
$app->get('/auth/:email/:password', 'APIrequest', $ipAuth ,function($email,$password) use ($app) {

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
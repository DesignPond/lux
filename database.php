<?php
require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(array(
    'driver'      => 'mysql',
    'host'        => 'localhost',
    'database'    => 'test',
    'username'    => 'root',
    'password'    => 'root',
    'charset'     => 'utf8',
    'collation'   => 'utf8_unicode_ci',
   // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
    'prefix'      => ''
));

$capsule->bootEloquent();
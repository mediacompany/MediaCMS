<?php
// load composer

require 'vendor/autoload.php';
// load config global
require 'config.php';
use flight\Engine;
// instance $core scope
$core = new Engine();

session_start();
// $load core of MediaCMS
require 'core.php'; 
//require 'file-functions.php';
$core->route('/', function() use ($core){
    $core->render('home', array('title' => 'Mediahaus - Plantilla', 'classb' => 'home'));
});
$core->start();


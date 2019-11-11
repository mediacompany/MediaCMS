<?php
/* Erors log */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!file_exists('config.php')){
    header('Location: install.php');
    exit;
}

// load basic functions and config
require 'core/functions.php';
require 'config.php';

if (!defined('SITE')) {
    define('SITE', trailingslashit(
        ((!empty($_SERVER['HTTPS']))? 'https://' : 'http://').$_SERVER['HTTP_HOST'].((dirname($_SERVER['PHP_SELF'])=='/') ? '' : dirname($_SERVER['PHP_SELF'])))
    );
}

// load composer and flight
require ABSPATH.'vendor/autoload.php';
use flight\Engine;
$core = new Engine();
session_start();

// $load core of MediaCMS
require ABSPATH.'core/base.php';
require ABSPATH.'module_loader.php';

// Main route
$core->route('/', function() use ($core){
    $core->render('home', array('title' => 'Mediahaus - Plantilla', 'classb' => 'home'));
});


$core->start();
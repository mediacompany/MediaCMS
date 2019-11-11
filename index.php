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


if(!file_exists('config.json')){
   die("No se encuentra el archivo config.json y es fundamental para el funcionamiento de Media CMS. Por favor, lea la documentacion, y en caso de que lo haya eliminado vuelva a crearlo respetando la sintaxis.");
}else{
    $config_file = json_decode(file_get_contents('config.json'));
    // Comprobamos la existencia de los directorios y sino los creamos.
    foreach($config_file->assets_route as $type => $route){
        if($type == 'libraries'){
            continue;
        }
        // ruta principal
        if(!file_exists(ABSPATH.$route)){
            mkdir(ABSPATH.$route,0755);
        }
        // dir para css y js
        if(!file_exists(ABSPATH.$route.'css')){
            mkdir(ABSPATH.$route.'css',0755);
        }
        if(!file_exists(ABSPATH.$route.'js')){
            mkdir(ABSPATH.$route.'js',0755);
        }

        // consultamos la existencia de los archivos princiaples y sino los creamos
        foreach($config_file->assets->css->$type as $files){
            if(!file_exists(ABSPATH.$route.'css/'.$files)){
                $inital_content = " /* Archivo generado automaticamente por MediaCMS */ \n /* Todos los derechos reservados por MediaHaus by MediaCo. */ \n /* Nombre del archivo: $files */";
                file_put_contents(ABSPATH.$route.'css/'.$files,$inital_content);
            }
        }
        foreach($config_file->assets->js->$type as $files){
            if(!file_exists(ABSPATH.$route.'js/'.$files)){
                $inital_content = " /* Archivo generado automaticamente por MediaCMS */ \n /* Todos los derechos reservados por MediaHaus by MediaCo. */ \n /* Nombre del archivo: $files */";
                file_put_contents(ABSPATH.$route.'js/'.$files,$inital_content);
            }
        }

    }
}

// Main route
$core->route('/', function() use ($core){
    $core->render('home', array('title' => 'Mediahaus - Plantilla', 'classb' => 'home'));
});
$core->start();
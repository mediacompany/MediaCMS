<?php
require 'vendor/autoload.php';
require 'config.php';
use flight\Engine;
$app = new Engine();
session_start();
require 'helper.php';
require 'file-functions.php';
require 'admin.php';

$app->start();
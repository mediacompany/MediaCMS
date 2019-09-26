<?php
/** Absolute path to site directory. */
define('ABSPATH', dirname(__FILE__) . '/');
define('SITE', ((!empty($_SERVER['HTTPS']))? "https://" : "http://").$_SERVER['HTTP_HOST'].((dirname($_SERVER['PHP_SELF'])=="/") ? "" : dirname($_SERVER['PHP_SELF'])));
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','root');
define('DBNAME','biglieri');
define('DBPREFIX','mc');
define('SITENAME',' - MediaBack');
$roles = array(1 => 'Usuario', 4 => 'Nivel 3', 6 => 'Nivel 2', 8 => 'Nivel 1', 10 => 'Administrador');
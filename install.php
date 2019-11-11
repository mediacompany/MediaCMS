<?php
if (file_exists('config.php')) {
	die('Por favor elimina este archivo, MediaCMS, ya está instalado.');
}
if (file_exists('.htaccess')) {
	die('Por favor elimina el archivo .htaccess, para poder instalar MediaCMS.');
}
$paso = 0;
$text_btn = ['Probar DB','Instalar CMS'];
$text_guide = ['Probar conexion con Base de datos','Installar y configurar CMS','Instalación finalizada, recuerda eliminar el archivo install.php, usuario y contraseña por defecto: admin'];
session_start();
if (!empty($_POST['servername']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['dbname'])) {
	$servername = $_POST['servername'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$dbname = $_POST['dbname'];
	$prefix = $_POST['prefix'];
	try {
	    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   	$paso = 1;
	   	$_SESSION['mccms_install001'] = array(
	   		'servername' => $servername,
	   		'username' => $username,
	   		'password' => $password,
	   		'dbname' => $dbname,
	   		'prefix' => $prefix
	   	);
	}catch(PDOException $e){
	    	echo "Por favor revisa los datos de la DB esten correctos.<br>";
	    	echo "Connection failed: " . $e->getMessage();
	}
}
if (!empty($_POST['install_finish'])) {
	$db = $_SESSION['mccms_install001'];
	$prefix = $db['prefix'];
	try {
	    $conn = new PDO("mysql:host=".$db['servername'].";dbname=".$db['dbname'], $db['username'], $db['password']);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql_create_users = "
			CREATE TABLE IF NOT EXISTS `{$prefix}_users` (
			  `ID` int(11) NOT NULL AUTO_INCREMENT,
			  `user_user` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `user_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `user_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `user_birthday` date NOT NULL,
			  `user_level` tinyint(10) NOT NULL,
			  `user_info` text COLLATE utf8mb4_unicode_ci NOT NULL,
			  `user_register` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `user_ustate` tinyint(10) NOT NULL,
  				PRIMARY KEY (`ID`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			COMMIT;
	    ";
	    $conn->exec($sql_create_users);
	    $creado = date('Y-m-d H:i:s');
	    $admin = '$2y$10$vwzV5hzKVn6FDSydOzaOR.SPNRHpIBmBCyxiOoCH.GMD0PCW/O5TS';
	    $sql_create_admin = "INSERT INTO `{$prefix}_users` 
	    (`user_user`, `user_password`, `user_name`, `user_email`, `user_phone`, `user_birthday`, `user_level`, `user_info`, `user_register`, `user_ustate`) VALUES 
	    ('admin', '{$admin}', 'System Administrator', 'jose.marin@mediahaus.com.ar', '1128951853', '2019-07-10', 10, 'Nothing', '{$creado}', 1)";
		$conn->exec($sql_create_admin);
		$htaccess_data = "RewriteEngine On
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.*)$ index.php [QSA,L]";
		file_put_contents('.htaccess', $htaccess_data);
		$config_php = "<?php
		define('ABSPATH', trailingslashit(dirname(__FILE__)));
		define('DBHOST','".$db['servername']."');
		define('DBUSER','".$db['username']."');
		define('DBPASS','".$db['password']."');
		define('DBNAME','".$db['dbname']."');
		define('DBPREFIX','".$db['prefix']."');
		define('SITENAME',' - ".$_POST['sitename']."');
		define('ROL',array(1 => 'Usuario', 4 => 'Nivel 3', 6 => 'Nivel 2', 8 => 'Nivel 1', 10 => 'Administrador'));";
		file_put_contents('config.php', $config_php);
		$paso= 2;
		$_SESSION['mccms_install001'] = null;
	}catch(PDOException $e){
	    	echo "Algo salio mal en la instalacion.<br>";
	    	echo "Connection failed: " . $e->getMessage();
	}
	echo "instalacion realizada con éxito";
	header('Location: /');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>MediaCMS - Install</title>
	<style type="text/css">
		body{
			margin:0;
			padding: 30px 0;
			font-family: Arial, Helvetica, sans-serif;
		}
		form {
			width: 50%;
			margin: 0 auto;
			display: block;
		}
		input{
			width: 100%;
			line-height: 32px;
		}
		label{
			font-weight: bold;
			margin: 10px 0;
			display: block;
		}
		button{
			cursor: pointer;
			display: block;
			-webkit-appearance:none;
			margin: 20px auto;
		    padding: 12px 25px;
		    border: 2px solid #009EE3;
		    background: #009EE3;
		    border-radius: 30px;
		    color:#fff;
		    transition: all .3s;
		}
		button:hover{
		    background: #fff;
		    color:#000;
		}
	</style>
</head>
<body>
	<form method="POST" action="">
		<h1><?php echo $text_guide[$paso]; ?></h1>
		<?php if($paso == 0):?>
			<label>Host:</label>
			<input type="text" name="servername" value="localhost" placeholder="localhost" required>
			<label>DB Name:</label>
			<input type="text" name="dbname" value="" placeholder="Nombre de la DB" required>
			<label>DB Prefix:</label>
			<input type="text" name="prefix" value="mc" placeholder="Prefix de la DB" required>
			<label>DB User:</label>
			<input type="text" name="username" value="" placeholder="Usuario de la DB" required>
			<label>DB Password:</label>
			<input type="text" name="password" value="" placeholder="Contraseña de la DB" required>
		<?php endif;?>
		<?php if($paso == 1):?>
			<input type="hidden" name="install_finish" value="1">
			<label>SITENAME:</label>
			<input type="text" name="sitename" value="" placeholder="Nombre del Sitio" required>
		<?php endif;?>
		<?php if($paso != 2):?>
		<button type="submit"><?php echo $text_btn[$paso]; ?></button>
		<?php endif;?>
	</form>
</body>
</html>
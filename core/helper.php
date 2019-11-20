<?php
$core->set('config_file',json_decode(file_get_contents(SITE.'config.json')));
$core->set('flight.log_errors', true);
$core->set('modules', array());
$core->set('admin_js', array());
$core->set('admin_css', array());
$core->register('db', 'PDO', array("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER,DBPASS),
    function($db){
	    try {
	        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	        $db->exec("SET NAMES 'utf8mb4';");
	    } catch (PDOException $e) {
	        echo $e->getMessage();
	        die();
	    }
    }
);

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

/**
 * @param int $page = 1, 1-> front, 2-> user, 3->core
 * @param bool $css = false , true si quiere imprimir css en etiquetas de insercion html
 * @param bool $js = false, true si quiere imprimir js en etiquetas de insercion html
 * @param bool $libraries = true, true si quiere incluir la impresion de librerias
 * 
 */
$core->map('print_assets', function($page = 1,$css = false,$js = false,$lib = false) use ($core){


    echo "<!-- Print Assets -->";
    switch($page){
        case 1: // print front files
            css_print('front',$css,$lib);
            js_print('front',$js,$lib);
        break;
        
        case 2: // print user files
            css_print('user',$css,$lib);
            js_print('user',$js,$lib);
        break;

        case 3: // print admin files
            css_print('admin',$css,$lib);
            js_print('admin',$js,$lib);
        break;
    }
}); 
function css_print($type,$print,$lib){
    global $core;
    $config_file = $core->get('config_file');
    if($print){
        echo "<!-- CSS Files -->";
        if($lib){
            foreach($config_file->assets->css->library as $files){
                echo "<link rel='stylesheet' href='".SITE.$config_file->assets_route->libraries."css/".$files."' />";
            }
        }
        foreach($config_file->assets->css->$type as $files){
            echo "<link rel='stylesheet' href='".SITE.$config_file->assets_route->$type."css/".$files."' />";
        }

    }
}
function js_print($type,$print,$lib){
    global $core;
    $config_file = $core->get('config_file');
    if($print){
        echo "<!-- JS Files -->";
        if($lib){
            foreach($config_file->assets->js->library as $files){
                echo "<script src='".SITE.$config_file->assets_route->libraries."js/".$files."'></script>";
            }
        }
        foreach($config_file->assets->js->$type as $files){
            echo "<script src='".SITE.$config_file->assets_route->$type."js/".$files."'></script>";
        }
    }
}

require ABSPATH.'core/db-manager.php';
$core->map('checkAuthPermission',function($area = array()) use ($core){
    if(empty($_SESSION['mcb_user'])){
        $core->redirect('/');  
        die();
    }
    if( !empty($_SESSION['mcb_user']) && !(in_array($_SESSION['mcb_user']['level'], $area))  ){
        $core->set('flight.views.path', ABSPATH.'core/views');
        $core->render('noaccess', array(
                'title' => 'No Access - MediaCore',
                'section' => 'noaccess',
                'classb' => 'noaccess',
                'section_title' => 'No tenés permitido estar aquí! 🙈'
        ));
        die();
    }
});

$core->map('checkAuthPermission_ajax',function($area = array()) use ($core){
    if(empty($_SESSION['mcb_user'])){
        $core->json(array('error' => true,'state' => 0,'message' => 'you cannot use this ajax without logged in.','param' => null)); 
        die();
    }
    if( !empty($_SESSION['mcb_user']) && !(in_array($_SESSION['mcb_user']['level'], $area))  ){
        $core->json(array('error' => true,'state' => 0,'message' => 'you cannot use this ajax because dont have perrmission here.','param' => null));
        die();
    }
});
/*
$core->map('checkAuth',function($lvl) use ($core){
    $core->set('flight.views.path', ABSPATH.'core/views');
    if(empty($_SESSION['mcb_user'])){
        $core->render('notLogedIn', array('title' => 'Mediahaus - No Access', 'section' => 'noadmin'));
        die();
    }
    if( !empty($_SESSION['mcb_user']) && $_SESSION['mcb_user']['lvl'] < $lvl ){
        $core->render('notLvl', array('title' => 'Mediahaus - No Access', 'section' => 'nolevel', 'sub_section' => ''));
        die();
    }
});
*/


/**
 * Funcion add_menu_page();
 * Funcion para agregar modulos a media CMS
 * @author Ramiro Macciuci - MediaHaus by MediaCo. 2019 - ramiro.macciuci@mediahaus.com.ar
 * @version 1.0.0
 * @copyright 2019 MediaHaus by MediaCo.
 * @link https://www.mediahaus.com.ar
 * @since Available since release 1.0.0
 * 
 * -- PARAMETROS --
 * @param string (required) $page_title = El texto que se mostrará en las etiquetas de título de la página cuando se seleccione el menú.
 * 
 * @param array (required) $menu_title = como key el nombre del archivo principal, como value el nombre a mostrar en el menu
 * 
 * @param array  (optional) $capability = Niveles de autenticacion del usuario para mostrarle este modulo, si no se especifica se mostrara a todos los usuarios.
 * @example $capability = Array(10,8,1) -> se les mostrara a los usuarios con nivel de autenticacion 10 8 y 1
 * 
 * @param string (invocable) (opcional) $function -> funcion para invocar en el momento que se carga el modulo.
 * 
 * @param int (position) $position -> numero de orden en el menu, default = 0
 * 
 * @param array $submenu (optional) -> submenu opcional para mostrar, como key nombre de la pagina a mostrar, como value, nombre a mostrar en el submenu
 * 
 * @param string $dir (required) -> es necesario declarar constante __DIR__
 * 
 * Esta funcion definira las rutas automaticamente para generar todas las vistas necesarias para el funcionamiento del modulo. Tanto de la vista principal como de las subpaginas.
 * 
 * @return array rutas definidas automaticamente por la funcion, only for developers
 * 
 * @example $routes = $core->add_menu_page('Example',array('admin'=>'Main example'),array(10),'',0,array('subexample' => 'Sub Menus'),__DIR__);
 * 
 *  */
 $core->map('add_menu_page',function($page_title,$menu_title = array(),$capability = array(10,8,6,1),$function = "",$position = 0, $submenu = array(), $dir = __DIR__) use ($core){
    // invocamos la funcion especificada
    if(!empty($function)){
        $function();
    }
    if(empty($page_title) || !is_array($menu_title) || empty($menu_title) || empty($capability)){
        die("Error en la sintaxis de la funcion");
    }
    $data = array(
        'module_title' => $page_title,
        'menu_title' => $menu_title[array_key_first($menu_title)],
        'module_url' => array_key_first($menu_title),
        'capability' => $capability,
        'submenu' => $submenu,
        'dir' => $dir
    );
    $routes = array('main' => 'GET | '.SITE.'admin/'.$data['module_url']);
    $core->route('GET /admin/'.$data['module_url'], function() use ($core,$data){
        $core->checkAuthPermission($data['capability']);
        $core->render_admin(
            array(
                'title' => $data['module_title'],
                'classb' => 'module'.$data['module_url'],
                'section' => 'module'.$data['module_url'],
                'section_title' => $data['menu_title'],
                'module_file' => $data['dir'].'/'.$data['module_url'].'.php',
                'data' => (object) array('ID' => 0, 'foo' => 'string'),
                'foo' => 'bar'
            )
        );
    });

    if(!empty($submenu)){
        foreach($submenu as $url => $submenu){
            $temporaldata = array(
                'url' => $url,
                'name' => $submenu
            );
            $routes[$submenu] = 'GET | '.SITE.'admin/'.$data['module_url'].'/'.$url;
            $core->route('GET /admin/'.$data['module_url'].'/'.$url, function() use ($core,$data,$temporaldata){
                $core->checkAuthPermission($data['capability']);
                $core->render_admin(
                    array(
                        'title' => $temporaldata['name'],
                        'classb' => 'module'.$data['module_url'],
                        'section' => 'module'.$data['module_url'],
                        'section_title' => $data['menu_title'],
                        'module_file' => $data['dir'].'/'.$temporaldata['url'].'.php',
                        'data' => (object) array('ID' => 0, 'foo' => 'string'),
                        'foo' => 'bar'
                    )
                );
            });
            $temporaldata = "";
        }
    }
    $modules = $core->get('modules');
    if(!empty($position) && $position != 0){
        if(array_key_exists($position,$modules)){
            die("La posicion especificada, ya existe registrada en el CMS");
        }
        $modules[$position] = $data;
    }else{
        $modules[] = $data;
    }
    $core->set('modules',$modules);
    return $routes;
 });

$core->map('register_module',function($module_data = array()) use ($core){
    $modules = $core->get('modules');
    $modules[$module_data['order']] = $module_data;
    $core->set('modules',$modules);
});

$core->map('send_mail',function($body = "<h1>Media CMS</h1>",$subject = "Media CMS",$to = array(),$reply = array()) use ($core){
    $mail = new PHPMailer();
    try {
        //server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'mail.mediacore.com.ar';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'enviador@mediacore.com.ar';
        $mail->Password = 'HScHO}WE)(mE';
        $mail->CharSet = 'UTF-8';

        //Contents
        $mail->Subject = $subject;
        $mail->msgHTML($body);
        $mail->AltBody = '';
        $mail->Body = $body;
        
        // recipients
        $mail->setFrom('enviador@mediacore.com.ar', 'Media CMS');
        if(!empty($to)){
            foreach($to as $mailto => $name){
                $mail->addAddress($mailto,$name);
            }
        }
        if(!empty($reply)){
            $mail->addReplyTo(array_key_first($reply),$reply[array_key_first($reply)]);
        }

        // send mail
        $mail->send();
        echo "<script>console.log('mensaje enviado');</script>";
    }catch (Exception $e){
        echo "<script>console.log('el mensaje no se pudo enviar. Error: {$mail->ErrorInfo}');</script>";
    }
});
  
/**
     * Funcion para solicitar cambio de contraseña, ingresamos el mail, y generara una nueva contraseña
     * @param string $mail = email de cuenta para generar nueva contraseña
     */
$core->map('renovate_password',function($mail) use ($core){
    if(!$core->get_row('users','*',"WHERE user_email = '{$mail}'")){
        die('usuario inexistente');
    }
    // generamos la nueva contraseña
    $new_password = substr(md5(microtime()),0,10);

    // almacenamos la nueva contraseña en la base de datos
    $data = array(
        "user_password" => password_hash($new_password,PASSWORD_DEFAULT)
    );
    $core->update_on('users',$data,array("user_email" => "'$mail'"));

    // enviamos un mail con la nueva contraseña
    $subject_to = "Contraseña actualizada en MediaCMS";
    $body_to = "<h1>Se ha actualizado tu contraseña en media CMS</h1> \n
                <h4 style='color:#000;'>Nueva contraseña <strong style='color:#f00;'>$new_password</strong></h4>
                <a href='".SITE."admin'>Ingresa presionando aqui</a>";
    $core->send_mail($body_to,$subject_to,array($mail => "Usuario"));
});

$core->map('prettyDate',function($date){
    $date_source = explode('-', date ( 'Y-n-j' , strtotime($date)));
    $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    echo $date_source[2].' de '.$meses[$date_source[1]].', '.$date_source[0];
});

$core->map('file_upload',function($file_data=null,$folder=null,$set_name=null,$max_file_size=1,$max_file_size_measurment='MB'){
    if (!is_array($file_data)) {
        return  array('error' => 'yes', 'message' => 'File data is invalid!');
    }
    if ($file_data['size'] > $max_file_size*MULTIPLIER[$max_file_size_measurment]) {
        return  array('error' => 'yes', 'message' => 'File size exceeds the limit of: '.$max_file_size.$max_file_size_measurment);
    }
    if (empty($folder)) {
        return  array('error' => 'yes', 'message' => 'Folder to upload is empty!');
    }
    if (!file_exists(ABSPATH.$folder)) {
        mkdir(ABSPATH.$folder, 0755);
    }
    $filename = strtolower( pathinfo($file_data['name'], PATHINFO_FILENAME) );
    $ext = strtolower( pathinfo($file_data['name'], PATHINFO_EXTENSION) );
    if (!empty($set_name)) {
        $file_name = slugify($set_name). '.' . $ext;
    }else{
        $file_name = slugify($filename). '.' . $ext;
    }
    $final_file = ABSPATH . trailingslashit($folder) . $file_name;
    move_uploaded_file($file_data["tmp_name"], $final_file);
    return array('error'=>'no','message'=>'File uploaded succesfully','path'=>$final_file,'url'=>SITE.trailingslashit($folder).$file_name);
});

$core->map('header',function() use ($core){
    if (!empty($core->get('admin_css'))) {
        foreach ($core->get('admin_css') as $key => $value) {
            echo "<link rel='stylesheet' href='".SITE.$value."'>";
        }
    }
});

$core->map('footer',function() use ($core){
    if (!empty($core->get('admin_js'))) {
        foreach ($core->get('admin_js') as $key => $value) {
            echo "<script src='".SITE.$value."'></script>";
        }
    }
});

$core->map('header_front',function() use ($core){
    if (!empty($core->get('admin_css'))) {
        foreach ($core->get('admin_css') as $key => $value) {
            echo "<link rel='stylesheet' href='".SITE.$value."'>";
        }
    }
});

$core->map('footer_front',function() use ($core){
    if (!empty($core->get('admin_js'))) {
        foreach ($core->get('admin_js') as $key => $value) {
            echo "<script src='".SITE.$value."'></script>";
        }
    }
});

$core->map('notify_helper',function(){
    $notifycations = array();
    if (!empty($_SESSION['system_notify'])) {
        $notifycations = $_SESSION['system_notify'];
    }
    if (!empty($notifycations)) {
        foreach ($notifycations as $key => $notify) {
            // se deja expire para notificaciones largas a futuro
            //if ($notify['expire'] < $actualtime ) {
            echo "
                <div class='alert alert-{$notify['type']} alert-dismissible'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                  {$notify['text']}
                </div>
            ";
        }
        $_SESSION['system_notify'] = array();
    }
});

$core->map('notify_helper_add',function($notify){
    if (empty($_SESSION['system_notify'])) {
        $_SESSION['system_notify'] = array();
    }
    array_push($_SESSION['system_notify'], $notify);
});

$core->map('render_admin',function($data = array()) use ($core){
    $core->set('flight.views.path', ABSPATH.'core/views');
    $core->render('global',$data);
});

$core->map('display_pagination',function($data = array()) use ($core){
    //$core->set('flight.views.path', ABSPATH.'/core/views');
    //$core->render('global',$data);
    echo '
        <nav aria-label="Page navigation" class="text-right">
          <ul class="pagination">
            <li>
              <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li>
              <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
    ';
});
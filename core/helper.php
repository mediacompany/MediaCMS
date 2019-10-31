<?php
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
$core->map('register_module',function($module_data = array()) use ($core){
    $modules = $core->get('modules');
    $modules[$module_data['order']] = $module_data;
    $core->set('modules',$modules);
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
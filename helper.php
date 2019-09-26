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
require ABSPATH.'/core/db-manager.php';
$core->map('checkAuthPermission',function($area = array()){
    global $core;
    if(empty($_SESSION['mcb_user'])){
        $core->redirect('/');  
        die();
    }
    if( !empty($_SESSION['mcb_user']) && !(in_array($_SESSION['mcb_user']['level'], $area))  ){
        $core->set('flight.views.path', ABSPATH.'/core/views');
        $core->render('noaccess', array(
                'title' => 'No Access - MediaCore',
                'section' => 'noaccess',
                'classb' => 'noaccess',
                'section_title' => 'No tenés permitido estar aquí! 🙈'
        ));
        die();
    }
});
$core->map('checkAuth',function($lvl){
    global $core;
    $core->set('flight.views.path', ABSPATH.'/core/views');
    if(empty($_SESSION['mcb_user'])){
        $core->render('notLogedIn', array('title' => 'Mediahaus - No Access', 'section' => 'noadmin'));
        die();
    }
    if( !empty($_SESSION['mcb_user']) && $_SESSION['mcb_user']['lvl'] < $lvl ){
        $core->render('notLvl', array('title' => 'Mediahaus - No Access', 'section' => 'nolevel', 'sub_section' => ''));
        die();
    }
});
$core->map('register_module',function($module_data = array()){
    global $core;
    $modules = $core->get('modules');
    $modules[$module_data['order']] = $module_data;
    $core->set('modules',$modules);
});
$core->map('prettyDate',function($date){
    $date_source = explode('-', date ( 'Y-n-j' , strtotime($date)));
    $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    echo $date_source[2].' de '.$meses[$date_source[1]].', '.$date_source[0];
});

$core->map('file_upload',function($file_data = null,$folder = null,$set_name = null,$max_file_size = 1,$max_file_size_measurment = 'MB', $ext = 'jpg' ){
    $multipier = ['KB' => 1024, 'MB' => 1048576, 'GB' => 1073741824, 'TB' => 1099511627776];
    //var_dump($max_file_size*$multipier[$max_file_size_measurment]);die();
    if (!is_array($file_data)) {
        return  array('error' => 'yes', 'message' => 'File data is invalid!');
    }
    if ($file_data['size'] > $max_file_size*$multipier[$max_file_size_measurment]) {
        return  array('error' => 'yes', 'message' => 'File size exceeds the limit of: '.$max_file_size.$max_file_size_measurment);
    }
    if (!empty($set_name)) {
        $file_name = $set_name. '.' . $ext;
    }else{
        $temp = explode(".", $file_data["name"]);
        $file_name = $temp[0]. '.' . $ext;
    }
    move_uploaded_file($file_data["tmp_name"], ABSPATH . $folder . $file_name );
    return array('error' => 'no', 'message' => 'File uploaded succesfully', 'path' => ABSPATH . $folder . $file_name, 'url' => SITE .'/'. $folder . $file_name );

});

$core->map('header',function(){
    global $core;
    if (!empty($core->get('admin_css'))) {
        foreach ($core->get('admin_css') as $key => $value) {
            echo "<link rel='stylesheet' href='".SITE.$value."'>";
        }
    }
});

$core->map('footer',function(){
    global $core;
    if (!empty($core->get('admin_js'))) {
        foreach ($core->get('admin_js') as $key => $value) {
            echo "<script src='".SITE.$value."'></script>";
        }
    }
});

$core->map('header_front',function(){
    global $core;
    if (!empty($core->get('admin_css'))) {
        foreach ($core->get('admin_css') as $key => $value) {
            echo "<link rel='stylesheet' href='".SITE.$value."'>";
        }
    }
});

$core->map('footer_front',function(){
    global $core;
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

$core->map('render_admin',function($data = array()){
    global $core;
    $core->set('flight.views.path', ABSPATH.'/core/views');
    $core->render('global',$data);
});

/**
 * Load the MediaCompany modules.
 *
 * custom plugins, MediaCompany
 *
 * @since 1.0.0
 *
 */

foreach(glob('modules/*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace('modules/', '', $dir);
    $filename = 'modules/'.$dir.'/'.$dir.'.php';
    include $filename;
}

/**
 * Outputs the html checked attribute.
 *
 * Compares the first two arguments and if identical marks as checked
 *
 * @since 1.0.0
 *
 * @param mixed $checked One of the values to compare
 * @param mixed $current (true) The other value to compare if not just true
 * @param bool  $echo    Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function checked( $checked, $current = true, $echo = true ) {
    return __checked_selected_helper( $checked, $current, $echo, 'checked' );
}

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected
 *
 * @since 1.0.0
 *
 * @param mixed $selected One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function selected( $selected, $current = true, $echo = true ) {
    return __checked_selected_helper( $selected, $current, $echo, 'selected' );
}

/**
 * Outputs the html disabled attribute.
 *
 * Compares the first two arguments and if identical marks as disabled
 *
 * @since 3.0.0
 *
 * @param mixed $disabled One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function disabled( $disabled, $current = true, $echo = true ) {
    return __checked_selected_helper( $disabled, $current, $echo, 'disabled' );
}

/**
 * Outputs the html readonly attribute.
 *
 * Compares the first two arguments and if identical marks as readonly
 *
 * @since 4.9.0
 *
 * @param mixed $readonly One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function readonly( $readonly, $current = true, $echo = true ) {
    return __checked_selected_helper( $readonly, $current, $echo, 'readonly' );
}

/**
 * Private helper function for checked, selected, disabled and readonly.
 *
 * Compares the first two arguments and if identical marks as $type
 *
 * @since 2.8.0
 * @access private
 *
 * @param mixed  $helper  One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param bool   $echo    Whether to echo or just return the string
 * @param string $type    The type of checked|selected|disabled|readonly we are doing
 * @return string html attribute or empty string
 */
function __checked_selected_helper( $helper, $current, $echo, $type ) {
    if ( (string) $helper === (string) $current ) {
        $result = " $type='$type'";
    } else {
        $result = '';
    }

    if ( $echo ) {
        echo $result;
    }

    return $result;
}
function current_page( $current, $page ) {
    if ( (string) $page === (string) $current ) {
        $result = 'class="active"';
    } else {
        $result = '';
    }
    echo $result;
}
<?php
$app->set('flight.log_errors', true);
$app->set('modules', array());
$app->register('db', 'PDO', array("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER,DBPASS),
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
$app->map('checkAuthPermission',function($area = array()){
    global $app;
    if(empty($_SESSION['mcb_user'])){
        $app->redirect('/');  
        die();
    }

    if( !empty($_SESSION['mcb_user']) && !(in_array($_SESSION['mcb_user']['level'], $area))  ){
        $app->render('admin/noaccess', array(
                'title' => 'No Access - MediaCore',
                'section' => 'noaccess',
                'classb' => 'noaccess',
                'section_title' => 'No tenés permitido estar aquí! 🙈'
        ));
        die();
    }
});
$app->map('checkAuth',function($lvl){
    global $app;
    if(empty($_SESSION['mcb_user'])){
        $app->render('admin/notLogedIn', array('title' => 'Mediahaus - No Access', 'section' => 'noadmin'));
        die();
    }
    if( !empty($_SESSION['mcb_user']) && $_SESSION['mcb_user']['lvl'] < $lvl ){
        $app->render('admin/notLvl', array('title' => 'Mediahaus - No Access', 'section' => 'nolevel', 'sub_section' => ''));
        die();
    }
});
/*
$app->map('checkAuthPermission',function($area = array()){
    global $app;
    if(empty($_SESSION['mcb_user'])){
        $app->redirect('/');  
        die();
    }
    if( !empty($_SESSION['mcb_user']) && !(in_array($_SESSION['mcb_user']['area'], $area))  ){
        $app->render('noaccess', array(
                'title' => 'No Access - MediaCore',
                'section' => 'noaccess',
                'classb' => 'noaccess',
                'section_title' => 'No tenés permitido estar aquí! 🙈'
        ));
        die();
    }
});
*/
$app->map('register_module',function($module_data = array()){
    global $app;
    $modules = $app->get('modules');
    $modules[$module_data['order']] = $module_data;
    $app->set('modules',$modules);
});
$app->map('get_users',function($filter = null){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $users = $db->prepare("SELECT * FROM `{$prefix}_users`");
    if (!empty($filter)) {
        $users = $db->prepare("SELECT * FROM `{$prefix}_resources` WHERE user_level = {$filter} ");
    }
    $users->execute();
    $users = $users->fetchAll();
    return $users;
});
$app->map('prettyDate',function($date){
    $date_source = explode('-', date ( 'Y-n-j' , strtotime($date)));
    $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    echo $date_source[2].' de '.$meses[$date_source[1]].', '.$date_source[0];
});




/*
$app->map('get_situation_by',function($by,$filter){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    switch ($by) {
        case 'area':
            $situation = $db->prepare("SELECT * FROM `{$prefix}_resources` WHERE resource_area = $filter");
            $situation->execute();
            $situation = $situation->fetchAll();
            ob_start();
                echo '<ul>';
                foreach ($situation as $key => $value) {
                    $shorname =  explode(' ',$value->resource_name);
    $count = $db->query("SELECT COUNT(*) FROM `{$prefix}_task` WHERE task_area = $filter AND ID_resource = $value->ID")->fetchColumn();
    $color_situation = 'color_situation_'.$count;
    if ($count > 4) {
        $color_situation = 'color_situation_4';
    }
                ?>
                    <li>
                        <div class="situation_box">
                            <?php $app->get_avatar($value->ID); ?>
                            <div class="situation_count <?php echo $color_situation; ?>">
                                <?php //echo $count; ?>
                            </div>
                        </div>
                        <div class="situation_user_name">
                            <?php echo $shorname[0]; ?>
                        </div>
                    </li>
                <?php
                }
                echo '</ul>';
            echo ob_get_clean();
            break;
        case 'client':
                $client_resources = $db->query("SELECT client_resources FROM `{$prefix}_clients` WHERE ID = $filter")->fetchColumn();
                $client_resources = unserialize($client_resources);
                ob_start();
                echo '<ul>';
                foreach ($client_resources as $key => $resource) {
                    $resource = $db->query("SELECT ID,resource_name FROM `{$prefix}_resources` WHERE ID = $resource")->fetch();
                    //var_dump($resource);
                    $shorname =  explode(' ',$resource->resource_name);
    $count = $db->query("SELECT COUNT(*) FROM `{$prefix}_task` WHERE ID_client = $filter AND ID_resource = $resource->ID")->fetchColumn();
    $color_situation = 'color_situation_'.$count;
    if ($count > 4) {
        $color_situation = 'color_situation_4';
    }
                ?>
                    <li>
                        <div class="situation_box">
                            <?php $app->get_avatar($resource->ID); ?>
                            <div class="situation_count <?php echo $color_situation; ?>"> </div>
                        </div>
                        <div class="situation_user_name">
                            <?php echo $shorname[0]; ?>
                        </div>
                    </li>
                <?php
                }
                echo '</ul>';
                echo ob_get_clean();                 
            break;
        default:
            echo "fail get_situation_by()";
            break;
    }
});

$app->map('get_resources',function($filter = null){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $resources = $db->prepare("SELECT * FROM `{$prefix}_resources`");
    if (!empty($filter)) {
        $resources = $db->prepare("SELECT * FROM `{$prefix}_resources` WHERE resource_area = {$filter} ");
    }
    $resources->execute();
    $resources = $resources->fetchAll();
    return $resources;
});

$app->map('get_resource',function($ID){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    if (empty($ID)) {
        return null;
    }
    $resource = $db->prepare("SELECT * FROM `{$prefix}_resources` WHERE ID = {$ID} ");
    $resource->execute();
    $resource = $resource->fetchAll();
    $resource[0]->shortname = explode(' ', $resource[0]->resource_name)[0];
    return $resource[0];
});

$app->map('get_clients',function($filter = null){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $clients = $db->prepare("SELECT * FROM `{$prefix}_clients` ORDER BY ID DESC ");
    $clients->execute();
    $clients = $clients->fetchAll();
    return $clients;
});
$app->map('get_client',function($ID){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    if (empty($ID)) {
        return null;
    }
    $client = $db->prepare("SELECT * FROM `{$prefix}_clients` WHERE ID = {$ID} ");
    $client->execute();
    $client = $client->fetchAll();
    return $client[0];
});

$app->map('get_tasks',function($view = 'administrator', $filter = null){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $tasks = $db->prepare("SELECT * FROM `{$prefix}_task` ORDER BY ID DESC ");
    $tasks->execute();
    $tasks = $tasks->fetchAll();
    $resource_type = $app->get_areas();
    ob_start();
    include ABSPATH.'src/helper-views/task-manager-'.$view.'.php';
    echo ob_get_clean();
});

$app->map('get_areas',function($unset = null){
    $areas = array(
        1 => 'Diseño',
        2 => 'Desarrollo',
        3 => 'Editor de Medios',
        4 => 'Community Manager',
        5 => 'Gerencia',
        6 => 'Finanzas',
        7 => 'Controller',
        8 => 'Administrador'
    );
    if ($unset) {
        foreach($unset as $key) {
           unset($areas[$key]);
        }
    }
    return $areas;
});


$app->map('get_deadline_text',function($deadline){
    $datetime1 = new DateTime();
    $datetime2 = new DateTime($deadline);
    $interval = $datetime1->diff($datetime2);
    $created_at = date('Y-m-d H:i:s');
    $deadline_date = explode('-', date ( 'n-d' , strtotime ( "+{$interval->d} days" , strtotime($created_at) ) ));
    $meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    if ($interval->d != 0) {
        echo '<i class="fa fa-calendar-o" aria-hidden="true"></i> '.$meses[$deadline_date[0]].' '.$deadline_date[1];
    }else{
        echo '<i class="fa fa-calendar-o" aria-hidden="true"></i> Hoy';
    }
});

$app->map('get_avatar',function($ID){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    if (empty($ID)) {
        return null;
    }
    $resource = $db->prepare("SELECT resource_name FROM `{$prefix}_resources` WHERE ID = {$ID} ");
    $resource->execute();
    $resource = $resource->fetchAll();
    //return $resource[0];
    if (file_exists(ABSPATH.'img/users/u'.$ID.'/profile.jpg')) {
         echo '<div class="user_pic" style="background-image:url('.SITE.'/img/users/u'.$ID.'/profile.jpg"></div>';
    }else{
      $shorname =  explode(' ',$resource[0]->resource_name);
      if (!empty($shorname[1])) {
          $shorname = $shorname[0][0].$shorname[1][0];
      }else{
        $shorname = $shorname[0][0].$shorname[0][1];
      }
      echo '<div class="user_pic">'.$shorname.'</div>';
    }
});
*/

$app->map('file_upload',function($file_data = null,$folder = null,$set_name = null,$max_file_size = 1,$max_file_size_measurment = 'MB', $ext = 'jpg' ){
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
    return $result;
}
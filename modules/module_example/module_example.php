<?php
/**
 * All module need the file with same name of the folder, need unique can use - and _ 
 * him require a defaults params on array in to register_module function.
 *
 * @since 1.0.0
 *
 * @param name the name on admin menu
 * @param url: the url on front
 * @param admin-url: the url on admin side
 * @param order: the orden on menu see "Menu orders" on documentation of backend
 * @param (optional) submenu = An array for simples custom menu, of main menu of module, like category or any type of simple data,
 *  the model is key is the url and value is the Name of menu
 * @return string html attribute or empty string
 */
$core->register_module(array('name' => 'Example','url' => 'example','admin-url' => 'example', 'order' => 0,'submenu' => array('subexample' => 'Sub Example')));

// if you need a custom table on DB you can create first
/** 
 * To create tables automatic of your module you can use the function $core-add_table($param1,$param2);
 * @param $param1 "table_name"
 * @param $param2 Standar SQL to create rows separated by , "ID` BIGINT NOT NULL AUTO_INCREMENT,`bar_column` VARCHAR(255) NOT NULL,PRIMARY KEY (`ID`)"
 */

$core->add_table('example',"
  `ID` BIGINT NOT NULL AUTO_INCREMENT,
  `foo_column` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bar_column` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ID`)");

/**
 * You can map any functions to use on front or backend of the CMS
 */

$core->map('get_example',function($ID) use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $get_example = $db->prepare("SELECT * FROM `{$prefix}_example` WHERE ID = {$cat} ");
    $get_example->execute();
    return $get_example->fetch();
              
});

$core->map('get_examples',function($order = 'DESC') use ($core){
    global $core;
    $db = $core->db();
    $prefix = DBPREFIX; 
    $get_examples = $db->prepare("SELECT * FROM {$prefix}_example ORDER BY $order"); 
    $get_examples->execute();
    return $get_examples->fetchAll();
});

// Make admin screen need full URL you want and need be unique
$core->route('GET /admin/example/', function() use ($core){
    // can sent permission to screen
    $core->checkAuthPermission(array(8,10));
    // need admin render to include your own php file that display ur content 
    $core->render_admin( 
        array('title' => 'Noticias - MediaCore', // required
          'classb' => 'novedades_admin', // required
          'section' => 'novedades_admin', // required
          'section_title' => 'Section Example title', // required
          'data' => (object) array('ID' => 0, 'foo' => 'string'), // required your function or prev SQL 
          'module_file' => __DIR__.'/admin.php', // load  specific module - required
          'foo' => 'bar' // you can pass any values you need
        )
    );
});

// Make admin screen need full URL you want and need be unique
$core->route('POST /admin/example/', function() use ($core){
    // can sent permission to screen
    $core->checkAuthPermission(array(8,10));
    $request = $core->request()->data;
    $core->update_on('example', array('bar_column' => $request->bar_column), array('ID' => 1));
    $core->notify_helper_add(array('type' => 'success', 'expire' => time()+10, 'created' => time(), 'text' => '<strong>Acción realizada exitosamente!</strong>.'));
    $core->redirect('/admin/example');
});

// Make admin screen need full URL you want and need be unique
$core->route('GET /admin/example/subexample', function() use ($core){
    // can sent permission to screen
    $core->checkAuthPermission(array(8,10));
    // need admin render to include your own php file that display ur content 
    $core->render_admin( 
        array('title' => 'Noticias - MediaCore', // required
          'classb' => 'novedades_admin', // required
          'section' => 'novedades_admin', // required
          'section_title' => 'Sub Section Example title', // required
          'data' => (object) array('ID' => 1, 'foo' => 'string'), // required your function or prev SQL 
          'module_file' => __DIR__.'/admin.php', // load  specific module - required
          'foo' => 'bar' // you can pass any values you need
        )
    );
});

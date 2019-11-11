<?php

$routes = $core->add_menu_page('Example',array('admin'=>'Main example'),array(10),'',0,array('subexample' => 'Sub Menus'),__DIR__);

var_dump($routes);

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
$core->route('POST /admin/example/', function() use ($core){
    // can sent permission to screen
    $core->checkAuthPermission(array(8,10));
    $request = $core->request()->data;
    $core->update_on('example', array('bar_column' => $request->bar_column), array('ID' => 1));
    $core->notify_helper_add(array('type' => 'success', 'expire' => time()+10, 'created' => time(), 'text' => '<strong>Acción realizada exitosamente!</strong>.'));
    $core->redirect('/admin/example');
});


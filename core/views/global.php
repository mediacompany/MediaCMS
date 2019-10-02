<?php 
include 'header.php'; // load global admin header

if (file_exists($module_file)) {
	require_once $module_file;
}else{
	$module_file = (!empty($module_file))? $module_file : 'module_file is not define.' ; 
	throw new Exception('Ops '.$module_file.' Your file not exist dummbass!!! ');
}

include 'footer.php'; // load global admin footer
?>
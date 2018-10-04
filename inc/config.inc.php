<?php
session_start();
ob_start();
$app_root = "/template3";
$site_root = 'http://'.$_SERVER['HTTP_HOST'].$app_root;
define('APP_ROOT',$app_root);
define('DOC_ROOT',$_SERVER["DOCUMENT_ROOT"].APP_ROOT);
define('SOURCE_ROOT',$site_root);
define('CSS_PATH', SOURCE_ROOT.'/common/css');
define('JS_PATH', SOURCE_ROOT.'/common/js');
define('IMAGE_PATH', SOURCE_ROOT.'/common/images');

include DOC_ROOT.'/Classes/Database/class_database.php';
include DOC_ROOT.'/Classes/ProgramFiles/ShopCore.php';

$db=new ShopCore("localhost","root","","inventory3");
?>
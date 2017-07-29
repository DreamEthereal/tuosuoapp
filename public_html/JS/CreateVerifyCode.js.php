<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
error_reporting(0);
include_once ROOT_PATH . 'JS/CreateVerifyCode.class.php';
$img = new Securimage();
$img->image_width = 140;
$img->image_height = 40;
$img->show();

?>

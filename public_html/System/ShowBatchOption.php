<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$EnableQCoreClass->setTemplateFile('BatchOptionFile', 'BatchOption.html');
$EnableQCoreClass->replace('isRange', (int) $_GET['isRange']);
$EnableQCoreClass->parse('BatchOption', 'BatchOptionFile');
$EnableQCoreClass->output('BatchOption');

?>

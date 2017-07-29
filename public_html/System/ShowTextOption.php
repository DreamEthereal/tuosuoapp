<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$EnableQCoreClass->setTemplateFile('TextOptionFile', 'TextOption.html');
$EnableQCoreClass->parse('TextOption', 'TextOptionFile');
$EnableQCoreClass->output('TextOption');

?>

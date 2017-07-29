<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|5');
$_GET['selectedID'] = (int) $_GET['selectedID'];
$SQL = ' SELECT optionName FROM ' . OPTION_TABLE . ' WHERE optionID=\'' . $_GET['selectedID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$optionName = str_replace("\r", '', $Row['optionName']);
$optionNameArray = explode('###', $optionName);
$optionName = implode("\r\n", $optionNameArray);
echo '<textarea rows=6 cols=60 name=\'optionName\' id=\'optionName\'>' . $optionName . '</textarea>';

?>

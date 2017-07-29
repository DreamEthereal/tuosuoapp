<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|3|5|7');
$_GET['selectedID'] = (int) $_GET['selectedID'];
$_GET['surveyID'] = (int) $_GET['surveyID'];
$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET mainShowQtn=\'' . $_GET['selectedID'] . '\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$DB->query($SQL);
echo 'succ';

?>

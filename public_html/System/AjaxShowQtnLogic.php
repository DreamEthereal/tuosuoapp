<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);

if ($_GET['Action'] == 'SetQtnLogicRel') {
	$_GET['isLogicAnd'] = (int) $_GET['isLogicAnd'];
	$_GET['questionID'] = (int) $_GET['questionID'];
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isLogicAnd=\'' . $_GET['isLogicAnd'] . '\' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$DB->query($SQL);
	exit();
}

$theQtnID = (int) $_GET['questionID'];
$theIsLogicAnd = (int) $_GET['isLogicAnd'];
require 'ShowQtnLogic.inc.php';
echo $conList;
exit();

?>

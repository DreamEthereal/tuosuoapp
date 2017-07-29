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

	switch ($_GET['questionType']) {
	case '6':
	case '7':
	case '26':
	case '27':
		$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET isLogicAnd=\'' . $_GET['isLogicAnd'] . '\' WHERE question_range_optionID=\'' . $_GET['qtnID'] . '\' ';
		break;

	case '10':
	case '15':
	case '16':
		$SQL = ' UPDATE ' . QUESTION_RANK_TABLE . ' SET isLogicAnd=\'' . $_GET['isLogicAnd'] . '\' WHERE question_rankID=\'' . $_GET['qtnID'] . '\' ';
		break;
	}

	$DB->query($SQL);
	exit();
}

switch ($_GET['ajaxType']) {
case 2:
	$theQtnID = (int) $_GET['questionID'];
	$theQtnAssID = (int) $_GET['qtnID'];
	$theIsLogicAnd = (int) $_GET['isLogicAnd'];
	$conList = '';
	require 'ShowQtnAssociateSingle.inc.php';
	echo $conList;
	exit();
	break;

default:
	$theQtnID = (int) $_GET['questionID'];
	$theQuestionType = (int) $_GET['questionType'];
	require 'ShowQtnAssociate.inc.php';
	echo $conList;
	exit();
	break;
}

?>

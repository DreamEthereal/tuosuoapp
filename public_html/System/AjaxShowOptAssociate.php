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
	case '2':
	case '24':
		$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET isLogicAnd=\'' . $_GET['isLogicAnd'] . '\' WHERE question_radioID=\'' . $_GET['optionID'] . '\' ';
		break;

	case '3':
	case '25':
		$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET isLogicAnd=\'' . $_GET['isLogicAnd'] . '\' WHERE question_checkboxID=\'' . $_GET['optionID'] . '\' ';
		break;

	case '6':
	case '7':
	case '19':
	case '28':
		$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET isLogicAnd=\'' . $_GET['isLogicAnd'] . '\' WHERE question_range_answerID=\'' . $_GET['optionID'] . '\' ';
		break;
	}

	$DB->query($SQL);
	exit();
}

switch ($_GET['ajaxType']) {
case 2:
	$theQtnID = (int) $_GET['questionID'];
	$theOptAssID = (int) $_GET['optionID'];
	$theIsLogicAnd = (int) $_GET['isLogicAnd'];
	$conList = '';
	require 'ShowOptAssociateSingle.inc.php';
	echo $conList;
	exit();
	break;

default:
	$theQtnID = (int) $_GET['questionID'];
	$theQuestionType = (int) $_GET['questionType'];
	require 'ShowOptAssociate.inc.php';
	echo $conList;
	exit();
	break;
}

?>

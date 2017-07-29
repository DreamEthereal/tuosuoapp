<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$qSQL = ' SELECT DISTINCT optionID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $theQtnID . '\' AND assType=2 ORDER BY optionID ASC ';
$qResult = $DB->query($qSQL);
$conList = '';

while ($qRow = $DB->queryArray($qResult)) {
	switch ($theQuestionType) {
	case '2':
	case '24':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID =\'' . $qRow['optionID'] . '\'  ';
		break;

	case '3':
	case '25':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $qRow['optionID'] . '\'  ';
		break;

	case '6':
	case '7':
	case '19':
	case '28':
		$rSQL = ' SELECT optionAnswer as optionName,isLogicAnd FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $qRow['optionID'] . '\'  ';
		break;
	}

	$rRow = $DB->queryFirstRow($rSQL);
	$theIsLogicAnd = $rRow['isLogicAnd'];
	$isLogicAnd0 = ($theIsLogicAnd == 0 ? 'checked' : '');
	$isLogicAnd1 = ($theIsLogicAnd == 1 ? 'checked' : '');
	$conList .= '<span style="background:#f6e06a;height:35px;padding:10px;line-height:40px;"><b>' . qnohtmltag($rRow['optionName'], 1) . '</b></span>&nbsp;&nbsp;&nbsp;';
	if (!isset($theViewType) || ($theViewType != 1)) {
		$conList .= '题间运算关系：<input type=radio style="background:#f5fafe" name="isLogicAnd_' . $theQtnID . '_' . $qRow['optionID'] . '" id="isLogicAnd_' . $theQtnID . '_' . $qRow['optionID'] . '" value="1" onclick="javascript:HttpRequest(' . $theQtnID . ',' . $qRow['optionID'] . ',' . $theQuestionType . ',1);" ' . $isLogicAnd1 . '><b>&nbsp;与</b>&nbsp;<input type=radio style="background:#f5fafe" name="isLogicAnd_' . $theQtnID . '_' . $qRow['optionID'] . '" id="isLogicAnd_' . $theQtnID . '_' . $qRow['optionID'] . '" value="0" onclick="javascript:HttpRequest(' . $theQtnID . ',' . $qRow['optionID'] . ',' . $theQuestionType . ',0);" ' . $isLogicAnd0 . '><b>&nbsp;或</b><br/>';
	}

	$conList .= '<hr style="margin-top:0px;height:1px;border-top:0px;border-bottom:1px solid #ccc;overflow:hidden">';
	$theOptAssID = $qRow['optionID'];
	require 'ShowOptAssociateSingle.inc.php';
	$conList .= '<br/>';
}

$EnableQCoreClass->replace('conList', substr($conList, 0, -5));

?>

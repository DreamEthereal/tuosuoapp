<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$qSQL = ' SELECT DISTINCT qtnID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $theQtnID . '\' AND assType=1 ORDER BY qtnID ASC ';
$qResult = $DB->query($qSQL);
$conList = '';

while ($qRow = $DB->queryArray($qResult)) {
	switch ($theQuestionType) {
	case '6':
	case '7':
	case '26':
	case '27':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $qRow['qtnID'] . '\'';
		break;

	case '10':
	case '15':
	case '16':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID =\'' . $qRow['qtnID'] . '\'';
		break;
	}

	$rRow = $DB->queryFirstRow($rSQL);
	$theIsLogicAnd = $rRow['isLogicAnd'];
	$isLogicAnd0 = ($theIsLogicAnd == 0 ? 'checked' : '');
	$isLogicAnd1 = ($theIsLogicAnd == 1 ? 'checked' : '');
	$conList .= '<span style="background:#f6e06a;height:35px;padding:10px;line-height:40px;"><b>' . qnohtmltag($rRow['optionName'], 1) . '</b></span>&nbsp;&nbsp;&nbsp;';
	if (!isset($theViewType) || ($theViewType != 1)) {
		$conList .= '题间关系：<input type=radio style="background:#f5fafe" name="isLogicAnd_' . $theQtnID . '_' . $qRow['qtnID'] . '" id="isLogicAnd_' . $theQtnID . '_' . $qRow['qtnID'] . '" value="1" onclick="javascript:HttpRequest(' . $theQtnID . ',' . $qRow['qtnID'] . ',' . $theQuestionType . ',1);" ' . $isLogicAnd1 . '><b>&nbsp;与</b>&nbsp;<input type=radio style="background:#f5fafe" name="isLogicAnd_' . $theQtnID . '_' . $qRow['qtnID'] . '" id="isLogicAnd_' . $theQtnID . '_' . $qRow['qtnID'] . '" value="0" onclick="javascript:HttpRequest(' . $theQtnID . ',' . $qRow['qtnID'] . ',' . $theQuestionType . ',0);" ' . $isLogicAnd0 . '><b>&nbsp;或</b><br/>';
	}

	$conList .= '<hr style="height:1px;border-top:0px;border-bottom:1px solid #ccc;overflow:hidden">';
	$theQtnAssID = $qRow['qtnID'];
	require 'ShowQtnAssociateSingle.inc.php';
	$conList .= '<br/>';
}

$EnableQCoreClass->replace('conList', substr($conList, 0, -5));

?>

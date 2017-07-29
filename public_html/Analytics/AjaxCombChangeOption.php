<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['selectedID'] = (int) $_GET['selectedID'];
_checkroletype('1|2|5');
$SQL = ' SELECT questionType,orderByID,isHaveOther,otherText,baseID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

switch ($Row['questionType']) {
case '2':
case '24':
	$RadioSQL = ' SELECT questionID,question_radioID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY optionOptionID ASC ';
	$RadioResult = $DB->query($RadioSQL);
	$optionList = '<select name="optionID[]" id="optionID" size=7 multiple style=\'width:880px\'><option value="">' . $lang['pls_select'] . '</option>';

	while ($RadioRow = $DB->queryArray($RadioResult)) {
		$optionName = qnohtmltag($RadioRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RadioRow['question_radioID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>';
	break;

case '3':
case '25':
	$CheckBoxSQL = ' SELECT questionID,question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY optionOptionID ASC ';
	$CheckBoxResult = $DB->query($CheckBoxSQL);
	$optionList = '<select name="optionID[]" id="optionID" size=7 multiple style=\'width:880px\'><option value="">' . $lang['pls_select'] . '</option>';

	while ($CheckBoxRow = $DB->queryArray($CheckBoxResult)) {
		$optionName = qnohtmltag($CheckBoxRow['optionName'], 1);
		$optionList .= '<option value=\'' . $CheckBoxRow['question_checkboxID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>';
	break;

case '18':
	$CondSQL = ' SELECT questionID,question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_yesnoID ASC ';
	$CondResult = $DB->query($CondSQL);
	$optionList = '<select name="optionID[]" id="optionID" size=7 multiple style=\'width:880px\'><option value="">' . $lang['pls_select'] . '</option>';

	while ($CondRow = $DB->queryArray($CondResult)) {
		$optionName = qnohtmltag($CondRow['optionName'], 1);
		$optionList .= '<option value=\'' . $CondRow['question_yesnoID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>';
	break;

case '6':
case '7':
case '19':
case '26':
case '28':
	$RangeSQL = ' SELECT questionID,question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_range_answerID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="optionID[]" id="optionID" size=7 multiple style=\'width:880px\'><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionAnswer'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_range_answerID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>';
	break;
}

header('Content-Type:text/html; charset=gbk');
echo $optionList;

?>

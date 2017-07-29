<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
_checkroletype('1|2|5');
$field_Name = explode('_', trim($_GET['fieldname']));
$theGetContent = iconv('UTF-8', 'gbk', str_replace('&amp;', '&', unescape($_GET['content'])));
$updateContent = stripslashes($theGetContent);
$dbContent = qaddslashes($theGetContent);
$isCache = false;

switch ($field_Name['0']) {
case 'surveyTitle':
	switch ($field_Name['1']) {
	case '1':
		$dbContent = str_replace('\\\'', '', $dbContent);
		$dbContent = str_replace('"', '', $dbContent);
		$dbContent = str_replace('&', '', $dbContent);
		$dbContent = str_replace('\\', '', $dbContent);
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET surveyTitle = "' . qnoreturnchar($dbContent) . '" WHERE surveyID = \'' . $field_Name['2'] . '\' ';
		$updateContent = str_replace('\'', '', $updateContent);
		$updateContent = str_replace('"', '', $updateContent);
		$updateContent = str_replace('&', '', $updateContent);
		$updateContent = str_replace('\\', '', $updateContent);
		break;

	case '2':
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET surveySubTitle = "' . qnoreturnchar($dbContent) . '" WHERE surveyID = \'' . $field_Name['2'] . '\' ';
		break;

	case '3':
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET surveyInfo = "' . $dbContent . '" WHERE surveyID = \'' . $field_Name['2'] . '\' ';
		break;
	}

	$DB->query($SQL);
	break;

case 'questionName':
	$theString = qnoreturnchar($dbContent);
	$preg = '/\\<span[\\s]*id\\=(.*?)\\>.*?\\<\\/span\\>/si';

	if (preg_match_all($preg, $theString, $Matches, PREG_SET_ORDER)) {
		foreach ($Matches as $Value) {
			$theValue = explode('_', $Value[1]);
			$theString = str_replace($Value[0], '[Answer_' . $theValue[2] . ']', $theString);
		}
	}

	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName = "' . $theString . '" WHERE questionID = \'' . $field_Name['1'] . '\' ';
	$DB->query($SQL);
	$isCache = true;
	break;

case 'questionNotes':
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionNotes = "' . $dbContent . '" WHERE questionID = \'' . $field_Name['1'] . '\' ';
	$DB->query($SQL);
	$isCache = true;
	break;

case 'optionName':
	$dbContent = qnoreturnchar($dbContent);

	switch ($field_Name['1']) {
	case '2':
	case '24':
		if ($field_Name['3'] == '0') {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET otherText = "' . $dbContent . '" WHERE questionID = \'' . $field_Name['2'] . '\' ';
		}
		else {
			$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_radioID =\'' . $field_Name['3'] . '\' ';
		}

		$DB->query($SQL);
		break;

	case '3':
		if ($field_Name['3'] == '0') {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET otherText = "' . $dbContent . '" WHERE questionID = \'' . $field_Name['2'] . '\' ';
		}
		else if ($field_Name['3'] == '99999') {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET allowType = "' . $dbContent . '" WHERE questionID = \'' . $field_Name['2'] . '\' ';
		}
		else {
			$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_checkboxID =\'' . $field_Name['3'] . '\' ';
		}

		$DB->query($SQL);
		break;

	case '25':
		$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_checkboxID =\'' . $field_Name['3'] . '\' ';
		$DB->query($SQL);
		break;

	case '7':
	case '26':
	case '27':
		$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_optionID =\'' . $field_Name['3'] . '\' ';
		$DB->query($SQL);
		break;

	case '10':
	case '15':
	case '16':
		if ($field_Name['3'] == '0') {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET otherText = "' . $dbContent . '" WHERE questionID = \'' . $field_Name['2'] . '\' ';
		}
		else {
			$SQL = ' UPDATE ' . QUESTION_RANK_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_rankID =\'' . $field_Name['3'] . '\' ';
		}

		$DB->query($SQL);
		break;

	case '23':
		$SQL = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_yesnoID =\'' . $field_Name['3'] . '\' ';
		$DB->query($SQL);
		break;
	}

	$isCache = true;
	break;

case 'optionNameText':
	$dbContent = qnoreturnchar($dbContent);
	$SQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_optionID =\'' . $field_Name['3'] . '\' LIMIT 1 ';
	$HaveRow = $DB->queryFirstRow($SQL);
	$theNewOptionName = explode('|', $HaveRow['optionName']);

	if (1 < count($theNewOptionName)) {
		if ($field_Name['4'] == 'left') {
			$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName = "' . $dbContent . '|' . addslashes($theNewOptionName[1]) . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_optionID =\'' . $field_Name['3'] . '\' ';
		}
		else {
			$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName = "' . addslashes($theNewOptionName[0]) . '|' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_optionID =\'' . $field_Name['3'] . '\' ';
		}
	}
	else {
		$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_optionID =\'' . $field_Name['3'] . '\' ';
	}

	$DB->query($SQL);
	$isCache = true;
	break;

case 'optionLabel':
	$dbContent = qnoreturnchar($dbContent);
	$SQL = ' UPDATE ' . QUESTION_RANGE_LABEL_TABLE . ' SET optionLabel = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_labelID =\'' . $field_Name['3'] . '\' ';
	$DB->query($SQL);
	$isCache = true;
	break;

case 'textInfo':
	$SQL = ' UPDATE ' . QUESTION_INFO_TABLE . ' SET optionName ="' . $dbContent . '"  WHERE questionID = \'' . $field_Name['1'] . '\' ';
	$DB->query($SQL);
	$isCache = true;
	break;

case 'optionAnswer':
	$dbContent = qnoreturnchar($dbContent);
	$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET optionAnswer = "' . $dbContent . '" WHERE questionID=\'' . $field_Name['2'] . '\' AND question_range_answerID =\'' . $field_Name['3'] . '\' ';
	$DB->query($SQL);
	$isCache = true;
	break;
}

if ($isCache == true) {
	$_GET['sid'] = (int) $_GET['sid'];
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_GET['sid'] . '\' ';
	$DB->query($SQL);
}

$fieldname = $_GET['fieldname'];
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type:text/html; charset=gbk');
echo $_GET['fieldname'] . '|' . $updateContent;

?>

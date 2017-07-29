<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5');
header('Content-Type:text/html; charset=gbk');
$_GET['surveyID'] = (int) $_GET['surveyID'];

if ($_GET['type'] == 'single') {
	$baseQtnList = '<select name="baseID" id="baseID" style="width:720px;*width:730px">';
	$baseQtnList .= '<option value=\'\'>' . $lang['new_select_action'] . $lang['question_type_' . $_GET['newQuestionType']] . $lang['new_base_action'] . '</option>';
	$SQL = ' SELECT questionType,questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType IN (2,3,24,25) AND orderByID < ' . $_GET['orderByID'] . ' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
}
else {
	$baseQtnList = '<select name="baseID" id="baseID" style="width:530px;*width:540px">';
	$baseQtnList .= '<option value=\'\'>' . $lang['new_select_action'] . $lang['question_type_' . $_GET['newQuestionType']] . $lang['new_base_action'] . '</option>';
	$SQL = ' SELECT questionType,questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType IN (2,3,24,25) ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
}

while ($Row = $DB->queryArray($Result)) {
	switch ($_GET['newQuestionType']) {
	case '18':
		if (($Row['questionType'] == 2) || ($Row['questionType'] == 24)) {
			$baseQuestionName = qnohtmltag($Row['questionName'], 1);
			$baseQtnList .= '<option value=\'' . $Row['questionID'] . '\'>' . $baseQuestionName . '</option>';
		}

		break;

	default:
		if (($Row['questionType'] == 3) || ($Row['questionType'] == 25)) {
			$baseQuestionName = qnohtmltag($Row['questionName'], 1);
			$baseQtnList .= '<option value=\'' . $Row['questionID'] . '\'>' . $baseQuestionName . '</option>';
		}

		break;
	}
}

$baseQtnList .= '</select>';
echo $baseQtnList;

?>

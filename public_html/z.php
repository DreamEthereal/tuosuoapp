<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash']) != md5(trim($SerialRow['license']) . 'EnableQ')) {
	exit('EnableQ Security Violation');
}

if ($_GET['qid'] == '') {
	if ($_GET['qname'] == '') {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
	else {
		$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' ';
		$Sur_Row = $DB->queryFirstRow($SQL);

		if (!$Sur_Row) {
			_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
		}
	}
}
else {
	$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['qid'] . '\' ';
	$Sur_Row = $DB->queryFirstRow($SQL);

	if (!$Sur_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

if ($Sur_Row['status'] == '0') {
	_shownotes($lang['status_error'], $lang['design_survey'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
}

if (($_GET['questionid'] != '') && ($_GET['questionid'] != 0)) {
	$_GET['questionid'] = (int) $_GET['questionid'];
	$Q_SQL = ' SELECT questionType,questionName,isPublic FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionid'] . '\' AND surveyID = \'' . $Sur_Row['surveyID'] . '\' ';
	$Q_Row = $DB->queryFirstRow($Q_SQL);
	if (in_array($Q_Row['questionType'], array('2', '3', '24', '25')) || ($Q_Row['isPublic'] != '1')) {
		_shownotes($lang['system_error'], $lang['text_question_error'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
	}
}
else {
	_shownotes($lang['system_error'], $lang['no_text_question'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
}

if (($_GET['pnum'] == '') || ($_GET['pnum'] == 0)) {
	$pageNum = 10;
}
else {
	$pageNum = (int) $_GET['pnum'];
}

$thisProg = 'z.php?qname=' . $_GET['qname'] . '&questionid=' . $_GET['questionid'] . '&tplName=' . $_GET['tplName'] . '&pnum=' . $_GET['pnum'] . '&hash=' . $_GET['hash'];
$phyFullFile = $Config['absolutenessPath'] . 'Templates/' . $language . '/' . trim($_GET['tplName']) . '.html';
if ((trim($_GET['tplName']) != '') && file_exists($phyFullFile)) {
	$EnableQCoreClass->setTemplateFile('QuestionRadioFile', trim($_GET['tplName']) . '.html');
}
else {
	$EnableQCoreClass->setTemplateFile('QuestionRadioFile', 'uResultRadio.html');
}

$EnableQCoreClass->set_CycBlock('QuestionRadioFile', 'RADIO', 'radio');
$EnableQCoreClass->replace('radio', '');
$EnableQCoreClass->replace('surveyTitle', $Sur_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $Sur_Row['surveySubTitle']);
$EnableQCoreClass->replace('questionName', $Q_Row['questionName']);
$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $Sur_Row['surveyID'];
$CountRow = $DB->queryFirstRow($SQL);
$totalResponseNum = $CountRow['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);

if (in_array($Q_Row['questionType'], array('2', '24'))) {
	$SQL = ' SELECT a.optionName,count(*) as optionNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $Sur_Row['surveyID'] . ' b WHERE a.question_radioID = b.option_' . $_GET['questionid'] . ' group by a.question_radioID ';
}
else {
	$SQL = ' SELECT a.optionName,count(*) as optionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $Sur_Row['surveyID'] . ' b WHERE FIND_IN_SET(a.question_checkboxID , b.option_' . $_GET['questionid'] . ') group by a.question_checkboxID ';
}

$SQL .= ' ORDER BY optionNum DESC LIMIT 0,' . $pageNum . ' ';
$Result = $DB->query($SQL);
$orderID = 0;

while ($Row = $DB->queryArray($Result)) {
	$orderID++;
	$EnableQCoreClass->replace('optionOrderNo', $orderID);
	$EnableQCoreClass->replace('optionName', $Row['optionName']);
	$EnableQCoreClass->replace('optionNum', $Row['optionNum']);
	$optionPercent = countpercent($Row['optionNum'], $totalResponseNum);
	$EnableQCoreClass->replace('optionPercent', $optionPercent);
	$EnableQCoreClass->parse('radio', 'RADIO', true);
}

$Result = $EnableQCoreClass->parse('QuestionRadio', 'QuestionRadioFile');
echo $Result;

?>

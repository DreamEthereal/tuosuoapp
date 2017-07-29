<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash']) != md5(trim($SerialRow['license']) . 'EnableQ')) {
	exit('EnableQ Security Violation');
}

if ($_GET['surveyID'] == '') {
	if ($_GET['qname'] == '') {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
	else {
		$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1  ';
		$Sur_Row = $DB->queryFirstRow($SQL);

		if (!$Sur_Row) {
			_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
		}
	}
}
else {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 0,1 ';
	$Sur_Row = $DB->queryFirstRow($SQL);

	if (!$Sur_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

if ($Sur_Row['status'] == '0') {
	_shownotes($lang['status_error'], $lang['design_survey'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
}

if ($_GET['qid'] != '') {
	$_GET['qid'] = (int) $_GET['qid'];
	$Q_SQL = ' SELECT questionType,questionName,isPublic FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['qid'] . '\' AND surveyID=\'' . $Sur_Row['surveyID'] . '\' ';
	$Q_Row = $DB->queryFirstRow($Q_SQL);
	if (($Q_Row['questionType'] != '5') || ($Q_Row['isPublic'] != '1')) {
		_shownotes($lang['system_error'], $lang['text_question_error'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
	}
}
else {
	_shownotes($lang['system_error'], $lang['no_text_question'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
}

if ($_GET['cid'] != '') {
	$_GET['cid'] = (int) $_GET['cid'];
	$C_SQL = ' SELECT questionType,isPublic FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['cid'] . '\' AND surveyID=\'' . $Sur_Row['surveyID'] . '\' ';
	$C_Row = $DB->queryFirstRow($C_SQL);
	if ((($C_Row['questionType'] != '4') && ($C_Row['questionType'] != '5')) || ($C_Row['isPublic'] != '1')) {
		_shownotes($lang['system_error'], $lang['text_question_error'], $lang['survey_gname'] . $Sur_Row['surveyTitle']);
	}
}

$EnableQCoreClass->replace('theme', $Sur_Row['theme']);
$EnableQCoreClass->replace('surveyTitle', $Sur_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $Sur_Row['surveySubTitle']);
$EnableQCoreClass->replace('surveyInfo', $Sur_Row['surveyInfo']);
$thisProg = 't.php?qname=' . $_GET['qname'] . '&qid=' . $_GET['qid'] . '&cid=' . $_GET['cid'] . '&pnum=' . $_GET['pnum'];
$EnableQCoreClass->setTemplateFile('QuestionTextFile', 'uResultText.html');
$EnableQCoreClass->set_CycBlock('QuestionTextFile', 'TEXT', 'text');
$EnableQCoreClass->replace('text', '');

if ($_GET['pnum'] == '') {
	$pageNum = 20;
}
else {
	$pageNum = (int) $_GET['pnum'];
}

$EnableQCoreClass->replace('questionName', $Q_Row['questionName']);

if ($_GET['cid'] != '') {
	$SQL = ' SELECT administratorsName,ipAddress,joinTime,option_' . $_GET['qid'] . ',option_' . $_GET['cid'] . ' FROM ' . $table_prefix . 'response_' . $Sur_Row['surveyID'] . ' WHERE option_' . $_GET['qid'] . ' != \'\' ';
}
else {
	$SQL = ' SELECT administratorsName,ipAddress,joinTime,option_' . $_GET['qid'] . ' FROM ' . $table_prefix . 'response_' . $Sur_Row['surveyID'] . ' WHERE option_' . $_GET['qid'] . ' != \'\' ';
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $recordCount);
$EnableQCoreClass->replace('numPerPage', $pageNum);
$EnableQCoreClass->replace('numPage', ceil($recordCount / $pageNum));
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $pageNum;
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $pageNum . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));

	if ($_GET['cid'] != '') {
		$EnableQCoreClass->replace('ipAddress', $Row['option_' . $_GET['cid']]);
	}
	else {
		$ipAddress = explode('.', $Row['ipAddress']);
		$EnableQCoreClass->replace('ipAddress', $ipAddress['0'] . '.' . $ipAddress['1'] . '.' . $ipAddress['2'] . '.*');
	}

	$EnableQCoreClass->replace('textContent', nl2br($Row['option_' . $_GET['qid']]));
	$EnableQCoreClass->parse('text', 'TEXT', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $pageNum);
$pagebar = $PAGES->whole_num_bar();
$EnableQCoreClass->replace('pagesList', $pagebar);
$Result = $EnableQCoreClass->parse('QuestionText', 'QuestionTextFile');
echo $Result;
exit();

?>

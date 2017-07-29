<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(3);
$thisProg = 'ShowCustSurvey.php';
$EnableQCoreClass->setTemplateFile('MainPageFile', 'CustSurveyList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');
$EnableQCoreClass->replace('nick_Name', $_SESSION['administratorsName']);
$ConfigRow['topicNum'] = 3;
$AuthSQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\' ';
$AuthRow = $DB->queryFirstRow($AuthSQL);
$theAbsPath = explode('-', $AuthRow['absPath']);

if (count($theAbsPath) == 1) {
	$theOwnerId = $_SESSION['adminRoleGroupID'];
}
else {
	$theOwnerId = $theAbsPath[1];
}

if ($theOwnerId != 0) {
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND projectType = 1 AND projectOwner = \'' . $theOwnerId . '\' ';
}
else {
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
}

$Result = $DB->query($SQL);
$totalNum = $recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);
$tmp = 2;

while ($Row = $DB->queryArray($Result)) {
	if (($tmp % 2) == 0) {
		$EnableQCoreClass->replace('classSurvey', 'class="surveyList"');
	}
	else {
		$EnableQCoreClass->replace('classSurvey', '');
	}

	$tmp++;
	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', qnohtmltag($Row['surveyTitle']));
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$EnableQCoreClass->replace('endTime', $Row['endTime']);
	$EnableQCoreClass->replace('actionName', '报告');

	switch ($Row['status']) {
		case '1':
			$EnableQCoreClass->replace('surveyStatus', '<span style="background:#339933;padding:3px 8px 3px 8px;color:#fff">正在执行</span>');
			$EnableQCoreClass->replace('topMargin', 140);
			break;

		case '2':
			$EnableQCoreClass->replace('surveyStatus', '<span style="background:#CC0000;padding:3px 8px 3px 8px;color:#fff">已经结束</span>');
			$EnableQCoreClass->replace('topMargin', 140);
			break;
	}

	$SQL = ' SELECT COUNT(*) as qtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND questionType != 8 LIMIT 0,1';
	$QtnHaveRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('qtnNum', $QtnHaveRow['qtnNum']);
	$SQL = ' SELECT COUNT(*) as pagesNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND questionType = 8 LIMIT 0,1';
	$PagesHaveRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('pagesNum', $PagesHaveRow['pagesNum'] + 1);
	$SQL = ' SELECT COUNT(*) as inputerNum FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1';
	$iRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('inputerNum', $iRow['inputerNum']);
	$SQL = ' SELECT COUNT(*) as autherNum FROM ' . VIEWUSERLIST_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND isAuth =1 LIMIT 0,1';
	$iRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('autherNum', $iRow['autherNum']);
	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('MainPage', 'MainPageFile');
$EnableQCoreClass->output('MainPage', false);

?>

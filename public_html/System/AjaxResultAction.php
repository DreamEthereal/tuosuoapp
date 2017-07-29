<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
header('Content-Type:text/html; charset=gbk');
$_POST['surveyID'] = (int) $_POST['surveyID'];
_checkpassport('1|2|3|5|7', $_POST['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['projectType'] == 1) {
	$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyResultMyAction.html');
	$SQL = ' SELECT * FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		$EnableQCoreClass->replace('haveTask', $lang['have_survey_task']);
		$EnableQCoreClass->replace('taskbtn', '');
	}
	else {
		$EnableQCoreClass->replace('haveTask', $lang['no_survey_task']);
		$EnableQCoreClass->replace('taskbtn', 'none');
	}
}
else {
	$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyResultAction.html');
}

$EnableQCoreClass->replace('begin_Time', $Row['beginTime']);
$EnableQCoreClass->replace('end_Time', $Row['endTime']);
$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
$R_Row = $DB->queryFirstRow($R_SQL);
$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);

if ($License['isEvalUsers']) {
	$EnableQCoreClass->replace('exportURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
	$EnableQCoreClass->replace('spssURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
	$EnableQCoreClass->replace('labelURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
}
else {
	if (($_SESSION['adminRoleType'] == '3') && ($Row['isExportData'] == 1)) {
		$EnableQCoreClass->replace('isExportData', 'none');
		$EnableQCoreClass->replace('exportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
		$EnableQCoreClass->replace('spssURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
		$EnableQCoreClass->replace('labelURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	}
	else {
		$EnableQCoreClass->replace('isExportData', '');
		$EnableQCoreClass->replace('exportURL', 'onclick="javascript:window.location.href=\'../Export/Export.result.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
		$EnableQCoreClass->replace('spssURL', 'onclick="javascript:window.location.href=\'../Export/Export.spss.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
		$EnableQCoreClass->replace('labelURL', 'onclick="javascript:window.location.href=\'../Export/Export.label.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
	}
}

$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
$HaveRow = $DB->queryFirstRow($SQL);

if ($HaveRow) {
	$EnableQCoreClass->replace('havePlan', $lang['have_survey_plan']);
	$EnableQCoreClass->replace('planbtn', '');
}
else {
	$EnableQCoreClass->replace('havePlan', $lang['no_survey_plan']);
	$EnableQCoreClass->replace('planbtn', 'none');
}

$SQL = ' SELECT count(*) as countNum FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND fatherId !=0 LIMIT 0,1 ';
$HaveRow = $DB->queryFirstRow($SQL);

if ($HaveRow['countNum'] != 0) {
	$EnableQCoreClass->replace('haveIndex', $lang['have_survey_index'] . '[ <span class=red>' . $HaveRow['countNum'] . '</span> ]');
	$EnableQCoreClass->replace('indexbtn', '');
}
else {
	$EnableQCoreClass->replace('haveIndex', $lang['no_survey_index']);
	$EnableQCoreClass->replace('indexbtn', 'none');
}

$EnableQCoreClass->replace('survey_Title', qnohtmltag($Row['surveyTitle']));
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Row['surveyTitle']));
$EnableQCoreClass->replace('survey_Name', $Row['surveyName']);
$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
$EnableQCoreClass->replace('surveyLang', $lang['lang_' . $Row['lang']]);
$EnableQCoreClass->replace('createDate', date('Y-m-d', $Row['joinTime']));
$EnableQCoreClass->replace('projectType', $lang['projectType_' . $Row['projectType']]);
$EnableQCoreClass->replace('surveyStatus', $lang['isPublic_' . $Row['isPublic']]);
$SQL = ' SELECT COUNT(*) as qtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionType != 8 LIMIT 0,1';
$QtnHaveRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('qtnNum', $QtnHaveRow['qtnNum']);
$SQL = ' SELECT COUNT(*) as pagesNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionType = 8 LIMIT 0,1';
$PagesHaveRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('pagesNum', $PagesHaveRow['pagesNum'] + 1);

if ($QtnHaveRow['qtnNum'] == 0) {
	$EnableQCoreClass->replace('isHaveQtn', 'none');
}
else {
	$EnableQCoreClass->replace('isHaveQtn', '');
}

$AdminSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
$UserRow = $DB->queryFirstRow($AdminSQL);

if (!$UserRow) {
	$EnableQCoreClass->replace('ownerUser', $lang['deleted_user']);
}
else {
	$EnableQCoreClass->replace('ownerUser', _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']));
}

$ActionPage = $EnableQCoreClass->parse('ActionPage', 'ActionPageFile');
echo $ActionPage;

?>

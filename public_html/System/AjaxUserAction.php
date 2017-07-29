<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype(6);
$_POST['surveyID'] = (int) $_POST['surveyID'];
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyUserAction.html');
$EnableQCoreClass->replace('begin_Time', $Row['beginTime']);
$EnableQCoreClass->replace('end_Time', $Row['endTime']);

switch ($Row['status']) {
case '0':
case '1':
	if ($Row['projectType'] == 1) {
		$EnableQCoreClass->replace('haveTaskSurvey', '');
		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('taskbtn', '');
		$SQL = ' SELECT * FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			$EnableQCoreClass->replace('haveTask', $lang['have_survey_task']);
		}
		else {
			$EnableQCoreClass->replace('haveTask', $lang['no_survey_task']);
		}
	}
	else {
		$EnableQCoreClass->replace('haveTaskSurvey', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('taskbtn', 'none');
	}

	break;

case '2':
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('taskbtn', 'none');
	break;
}

$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
$R_Row = $DB->queryFirstRow($R_SQL);
$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);
$EnableQCoreClass->replace('survey_Title', $Row['surveyTitle']);
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

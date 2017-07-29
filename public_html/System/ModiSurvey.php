<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,surveyTitle,administratorsID,isLogicAnd,surveyName,lang,isRecord FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '1') {
	_showerror($lang['status_error'], $Sur_G_Row['surveyTitle'] . ':' . $lang['no_edit_survey']);
}

$thisProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$thisURLStr = '?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$thisURLString = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('logicURL', 'ShowSurveyLogic.php?' . $thisURLString);
$EnableQCoreClass->replace('relationURL', 'ShowValueRelation.php?' . $thisURLString);
$EnableQCoreClass->replace('quotaURL', 'ShowSurveyQuota.php?' . $thisURLString);
$EnableQCoreClass->replace('questionListURL', $thisProg);

if ($_GET['Action'] == 'View') {
	$global_surveyID = $_GET['surveyID'];
	$global_surveyTitle = $Sur_G_Row['surveyTitle'];

	if ($_GET['questionType'] != '8') {
		$ModuleName = $Module[$_GET['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.edit.php';
	}
}

$EnableQCoreClass->setTemplateFile('QuestionListFile', 'ModiSurvey.html');
$QuestionList = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
echo $QuestionList;

?>

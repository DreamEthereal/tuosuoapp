<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$thisProg = 'ShowOptAssociateList.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$thisURLString = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('logicURL', 'ShowSurveyLogic.php?' . $thisURLString);
$EnableQCoreClass->replace('quotaURL', 'ShowSurveyQuota.php?' . $thisURLString);
$EnableQCoreClass->replace('relationURL', 'ShowValueRelation.php?' . $thisURLString);
$EnableQCoreClass->replace('optAssURL', 'ShowOptAssociateList.php?' . $thisURLString);
$EnableQCoreClass->replace('qtnAssURL', 'ShowQtnAssociateList.php?' . $thisURLString);
$EnableQCoreClass->replace('questionListURL', 'ModiSurvey.php?' . $thisURLString);
$EnableQCoreClass->setTemplateFile('SurveyAssFile', 'SurveyOptAssociateList.html');
$EnableQCoreClass->set_CycBlock('SurveyAssFile', 'LOGIC', 'logic');
$EnableQCoreClass->replace('logic', '');
$theLogicNum = 0;
$Question = array();
$SQL = ' SELECT questionID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND assType=2 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$Question[] = $Row['questionID'];
}

if (!empty($Question)) {
	$QuestionList = implode(',', $Question);
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND questionID IN (' . $QuestionList . ') ORDER BY orderByID ASC ';
}
else {
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID = 0 ';
}

$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$questionName = qnohtmltag($Row['questionName'], 1);
	$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;[' . $lang['question_type_' . $Row['questionType']] . ']');
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$theQtnID = $Row['questionID'];
	$theQuestionType = $Row['questionType'];
	$theViewType = 1;
	require 'ShowOptAssociate.inc.php';
	$EnableQCoreClass->parse('logic', 'LOGIC', true);
}

$EnableQCoreClass->replace('recNum', $theLogicNum);
$EnableQCoreClass->parse('SurveyAss', 'SurveyAssFile');
$EnableQCoreClass->output('SurveyAss');

?>

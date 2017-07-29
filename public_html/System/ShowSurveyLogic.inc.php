<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('SurveyLogicFile', 'SurveyLogic.html');
$EnableQCoreClass->set_CycBlock('SurveyLogicFile', 'LOGIC', 'logic');
$EnableQCoreClass->replace('logic', '');
$EnableQCoreClass->replace('newLogicURL', $thisProg . '&DO=AddLogicNew');
$EnableQCoreClass->replace('importLogicURL', $thisProg . '&DO=ImportLogic');
$theLogicNum = 0;
$Question = array();
$SQL = ' SELECT questionID,condOnID,optionID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND quotaID = 0 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$Question[] = $Row['questionID'];
}

if (!empty($Question)) {
	$QuestionList = implode(',', $Question);
	$SQL = ' SELECT questionID,questionName,questionType,isLogicAnd FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND questionID IN (' . $QuestionList . ') ORDER BY orderByID ASC ';
}
else {
	$SQL = ' SELECT questionID,questionName,questionType,isLogicAnd FROM ' . QUESTION_TABLE . ' WHERE surveyID = 0 ';
}

$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$questionName = qnohtmltag($Row['questionName'], 1);
	$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;[' . $lang['question_type_' . $Row['questionType']] . ']');
	$EnableQCoreClass->replace('questionID', $Row['questionID']);

	if ($Row['isLogicAnd'] == 1) {
		$EnableQCoreClass->replace('isLogicAnd1', 'checked');
		$EnableQCoreClass->replace('isLogicAnd0', '');
	}
	else {
		$EnableQCoreClass->replace('isLogicAnd0', 'checked');
		$EnableQCoreClass->replace('isLogicAnd1', '');
	}

	$theQtnID = $Row['questionID'];
	$theIsLogicAnd = $Row['isLogicAnd'];
	require 'ShowQtnLogic.inc.php';
	$EnableQCoreClass->replace('editURL', $thisProg . '&DOes=EditLogic&questionID=' . $Row['questionID'] . '&questionName=' . urlencode($Row['questionName']));
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&DOes=DeleLogic&questionID=' . $Row['questionID'] . '&questionName=' . urlencode($Row['questionName']));
	$EnableQCoreClass->parse('logic', 'LOGIC', true);
}

$EnableQCoreClass->replace('recNum', $theLogicNum);
$hSQL = ' SELECT * FROM ' . CONDITIONS_TABLE . ' WHERE administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND quotaID = 0 LIMIT 1 ';
$hRow = $DB->queryFirstRow($hSQL);

if ($hRow) {
	$EnableQCoreClass->replace('recHaveNum', '1');
}
else {
	$EnableQCoreClass->replace('recHaveNum', '0');
}

$EnableQCoreClass->parse('SurveyLogic', 'SurveyLogicFile');
$EnableQCoreClass->output('SurveyLogic');

?>

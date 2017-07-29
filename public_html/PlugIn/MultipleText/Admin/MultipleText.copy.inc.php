<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY question_range_optionID ASC  ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $OptRow['optionName'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$multiTextOptionArray[$OptRow['question_range_optionID']] = $DB->_GetInsertID();
}

$SQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY optionOptionID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionLabel=\'' . $OptRow['optionLabel'] . '\',optionSize=\'' . $OptRow['optionSize'] . '\',isRequired=\'' . $OptRow['isRequired'] . '\',isCheckType=\'' . $OptRow['isCheckType'] . '\',minOption=\'' . $OptRow['minOption'] . '\',maxOption=\'' . $OptRow['maxOption'] . '\' ';
	$DB->query($SQL);
	$multiTextLabelArray[$OptRow['question_range_labelID']] = $DB->_GetInsertID();
	updateorderid('question_range_label');
}

?>

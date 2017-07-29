<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY question_yesnoID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . qaddslashes($OptRow['optionName'], 1) . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',isUnkown=\'' . $OptRow['isUnkown'] . '\',isNA=\'' . $OptRow['isNA'] . '\' ';
	$DB->query($SQL);
	$yesNoArray[$OptRow['question_yesnoID']] = $DB->_GetInsertID();
	updateorderid('question_yesno');
}

?>

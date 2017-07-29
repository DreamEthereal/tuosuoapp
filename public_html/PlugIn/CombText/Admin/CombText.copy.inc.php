<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY question_yesnoID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $OptRow['optionName'] . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionSize=\'' . $OptRow['optionSize'] . '\',isRequired=\'' . $OptRow['isRequired'] . '\',isCheckType=\'' . $OptRow['isCheckType'] . '\',minOption=\'' . $OptRow['minOption'] . '\',maxOption=\'' . $OptRow['maxOption'] . '\',unitText=\'' . $OptRow['unitText'] . '\' ';
	$DB->query($SQL);
	$combTextValueArray[$OptRow['question_yesnoID']] = $DB->_GetInsertID();
	updateorderid('question_yesno');
}

?>

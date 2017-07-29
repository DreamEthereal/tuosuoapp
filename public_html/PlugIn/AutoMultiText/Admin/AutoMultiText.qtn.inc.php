<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT questionName,questionType,orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QutRow['baseID'] . '\' ';
$BaseQtnRow = $DB->queryFirstRow($SQL);
$NewQtnSQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE questionName=\'' . qaddslashes($BaseQtnRow['questionName'], 1) . '\' AND questionType=\'' . $BaseQtnRow['questionType'] . '\' AND orderByID =\'' . $BaseQtnRow['orderByID'] . '\' AND surveyID =\'' . $theNewSurveyID . '\' LIMIT 0,1 ';
$NewQtnRow = $DB->queryFirstRow($NewQtnSQL);
$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $NewQtnRow['questionID'] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
$DB->query($SQL);
$SQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY optionOptionID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionLabel=\'' . $OptRow['optionLabel'] . '\',optionSize=\'' . $OptRow['optionSize'] . '\',isRequired=\'' . $OptRow['isRequired'] . '\',isCheckType=\'' . $OptRow['isCheckType'] . '\',minOption=\'' . $OptRow['minOption'] . '\',maxOption=\'' . $OptRow['maxOption'] . '\' ';
	$DB->query($SQL);
	updateorderid('question_range_label');
}

?>

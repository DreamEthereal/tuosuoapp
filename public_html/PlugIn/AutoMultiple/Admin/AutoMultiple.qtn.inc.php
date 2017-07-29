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
$SQL = ' SELECT * FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY question_range_answerID ASC ';
$AnwResult = $DB->query($SQL);

while ($AnwRow = $DB->queryArray($AnwResult)) {
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . qaddslashes($AnwRow['optionAnswer'], 1) . '\',optionCoeff=\'' . $AnwRow['optionCoeff'] . '\',optionValue=\'' . $AnwRow['optionValue'] . '\',itemCode=\'' . $AnwRow['itemCode'] . '\',isLogicAnd=\'' . $AnwRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
}

?>

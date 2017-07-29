<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $newQtnArray[$QutRow['baseID']] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
$DB->query($SQL);
$SQL = ' SELECT * FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY question_range_answerID ASC ';
$AnwResult = $DB->query($SQL);

while ($AnwRow = $DB->queryArray($AnwResult)) {
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . qaddslashes($AnwRow['optionAnswer'], 1) . '\',optionCoeff=\'' . $AnwRow['optionCoeff'] . '\',optionValue=\'' . $AnwRow['optionValue'] . '\',itemCode=\'' . $AnwRow['itemCode'] . '\',isUnkown=\'' . $AnwRow['isUnkown'] . '\',isNA=\'' . $AnwRow['isNA'] . '\',isLogicAnd=\'' . $AnwRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$answerArray[$AnwRow['question_range_answerID']] = $DB->_GetInsertID();
}

?>

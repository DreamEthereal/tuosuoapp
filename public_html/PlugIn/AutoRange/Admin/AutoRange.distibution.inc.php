<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $newQtnArray[$QutRow['baseID']] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
$DB->query($SQL);

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $AnwRow) {
	$AnwRow = qbr2nl(qquoteconvertstring($AnwRow));
	$AnwRow = qaddslashes($AnwRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . $AnwRow['optionAnswer'] . '\',optionCoeff=\'' . $AnwRow['optionCoeff'] . '\',optionValue=\'' . $AnwRow['optionValue'] . '\',itemCode=\'' . $AnwRow['itemCode'] . '\',isUnkown=\'' . $AnwRow['isUnkown'] . '\',isNA=\'' . $AnwRow['isNA'] . '\',isLogicAnd=\'' . $AnwRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$answerArray[$question_range_answerID] = $DB->_GetInsertID();
}

?>

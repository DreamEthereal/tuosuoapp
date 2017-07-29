<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $OptRow) {
	$OptRow = qbr2nl(qquoteconvertstring($OptRow));
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $OptRow['optionName'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$rangeArray[$question_range_optionID] = $DB->_GetInsertID();
}

foreach ($LabelListArray[$questionID] as $question_range_labelID => $LabRow) {
	$LabRow = qbr2nl(qquoteconvertstring($LabRow));
	$LabRow = qaddslashes($LabRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionLabel=\'' . $LabRow['optionLabel'] . '\' ';
	$DB->query($SQL);
	$multiTextLabelArray[$question_range_labelID] = $DB->_GetInsertID();
}

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $AnwRow) {
	$AnwRow = qbr2nl(qquoteconvertstring($AnwRow));
	$AnwRow = qaddslashes($AnwRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . $AnwRow['optionAnswer'] . '\',optionCoeff=\'' . $AnwRow['optionCoeff'] . '\',optionValue=\'' . $AnwRow['optionValue'] . '\',itemCode=\'' . $AnwRow['itemCode'] . '\',isUnkown=\'' . $AnwRow['isUnkown'] . '\',isNA=\'' . $AnwRow['isNA'] . '\' ';
	$DB->query($SQL);
}

?>

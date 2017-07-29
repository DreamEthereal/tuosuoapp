<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $OptRow) {
	$OptRow = qbr2nl(qquoteconvertstring($OptRow));
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $OptRow['optionName'] . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',isUnkown=\'' . $OptRow['isUnkown'] . '\',isNA=\'' . $OptRow['isNA'] . '\' ';
	$DB->query($SQL);
	$yesNoArray[$question_yesnoID] = $DB->_GetInsertID();
	updateorderid('question_yesno');
}

?>

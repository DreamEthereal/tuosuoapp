<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($RadioListArray[$questionID] as $question_radioID => $OptRow) {
	$OptRow = qbr2nl(qquoteconvertstring($OptRow));
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionName=\'' . $OptRow['optionName'] . '\',createDate=\'' . $OptRow['createDate'] . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',isUnkown=\'' . $OptRow['isUnkown'] . '\',isNA=\'' . $OptRow['isNA'] . '\',optionMargin=\'' . $OptRow['optionMargin'] . '\',isHaveText=\'' . $OptRow['isHaveText'] . '\',optionSize=\'' . $OptRow['optionSize'] . '\',isRequired=\'' . $OptRow['isRequired'] . '\',isRetain=\'' . $OptRow['isRetain'] . '\',isCheckType=\'' . $OptRow['isCheckType'] . '\',minOption=\'' . $OptRow['minOption'] . '\',maxOption=\'' . $OptRow['maxOption'] . '\',unitText=\'' . $OptRow['unitText'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$radioArray[$OptRow['question_radioID']] = $DB->_GetInsertID();
	updateorderid('question_radio');
}

?>

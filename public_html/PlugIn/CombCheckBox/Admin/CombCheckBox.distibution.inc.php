<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $OptRow) {
	$OptRow = qbr2nl(qquoteconvertstring($OptRow));
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionName=\'' . $OptRow['optionName'] . '\',groupNum=\'' . $OptRow['groupNum'] . '\',createDate=\'' . $OptRow['createDate'] . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',optionMargin=\'' . $OptRow['optionMargin'] . '\',isExclusive=\'' . $OptRow['isExclusive'] . '\',isNA=\'' . $OptRow['isNA'] . '\',isHaveText=\'' . $OptRow['isHaveText'] . '\',optionSize=\'' . $OptRow['optionSize'] . '\',isRequired=\'' . $OptRow['isRequired'] . '\',isRetain=\'' . $OptRow['isRetain'] . '\',isCheckType=\'' . $OptRow['isCheckType'] . '\',minOption=\'' . $OptRow['minOption'] . '\',maxOption=\'' . $OptRow['maxOption'] . '\',unitText=\'' . $OptRow['unitText'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$checkboxArray[$OptRow['question_checkboxID']] = $DB->_GetInsertID();
	updateorderid('question_checkbox');
}

?>

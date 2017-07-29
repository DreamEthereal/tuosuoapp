<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY optionOptionID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionName=\'' . $OptRow['optionName'] . '\',groupNum=\'' . $OptRow['groupNum'] . '\',createDate=\'' . $OptRow['createDate'] . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',optionMargin=\'' . $OptRow['optionMargin'] . '\',isExclusive=\'' . $OptRow['isExclusive'] . '\',isNA=\'' . $OptRow['isNA'] . '\',isHaveText=\'' . $OptRow['isHaveText'] . '\',optionSize=\'' . $OptRow['optionSize'] . '\',isRequired=\'' . $OptRow['isRequired'] . '\',isRetain=\'' . $OptRow['isRetain'] . '\',isCheckType=\'' . $OptRow['isCheckType'] . '\',minOption=\'' . $OptRow['minOption'] . '\',maxOption=\'' . $OptRow['maxOption'] . '\',unitText=\'' . $OptRow['unitText'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
	$checkboxArray[$OptRow['question_checkboxID']] = $DB->_GetInsertID();
	updateorderid('question_checkbox');
}

?>

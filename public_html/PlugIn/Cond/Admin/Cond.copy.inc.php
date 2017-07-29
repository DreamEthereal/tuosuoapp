<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $newQtnArray[$QutRow['baseID']] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
$DB->query($SQL);
$SQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $QutRow['questionID'] . '\' ORDER BY question_yesnoID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$InSQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID =\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName = \'' . qaddslashes($Row['optionName'], 1) . '\',optionCoeff = \'' . $Row['optionCoeff'] . '\',optionValue = \'' . $Row['optionValue'] . '\',itemCode = \'' . $Row['itemCode'] . '\',isUnkown = \'' . $Row['isUnkown'] . '\',isNA = \'' . $Row['isNA'] . '\',orderByID = \'' . $Row['orderByID'] . '\' ';
	$DB->query($InSQL);
	$yesNoArray[$Row['question_yesnoID']] = $DB->_GetInsertID();
}

$SQL = ' SELECT * FROM ' . CONDREL_TABLE . ' WHERE questionID = \'' . $QutRow['questionID'] . '\' ORDER BY condRelID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if ($Row['fatherID'] != 0) {
		$theNewFatherID = $radioArray[$Row['fatherID']];
	}
	else {
		$theNewFatherID = 0;
	}

	$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theNewFatherID . '\',sonID=\'' . $yesNoArray[$Row['sonID']] . '\',questionID=\'' . $newQuestionID . '\' ';
	$DB->query($SQL);
}

?>

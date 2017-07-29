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
$SQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $QutRow['questionID'] . '\' ORDER BY question_yesnoID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$InSQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID =\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName = \'' . qaddslashes($Row['optionName'], 1) . '\',optionCoeff = \'' . $Row['optionCoeff'] . '\',optionValue = \'' . $Row['optionValue'] . '\',itemCode = \'' . $Row['itemCode'] . '\',orderByID = \'' . $Row['orderByID'] . '\' ';
	$DB->query($InSQL);
}

$SQL = ' SELECT * FROM ' . CONDREL_TABLE . ' WHERE questionID = \'' . $QutRow['questionID'] . '\' ORDER BY condRelID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if ($Row['fatherID'] != 0) {
		$theOldSQL = ' SELECT optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID = \'' . $Row['fatherID'] . '\' ';
		$theOldRow = $DB->queryFirstRow($theOldSQL);
		$theNewSQL = ' SELECT question_radioID as optionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $NewQtnRow['questionID'] . '\' AND optionName =\'' . qaddslashes($theOldRow['optionName'], 1) . '\' ';
		$theNewRow = $DB->queryFirstRow($theNewSQL);
		$theNewFatherID = $theNewRow['optionID'];
	}
	else {
		$theNewFatherID = 0;
	}

	$theSonOldSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID = \'' . $Row['sonID'] . '\' ';
	$theSonOldRow = $DB->queryFirstRow($theSonOldSQL);
	$theSonNewSQL = ' SELECT question_yesnoID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $newQuestionID . '\' AND optionName =\'' . qaddslashes($theSonOldRow['optionName'], 1) . '\' ';
	$theSonNewRow = $DB->queryFirstRow($theSonNewSQL);
	$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theNewFatherID . '\',sonID=\'' . $theSonNewRow['question_yesnoID'] . '\',questionID=\'' . $newQuestionID . '\' ';
	$DB->query($SQL);
}

?>

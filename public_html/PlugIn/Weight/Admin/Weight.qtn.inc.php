<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\'  ORDER BY question_rankID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $OptRow['optionName'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';
	$DB->query($SQL);
}

if ($QutRow['isSelect'] == 2) {
	$SQL = ' SELECT questionName,questionType,isRequired,isCheckType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QutRow['baseID'] . '\' ';
	$BaseQtnRow = $DB->queryFirstRow($SQL);
	$NewQtnSQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE questionName=\'' . qaddslashes($BaseQtnRow['questionName'], 1) . '\' AND questionType=\'' . $BaseQtnRow['questionType'] . '\' AND isRequired =\'' . $BaseQtnRow['isRequired'] . '\' AND isCheckType =\'' . $BaseQtnRow['isCheckType'] . '\' AND surveyID =\'' . $theNewSurveyID . '\' ';
	$NewQtnRow = $DB->queryFirstRow($NewQtnSQL);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $NewQtnRow['questionID'] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
	$DB->query($SQL);
}

?>

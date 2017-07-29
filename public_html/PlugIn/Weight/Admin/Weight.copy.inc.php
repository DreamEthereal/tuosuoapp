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
	$weightValueArray[$OptRow['question_rankID']] = $DB->_GetInsertID();
}

if ($QutRow['isSelect'] == 2) {
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $newQtnArray[$QutRow['baseID']] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
	$DB->query($SQL);
}

?>

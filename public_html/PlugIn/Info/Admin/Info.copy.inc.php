<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_INFO_TABLE . ' WHERE questionID =\'' . $QutRow['questionID'] . '\' ';
$OptRow = $DB->queryFirstRow($SQL);
$OptRow = qaddslashes($OptRow, 1);
$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $OptRow['optionName'] . '\' ';
$DB->query($SQL);

?>

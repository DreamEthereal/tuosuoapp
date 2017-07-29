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

?>

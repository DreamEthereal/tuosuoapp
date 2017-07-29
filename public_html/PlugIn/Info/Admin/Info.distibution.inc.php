<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . qaddslashes(qbr2nl(qquoteconvertstring($InfoListArray[$questionID]['optionName'])), 1) . '\' ';
$DB->query($SQL);

?>

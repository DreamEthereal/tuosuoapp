<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_6'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'6\',isRequired=0,orderByID=\'' . $theNewOrderByID . '\' ';
$DB->query($SQL);
$lastQuestionID = $DB->_GetInsertID();
$i = 1;

for (; $i <= 5; $i++) {
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['new_option_name'] . $i . '\' ';
	$DB->query($SQL);
}

$i = 1;

for (; $i <= 5; $i++) {
	$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionAnswer=\'' . $lang['new_answer_name'] . $i . '\' ';
	$DB->query($SQL);
}

?>

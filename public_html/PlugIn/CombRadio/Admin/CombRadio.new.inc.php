<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_24'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'24\',orderByID=\'' . $theNewOrderByID . '\',isHaveOther=0,isSelect=0 ';
$DB->query($SQL);
$lastQuestionID = $DB->_GetInsertID();
$time = time();
$i = 1;

for (; $i <= 4; $i++) {
	$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . $lang['new_answer_name'] . $i . '\',createDate=\'' . $time . '\',isHaveText=1 ';
	$DB->query($SQL);
	updateorderid('question_radio');
}

?>

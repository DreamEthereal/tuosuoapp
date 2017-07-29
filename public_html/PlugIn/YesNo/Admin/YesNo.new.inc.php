<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_1'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'1\',isRequired=1,isSelect=1,orderByID=\'' . $theNewOrderByID . '\' ';
$DB->query($SQL);
$lastQuestionID = $DB->_GetInsertID();
$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['yesno_1_1'] . '\' ';
$DB->query($SQL);
$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['yesno_1_2'] . '\' ';
$DB->query($SQL);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['insert_info_title'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'9\',orderByID=\'' . $theNewOrderByID . '\' ';
$DB->query($SQL);
$lastQuestionID = $DB->_GetInsertID();
$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['insert_info_content'] . '\' ';
$DB->query($SQL);

?>

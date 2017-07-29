<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_22'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'22\',isRequired=0,maxSize=100,isSelect=1,orderByID=\'' . $theNewOrderByID . '\',baseID=\'' . $_POST['baseID'] . '\' ';
$DB->query($SQL);

?>

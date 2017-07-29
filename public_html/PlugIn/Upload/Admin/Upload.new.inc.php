<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_11'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'11\',isRequired=0,maxSize=2,length=25,allowType=\'.rar|.zip|.gzip|.pdf|.doc|.jpg|.jpeg|.gif|.bmp|.png|.txt|.xls|\',orderByID=\'' . $theNewOrderByID . '\',hiddenVarName=\'1\' ';
$DB->query($SQL);

?>

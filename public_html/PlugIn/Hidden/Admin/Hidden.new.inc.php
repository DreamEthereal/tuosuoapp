<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_12'] . '\',hiddenFromSession=5,surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'12\',orderByID=\'' . $theNewOrderByID . '\' ';
$DB->query($SQL);

?>

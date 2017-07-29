<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$RelSQL = ' DELETE FROM ' . CONDREL_TABLE . ' WHERE questionID=\'' . $questionID . '\'';
$DB->query($RelSQL);
$SQL = ' DELETE FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $questionID . '\'';
$DB->query($SQL);

?>

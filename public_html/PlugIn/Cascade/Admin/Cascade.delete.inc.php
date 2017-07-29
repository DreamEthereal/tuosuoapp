<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' DELETE FROM ' . CASCADE_TABLE . ' WHERE questionID=\'' . $questionID . '\'';
$DB->query($SQL);

?>

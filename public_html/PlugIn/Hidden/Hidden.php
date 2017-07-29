<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$this_hidden_list .= 'option_' . $questionID . '|';
if (($isAuthDataFlag == 1) || ($isAuthAppDataFlag == 1)) {
	$EnableQCoreClass->replace('authList', '');
}

?>

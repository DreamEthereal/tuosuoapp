<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['hiddenVarName'] != '') {
	$header .= ',"' . $theQtnArray['hiddenVarName'] . '"';
}
else {
	$header .= ',"' . 'VAR' . $questionID . '"';
}

$this_fields_list .= 'option_' . $questionID . '|';

?>

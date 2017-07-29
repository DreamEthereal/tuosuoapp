<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$header .= ',"' . $theQtnArray['alias'] . '"';
}
else {
	$header .= ',"' . 'VAR' . $questionID . '"';
}

$this_fields_list .= 'option_' . $questionID . '|';

?>

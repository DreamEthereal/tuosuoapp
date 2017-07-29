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

if ($theQtnArray['isHaveUnkown'] == 2) {
	$this_fields_list .= '4#option_' . $questionID . '#isHaveUnkown_' . $questionID . '|';
}
else {
	$this_fields_list .= 'option_' . $questionID . '|';
}

?>

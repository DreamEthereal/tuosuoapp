<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$i = 1;

for (; $i <= $theQtnArray['maxSize']; $i++) {
	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_' . $i . '"';
	}

	$this_fields_list .= '31#' . $questionID . '#option_' . $questionID . '_' . $i . '|';
}

?>

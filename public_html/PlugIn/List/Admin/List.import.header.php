<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$i = 1;

for (; $i <= $theQtnArray['rows']; $i++) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#14#option_' . $questionID . '_' . $i . '|';
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $theQtnArray['maxSize']; $i++) {
	$tmp = $i - 1;
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theUnitText[$tmp]) . '"';
	$this_fields_list .= '31#' . $questionID . '#option_' . $questionID . '_' . $i . '|';
}

?>

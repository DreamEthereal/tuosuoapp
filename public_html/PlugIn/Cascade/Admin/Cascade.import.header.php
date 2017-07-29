<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$option_order_array = array();
$i = 1;

for (; $i <= $theQtnArray['maxSize']; $i++) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#31#option_' . $questionID . '_' . $i . '#' . $questionID . '#' . $i . '|';

	foreach ($CascadeArray[$questionID] as $nodeID => $theQuestionArray) {
		if ($theQuestionArray['level'] == $i) {
			$option_order_array[$nodeID] = $theQuestionArray['nodeFatherID'];
		}
	}
}

$option_tran_array[$questionID] = $option_order_array;

?>

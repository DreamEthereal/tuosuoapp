<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$question_order_id = 1;
$option_order_array = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$option_order_array[$theQuestionArray['itemCode']] = $question_radioID;
	}
	else {
		$option_order_array[$question_order_id] = $question_radioID;
	}

	$question_order_id++;
}

$theCsvColNum++;
$this_fields_list .= $theCsvColNum . '#2#' . $questionID . '#option_' . $questionID . '|';
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$option_order_array[$theQtnArray['otherCode']] = 0;
	}
	else {
		$option_order_array[99] = 0;
	}

	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#2#0#TextOtherValue_' . $questionID . '#' . $questionID . '|';
}

$option_tran_array[$questionID] = $option_order_array;

?>

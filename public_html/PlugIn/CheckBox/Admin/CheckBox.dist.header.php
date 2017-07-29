<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$question_order_id = 1;
$option_order_array = array();

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$option_order_array[$theQuestionArray['itemCode']] = $question_checkboxID;
	}
	else {
		$option_order_array[$question_order_id] = $question_checkboxID;
	}

	$question_order_id++;
}

$this_fields_list .= '3#' . $questionID . '#option_' . $questionID . '#TextOtherValue_' . $questionID . '|';
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$option_order_array[$theQtnArray['otherCode']] = 0;
	}
	else {
		$option_order_array[99] = 0;
	}

	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

if ($theQtnArray['isNeg'] == '1') {
	if ($theQtnArray['negCode'] != 0) {
		$option_order_array[$theQtnArray['negCode']] = 99999;
	}
	else {
		$option_order_array[99999] = 99999;
	}
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

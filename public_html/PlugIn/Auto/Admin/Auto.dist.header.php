<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$question_order_id = 1;
$option_order_array = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$option_order_array[$theQuestionArray['itemCode']] = $question_checkboxID;
	}
	else {
		$option_order_array[$question_order_id] = $question_checkboxID;
	}

	$question_order_id++;
}

$this_fields_list .= '17#' . $questionID . '#option_' . $questionID . '#' . $theQtnArray['isSelect'] . '|';

if ($theBaseQtnArray['isHaveOther'] == '1') {
	if ($theBaseQtnArray['otherCode'] != 0) {
		$option_order_array[$theBaseQtnArray['otherCode']] = 0;
	}
	else {
		$option_order_array[99] = 0;
	}
}

if ($theQtnArray['isCheckType'] == '1') {
	$option_order_array[99999] = 99999;
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

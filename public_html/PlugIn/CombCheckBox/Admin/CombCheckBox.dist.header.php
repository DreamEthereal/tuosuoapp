<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$question_order_id = 1;
$option_order_array = array();
$this_fields_list .= '25#' . $questionID . '#option_' . $questionID . '|';

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$option_order_array[$theQuestionArray['itemCode']] = $question_checkboxID;
	}
	else {
		$option_order_array[$question_order_id] = $question_checkboxID;
	}

	$question_order_id++;

	if ($theQuestionArray['isHaveText'] == 1) {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
	}
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

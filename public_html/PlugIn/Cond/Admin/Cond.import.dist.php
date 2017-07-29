<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$question_order_id = 1;
$option_order_array = array();

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$option_order_array[$theQuestionArray['itemCode']] = $question_yesnoID;
	}
	else {
		$option_order_array[$question_order_id] = $question_yesnoID;
	}

	$question_order_id++;
}

if ($theQtnArray['isSelect'] == 0) {
	$this_fields_list .= '0#18#' . $questionID . '#option_' . $questionID . '#1|';
}
else {
	$this_fields_list .= '0#18#' . $questionID . '#option_' . $questionID . '#2|';
}

$option_tran_array[$questionID] = $option_order_array;

?>

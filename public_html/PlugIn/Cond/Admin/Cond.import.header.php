<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 0) {
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

	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#18#' . $questionID . '#option_' . $questionID . '#1|';
	$option_tran_array[$questionID] = $option_order_array;
}
else {
	$theCsvColNum++;
	$theColStartNum = $theCsvColNum;
	$option_order_array = array();

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$option_order_array[$theCsvColNum] = $question_yesnoID;
		$theCsvColNum++;
	}

	$theCsvColNum--;
	$theColEndNum = $theCsvColNum;
	$this_fields_list .= $theColStartNum . '#18#' . $questionID . '#option_' . $questionID . '#0#' . $theColEndNum . '|';
	$option_tran_array[$questionID] = $option_order_array;
}

?>

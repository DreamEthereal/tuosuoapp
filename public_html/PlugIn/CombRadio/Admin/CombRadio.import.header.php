<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theCsvColNum++;
$theStartColNum = $theCsvColNum;
$this_fields_list .= $theCsvColNum . '#24#' . $questionID . '#option_' . $questionID . '|';
$question_order_id = 1;
$option_order_array = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$option_order_array[$theQuestionArray['itemCode']] = $question_radioID;
	}
	else {
		$option_order_array[$question_order_id] = $question_radioID;
	}

	if ($theQuestionArray['isHaveText'] == 1) {
		$theCsvColNum++;

		if ($theQuestionArray['itemCode'] != 0) {
			$this_fields_list .= $theCsvColNum . '#24#0#TextOtherValue_' . $questionID . '_' . $question_radioID . '#' . $theStartColNum . '#' . $theQuestionArray['itemCode'] . '|';
		}
		else {
			$this_fields_list .= $theCsvColNum . '#24#0#TextOtherValue_' . $questionID . '_' . $question_radioID . '#' . $theStartColNum . '#' . $question_order_id . '|';
		}
	}

	$question_order_id++;
}

$option_tran_array[$questionID] = $option_order_array;

?>

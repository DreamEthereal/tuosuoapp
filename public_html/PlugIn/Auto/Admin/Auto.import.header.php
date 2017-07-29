<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['isSelect'] == 1) {
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

	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#17#' . $questionID . '#option_' . $questionID . '#1|';

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

	$option_tran_array[$questionID] = $option_order_array;
}
else {
	$theCsvColNum++;
	$theColStartNum = $theCsvColNum;
	$option_order_array = array();

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$option_order_array[$theCsvColNum] = $question_checkboxID;
		$theCsvColNum++;
	}

	$thisCsvColNum = $theCsvColNum - 1;

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$thisCsvColNum++;
		$option_order_array[$thisCsvColNum] = '0';
	}

	if ($theQtnArray['isCheckType'] == 1) {
		$thisCsvColNum++;
		$option_order_array[$thisCsvColNum] = '99999';
	}

	$theColEndNum = $theCsvColNum = $thisCsvColNum;
	$this_fields_list .= $theColStartNum . '#17#' . $questionID . '#option_' . $questionID . '#0#' . $theColEndNum . '|';
	$option_tran_array[$questionID] = $option_order_array;
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theCsvColNum++;
$theColStartNum = $theCsvColNum;
$option_order_array = array();

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$option_order_array[$theCsvColNum] = $question_checkboxID;

	if ($theQuestionArray['isHaveText'] == 1) {
		$theCsvColNum++;
		$theTextID = $theCsvColNum . '#25#0#TextOtherValue_' . $questionID . '_' . $question_checkboxID;
		$this_fields_list .= $theTextID . '|';
	}

	$theCsvColNum++;
}

$theCsvColNum--;
$theColEndNum = $theCsvColNum;
$this_fields_list .= $theColStartNum . '#25#' . $questionID . '#option_' . $questionID . '#' . $theColEndNum . '|';
$option_tran_array[$questionID] = $option_order_array;

?>

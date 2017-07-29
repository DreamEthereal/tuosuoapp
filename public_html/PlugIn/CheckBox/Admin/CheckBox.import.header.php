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
	$theCsvColNum++;
}

$thisCsvColNum = $theCsvColNum - 1;
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$thisCsvColNum++;
	$option_order_array[$thisCsvColNum] = '0';
}

if ($theQtnArray['isNeg'] == 1) {
	$thisCsvColNum++;
	$option_order_array[$thisCsvColNum] = '99999';
}

$theColEndNum = $theCsvColNum = $thisCsvColNum;
$this_fields_list .= $theColStartNum . '#3#' . $questionID . '#option_' . $questionID . '#' . $theColEndNum . '#' . $theQtnArray['isNeg'] . '|';
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#3#0#TextOtherValue_' . $questionID . '#' . $theColEndNum . '#' . $theQtnArray['isNeg'] . '|';
}

$option_tran_array[$questionID] = $option_order_array;

?>

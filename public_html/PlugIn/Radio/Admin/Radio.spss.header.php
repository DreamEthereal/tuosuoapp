<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$varName = $theQtnArray['alias'];
}
else {
	$varName = 'VAR' . $questionID;
}

$header .= ',"' . $varName . '"';
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

$this_fields_list .= '2#' . $questionID . '#option_' . $questionID . '#TextOtherValue_' . $questionID . '|';
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$option_order_array[$theQtnArray['otherCode']] = 0;
		$header .= ',"' . $varName . '_' . $theQtnArray['otherCode'] . '_text' . '"';
	}
	else {
		$option_order_array[99] = 0;
		$header .= ',"' . $varName . '_99_text' . '"';
	}

	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

if ($theQtnArray['isSelect'] == 1) {
	$header .= ',"' . $VarName . '"';
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
		if ($theQtnArray['negCode'] != 0) {
			$option_order_array[$theQtnArray['negCode']] = 99999;
		}
		else {
			$option_order_array[99999] = 99999;
		}
	}

	$option_tran_array[$questionID] = array_flip($option_order_array);
}
else {
	$i = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$i++;

		if ($theQuestionArray['itemCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '"';
		}
		else {
			$header .= ',"' . $VarName . '_' . $i . '"';
		}

		$this_fields_list .= '17#option_' . $questionID . '#' . $question_checkboxID . '#' . $theQtnArray['isSelect'] . '|';
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		if ($theBaseQtnArray['otherCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '"';
		}
		else {
			$header .= ',"' . $VarName . '_99' . '"';
		}

		$this_fields_list .= '17#option_' . $questionID . '#0#' . $theQtnArray['isSelect'] . '|';
	}

	if ($theQtnArray['isCheckType'] == '1') {
		if ($theQtnArray['negCode'] != 0) {
			$negCode = $theQtnArray['negCode'];
		}
		else {
			$negCode = '99999';
		}

		if ($theQtnArray['alias'] != '') {
			$header .= ',"' . $theQtnArray['alias'] . '_' . $negCode . '"';
		}
		else {
			$header .= ',"' . 'VAR' . $questionID . '_' . $negCode . '"';
		}

		$this_fields_list .= '17#option_' . $questionID . '#99999#' . $theQtnArray['isSelect'] . '|';
	}
}

?>

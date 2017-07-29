<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$header .= ',"' . $VarName . '"';
$this_fields_list .= '24#' . $questionID . '#option_' . $questionID . '|';
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
		if ($theQuestionArray['itemCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '_text' . '"';
		}
		else {
			$header .= ',"' . $VarName . '_' . $question_order_id . '_text' . '"';
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_radioID . '|';
	}

	$question_order_id++;
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

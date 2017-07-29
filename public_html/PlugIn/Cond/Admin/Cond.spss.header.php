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

if ($theQtnArray['isSelect'] == 0) {
	$header .= ',"' . $VarName . '"';
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

	$this_fields_list .= '18#' . $questionID . '#option_' . $questionID . '#' . $theQtnArray['isSelect'] . '|';
	$option_tran_array[$questionID] = array_flip($option_order_array);
}
else {
	$i = 0;

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$i++;

		if ($theQuestionArray['itemCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '"';
		}
		else {
			$header .= ',"' . $VarName . '_' . $i . '"';
		}

		$this_fields_list .= '18#option_' . $questionID . '#' . $question_yesnoID . '#' . $theQtnArray['isSelect'] . '|';
	}
}

?>

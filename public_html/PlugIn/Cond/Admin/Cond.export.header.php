<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == '0') {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$option_order_array = array();

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$option_order_array[$question_yesnoID] = qshowexportquotechar($theQuestionArray['optionName']);
	}

	$this_fields_list .= '18#' . $theQtnArray['isSelect'] . '#' . $questionID . '#option_' . $questionID . '|';
	$option_tran_array[$questionID] = $option_order_array;
}
else {
	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
		$this_fields_list .= '18#' . $theQtnArray['isSelect'] . '#option_' . $questionID . '#' . $question_yesnoID . '#' . $questionID . '|';
		$option_order_array[$question_yesnoID] = qshowexportquotechar($theQuestionArray['optionName']);
	}

	$option_tran_array[$questionID] = $option_order_array;
}

?>

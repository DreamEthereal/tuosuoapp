<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastOptionId = count($OptionListArray[$questionID]) - 1;
$tmp = 0;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$this_fields_list .= '7#' . $questionID . '#option_' . $questionID . '_' . $question_range_optionID . '|';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

$question_order_id = 1;
$option_order_array = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	if ($theAnswerArray['itemCode'] != 0) {
		$option_order_array[$theAnswerArray['itemCode']] = $question_range_answerID;
	}
	else {
		$option_order_array[$question_order_id] = $question_range_answerID;
	}

	$question_order_id++;
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

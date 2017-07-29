<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
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

$i = 0;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$i++;
	$j = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$j++;

		if ($theQtnArray['alias'] != '') {
			$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '_' . $j . '"';
		}
		else {
			$header .= ',"' . 'VAR' . $questionID . '_' . $i . '_' . $j . '"';
		}

		$this_fields_list .= '26#' . $questionID . '#option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID . '|';
	}
}

$option_tran_array[$questionID] = array_flip($option_order_array);

?>

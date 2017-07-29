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

$i = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$i++;

	if ($theQuestionArray['itemCode'] != 0) {
		$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '"';
	}
	else {
		$header .= ',"' . $VarName . '_' . $i . '"';
	}

	$this_fields_list .= '19#' . $questionID . '#option_' . $questionID . '_' . $question_checkboxID . '|';
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	if ($theBaseQtnArray['otherCode'] != 0) {
		$header .= ',"' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '"';
	}
	else {
		$header .= ',"' . $VarName . '_99' . '"';
	}

	$this_fields_list .= '19#' . $questionID . '#option_' . $questionID . '_0' . '|';
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

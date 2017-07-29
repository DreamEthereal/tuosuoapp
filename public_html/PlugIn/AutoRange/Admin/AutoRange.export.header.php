<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '19#' . $questionID . '#option_' . $questionID . '_' . $question_checkboxID . '|';
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theBaseQtnArray['otherText']) . '"';
	$this_fields_list .= '19#' . $questionID . '#option_' . $questionID . '_0' . '|';
}

$option_order_array = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$option_order_array[$question_range_answerID] = qshowexportquotechar($theAnswerArray['optionAnswer']);
}

$option_tran_array[$questionID] = $option_order_array;

?>

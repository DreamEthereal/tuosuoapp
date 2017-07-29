<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$option_order_array = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . qshowexportquotechar($theAnswerArray['optionAnswer']) . '"';
		$this_fields_list .= '28#option_' . $questionID . '_' . $question_checkboxID . '#' . $question_range_answerID . '#' . $questionID . '|';
		$option_order_array[$question_range_answerID] = qshowexportquotechar($theAnswerArray['optionAnswer']);
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theBaseQtnArray['otherText']) . ' - ' . qshowexportquotechar($theAnswerArray['optionAnswer']) . '"';
		$this_fields_list .= '28#option_' . $questionID . '_0#' . $question_range_answerID . '#' . $questionID . '|';
		$option_order_array[$question_range_answerID] = qshowexportquotechar($theAnswerArray['optionAnswer']);
	}
}

$option_tran_array[$questionID] = $option_order_array;

?>

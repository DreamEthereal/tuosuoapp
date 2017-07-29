<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$option_order_array = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$option_order_array[$question_range_answerID] = qshowexportquotechar($theAnswerArray['optionAnswer']);
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . qshowexportquotechar($theLabelArray['optionLabel']) . '"';
		$this_fields_list .= '26#' . $questionID . '#option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID . '|';
	}
}

$option_tran_array[$questionID] = $option_order_array;

?>

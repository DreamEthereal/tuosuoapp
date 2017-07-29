<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$tmp = 0;
$lastOptionId = count($OptionListArray[$questionID]) - 1;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '6#' . $questionID . '#option_' . $questionID . '_' . $question_range_optionID . '|';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - Text' . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

$option_order_array = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$option_order_array[$question_range_answerID] = qshowexportquotechar($theAnswerArray['optionAnswer']);
}

$option_tran_array[$questionID] = $option_order_array;

?>

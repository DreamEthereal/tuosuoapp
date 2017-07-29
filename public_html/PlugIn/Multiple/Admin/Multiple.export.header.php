<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$tmp = 0;
$lastOptionId = count($OptionListArray[$questionID]) - 1;
$option_order_array = array();

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . qshowexportquotechar($theAnswerArray['optionAnswer']) . '"';
		$this_fields_list .= '7#option_' . $questionID . '_' . $question_range_optionID . '#' . $question_range_answerID . '#' . $questionID . '|';
		$option_order_array[$question_range_answerID] = qshowexportquotechar($theAnswerArray['optionAnswer']);
	}

	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - Text' . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

$option_tran_array[$questionID] = $option_order_array;

?>

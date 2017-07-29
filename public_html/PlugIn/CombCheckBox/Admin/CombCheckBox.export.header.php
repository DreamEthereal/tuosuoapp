<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '25#option_' . $questionID . '#' . $question_checkboxID . '#' . $questionID . '|';
	$option_order_array[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);

	if ($theQuestionArray['isHaveText'] == 1) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - Text' . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
	}
}

$option_tran_array[$questionID] = $option_order_array;

?>

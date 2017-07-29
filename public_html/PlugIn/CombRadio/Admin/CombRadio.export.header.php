<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
$this_fields_list .= '24#' . $questionID . '#option_' . $questionID . '|';
$option_order_array = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$option_order_array[$question_radioID] = qshowexportquotechar($theQuestionArray['optionName']);

	if ($theQuestionArray['isHaveText'] == 1) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - Text' . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_radioID . '|';
	}
}

$option_tran_array[$questionID] = $option_order_array;

?>

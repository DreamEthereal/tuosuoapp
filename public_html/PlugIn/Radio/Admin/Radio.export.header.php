<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
$option_order_array = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$option_order_array[$question_radioID] = qshowexportquotechar($theQuestionArray['optionName']);
}

$this_fields_list .= '2#' . $questionID . '#option_' . $questionID . '#TextOtherValue_' . $questionID . '#' . qshowexportquotechar($theQtnArray['otherText']) . '|';
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$option_order_array[0] = qshowexportquotechar($theQtnArray['otherText']);
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQtnArray['otherText']) . ' - Text' . '"';
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

$option_tran_array[$questionID] = $option_order_array;

?>

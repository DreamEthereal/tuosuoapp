<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '3#option_' . $questionID . '#' . $question_checkboxID . '#' . $questionID . '|';
	$option_order_array[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$option_order_array[0] = qshowexportquotechar($theQtnArray['otherText']);
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQtnArray['otherText']) . '"';
	$this_fields_list .= '3#option_' . $questionID . '#0' . '#' . $questionID . '|';
}

if ($theQtnArray['isNeg'] == '1') {
	$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qshowexportquotechar($theQtnArray['allowType']));
	$option_order_array[99999] = $negText;
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . $negText . '"';
	$this_fields_list .= '3#option_' . $questionID . '#99999' . '#' . $questionID . '|';
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQtnArray['otherText']) . ' - Text' . '"';
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

$option_tran_array[$questionID] = $option_order_array;

?>

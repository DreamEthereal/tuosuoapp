<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['isSelect'] == '1') {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$option_order_array = array();

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$option_order_array[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);
	}

	$this_fields_list .= '17#' . $theQtnArray['isSelect'] . '#' . $questionID . '#option_' . $questionID . '|';

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$option_order_array[0] = qshowexportquotechar($theBaseQtnArray['otherText']);
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qshowexportquotechar($theQtnArray['allowType']));
		$option_order_array[99999] = $negText;
	}

	$option_tran_array[$questionID] = $option_order_array;
}
else {
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
		$this_fields_list .= '17#' . $theQtnArray['isSelect'] . '#option_' . $questionID . '#' . $question_checkboxID . '#' . $questionID . '|';
		$option_order_array[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$option_order_array[0] = qshowexportquotechar($theBaseQtnArray['otherText']);
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theBaseQtnArray['otherText']) . '"';
		$this_fields_list .= '17#' . $theQtnArray['isSelect'] . '#option_' . $questionID . '#0' . '#' . $questionID . '|';
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qshowexportquotechar($theQtnArray['allowType']));
		$option_order_array[99999] = $negText;
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . $negText . '"';
		$this_fields_list .= '17#' . $theQtnArray['isSelect'] . '#option_' . $questionID . '#99999' . '#' . $questionID . '|';
	}

	$option_tran_array[$questionID] = $option_order_array;
}

?>

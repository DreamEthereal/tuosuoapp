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
	$tempi = 1;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theCsvColNum++;
		$option_order_array[$theCsvColNum] = $question_range_answerID;

		if ($tempi == 1) {
			$theColStartNum = $theCsvColNum;
		}

		if ($tempi == count($AnswerListArray[$questionID])) {
			$theColEndNum = $theCsvColNum;
		}

		$tempi++;
	}

	$this_fields_list .= $theColStartNum . '#28#' . $questionID . '#option_' . $questionID . '_' . $question_checkboxID . '#' . $theColEndNum . '|';
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$tempi = 1;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theCsvColNum++;
		$option_order_array[$theCsvColNum] = $question_range_answerID;

		if ($tempi == 1) {
			$theColStartNum = $theCsvColNum;
		}

		if ($tempi == count($AnswerListArray[$questionID])) {
			$theColEndNum = $theCsvColNum;
		}

		$tempi++;
	}

	$this_fields_list .= $theColStartNum . '#28#' . $questionID . '#option_' . $questionID . '_0#' . $theColEndNum . '|';
}

$option_tran_array[$questionID] = $option_order_array;

?>

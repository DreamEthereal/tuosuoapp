<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$option_order_array = array();
$tmp = 0;
$lastOptionId = count($OptionListArray[$questionID]) - 1;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
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

	$this_fields_list .= $theColStartNum . '#7#' . $questionID . '#option_' . $questionID . '_' . $question_range_optionID . '#' . $theColEndNum . '|';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#7#0#TextOtherValue_' . $questionID . '#' . $theColEndNum . '#' . $theColStartNum . '|';
	}

	$tmp++;
}

$option_tran_array[$questionID] = $option_order_array;

?>

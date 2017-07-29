<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['alias'] != '') {
	$varName = $theQtnArray['alias'];
}
else {
	$varName = 'VAR' . $questionID;
}

$i = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$i++;
	$j = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;

		if ($theQuestionArray['itemCode'] != 0) {
			if ($theAnswerArray['itemCode'] != 0) {
				$header .= ',"' . $varName . '_' . $theQuestionArray['itemCode'] . '_' . $theAnswerArray['itemCode'] . '"';
			}
			else {
				$header .= ',"' . $varName . '_' . $theQuestionArray['itemCode'] . '_' . $j . '"';
			}
		}
		else if ($theAnswerArray['itemCode'] != 0) {
			$header .= ',"' . $varName . '_' . $i . '_' . $theAnswerArray['itemCode'] . '"';
		}
		else {
			$header .= ',"' . $varName . '_' . $i . '_' . $j . '"';
		}

		$this_fields_list .= '28#option_' . $questionID . '_' . $question_checkboxID . '#' . $question_range_answerID . '|';
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$j = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;

		if ($theBaseQtnArray['otherCode'] != 0) {
			if ($theAnswerArray['itemCode'] != 0) {
				$header .= ',"' . $varName . '_' . $theBaseQtnArray['otherCode'] . '_' . $theAnswerArray['itemCode'] . '"';
			}
			else {
				$header .= ',"' . $varName . '_' . $theBaseQtnArray['otherCode'] . '_' . $j . '"';
			}
		}
		else if ($theAnswerArray['itemCode'] != 0) {
			$header .= ',"' . $varName . '_99_' . $theAnswerArray['itemCode'] . '"';
		}
		else {
			$header .= ',"' . $varName . '_99_' . $j . '"';
		}

		$this_fields_list .= '28#option_' . $questionID . '_0#' . $question_range_answerID . '|';
	}
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

switch ($QtnListArray[$questionID]['isSelect']) {
case '1':
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#21#' . $questionID . '#option_' . $questionID . '_' . $question_checkboxID . '#1#' . $QtnListArray[$questionID]['startScale'] . '#' . $QtnListArray[$questionID]['endScale'] . '|';

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#21#0#TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#21#' . $questionID . '#option_' . $questionID . '_0#1#' . $QtnListArray[$questionID]['startScale'] . '#' . $QtnListArray[$questionID]['endScale'] . '|';

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#21#0#TextOtherValue_' . $questionID . '_0|';
		}
	}

	break;

case '0':
	$theRankNumber = '';
	$i = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $i; $i--) {
		$theRankNumber .= ($QtnListArray[$questionID]['weight'] * $i) . '*';
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$theRankNumber .= '99*';
	}

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#21#' . $questionID . '#option_' . $questionID . '_' . $question_checkboxID . '#0#' . substr($theRankNumber, 0, -1) . '#' . $QtnListArray[$questionID]['weight'] . '|';

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#21#0#TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#21#' . $questionID . '#option_' . $questionID . '_0#0#' . substr($theRankNumber, 0, -1) . '#' . $QtnListArray[$questionID]['weight'] . '|';

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#21#0#TextOtherValue_' . $questionID . '_0|';
		}
	}

	break;

case '2':
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#21#' . $questionID . '#option_' . $questionID . '_' . $question_checkboxID . '#2#' . $QtnListArray[$questionID]['startScale'] . '#' . $QtnListArray[$questionID]['endScale'] . '|';
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#21#' . $questionID . '#option_' . $questionID . '_0#2#' . $QtnListArray[$questionID]['startScale'] . '#' . $QtnListArray[$questionID]['endScale'] . '|';
	}

	break;
}

?>

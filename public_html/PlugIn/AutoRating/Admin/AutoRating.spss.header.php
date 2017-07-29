<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$i = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$i++;

	if ($theQuestionArray['itemCode'] != 0) {
		$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '"';
	}
	else {
		$header .= ',"' . $VarName . '_' . $i . '"';
	}

	$this_fields_list .= '21#' . $theQtnArray['weight'] . '#option_' . $questionID . '_' . $question_checkboxID . '|';

	if ($theQtnArray['isHaveOther'] == '1') {
		if ($theQuestionArray['itemCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '_why_text' . '"';
		}
		else {
			$header .= ',"' . $VarName . '_' . $i . '_why_text' . '"';
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	if ($theBaseQtnArray['otherCode'] != 0) {
		$header .= ',"' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '"';
	}
	else {
		$header .= ',"' . $VarName . '_99' . '"';
	}

	$this_fields_list .= '21#' . $theQtnArray['weight'] . '#option_' . $questionID . '_0' . '|';

	if ($theQtnArray['isHaveOther'] == '1') {
		if ($theBaseQtnArray['otherCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_why_text' . '"';
		}
		else {
			$header .= ',"' . $VarName . '_99_why_text' . '"';
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '_0' . '|';
	}
}

?>

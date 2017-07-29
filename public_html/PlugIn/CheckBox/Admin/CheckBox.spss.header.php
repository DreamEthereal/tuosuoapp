<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$varName = $theQtnArray['alias'];
}
else {
	$varName = 'VAR' . $questionID;
}

$i = 0;

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$i++;

	if ($theQuestionArray['itemCode'] != 0) {
		$header .= ',"' . $varName . '_' . $theQuestionArray['itemCode'] . '"';
	}
	else {
		$header .= ',"' . $varName . '_' . $i . '"';
	}

	$this_fields_list .= '3#option_' . $questionID . '#' . $question_checkboxID . '|';
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$header .= ',"' . $varName . '_' . $theQtnArray['otherCode'] . '"';
	}
	else {
		$header .= ',"' . $varName . '_99' . '"';
	}

	$this_fields_list .= '3#option_' . $questionID . '#0|';
}

if ($theQtnArray['isNeg'] == '1') {
	if ($theQtnArray['negCode'] != 0) {
		$header .= ',"' . $varName . '_' . $theQtnArray['negCode'] . '"';
	}
	else {
		$header .= ',"' . $varName . '_99999' . '"';
	}

	$this_fields_list .= '3#option_' . $questionID . '#99999|';
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$header .= ',"' . $varName . '_' . $theQtnArray['otherCode'] . '_text' . '"';
	}
	else {
		$header .= ',"' . $varName . '_99_text' . '"';
	}

	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

?>

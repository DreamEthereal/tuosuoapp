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
	$j = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$j++;

		if ($theQuestionArray['itemCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '_' . $j . '"';
		}
		else {
			$header .= ',"' . $VarName . '_' . $i . '_' . $j . '"';
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $question_checkboxID . '_' . $question_range_labelID . '|';
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$j = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$j++;

		if ($theBaseQtnArray['otherCode'] != 0) {
			$header .= ',"' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_' . $j . '"';
		}
		else {
			$header .= ',"' . $VarName . '_99_' . $j . '"';
		}

		$this_fields_list .= 'option_' . $questionID . '_0_' . $question_range_labelID . '|';
	}
}

?>

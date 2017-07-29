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
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theLabelArray['optionLabel']) . '\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theLabelArray['optionLabel']) . '\'.' . "\r\n" . '';
		}
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$j = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$j++;

		if ($theBaseQtnArray['otherCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '-' . qconverionlabel($theLabelArray['optionLabel']) . '\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_99_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '-' . qconverionlabel($theLabelArray['optionLabel']) . '\'.' . "\r\n" . '';
		}
	}
}

?>

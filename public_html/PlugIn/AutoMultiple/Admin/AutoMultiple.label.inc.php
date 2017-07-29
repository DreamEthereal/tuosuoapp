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

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;

		if ($theQuestionArray['itemCode'] != 0) {
			if ($theAnswerArray['itemCode'] != 0) {
				$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . '_' . $theAnswerArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
				$content .= ' VALUE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . '_' . $theAnswerArray['itemCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
			}
			else {
				$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
				$content .= ' VALUE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . '_' . $j . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
			}
		}
		else if ($theAnswerArray['itemCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_' . $theAnswerArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $i . '_' . $theAnswerArray['itemCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $i . '_' . $j . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$j = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;

		if ($theBaseQtnArray['otherCode'] != 0) {
			if ($theAnswerArray['itemCode'] != 0) {
				$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_' . $theAnswerArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
				$content .= ' VALUE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_' . $theAnswerArray['itemCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
			}
			else {
				$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
				$content .= ' VALUE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '_' . $j . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
			}
		}
		else if ($theAnswerArray['itemCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_99_' . $theAnswerArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_99_' . $theAnswerArray['itemCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_99_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_99_' . $j . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
	}
}

?>

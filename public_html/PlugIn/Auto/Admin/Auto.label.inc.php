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

if ($theQtnArray['isSelect'] == '1') {
	$content .= ' VARIABLE LABELS ' . $VarName . ' \'' . qconverionlabel($theQtnArray['questionName']) . '\'.' . "\r\n" . '';
	$question_order_id = 1;
	$content .= ' VALUE LABELS ' . $VarName . ' ';

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		if ($theQuestionArray['itemCode'] != 0) {
			$content .= $theQuestionArray['itemCode'] . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
		}
		else {
			$content .= $question_order_id . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
		}

		$question_order_id++;
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		if ($theBaseQtnArray['otherCode'] != 0) {
			$content .= '' . $theBaseQtnArray['otherCode'] . ' \'' . qconverionlabel($theBaseQtnArray['otherText']) . '\' ';
		}
		else {
			$content .= '99 \'' . qconverionlabel($theBaseQtnArray['otherText']) . '\' ';
		}
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qconverionlabel($QtnListArray[$questionID]['allowType']));

		if ($theQtnArray['negCode'] != 0) {
			$content .= $theQtnArray['negCode'] . ' \'' . qconverionlabel($negText) . '\' ';
		}
		else {
			$content .= '99999 \'' . qconverionlabel($negText) . '\' ';
		}
	}

	$content .= '.' . "\r\n" . '';
}
else {
	$i = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$i++;

		if ($theQuestionArray['itemCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $i . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		if ($theBaseQtnArray['otherCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_99 \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_99 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qconverionlabel($QtnListArray[$questionID]['allowType']));

		if ($theQtnArray['negCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQtnArray['negCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . $negText . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $theQtnArray['negCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_99999 \'' . qconverionlabel($theQtnArray['questionName']) . '-' . $negText . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_99999 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
	}
}

?>

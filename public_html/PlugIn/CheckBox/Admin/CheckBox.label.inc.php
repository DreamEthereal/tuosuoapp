<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$i = 0;

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
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

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQtnArray['otherCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '\'.' . "\r\n" . '';
		$content .= ' VALUE LABELS ' . $VarName . '_' . $theQtnArray['otherCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
	}
	else {
		$content .= ' VARIABLE LABELS ' . $VarName . '_99 \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '\'.' . "\r\n" . '';
		$content .= ' VALUE LABELS ' . $VarName . '_99 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
	}
}

if ($theQtnArray['isNeg'] == '1') {
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

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQtnArray['otherCode'] . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '-Text\'.' . "\r\n" . '';
	}
	else {
		$content .= ' VARIABLE LABELS ' . $VarName . '_99_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '-Text\'.' . "\r\n" . '';
	}
}

?>

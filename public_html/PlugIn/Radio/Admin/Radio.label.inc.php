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

$content .= ' VARIABLE LABELS ' . $VarName . ' \'' . qconverionlabel($theQtnArray['questionName']) . '\'.' . "\r\n" . '';
$thecontent = ' VALUE LABELS ' . $VarName . ' ';
$question_order_id = 1;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$thecontent .= $theQuestionArray['itemCode'] . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
	}
	else {
		$thecontent .= $question_order_id . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
	}

	$question_order_id++;
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		$thecontent .= $theQtnArray['otherCode'] . ' \'' . qconverionlabel($theQtnArray['otherText']) . '\' ';
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQtnArray['otherCode'] . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '-Text\'.' . "\r\n" . '';
	}
	else {
		$thecontent .= ' 99 \'' . qconverionlabel($theQtnArray['otherText']) . '\' ';
		$content .= ' VARIABLE LABELS ' . $VarName . '_99_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '-Text\'.' . "\r\n" . '';
	}
}

$thecontent .= '.' . "\r\n" . '';
$content .= $thecontent;

?>

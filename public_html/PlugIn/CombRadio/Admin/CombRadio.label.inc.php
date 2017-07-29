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
$content .= ' VALUE LABELS ' . $VarName . ' ';
$question_order_id = 1;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		$content .= $theQuestionArray['itemCode'] . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
	}
	else {
		$content .= $question_order_id . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
	}

	$question_order_id++;
}

$content .= '.' . "\r\n" . '';
$question_order_id = 1;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['isHaveText'] == 1) {
		if ($theQuestionArray['itemCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-Text\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $question_order_id . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-Text\'.' . "\r\n" . '';
		}
	}

	$question_order_id++;
}

?>

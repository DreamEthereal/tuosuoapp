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

if ($theQtnArray['isSelect'] == '0') {
	$content .= ' VARIABLE LABELS ' . $VarName . ' \'' . qconverionlabel($theQtnArray['questionName']) . '\'.' . "\r\n" . '';
	$question_order_id = 1;
	$content .= ' VALUE LABELS ' . $VarName . ' ';

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		if ($theQuestionArray['itemCode'] != 0) {
			$content .= $theQuestionArray['itemCode'] . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
		}
		else {
			$content .= $question_order_id . ' \'' . qconverionlabel($theQuestionArray['optionName']) . '\' ';
		}

		$question_order_id++;
	}

	$content .= '.' . "\r\n" . '';
}
else {
	$i = 0;

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
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
}

?>

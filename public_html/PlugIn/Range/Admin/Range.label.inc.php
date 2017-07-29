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
$lastOptionId = count($OptionListArray[$questionID]) - 1;
$tmp = 0;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$i++;
	$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
	$question_order_id = 1;
	$content .= ' VALUE LABELS ' . $VarName . '_' . $i . ' ';

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		if ($theAnswerArray['itemCode'] != 0) {
			$content .= $theAnswerArray['itemCode'] . ' \'' . qconverionlabel($theAnswerArray['optionAnswer']) . '\' ';
		}
		else {
			$content .= $question_order_id . ' \'' . qconverionlabel($theAnswerArray['optionAnswer']) . '\' ';
		}

		$question_order_id++;
	}

	$content .= '.' . "\r\n" . '';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-Text\'.' . "\r\n" . '';
	}

	$tmp++;
}

?>

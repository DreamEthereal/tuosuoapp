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

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$i++;
	$j = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$j++;
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theLabelArray['optionLabel']) . '\'.' . "\r\n" . '';
		$content .= ' VALUE LABELS ' . $VarName . '_' . $i . '_' . $j . ' ';
		$question_order_id = 1;

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
	}
}

?>

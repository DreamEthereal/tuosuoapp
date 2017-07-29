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
	$j = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;

		if ($theAnswerArray['itemCode'] != 0) {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_' . $theAnswerArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $i . '_' . $theAnswerArray['itemCode'] . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
		else {
			$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_' . $j . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-' . qconverionlabel($theAnswerArray['optionAnswer']) . '\'.' . "\r\n" . '';
			$content .= ' VALUE LABELS ' . $VarName . '_' . $i . '_' . $j . ' 1 \'Selected\' 0 \'UnSelected\'.' . "\r\n" . '';
		}
	}

	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-Text\'.' . "\r\n" . '';
	}

	$tmp++;
}

?>

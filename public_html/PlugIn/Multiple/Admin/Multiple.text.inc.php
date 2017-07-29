<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_multi'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";
$content .= "\r\n";
$content .= $lang['txt_row'];
$optionTotalNum = count($OptionListArray[$questionID]);
$tmp = 0;
$lastOptionId = $optionTotalNum - 1;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	if ($theQtnArray['isHaveOther'] != 1) {
		$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
	}
	else if ($tmp != $lastOptionId) {
		$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
	}
	else {
		$content .= $lang['txt_other_1'] . qshowquotechar($theQuestionArray['optionName']) . "\r\n";
	}

	$tmp++;
}

$content .= "\r\n";
$content .= $lang['txt_col'];

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$content .= qshowquotechar($theAnswerArray['optionAnswer']) . "\r\n";
}

?>

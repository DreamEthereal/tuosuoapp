<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_combrange'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";
$content .= $lang['txt_row'];

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

$content .= $lang['txt_col'];

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	$content .= qshowquotechar($theLabelArray['optionLabel']) . "\r\n";
}

$content .= $lang['txt_cell'];

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$content .= qshowquotechar($theAnswerArray['optionAnswer']) . "\r\n";
}

?>

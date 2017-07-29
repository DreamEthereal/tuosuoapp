<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_multipletext'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";
$content .= $lang['txt_row'];

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

$content .= $lang['txt_col'];

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	$content .= qshowquotechar($theLabelArray['optionLabel']) . "\r\n";
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_checkbox'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

if ($theQtnArray['isHaveOther'] == 1) {
	$content .= $lang['txt_other_1'] . qshowquotechar($theQtnArray['otherText']) . "\r\n";
}

if ($theQtnArray['isNeg'] == 1) {
	$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qshowquotechar($theQtnArray['allowType']));
	$content .= $lang['txt_neg_1'] . $negText . "\r\n";
}

?>

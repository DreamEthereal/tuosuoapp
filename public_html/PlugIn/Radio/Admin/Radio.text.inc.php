<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_radio'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

if ($theQtnArray['isHaveOther'] == 1) {
	$content .= $lang['txt_other_1'] . qshowquotechar($theQtnArray['otherText']) . "\r\n";
}

?>

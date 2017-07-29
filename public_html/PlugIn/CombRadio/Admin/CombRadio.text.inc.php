<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_combradio'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_combcheckbox'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

?>

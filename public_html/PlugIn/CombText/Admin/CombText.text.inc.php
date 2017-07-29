<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_combtext'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

?>

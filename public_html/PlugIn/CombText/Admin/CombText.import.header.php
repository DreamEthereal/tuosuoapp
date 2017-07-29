<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#23#option_' . $questionID . '_' . $question_yesnoID . '#' . $theQtnArray['isHaveUnkown'] . '#isHaveUnkown_' . $questionID . '_' . $question_yesnoID . '|';
}

?>

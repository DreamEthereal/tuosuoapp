<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$i = 0;

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$i++;

	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_' . $i . '"';
	}

	if ($theQtnArray['isHaveUnkown'] == 2) {
		$this_fields_list .= '23#option_' . $questionID . '_' . $question_yesnoID . '#isHaveUnkown_' . $questionID . '_' . $question_yesnoID . '|';
	}
	else {
		$this_fields_list .= 'option_' . $questionID . '_' . $question_yesnoID . '|';
	}
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$this_fields_list .= 'option_' . $questionID . '_' . $question_yesnoID . '|';
	$this_fileds_type .= 'optionchar|';

	if ($theQtnArray['isHaveUnkown'] == 2) {
		$this_fields_list .= 'isHaveUnkown_' . $questionID . '_' . $question_yesnoID . '|';
		$this_fileds_type .= 'int|';
	}
}

?>

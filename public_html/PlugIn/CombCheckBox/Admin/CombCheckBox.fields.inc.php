<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$this_fields_list .= 'option_' . $questionID . '|';
$this_fileds_type .= 'multichar|';

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	if ($theQuestionArray['isHaveText'] == 1) {
		$theTextID = 'TextOtherValue_' . $questionID . '_' . $question_checkboxID;
		$this_fields_list .= $theTextID . '|';
		$this_fileds_type .= 'optionchar|';
	}
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$this_fields_list .= 'option_' . $questionID . '|';
$this_fileds_type .= 'int|';
$this_index_fields .= 'option_' . $questionID . '|';

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['isHaveText'] == 1) {
		$theTextID = 'TextOtherValue_' . $questionID . '_' . $question_radioID;
		$this_fields_list .= $theTextID . '|';
		$this_fileds_type .= 'optionchar|';
	}
}

?>

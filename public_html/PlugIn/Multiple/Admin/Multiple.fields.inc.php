<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '|';
	$this_fileds_type .= 'multichar|';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	$this_fileds_type .= 'otherchar|';
}

?>

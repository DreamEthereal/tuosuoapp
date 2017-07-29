<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$this_fields_list .= 'option_' . $questionID . '_' . $question_rankID . '|';
	$this_fileds_type .= 'float|';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	$this_fileds_type .= 'otherchar|';
}

?>

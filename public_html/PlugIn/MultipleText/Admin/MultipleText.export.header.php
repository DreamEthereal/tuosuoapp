<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . qshowexportquotechar($theLabelArray['optionLabel']) . '"';
		$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID . '|';
	}
}

?>

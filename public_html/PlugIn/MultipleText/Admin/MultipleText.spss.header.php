<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$i = 0;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$i++;
	$j = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$j++;

		if ($theQtnArray['alias'] != '') {
			$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '_' . $j . '"';
		}
		else {
			$header .= ',"' . 'VAR' . $questionID . '_' . $i . '_' . $j . '"';
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID . '|';
	}
}

?>

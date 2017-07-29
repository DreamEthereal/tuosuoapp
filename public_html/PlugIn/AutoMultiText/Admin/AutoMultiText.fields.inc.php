<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionAutoArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionAutoArray[$question_checkboxID] = $theQuestionArray['optionName'];
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionAutoArray[0] = $theBaseQtnArray['otherText'];
}

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$theOptionID = 'option_' . $questionID . '_' . $optionAutoID . '_' . $question_range_labelID;
		$this_fields_list .= $theOptionID . '|';
		$this_fileds_type .= 'mtchar|';
	}
}

?>

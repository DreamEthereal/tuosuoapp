<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . qshowexportquotechar($theLabelArray['optionLabel']) . '"';
		$this_fields_list .= 'option_' . $questionID . '_' . $question_checkboxID . '_' . $question_range_labelID . '|';
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theBaseQtnArray['otherText']) . ' - ' . qshowexportquotechar($theLabelArray['optionLabel']) . '"';
		$this_fields_list .= 'option_' . $questionID . '_0_' . $question_range_labelID . '|';
	}
}

?>

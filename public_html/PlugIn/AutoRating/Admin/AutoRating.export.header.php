<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '21#' . $theQtnArray['weight'] . '#option_' . $questionID . '_' . $question_checkboxID . '|';

	if ($theQtnArray['isHaveOther'] == '1') {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . $lang['why_your_rating'] . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theBaseQtnArray['otherText']) . '"';
	$this_fields_list .= '21#' . $theQtnArray['weight'] . '#option_' . $questionID . '_0' . '|';

	if ($theQtnArray['isHaveOther'] == '1') {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theBaseQtnArray['otherText']) . ' - ' . $lang['why_your_rating'] . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '_0' . '|';
	}
}

?>

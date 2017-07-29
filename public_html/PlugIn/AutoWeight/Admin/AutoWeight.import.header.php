<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#22#option_' . $questionID . '_' . $question_checkboxID . '|';
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#22#option_' . $questionID . '_0|';
}

?>

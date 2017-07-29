<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RatingCond.html');
$EnableQCoreClass->replace('questionID', $theQtnArray['questionID']);
$EnableQCoreClass->replace('thisNo', $thisNo);
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionList = '';

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$optionList .= '<option value=\'' . $question_checkboxID . '\'>' . $optionName . '</option>' . "\n" . '';
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionList .= '<option value=\'0\'>' . qnospecialchar($theBaseQtnArray['otherText']) . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('optionList', $optionList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

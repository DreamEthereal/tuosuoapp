<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeCond.html');
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
$valueList = '';

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
	$valueList .= '<option value=\'' . $question_range_answerID . '\'>' . $optionAnswer . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('valueList', $valueList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

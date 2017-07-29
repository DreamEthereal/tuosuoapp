<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRangeCond.html');
$EnableQCoreClass->replace('questionID', $theQtnArray['questionID']);
$EnableQCoreClass->replace('thisNo', $thisNo);
$optionList = '';

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$optionList .= '<option value=\'' . $question_range_optionID . '\'>' . $optionName . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('optionList', $optionList);
$labelList = '';

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	$optionLabel = qnospecialchar($theLabelArray['optionLabel']);
	$labelList .= '<option value=\'' . $question_range_labelID . '\'>' . $optionLabel . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('labelList', $labelList);
$valueList = '';

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
	$valueList .= '<option value=\'' . $question_range_answerID . '\'>' . $optionAnswer . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('valueList', $valueList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

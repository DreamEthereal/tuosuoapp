<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'MultipleTextView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->set_CycBlock('OPTION', 'SUBOPTION', 'suboption' . $questionID);
$EnableQCoreClass->replace('suboption' . $questionID, '');
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ANSWER', 'answer' . $questionID);
$EnableQCoreClass->replace('answer' . $questionID, '');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_29'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$Label = array();

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	$EnableQCoreClass->replace('optionLabelName', qnospecialchar($theLabelArray['optionLabel']));
	$Label[$question_range_labelID] = $theLabelArray['optionLabel'];
	$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('optionName', $optionName);

	foreach ($Label as $question_range_labelID => $optionLabel) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . '_' . $question_range_labelID . ' != \'\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('surveyID', $surveyID);
		$EnableQCoreClass->replace('questionID', $questionID);
		$EnableQCoreClass->replace('textType', 'automultitext');
		$EnableQCoreClass->replace('surveyName', urlencode($_GET['surveyTitle']));
		$EnableQCoreClass->replace('optionID', $question_checkboxID);
		$EnableQCoreClass->replace('labelID', $question_range_labelID);
		$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('suboption' . $questionID);
}

unset($Label);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qcrossqtnname($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qcrossqtnname($theBaseQtnArray['otherText']);
}

$t = 0;

foreach ($optionArray as $question_checkboxID => $optionName) {
	$Headings[$t] = $optionName;

	switch ($QtnListArray[$questionID]['isSelect']) {
	case '0':
		$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_checkboxID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'99\' and ' . $dataSource;
		break;

	case '1':
		$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_checkboxID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
		break;

	case '2':
		$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_checkboxID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' and ' . $dataSource;
		break;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[$t] = number_format($OptionCountRow['item_avg_answerNum'] * $QtnListArray[$questionID]['weight'], 2);
	$t++;
}

?>

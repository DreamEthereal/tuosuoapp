<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'WeightView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_22'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
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
	$EnableQCoreClass->replace('subQuestionName', $optionName);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$optionPercent = countpercent($thisOptionResponseNum, $totalResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', $optionPercent);
	$thisSkipNum = $totalResponseNum - $thisOptionResponseNum;
	$EnableQCoreClass->replace('skip_answerNum', $thisSkipNum);
	$optionPercent = countpercent($thisSkipNum, $totalResponseNum);
	$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
	$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_checkboxID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_checkboxID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum,STDDEV(option_' . $questionID . '_' . $question_checkboxID . ') as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

	if ($OptionCountRow) {
		$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum']);
		$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum']);
		$EnableQCoreClass->replace('item_tal_answerNum', $OptionCountRow['item_sum_answerNum']);
		$EnableQCoreClass->replace('item_std_answerNum', @round($OptionCountRow['item_std_answerNum'], 5));
	}
	else {
		$EnableQCoreClass->replace('item_min_answerNum', 0);
		$EnableQCoreClass->replace('item_max_answerNum', 0);
		$EnableQCoreClass->replace('item_tal_answerNum', 0);
		$EnableQCoreClass->replace('item_std_answerNum', 0);
	}

	if ($thisOptionResponseNum != 0) {
		$EnableQCoreClass->replace('item_avg_answerNum', round($OptionCountRow['item_sum_answerNum'] / $thisOptionResponseNum, 5));
	}
	else {
		$EnableQCoreClass->replace('item_avg_answerNum', 0);
	}

	$OptionCountSQL = ' SELECT option_' . $questionID . '_' . $question_checkboxID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_checkboxID . ' ORDER BY count DESC LIMIT 1 ';
	$ReValueRow = $DB->queryFirstRow($OptionCountSQL);

	if ($ReValueRow) {
		$EnableQCoreClass->replace('item_re_answerNum', $ReValueRow['option_' . $questionID . '_' . $question_checkboxID]);
	}
	else {
		$EnableQCoreClass->replace('item_re_answerNum', 0);
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
}

unset($optionArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qshowexportquotechar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
	$OptionCountSQL = ' SELECT Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$item_avg_answerNum = 0;

	if ($thisOptionResponseNum != 0) {
		$item_avg_answerNum = round($OptionCountRow['item_sum_answerNum'] / $thisOptionResponseNum, 5);
	}

	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . $optionName . '"';
	$content .= ',"' . $item_avg_answerNum . '"';
	$content .= "\r\n";
}

unset($optionArray);

?>

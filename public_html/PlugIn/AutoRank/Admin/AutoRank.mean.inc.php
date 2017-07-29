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
	$optionArray[$question_checkboxID] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('rowName', $optionName);
	$rowValidN = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theRankOptionID . ' != 0 ';
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
		$OptionCountSQL = ' SELECT SUM(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$this_sum_answerNum = $OptionCountRow['item_sum_answerNum'];
		$rowValidN += $thisOptionResponseNum;
		$rowSum += $this_sum_answerNum;
		$EnableQCoreClass->replace('validN', $thisOptionResponseNum);
		$EnableQCoreClass->replace('mean', meanaverage($this_sum_answerNum, $thisOptionResponseNum));
		$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowValidN', $rowValidN);
	$EnableQCoreClass->replace('rowMean', meanaverage($rowSum, $rowValidN));
	$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
}

unset($optionArray);

?>

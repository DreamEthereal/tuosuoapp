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

$isUnkown = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	if ($theAnswerArray['isUnkown'] == 1) {
		$isUnkown[] = $question_range_answerID;
	}
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('rowName', $optionName);
	$rowValidN = $rowValidNum = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$allOptionResponseNum = array();
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.option_' . $questionID . '_' . $question_checkboxID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_checkboxID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		$this_sum_answerNum = 0;
		$thisOptionResponseNum = 0;
		$unkownNum = 0;

		foreach ($allOptionResponseNum as $question_range_answerID => $optionResponseNum) {
			if (!in_array($question_range_answerID, $isUnkown)) {
				$this_sum_answerNum += $AnswerListArray[$questionID][$question_range_answerID]['itemCode'] * $optionResponseNum;
			}
			else {
				$unkownNum += $optionResponseNum;
			}

			$thisOptionResponseNum += $optionResponseNum;
		}

		$rowValidN += $thisOptionResponseNum;
		$rowValidNum += $thisOptionResponseNum - $unkownNum;
		$rowSum += $this_sum_answerNum;
		$EnableQCoreClass->replace('validN', $thisOptionResponseNum);
		$EnableQCoreClass->replace('mean', meanaverage($this_sum_answerNum, $thisOptionResponseNum - $unkownNum));
		$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowValidN', $rowValidN);
	$EnableQCoreClass->replace('rowMean', meanaverage($rowSum, $rowValidNum));
	$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
}

unset($optionArray);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$isUnkown = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	if ($theAnswerArray['isUnkown'] == 1) {
		$isUnkown[] = $question_range_answerID;
	}
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
	$rowValidN = $rowValidNum = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$allOptionResponseNum = array();
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.option_' . $questionID . '_' . $question_range_optionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_range_optionID . ' ORDER BY optionResponseNum DESC';
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

?>

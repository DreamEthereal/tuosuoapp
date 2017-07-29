<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']));
$isUnkown = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['isUnkown'] == 1) {
		$isUnkown[] = $question_radioID;
	}
}

$rowValidN = $rowValidNum = $rowSum = 0;

foreach ($theXName as $k => $thisXName) {
	$allOptionResponseNum = array();
	$OptionSQL = ' SELECT a.question_radioID,count(*) as optionResponseNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_radioID = b.option_' . $questionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allOptionResponseNum[$OptionRow['question_radioID']] = $OptionRow['optionResponseNum'];
	}

	if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE option_' . $questionID . ' = 0 AND TextOtherValue_' . $questionID . ' != \'\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$allOptionResponseNum[0] = $OptionCountRow['optionResponseNum'];
	}

	$this_sum_answerNum = 0;
	$thisOptionResponseNum = 0;
	$unkownNum = 0;

	foreach ($allOptionResponseNum as $question_radioID => $optionResponseNum) {
		if ($question_radioID == 0) {
			if ($QtnListArray[$questionID]['isUnkown'] != 1) {
				$this_sum_answerNum += $QtnListArray[$questionID]['otherCode'] * $optionResponseNum;
			}
			else {
				$unkownNum += $optionResponseNum;
			}
		}
		else if (!in_array($question_radioID, $isUnkown)) {
			$this_sum_answerNum += $RadioListArray[$questionID][$question_radioID]['itemCode'] * $optionResponseNum;
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

?>

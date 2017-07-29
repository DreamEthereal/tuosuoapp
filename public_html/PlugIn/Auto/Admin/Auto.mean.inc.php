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
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
}

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']));
	$rowValidN = $rowSum = $allNoneNum = 0;

	foreach ($theXName as $k => $thisXName) {
		$allOptionResponseNum = array();
		$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND a.question_checkboxID = b.option_' . $questionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
		$OptionResult = $DB->query($OptionSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
		}

		if ($theBaseQtnArray['isHaveOther'] == 1) {
			$OptionSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
			$OptionRow = $DB->queryFirstRow($OptionSQL);
			$allOptionResponseNum[0] = $OptionRow['optionResponseNum'];
		}

		if ($QtnListArray[$questionID]['isCheckType'] == 1) {
			$OptionSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
			$OptionRow = $DB->queryFirstRow($OptionSQL);
			$allOptionResponseNum[99999] = $OptionRow['optionResponseNum'];
			$allNoneNum += $OptionRow['optionResponseNum'];
		}

		$this_sum_answerNum = 0;
		$thisOptionResponseNum = 0;

		foreach ($allOptionResponseNum as $question_checkboxID => $optionResponseNum) {
			if ($question_checkboxID != 99999) {
				if ($question_checkboxID == 0) {
					$this_sum_answerNum += $theBaseQtnArray['otherCode'] * $optionResponseNum;
				}
				else {
					$this_sum_answerNum += $CheckBoxListArray[$theBaseID][$question_checkboxID]['itemCode'] * $optionResponseNum;
				}
			}

			$thisOptionResponseNum += $optionResponseNum;
		}

		$rowValidN += $thisOptionResponseNum;
		$rowSum += $this_sum_answerNum;
		$EnableQCoreClass->replace('validN', $thisOptionResponseNum);
		$EnableQCoreClass->replace('mean', meanaverage($this_sum_answerNum, $thisOptionResponseNum - $allOptionResponseNum[99999]));
		$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowValidN', $rowValidN);
	$EnableQCoreClass->replace('rowMean', meanaverage($rowSum, $rowValidN - $allNoneNum));
	$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
}

?>

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
	$rowValidN = $rowValidNum = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;

		switch ($QtnListArray[$questionID]['isSelect']) {
		case '0':
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theRankOptionID . ' != 0 ';
			break;

		case '1':
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theRankOptionID . ' != 0 AND b.' . $theRankOptionID . ' != \'0.00\' ';
			break;

		case '2':
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theRankOptionID . ' != 0 ';
			break;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
		$unKnowNum = 0;

		switch ($QtnListArray[$questionID]['isSelect']) {
		case '0':
			if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
				$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theRankOptionID . ' = 99 ';
				$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
				$unKnowNum = $OptionCountRow['optionResponseNum'];
			}

			break;
		}

		$validNum = $thisOptionResponseNum - $unKnowNum;

		switch ($QtnListArray[$questionID]['isSelect']) {
		case '0':
			if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
				$OptionCountSQL = ' SELECT SUM(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 AND b.' . $theRankOptionID . ' !=99 and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
			}
			else {
				$OptionCountSQL = ' SELECT SUM(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
			}

			break;

		case '1':
			$OptionCountSQL = ' SELECT SUM(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 AND b.' . $theRankOptionID . ' !=\'0.00\' and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
			break;

		case '2':
			$OptionCountSQL = ' SELECT SUM(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
			break;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$this_sum_answerNum = $OptionCountRow['item_sum_answerNum'] * $QtnListArray[$questionID]['weight'];
		$rowValidN += $thisOptionResponseNum;
		$rowValidNum += $validNum;
		$rowSum += $this_sum_answerNum;
		$EnableQCoreClass->replace('validN', $thisOptionResponseNum);
		$EnableQCoreClass->replace('mean', meanaverage($this_sum_answerNum, $validNum));
		$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowValidN', $rowValidN);
	$EnableQCoreClass->replace('rowMean', meanaverage($rowSum, $rowValidNum));
	$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
}

unset($optionArray);

?>

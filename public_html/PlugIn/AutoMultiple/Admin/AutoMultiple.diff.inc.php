<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theDim[$theDimLabel]['dimSName'] = $theSRow['surveyTitle'];
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

$t = 0;

foreach ($optionArray as $question_checkboxID => $optionName) {
	$theDim[$theDimLabel]['dimQtnName'][$t] = $optionName;
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' !=\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$theDim[$theDimLabel]['dimSum'][$t] = $OptionCountRow['optionResponseNum'];
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_range_answerID,b.option_' . $questionID . '_' . $question_checkboxID . ') and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY a.question_range_answerID ORDER BY optionResponseNum DESC';
	$OptionCountResult = $DB->query($OptionCountSQL);

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
		$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
	}

	$k = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theDim[$theDimLabel]['dimName'][$t][$k] = qnospecialchar($theAnswerArray['optionAnswer']);

		if (in_array($question_range_answerID, $allResponseOptionID)) {
			$theDim[$theDimLabel]['dimNum'][$t][$k] = $allOptionResponseNum[$question_range_answerID];
			$theDim[$theDimLabel]['dimPercent'][$t][$k] = countpercent($allOptionResponseNum[$question_range_answerID], $theTotalResponseNum);
		}
		else {
			$theDim[$theDimLabel]['dimNum'][$t][$k] = 0;
			$theDim[$theDimLabel]['dimPercent'][$t][$k] = 0;
		}

		$k++;
	}

	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

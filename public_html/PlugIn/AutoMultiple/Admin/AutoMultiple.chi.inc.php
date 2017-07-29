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

$t = 0;
$Headings = $ObsFreq = array();

foreach ($optionArray as $question_checkboxID => $optionName) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' !=\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_range_answerID,b.option_' . $questionID . '_' . $question_checkboxID . ') and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY a.question_range_answerID ORDER BY optionResponseNum DESC ';
	$OptionCountResult = $DB->query($OptionCountSQL);

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
		$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
	}

	$k = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theTitleName[$t][$k] = $optionName . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']);
		$Headings[$t][$k][0] = $lang['checkbox_checked'];
		$Headings[$t][$k][1] = $lang['checkbox_no_checked'];

		if (in_array($question_range_answerID, $allResponseOptionID)) {
			$ObsFreq[$t][$k][0] = $allOptionResponseNum[$question_range_answerID];
			$ObsFreq[$t][$k][1] = $theTotalResponseNum - $allOptionResponseNum[$question_range_answerID];
		}
		else {
			$ObsFreq[$t][$k][0] = 0;
			$ObsFreq[$t][$k][1] = $theTotalResponseNum;
		}

		$k++;
	}

	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

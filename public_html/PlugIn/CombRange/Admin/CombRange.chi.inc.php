<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$t = 0;
$Headings = $ObsFreq = array();

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$k = 0;

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$allResponseOptionID = array();
		$allOptionResponseNum = array();
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.' . $theOptionID . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.' . $theOptionID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		$theTitleName[$t][$k] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']);
		$l = 0;

		foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
			$Headings[$t][$k][$l] = qnospecialchar($theAnswerArray['optionAnswer']);

			if (in_array($question_range_answerID, $allResponseOptionID)) {
				$ObsFreq[$t][$k][$l] = $allOptionResponseNum[$question_range_answerID];
			}
			else {
				$ObsFreq[$t][$k][$l] = 0;
			}

			$l++;
		}

		$k++;
		unset($allResponseOptionID);
		unset($allOptionResponseNum);
	}

	$t++;
}

?>

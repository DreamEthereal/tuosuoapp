<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$allResponseOptionID = array();
$allOptionResponseNum = array();
$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.option_' . $questionID . '_' . $optionID . ' and ' . $dataSource;
$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $optionID . ' ORDER BY optionResponseNum DESC';
$OptionCountResult = $DB->query($OptionCountSQL);

while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
	$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
	$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
}

$k = 0;

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$Headings[$k] = qcrossqtnname($theAnswerArray['optionAnswer']);

	if (in_array($question_range_answerID, $allResponseOptionID)) {
		$ObsFreq[$k] = $allOptionResponseNum[$question_range_answerID];
	}
	else {
		$ObsFreq[$k] = 0;
	}

	$k++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

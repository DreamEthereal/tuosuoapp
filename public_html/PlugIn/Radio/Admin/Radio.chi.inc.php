<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$TitleName = qnospecialchar($QtnListArray[$questionID]['questionName']);
$Headings = $ObsFreq = array();
$OptionSQL = ' SELECT a.question_radioID,count(*) as optionResponseNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_radioID = b.option_' . $questionID . ' and ' . $dataSource;
$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_radioID'];
	$allOptionResponseNum[$OptionRow['question_radioID']] = $OptionRow['optionResponseNum'];
}

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$Headings[] = qnospecialchar($theQuestionArray['optionName']);

	if (in_array($question_radioID, $allResponseOptionID)) {
		$ObsFreq[] = $allOptionResponseNum[$question_radioID];
	}
	else {
		$ObsFreq[] = 0;
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$Headings[] = qnospecialchar($QtnListArray[$questionID]['otherText']);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[] = $OptionCountRow['optionResponseNum'];
}

?>

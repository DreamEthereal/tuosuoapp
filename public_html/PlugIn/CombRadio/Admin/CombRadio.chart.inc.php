<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

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
	$Headings[] = qcrossqtnname($theQuestionArray['optionName']);

	if (in_array($question_radioID, $allResponseOptionID)) {
		$ObsFreq[] = $allOptionResponseNum[$question_radioID];
	}
	else {
		$ObsFreq[] = 0;
	}
}

$totalValue = array_sum($ObsFreq);
unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

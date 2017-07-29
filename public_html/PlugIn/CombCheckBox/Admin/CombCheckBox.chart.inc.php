<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
$totalValue = $theTotalResponseNum;
$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_checkboxID'];
	$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$Headings[] = qcrossqtnname($theQuestionArray['optionName']);

	if (in_array($question_checkboxID, $allResponseOptionID)) {
		$ObsFreq[] = $allOptionResponseNum[$question_checkboxID];
	}
	else {
		$ObsFreq[] = 0;
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

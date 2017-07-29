<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theDim[$theDimLabel]['dimSName'] = $theSRow['surveyTitle'];
$theDim[$theDimLabel]['dimQtnName'] = qnospecialchar($QtnListArray[$questionID]['questionName']);
if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ((b.option_' . $questionID . ' != 0) OR ( b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\')) and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != 0 and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$theDim[$theDimLabel]['dimSum'] = $OptionCountRow['optionResponseNum'];
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
	$theDim[$theDimLabel]['dimName'][] = qnospecialchar($theQuestionArray['optionName']);

	if (in_array($question_radioID, $allResponseOptionID)) {
		$theDim[$theDimLabel]['dimNum'][] = $allOptionResponseNum[$question_radioID];
		$theDim[$theDimLabel]['dimPercent'][] = countpercent($allOptionResponseNum[$question_radioID], $theDim[$theDimLabel]['dimSum']);
	}
	else {
		$theDim[$theDimLabel]['dimNum'][] = 0;
		$theDim[$theDimLabel]['dimPercent'][] = 0;
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$theDim[$theDimLabel]['dimName'][] = qnospecialchar($QtnListArray[$questionID]['otherText']);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theDim[$theDimLabel]['dimNum'][] = $OptionCountRow['optionResponseNum'];
	$theDim[$theDimLabel]['dimPercent'][] = countpercent($OptionCountRow['optionResponseNum'], $theDim[$theDimLabel]['dimSum']);
}

?>

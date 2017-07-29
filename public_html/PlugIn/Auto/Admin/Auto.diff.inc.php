<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theDim[$theDimLabel]['dimSName'] = $theSRow['surveyTitle'];
$theDim[$theDimLabel]['dimQtnName'] = qnospecialchar($QtnListArray[$questionID]['questionName']);
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$theDim[$theDimLabel]['dimSum'] = $OptionCountRow['optionResponseNum'];
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND a.question_checkboxID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_checkboxID'];
	$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
}

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$theDim[$theDimLabel]['dimName'][] = qnospecialchar($theQuestionArray['optionName']);

	if (in_array($question_checkboxID, $allResponseOptionID)) {
		$theDim[$theDimLabel]['dimNum'][] = $allOptionResponseNum[$question_checkboxID];
		$theDim[$theDimLabel]['dimPercent'][] = countpercent($allOptionResponseNum[$question_checkboxID], $theDim[$theDimLabel]['dimSum']);
	}
	else {
		$theDim[$theDimLabel]['dimNum'][] = 0;
		$theDim[$theDimLabel]['dimPercent'][] = 0;
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$theDim[$theDimLabel]['dimName'][] = qnospecialchar($theBaseQtnArray['otherText']);

	if ($QtnListArray[$questionID]['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theDim[$theDimLabel]['dimNum'][] = $OptionCountRow['optionResponseNum'];
	$theDim[$theDimLabel]['dimPercent'][] = countpercent($OptionCountRow['optionResponseNum'], $theDim[$theDimLabel]['dimSum']);
}

if ($QtnListArray[$questionID]['isCheckType'] == '1') {
	$theDim[$theDimLabel]['dimName'][] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']);

	if ($QtnListArray[$questionID]['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theDim[$theDimLabel]['dimNum'][] = $OptionCountRow['optionResponseNum'];
	$theDim[$theDimLabel]['dimPercent'][] = countpercent($OptionCountRow['optionResponseNum'], $theDim[$theDimLabel]['dimSum']);
}

?>

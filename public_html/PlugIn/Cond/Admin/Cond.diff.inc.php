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

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_yesnoID'];
	$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
}

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$theDim[$theDimLabel]['dimName'][] = qnospecialchar($theQuestionArray['optionName']);

	if (in_array($question_yesnoID, $allResponseOptionID)) {
		$theDim[$theDimLabel]['dimNum'][] = $allOptionResponseNum[$question_yesnoID];
		$theDim[$theDimLabel]['dimPercent'][] = countpercent($allOptionResponseNum[$question_yesnoID], $theDim[$theDimLabel]['dimSum']);
	}
	else {
		$theDim[$theDimLabel]['dimNum'][] = 0;
		$theDim[$theDimLabel]['dimPercent'][] = 0;
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

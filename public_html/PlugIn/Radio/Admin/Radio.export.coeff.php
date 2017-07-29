<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isHaveOther'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' = \'\' and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$other_answerNum = $OptionCountRow['optionResponseNum'];
}

$isUnkown = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['isUnkown'] == 1) {
		$isUnkown[] = $question_radioID;
	}
}

if (count($isUnkown) == 0) {
	$unkownNum = 0;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' IN (' . implode(',', $isUnkown) . ') and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$unkownNum = $OptionCountRow['optionResponseNum'];
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1') && ($theQtnArray['isUnkown'] == 1)) {
	$unkownNum += $other_answerNum;
}

$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$validNum = $thisOptionResponseNum - $unkownNum;
$OptionSQL = ' SELECT a.question_radioID,count(*) as optionResponseNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_radioID = b.option_' . $questionID . ' and ' . $dataSource;
$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_radioID'];
	$allOptionResponseNum[$OptionRow['question_radioID']] = $OptionRow['optionResponseNum'];
}

$total_optionCoeffNum = 0;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if (in_array($question_radioID, $allResponseOptionID)) {
		if (!in_array($question_radioID, $isUnkown)) {
			$optionCoeffNum = $theQuestionArray['itemCode'] * $allOptionResponseNum[$question_radioID];
			$total_optionCoeffNum += $optionCoeffNum;
		}
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['isUnkown'] != 1) {
		$optionCoeffNum = $theQtnArray['otherCode'] * $other_answerNum;
		$total_optionCoeffNum += $optionCoeffNum;
	}
}

$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
$content .= ',"' . $total_optionCoeffAvg . '"';
$content .= "\r\n";

?>

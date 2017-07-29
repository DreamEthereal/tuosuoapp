<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$isUnkown = array();

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	if ($theQuestionArray['isUnkown'] == 1) {
		$isUnkown[] = $question_yesnoID;
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

$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$validNum = $thisOptionResponseNum - $unkownNum;
$total_optionCoeffNum = 0;

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	if (!in_array($question_yesnoID, $isUnkown)) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $question_yesnoID . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$optionCoeffNum = $theQuestionArray['itemCode'] * $OptionCountRow['optionResponseNum'];
		$total_optionCoeffNum += $optionCoeffNum;
	}
}

$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
$content .= ',"' . $total_optionCoeffAvg . '"';
$content .= "\r\n";

?>

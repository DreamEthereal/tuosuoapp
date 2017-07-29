<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$isUnkown = array();
$OptSQL = ' SELECT question_yesnoID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $questionID . '\' AND isUnkown =1 ORDER BY question_yesnoID ';
$OptResult = $DB->query($OptSQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$isUnkown[] = $OptRow['question_yesnoID'];
}

if (count($isUnkown) == 0) {
	$unkownNum = 0;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' IN (' . implode(',', $isUnkown) . ') and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$unkownNum = $OptionCountRow['optionResponseNum'];
}

$validNum = $thisOptionResponseNum - $unkownNum;

if ($theQtnArray['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_yesnoID,a.itemCode,a.optionName,a.optionCoeff,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($OptionRow['optionName']) . '"';
		$optionCoeffNum = $OptionRow['itemCode'] * $OptionRow['optionResponseNum'];
		$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
		$content .= ',"' . $optionCoeffAvg . '"';
		$content .= "\r\n";
	}
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.itemCode,a.optionName,a.optionCoeff,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);
	$total_optionCoeffNum = 0;

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		if (!in_array($OptionRow['question_yesnoID'], $isUnkown)) {
			$optionCoeffNum = $OptionRow['itemCode'] * $OptionRow['optionResponseNum'];
			$total_optionCoeffNum += $optionCoeffNum;
		}
	}

	$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$content .= ',"' . $total_optionCoeffAvg . '"';
	$content .= "\r\n";
}

?>

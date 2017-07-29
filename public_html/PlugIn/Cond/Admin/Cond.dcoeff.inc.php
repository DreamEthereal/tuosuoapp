<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 1) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $theCoeffReportId . 'File', 'CheckBoxCoeffView.html');
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('other_optionName', '');
	$EnableQCoreClass->replace('other_answerNum', '');
	$EnableQCoreClass->replace('other_optionCoeff', '');
	$EnableQCoreClass->replace('other_optionCoeffNum', '');
	$EnableQCoreClass->replace('other_optionCoeffAvg', '');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $theCoeffReportId . 'File', 'YesNoCoeffView.html');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $theCoeffReportId . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_18'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
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

$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$validNum = $thisOptionResponseNum - $unkownNum;

if ($theQtnArray['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_yesnoID,a.itemCode,a.optionName,a.optionCoeff,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.itemCode,a.optionName,a.optionCoeff,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);
$total_optionCoeffNum = 0;

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($OptionRow['optionName']));
	$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
	$EnableQCoreClass->replace('optionCoeff', $OptionRow['itemCode']);

	if (in_array($OptionRow['question_yesnoID'], $isUnkown)) {
		$EnableQCoreClass->replace('optionCoeffNum', 0);
		$EnableQCoreClass->replace('optionCoeffAvg', 0);
	}
	else {
		$optionCoeffNum = $OptionRow['itemCode'] * $OptionRow['optionResponseNum'];
		$total_optionCoeffNum += $optionCoeffNum;
		$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 2));
		$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
		$EnableQCoreClass->replace('optionCoeffAvg', $optionCoeffAvg);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$EnableQCoreClass->replace('total_optionCoeffNum', $total_optionCoeffNum);
$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
$EnableQCoreClass->replace('total_optionCoeffAvg', $total_optionCoeffAvg);
$DataCrossHTML0 = $EnableQCoreClass->parse('ShowOption' . $theCoeffReportId, 'ShowOption' . $theCoeffReportId . 'File');
$DataCrossHTML = '<table width="100%">' . $DataCrossHTML0 . '</table>';

?>

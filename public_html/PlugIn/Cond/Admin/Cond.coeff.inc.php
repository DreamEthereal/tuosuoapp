<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 1) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxCoeffView.html');
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('other_optionName', '');
	$EnableQCoreClass->replace('other_answerNum', '');
	$EnableQCoreClass->replace('other_optionCoeff', '');
	$EnableQCoreClass->replace('other_optionCoeffNum', '');
	$EnableQCoreClass->replace('other_optionCoeffAvg', '');
	$EnableQCoreClass->replace('isHaveNeg', 'none');
	$EnableQCoreClass->replace('neg_optionName', '');
	$EnableQCoreClass->replace('neg_answerNum', '');
	$EnableQCoreClass->replace('neg_optionCoeff', '');
	$EnableQCoreClass->replace('neg_optionCoeffNum', '');
	$EnableQCoreClass->replace('neg_optionCoeffAvg', '');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoCoeffView.html');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$minOption = $maxOption = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';

	if ($theQtnArray['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $theQtnArray['minOption'] . $lang['option'] . ']';
	}

	if ($theQtnArray['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $theQtnArray['maxOption'] . $lang['option'] . ']';
	}
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_18'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
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
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

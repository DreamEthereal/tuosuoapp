<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CondView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
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
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$optionPercent = countpercent($thisOptionResponseNum, $totalResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', $optionPercent);

if ($theQtnArray['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($OptionRow['optionName']));
	$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
	$optionPercent = countpercent($OptionRow['optionResponseNum'], $totalResponseNum);
	$EnableQCoreClass->replace('optionPercent', $optionPercent);
	$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

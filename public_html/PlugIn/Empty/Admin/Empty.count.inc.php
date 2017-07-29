<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_30'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$EnableQCoreClass->replace('skip_answerNum', 0);
$EnableQCoreClass->replace('skip_optionPercent', 0);
$thisOptionResponseNum = $totalResponseNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', 100);
$OptionSQL = ' SELECT count(*) as optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '=1 and ' . $dataSource;
$OptionRow = $DB->queryFirstRow($OptionSQL);
$EnableQCoreClass->replace('optionName', 'True');
$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
$EnableQCoreClass->replace('optionPercent', countpercent($OptionRow['optionResponseNum'], $totalResponseNum));
$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionRow['optionResponseNum'], $thisOptionResponseNum));
$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
$EnableQCoreClass->replace('optionName', 'False');
$TheFalseNum = $thisOptionResponseNum - $OptionRow['optionResponseNum'];
$EnableQCoreClass->replace('answerNum', $TheFalseNum);
$EnableQCoreClass->replace('optionPercent', countpercent($TheFalseNum, $totalResponseNum));
$EnableQCoreClass->replace('optionValidPercent', countpercent($TheFalseNum, $thisOptionResponseNum));
$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

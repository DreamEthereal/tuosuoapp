<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'ListView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_14'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$TableFields = '';
$i = 1;

for (; $i <= $theQtnArray['rows']; $i++) {
	$EnableQCoreClass->replace('optionName', $i);
	$TableFields .= ' AND option_' . $questionID . '_' . $i . ' = \'\' ';
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . '_' . $i . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$answerNum = $OptionCountRow['optionResponseNum'];
	$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
	$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
	$EnableQCoreClass->replace('optionPercent', $optionPercent);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . '_' . $i . ' = \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('item_skip_answerNum', $OptionCountRow['optionResponseNum']);
	$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
	$EnableQCoreClass->replace('item_skip_optionPercent', $optionPercent);
	$EnableQCoreClass->replace('questionID', $questionID);
	$EnableQCoreClass->replace('surveyID', $surveyID);
	$EnableQCoreClass->replace('surveyName', urlencode($_GET['surveyTitle']));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE 1=1 ' . $TableFields . ' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

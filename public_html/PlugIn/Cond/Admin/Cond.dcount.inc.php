<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCount1D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']));
$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skipAnswerNum', $OptionCountRow['skipAnswerNum']);
$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));

if ($theQtnArray['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($OptionRow['optionName']));
	$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
	$EnableQCoreClass->replace('optionPercent', countpercent($OptionRow['optionResponseNum'], $totalRepAnswerNum));
	$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
$DataCrossHTML = preg_replace('/<!-- BEGIN OTHER -->(.*)<!-- END OTHER -->/s', '', $DataCrossHTML);

?>

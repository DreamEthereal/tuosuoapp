<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCount1D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']));
$EnableQCoreClass->replace('skipAnswerNum', 0);
$EnableQCoreClass->replace('skipAnswerPercent', 0);
$thisOptionResponseNum = $totalRepAnswerNum;
$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('repAnswerPercent', 100);
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
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
$DataCrossHTML = preg_replace('/<!-- BEGIN OTHER -->(.*)<!-- END OTHER -->/s', '', $DataCrossHTML);

?>

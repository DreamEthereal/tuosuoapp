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
$Conn = odbc_connect(trim($theQtnArray['DSNConnect']), trim($theQtnArray['DSNUser']), trim($theQtnArray['DSNPassword']));

if (!$Conn) {
	_showerror('System Error', 'Connection Failed:' . trim($theQtnArray['DSNConnect']) . '-' . trim($theQtnArray['DSNUser']) . '-' . trim($theQtnArray['DSNPassword']));
}

$ODBC_Result = odbc_exec($Conn, _getsql($theQtnArray['DSNSQL']));

if (!$ODBC_Result) {
	_showerror('System Error', 'Error in SQL:' . trim($theQtnArray['DSNSQL']));
}

while (odbc_fetch_row($ODBC_Result)) {
	$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
	$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
	$EnableQCoreClass->replace('optionName', $ItemDisplay);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $ItemValue . '\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalRepAnswerNum));
	$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
$DataCrossHTML = preg_replace('/<!-- BEGIN OTHER -->(.*)<!-- END OTHER -->/s', '', $DataCrossHTML);

?>

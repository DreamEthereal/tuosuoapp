<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DSNView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_13'] . ']';
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
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' =  \'' . $ItemValue . '\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
	$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$EnableQCoreClass->replace('surveyID', $surveyID);
$EnableQCoreClass->replace('questionID', $questionID);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

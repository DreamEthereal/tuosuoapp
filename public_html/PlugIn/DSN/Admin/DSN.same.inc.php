<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DataMatchingSame1D.html');
$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']));
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'TIME', 'time' . $questionID);
$EnableQCoreClass->replace('time' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'DIMSUM', 'dimsum' . $questionID);
$EnableQCoreClass->replace('dimsum' . $questionID, '');
$theTimes = explode('^', trim($theTimeCharText));
$theTotalResponseNum = array();

foreach ($theTimes as $theTime) {
	$theTimeArray = explode('*', $theTime);
	$theBeginTime = $theTimeArray[0];
	$theEndTime = $theTimeArray[1];
	$EnableQCoreClass->replace('timeName', date('y-n-j', $theBeginTime) . ' - ' . date('y-n-j', $theEndTime));
	$EnableQCoreClass->parse('time' . $questionID, 'TIME', true);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum[$theTime] = $OptionCountRow['optionResponseNum'];
	$EnableQCoreClass->replace('dimSum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->parse('dimsum' . $questionID, 'DIMSUM', true);
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'DIM', 'dim' . $questionID);
$EnableQCoreClass->set_CycBlock('DIM', 'DIMNUM', 'dimnum' . $questionID);
$EnableQCoreClass->replace('dim' . $questionID, '');
$EnableQCoreClass->replace('dimnum' . $questionID, '');
$Conn = odbc_connect(trim($QtnListArray[$questionID]['DSNConnect']), trim($QtnListArray[$questionID]['DSNUser']), trim($QtnListArray[$questionID]['DSNPassword']));

if (!$Conn) {
	_showerror('System Error', 'Connection Failed:' . trim($QtnListArray[$questionID]['DSNConnect']) . '-' . trim($QtnListArray[$questionID]['DSNUser']) . '-' . trim($QtnListArray[$questionID]['DSNPassword']));
}

$ODBC_Result = odbc_exec($Conn, _getsql($QtnListArray[$questionID]['DSNSQL']));

if (!$ODBC_Result) {
	_showerror('System Error', 'Error in SQL:' . trim($QtnListArray[$questionID]['DSNSQL']));
}

while (odbc_fetch_row($ODBC_Result)) {
	$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
	$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
	$EnableQCoreClass->replace('dimName', qnospecialchar($ItemDisplay));

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $ItemValue . '\' AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('dimNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('dimPercent', countpercent($OptionCountRow['optionResponseNum'], $theTotalResponseNum[$theTime]));
		$EnableQCoreClass->parse('dimnum' . $questionID, 'DIMNUM', true);
	}

	$EnableQCoreClass->parse('dim' . $questionID, 'DIM', true);
	$EnableQCoreClass->unreplace('dimnum' . $questionID);
}

unset($theTotalResponseNum);
$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

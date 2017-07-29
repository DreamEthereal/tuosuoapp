<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$TitleName = qnospecialchar($QtnListArray[$questionID]['questionName']);
$Headings = $ObsFreq = array();
$Conn = odbc_connect(trim($QtnListArray[$questionID]['DSNConnect']), trim($QtnListArray[$questionID]['DSNUser']), trim($QtnListArray[$questionID]['DSNPassword']));

if (!$Conn) {
	_showerror('System Error', 'Connection Failed:' . trim($QtnListArray[$questionID]['DSNConnect']) . '-' . trim($QtnListArray[$questionID]['DSNUser']) . '-' . trim($QtnListArray[$questionID]['DSNPassword']));
}

$ODBC_Result = odbc_exec($Conn, _getsql($QtnListArray[$questionID]['DSNSQL']));

if (!$ODBC_Result) {
	_showerror('System Error', 'Error in SQL:' . trim($QtnListArray[$questionID]['DSNSQL']));
}

while (odbc_fetch_row($ODBC_Result)) {
	$theDimLabeltemValue = odbc_result($ODBC_Result, 'ItemValue');
	$theDimLabeltemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
	$Headings[] = qnospecialchar($theDimLabeltemDisplay);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $theDimLabeltemValue . '\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[] = $OptionCountRow['optionResponseNum'];
}

?>

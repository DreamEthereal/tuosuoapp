<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$content .= ' VARIABLE LABELS ' . $VarName . ' \'' . qconverionlabel($theQtnArray['questionName']) . '\'.' . "\r\n" . '';
$content .= ' VALUE LABELS ' . $VarName . ' ';
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
	$content .= $ItemValue . ' \'' . qconverionlabel($ItemDisplay) . '\' ';
}

$content .= '.' . "\r\n" . '';

?>

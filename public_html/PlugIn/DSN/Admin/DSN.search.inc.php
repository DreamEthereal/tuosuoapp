<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$Conn = odbc_connect(trim($theQtnArray['DSNConnect']), trim($theQtnArray['DSNUser']), trim($theQtnArray['DSNPassword']));

if (!$Conn) {
	echo 'Connection Failed:' . trim($theQtnArray['DSNConnect']) . '-' . trim($theQtnArray['DSNUser']) . '-' . trim($theQtnArray['DSNPassword']);
	exit();
}

$ODBC_Result = odbc_exec($Conn, _getsql($theQtnArray['DSNSQL']));

if (!$ODBC_Result) {
	echo 'System Error';
	echo 'Error in SQL:' . trim($theQtnArray['DSNSQL']);
	exit();
}

$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" size=8 onchange=javascript:disLogicMode("opertion_' . $questionID . '_' . $thisNo . '","cond_' . $questionID . '_' . $thisNo . '");><option value="1" selected>选择</option><option value="2">未选择</option></select>';
$optionList .= '<select name="queryValue_' . $questionID . '_' . $thisNo . '[]" id="queryValue_' . $questionID . '_' . $thisNo . '" size=8 multiple>';

while (odbc_fetch_row($ODBC_Result)) {
	$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
	$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
	$optionName = qnohtmltag($ItemDisplay, 1);
	$optionList .= '<option value=\'' . $ItemValue . '\'>' . $optionName . '</option>';
}

$optionList .= '</select>';
$optionList .= '<br/>值间关系：<input disabled type=checkbox value="1" name="cond_' . $questionID . '_' . $thisNo . '" id="cond_' . $questionID . '_' . $thisNo . '">' . $lang['cond_logic_or'];
echo $optionList;

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$ipAddress = _getip();

if ($_SESSION['webArea'] != '') {
	$Area = $_SESSION['webArea'];
}
else {
	$Num = explode('.', $ipAddress);
	$Sip = sprintf('%03s', $Num[0]) . '.' . sprintf('%03s', $Num[1]) . '.' . sprintf('%03s', $Num[2]) . '.' . sprintf('%03s', $Num[3]);
	$CountSQL = ' SELECT Area FROM ' . IPDATABASE_TABLE . ' WHERE StartIp<=\'' . $Sip . '\' and EndIp>=\'' . $Sip . '\' ORDER BY StartIp LIMIT 0,1 ';

	if ($CountRow = $DB->queryFirstRow($CountSQL)) {
		$Area = $CountRow['Area'];
	}
	else {
		$Area = $lang['unknow_area'];
	}
}

?>

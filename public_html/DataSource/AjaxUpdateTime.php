<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|3|5|7');
$TimeSQL = ' SELECT min(joinTime) as beginTime,max(joinTime) as endTime,min(overTime) as beginOverTime, max(overTime) as endOverTime FROM ' . $table_prefix . 'response_' . $_GET['surveyID'];
$TimeRow = $DB->queryFirstRow($TimeSQL);
$beginTime = date('Y-m-d-H-i', $TimeRow['beginTime']);
$endTime = date('Y-m-d-H-i', $TimeRow['endTime'] + 60);
$beginOverTime = ($TimeRow['beginOverTime'] == '' ? 0 : $TimeRow['beginOverTime']);
$endOverTime = ($TimeRow['endOverTime'] == '' ? 0 : $TimeRow['endOverTime']);
if (!isset($_GET['type']) || ($_GET['type'] == 1)) {
	echo $beginTime . '$$$' . $endTime;
}
else {
	echo $beginOverTime . '$$$' . $endOverTime;
}

exit();

?>

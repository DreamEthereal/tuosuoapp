<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
@set_time_limit(0);
$thisProg = 'uRadarData.php?label=' . $_GET['label'] . '&data=' . $_GET['data'];
if ((trim($_GET['label']) == '') && (trim($_GET['data']) == '')) {
	echo ' ';
	exit();
}

$Headings = explode('***', base64_decode($_GET['label']));
$ObsFreq = explode('***', $_GET['data']);
$ColumnsData = '';

foreach ($Headings as $t => $theHeadings) {
	$ColumnsData .= iconv('gbk', 'UTF-8', $theHeadings);
	$ColumnsData .= ';' . $ObsFreq[$t];
	$ColumnsData .= "\r\n";
}

unset($Headings);
unset($ObsFreq);
echo $ColumnsData;
exit();

?>

<?php
//dezend by http://www.yunlu99.com/
function qconversionstring($string)
{
	$string = strip_tags($string);
	$string = str_replace('"', '""', $string);
	$string = str_replace('&quot;', '""', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$ResultContent = '';
$header = '"节点ID"';
$header .= ',"节点名称"';
$header .= ',"节点父ID"';
$header .= ',"节点层级"';
$header .= "\r\n";
$ResultContent .= $header;
$_GET['questionID'] = (int) $_GET['questionID'];
$SQL = ' SELECT * FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $_GET['questionID'] . '\' ORDER BY cascadeID ASC ';
$iResult = $DB->query($SQL);

while ($Row = $DB->queryArray($iResult)) {
	$content .= '"' . $Row['nodeID'] . '"';
	$content .= ',"' . qconversionstring($Row['nodeName']) . '"';
	$content .= ',"' . $Row['nodeFatherID'] . '"';
	$content .= ',"' . $Row['level'] . '"';
	$content .= "\r\n";
}

$ResultContent .= $content;
ob_start();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=Cascade_' . $_GET['questionID'] . '_' . date('Y-m-d') . '.csv');
echo $ResultContent;
exit();

?>

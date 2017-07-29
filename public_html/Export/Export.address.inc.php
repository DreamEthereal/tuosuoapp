<?php
//dezend by http://www.yunlu99.com/
function export_address()
{
	global $DB;
	global $lang;
	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd = '"' . $lang['start_ip_address'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['end_ip_address'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['ip_area'] . '"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;
	$_obf_xCnI = ' SELECT * FROM ' . IPDATABASE_TABLE . ' ORDER BY StartIp ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_b1BeeeefmU4_ = explode('.', $_obf_9WwQ['StartIp']);
		$_obf_rHjBu019Vg__ = intval($_obf_b1BeeeefmU4_[0]) . '.' . intval($_obf_b1BeeeefmU4_[1]) . '.' . intval($_obf_b1BeeeefmU4_[2]) . '.' . intval($_obf_b1BeeeefmU4_[3]);
		$_obf__WwKzYz1wA__ .= '"' . $_obf_rHjBu019Vg__ . '"';
		$_obf_Lblxy7ZV = explode('.', $_obf_9WwQ['EndIp']);
		$_obf_EgRuXGc_ = intval($_obf_Lblxy7ZV[0]) . '.' . intval($_obf_Lblxy7ZV[1]) . '.' . intval($_obf_Lblxy7ZV[2]) . '.' . intval($_obf_Lblxy7ZV[3]);
		$_obf__WwKzYz1wA__ .= ',"' . $_obf_EgRuXGc_ . '"';
		$_obf__WwKzYz1wA__ .= ',"' . qshowexportquotechar($_obf_9WwQ['Area']) . '"';
		$_obf__WwKzYz1wA__ .= "\r\n";
	}

	return $_obf__WwKzYz1wA__;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(1);
ob_start();
$ResultList = export_address();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=EnableQ_IP_Area_List_' . date('Y-m-d') . '.csv');
echo $ResultList;
exit();

?>

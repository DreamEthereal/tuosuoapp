<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
_checkroletype('1|2|3|4|5|7');

if ($License['isCell'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$SQL = ' SELECT * FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE traceID = \'' . $_GET['traceID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$mcc = $Row['accuracy'];
$mnc = $Row['latitude'];
$cid = $Row['longitude'];
$lac = $Row['altitude'];
$theCellString = $mcc . '#' . $mnc . '#' . $cid . '#' . $lac;
$key = md5($mcc . $mnc . 'enableq' . $cid . $lac);
$dataFlag = true;
$postData['key'] = $key;
$postData['mcc'] = $mcc;
$postData['mnc'] = $mnc;
$postData['cid'] = $cid;
$postData['lac'] = $lac;
$lbsContent = post_data_to_host($Config['lbsURL'], $postData);

if (trim($lbsContent) != '') {
	$theRtnStr = explode(',', $lbsContent);

	if (count($theRtnStr) < 2) {
		$dataFlag = false;
		$theCellString .= '#2';
	}
	else {
		$dataFlag = true;
		$longitude = $theRtnStr[1];
		$latitude = $theRtnStr[0];
	}
}
else {
	$dataFlag = false;
	$theCellString .= '#1';
}

if ($dataFlag == false) {
	_showerror('基站定位数据转换错误', '基站定位数据转换错误：目前已有的基站信息数据库无法对您取得的基站定位数据进行转换：' . $theCellString);
}

header('location:' . ROOT_PATH . 'Android/BaiduMap.php?gps_longitude=' . $longitude . '&gps_latitude=' . $latitude);

?>

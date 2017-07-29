<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

_checkroletype('1|2|3|4|5|7');
$hSQL = ' SELECT traceID FROM ' . DEVICE_TRACE_TABLE . ' WHERE deviceId =\'' . $_POST['deviceId'] . '\' AND isCell=\'' . $_POST['isCell'] . '\' LIMIT 1 ';
$hRow = $DB->queryFirstRow($hSQL);

if (!$hRow) {
	$SQL = ' INSERT INTO ' . DEVICE_TRACE_TABLE . ' SET brand =\'' . $_POST['brand'] . '\',model =\'' . $_POST['model'] . '\',deviceId =\'' . $_POST['deviceId'] . '\',gpsTime =\'' . $_POST['gpsTime'] . '\',accuracy =\'' . $_POST['accuracy'] . '\',longitude=\'' . $_POST['longitude'] . '\',latitude=\'' . $_POST['latitude'] . '\',altitude=\'' . $_POST['altitude'] . '\',nickUserName=\'' . iconv('UTF-8', 'GBK', $_POST['nickUserName']) . '\',isCell=\'' . $_POST['isCell'] . '\' ';
}
else {
	$SQL = ' UPDATE ' . DEVICE_TRACE_TABLE . ' SET brand =\'' . $_POST['brand'] . '\',model =\'' . $_POST['model'] . '\',deviceId =\'' . $_POST['deviceId'] . '\',gpsTime =\'' . $_POST['gpsTime'] . '\',accuracy =\'' . $_POST['accuracy'] . '\',longitude=\'' . $_POST['longitude'] . '\',latitude=\'' . $_POST['latitude'] . '\',altitude=\'' . $_POST['altitude'] . '\',nickUserName=\'' . iconv('UTF-8', 'GBK', $_POST['nickUserName']) . '\',isCell=\'' . $_POST['isCell'] . '\' WHERE traceID = \'' . $hRow['traceID'] . '\' ';
}

$DB->query($SQL);
echo 'true';
exit();

?>

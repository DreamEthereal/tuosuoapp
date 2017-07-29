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
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

sleep(1);
$tmpFilePathName = trim($_GET['filePathName']);
$destination = $Config['absolutenessPath'] . '/PerUserData/tmp/' . $tmpFilePathName . '/';
deletedir($destination);
echo 'true';
exit();

?>

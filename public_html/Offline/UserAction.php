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
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$SQL = ' INSERT INTO ' . ANDROID_LOG_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',userId =\'' . $_POST['userId'] . '\',nickName =\'' . iconv('UTF-8', 'GBK', $_POST['nickName']) . '\',actionMess=\'' . iconv('UTF-8', 'GBK', $_POST['actionMess']) . '\',actionTime =\'' . time() . '\',actionType=\'' . $_POST['actionType'] . '\' ';
$DB->query($SQL);
echo 'true';
exit();

?>

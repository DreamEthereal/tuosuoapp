<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
require_once ROOT_PATH . 'Entry/Global.android.php';

if ($License['isPanel'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyIndexFile', 'uAndroidIndex.html');
$pushURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -17) . 'Android/Push.php';
$EnableQCoreClass->replace('pushURL', $pushURL);
$EnableQCoreClass->replace('siteTitle', $Config['siteName']);
$SQL = ' SELECT isNotRegister FROM ' . ADMINISTRATORSCONFIG_TABLE . ' ';
$ConfigRow = $DB->queryFirstRow($SQL);

if ($ConfigRow['isNotRegister'] == 1) {
	$EnableQCoreClass->replace('isNotRegister', 'none');
}
else {
	$EnableQCoreClass->replace('isNotRegister', '');
}

$EnableQCoreClass->parse('SurveyIndex', 'SurveyIndexFile');
$EnableQCoreClass->output('SurveyIndex');
exit();

?>

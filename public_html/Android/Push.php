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
require_once ROOT_PATH . 'Entry/Global.android.php';
if (($License['isPanel'] != 1) && ($License['isOnline'] != 1)) {
	echo '';
	exit();
}

if (isset($_GET['type']) && ($_GET['type'] == 1)) {
	$SQL = ' SELECT * FROM ' . ANDROID_PUSH_TABLE . ' WHERE stat =1 AND isCommon IN (2,3) ORDER BY pushTime DESC ';
}
else {
	$SQL = ' SELECT * FROM ' . ANDROID_PUSH_TABLE . ' WHERE stat =1 AND isCommon IN (1,3) ORDER BY pushTime DESC ';
}

$Row = $DB->queryFirstRow($SQL);

if ($Row) {
	echo 'id=' . $Row['pushID'] . ';title=' . $Row['pushTitle'] . ';message=' . $Row['pushInfo'];
	if (($Row['surveyID'] != '0') || (trim($Row['pushURL']) != '')) {
		if ($Row['surveyID'] == 0) {
			echo ';url=' . $Row['pushURL'];
		}
		else {
			$pushURL = $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -17);
			$SSQL = ' SELECT surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $Row['surveyID'] . '\' ';
			$SSRow = $DB->queryFirstRow($SSQL);
			echo ';url=http://' . $pushURL . '/a.php?qname=' . $SSRow['surveyName'] . '&qlang=' . $SSRow['lang'];
		}
	}
}

exit();

?>

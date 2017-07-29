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
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';

if ($License['isOffline'] != 1) {
	exit('false');
}

$SQL = ' SELECT COUNT(*) FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
$haveRow = $DB->queryFirstRow($SQL);
$recNum = $haveRow[0];

if ($recNum == 0) {
	exit('false');
}
else {
	$SQL = ' SELECT status FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$sRow = $DB->queryFirstRow($SQL);

	if ($sRow['status'] != 1) {
		exit('false');
	}

	$SQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
	$Result = $DB->query($SQL);
	$theAllTask = array();

	while ($Row = $DB->queryArray($Result)) {
		$theAllTask[] = $Row['taskID'];
	}

	$SQL = ' SELECT taskID FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE area = \'' . $_SESSION['administratorsName'] . '\' ';
	$Result = $DB->query($SQL);
	$theOverTask = array();

	while ($Row = $DB->queryArray($Result)) {
		$theOverTask[] = $Row['taskID'];
	}

	$theNoOverTask = arraydiff($theAllTask, $theOverTask);
	exit('' . implode(',', $theNoOverTask) . '');
}

?>

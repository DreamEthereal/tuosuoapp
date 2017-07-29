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
set_time_limit(0);
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	header('Content-Type:text/html; charset=gbk');
	exit('false#######' . $lang['license_no_android']);
}

if ($_POST['Action'] == 'downloadTaskSubmit') {
	if ($_POST['surveyID'] != '') {
		$SQL = ' SELECT COUNT(*) FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
		$haveRow = $DB->queryFirstRow($SQL);
		$recNum = $haveRow[0];

		if ($recNum == 0) {
			exit('false#######问卷无任务，无法下载');
		}
		else {
			$SQL = ' SELECT status FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
			$sRow = $DB->queryFirstRow($SQL);

			if ($sRow['status'] != 1) {
				exit('false#######问卷处在设计状态，无法下载');
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

			if (count($theNoOverTask) == 0) {
				header('Content-Type:text/html; charset=gbk');
				exit('false#######问卷无最新任务，无须下载');
			}
			else {
				$theTask = array();
				$DSQL = ' SELECT a.taskID,a.surveyID,b.userGroupName,b.userGroupDesc FROM ' . TASK_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.surveyID = \'' . $_POST['surveyID'] . '\' AND a.administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND a.taskID = b.userGroupID AND taskID IN (' . implode(',', $theNoOverTask) . ') ORDER BY a.taskID ASC ';
				$DResult = $DB->query($DSQL);

				while ($DRow = $DB->queryArray($DResult)) {
					$theTask[$DRow['taskID']]['surveyID'] = $DRow['surveyID'];
					$theTask[$DRow['taskID']]['taskName'] = json_string($DRow['userGroupName']);
					$theTask[$DRow['taskID']]['taskDesc'] = json_string($DRow['userGroupDesc']);
				}

				$cacheContent = '';
				$cacheContent .= ' var TaskListArray = ' . json($theTask) . ';';
				header('Content-Type:text/html; charset=gbk');
				exit('true#######' . $_POST['surveyID'] . '#######' . $cacheContent);
			}
		}
	}
	else {
		header('Content-Type:text/html; charset=gbk');
		exit('false#######未选择需要自服务器同步任务的调查问卷');
	}
}

?>

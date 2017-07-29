<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');

if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
	require_once ROOT_PATH . 'Entry/Global.monitor.php';
}
else {
	require_once ROOT_PATH . 'Entry/Global.setup.php';
}

include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyID,status,administratorsID,surveyName,isCache,projectType,projectOwner,isDataSource FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
$thisProg = 'DataOverview.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
@set_time_limit(0);

if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
	if ($License['isMonitor'] != 1) {
		_showerror($lang['license_error'], $lang['license_no_android']);
	}

	if ($Sur_G_Row['projectType'] == 1) {
		$EnableQCoreClass->setTemplateFile('QuestionListFile', 'uMonitorMyOverview.html');
		$EnableQCoreClass->replace('isProjectType1', '');
		$flag = 1;
	}
	else {
		$EnableQCoreClass->setTemplateFile('QuestionListFile', 'uMonitorDataOverview.html');
		$EnableQCoreClass->replace('isProjectType1', 'none');
		$flag = 2;
	}
}
else if ($Sur_G_Row['projectType'] == 1) {
	$EnableQCoreClass->setTemplateFile('QuestionListFile', 'DataMyOverview.html');
	$flag = 1;
}
else {
	$EnableQCoreClass->setTemplateFile('QuestionListFile', 'DataOverview.html');
	$flag = 2;
}

if (isset($_POST['dataSource'])) {
	$_SESSION['dataSource' . $_GET['surveyID']] = $_POST['dataSource'];
}

if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
	$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
	$dataSourceId = $_SESSION['dataSource' . $_GET['surveyID']];
}
else {
	$dataSource = getdatasourcesql(0, $_GET['surveyID']);
	$dataSourceId = 0;
}

if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
	$dataSourceList = '';
	if (!isset($_SESSION['dataSource' . $_GET['surveyID']]) || ($_SESSION['dataSource' . $_GET['surveyID']] == 0)) {
		$dataSourceList .= '<option value=\'0\' selected>完成和导入数据</option>';
		$dataSource .= ' and b.overFlag IN (1,3) ';
	}
	else {
		$dataSourceList .= '<option value=\'0\'>完成和导入数据</option>';
	}

	$SQL = ' SELECT * FROM ' . QUERY_LIST_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) ORDER BY queryID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		if ($Row['defineShare'] == 0) {
			$dataSourceName = qnohtmltag($Row['queryName'], 1) . $lang['report_private'];
		}
		else {
			$dataSourceName = qnohtmltag($Row['queryName'], 1);
		}

		if (isset($_SESSION['dataSource' . $_GET['surveyID']]) && ($_SESSION['dataSource' . $_GET['surveyID']] == $Row['queryID'])) {
			$dataSourceList .= '<option value=\'' . $Row['queryID'] . '\' selected>' . $dataSourceName . '</option>';
		}
		else {
			$dataSourceList .= '<option value=\'' . $Row['queryID'] . '\'>' . $dataSourceName . '</option>';
		}
	}

	$EnableQCoreClass->replace('dataSourceList', $dataSourceList);
}

$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($SQL);
$totalResponseNum = $CountRow['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);
$TimeSQL = ' SELECT min(joinTime) as beginTime,max(joinTime) as endTime,min(overTime) as beginOverTime, max(overTime) as endOverTime FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
$TimeRow = $DB->queryFirstRow($TimeSQL);
$EnableQCoreClass->replace('beginTime', $TimeRow['beginTime'] == '' ? '0' : date('Y-m-d H:i:s', $TimeRow['beginTime']));
$EnableQCoreClass->replace('endTime', $TimeRow['endTime'] == '' ? '0' : date('Y-m-d H:i:s', $TimeRow['endTime']));
$EnableQCoreClass->replace('beginOverTime', $TimeRow['beginOverTime'] == '' ? 0 : $TimeRow['beginOverTime']);
$EnableQCoreClass->replace('endOverTime', $TimeRow['endOverTime'] == '' ? 0 : $TimeRow['endOverTime']);
$SQL = ' SELECT COUNT(*) as overFlag0Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.overFlag =0 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('overFlag0Num', $CRow['overFlag0Num']);
$EnableQCoreClass->replace('overFlag0Rate', countpercent($CRow['overFlag0Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as overFlag1Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.overFlag =1 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('overFlag1Num', $CRow['overFlag1Num']);
$EnableQCoreClass->replace('overFlag1Rate', countpercent($CRow['overFlag1Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as overFlag2Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.overFlag =2 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('overFlag2Num', $CRow['overFlag2Num']);
$EnableQCoreClass->replace('overFlag2Rate', countpercent($CRow['overFlag2Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as overFlag3Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.overFlag =3 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('overFlag3Num', $CRow['overFlag3Num']);
$EnableQCoreClass->replace('overFlag3Rate', countpercent($CRow['overFlag3Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as authStat0Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.authStat =0 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('authStat0Num', $CRow['authStat0Num']);
$EnableQCoreClass->replace('authStat0Rate', countpercent($CRow['authStat0Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as authStat3Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.authStat NOT IN (0,1,2) AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('authStat3Num', $CRow['authStat3Num']);
$EnableQCoreClass->replace('authStat3Rate', countpercent($CRow['authStat3Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as authStat1Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.authStat =1 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('authStat1Num', $CRow['authStat1Num']);
$EnableQCoreClass->replace('authStat1Rate', countpercent($CRow['authStat1Num'], $totalResponseNum));
$SQL = ' SELECT COUNT(*) as authStat2Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.authStat =2 AND ' . $dataSource;
$CRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('authStat2Num', $CRow['authStat2Num']);
$EnableQCoreClass->replace('authStat2Rate', countpercent($CRow['authStat2Num'], $totalResponseNum));

if ($Sur_G_Row['isDataSource'] == 0) {
	if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
		$EnableQCoreClass->setTemplateFile('DataSourceListFile', 'uDataSourceList0.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('DataSourceListFile', 'DataSourceList0.html');
	}

	$SQL = ' SELECT COUNT(*) as source0Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' AND ' . $dataSource;
	$CRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('source0Num', $CRow['source0Num']);
	$EnableQCoreClass->replace('source0Rate', countpercent($CRow['source0Num'], $totalResponseNum));
	$SQL = ' SELECT COUNT(*) as source1Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' AND ' . $dataSource;
	$CRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('source1Num', $CRow['source1Num']);
	$EnableQCoreClass->replace('source1Rate', countpercent($CRow['source1Num'], $totalResponseNum));
	$SQL = ' SELECT responseID FROM ' . ANDROID_INFO_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Result = $DB->query($SQL);
	$responseIDList = array();

	while ($Row = $DB->queryArray($Result)) {
		$responseIDList[] = $Row['responseID'];
	}

	if (count($responseIDList) == 0) {
		$SQL = ' SELECT  COUNT(*) as source2Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE responseID=0 AND ' . $dataSource;
	}
	else {
		$SQL = ' SELECT  COUNT(*) as source2Num FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE responseID IN ( ' . implode(',', $responseIDList) . ' ) AND ' . $dataSource;
	}

	$CRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('source2Num', $CRow['source2Num']);
	$EnableQCoreClass->replace('source2Rate', countpercent($CRow['source2Num'], $totalResponseNum));
	unset($responseIDList);
	$EnableQCoreClass->replace('dataSourcePageList', $EnableQCoreClass->parse('DataSourceList', 'DataSourceListFile'));
}
else {
	if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
		$EnableQCoreClass->setTemplateFile('DataSourceListFile', 'uDataSourceList1.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('DataSourceListFile', 'DataSourceList1.html');
	}

	$cSQL = ' SELECT b.dataSource,count(*) as dataSourceNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE 1=1 and ' . $dataSource;
	$cSQL .= ' GROUP BY b.dataSource ORDER BY dataSourceNum DESC';
	$cResult = $DB->query($cSQL);
	$allDataSourceNum = array();

	while ($cRow = $DB->queryArray($cResult)) {
		$allDataSourceNum[$cRow['dataSource']] = $cRow['dataSourceNum'];
	}

	$tmp = 0;

	for (; $tmp <= 8; $tmp++) {
		$EnableQCoreClass->replace('source' . $tmp . 'Num', '0');
		$EnableQCoreClass->replace('source' . $tmp . 'Rate', '0');
	}

	foreach ($allDataSourceNum as $dataSource => $dataSourceNum) {
		$EnableQCoreClass->replace('source' . $dataSource . 'Num', $dataSourceNum);
		$EnableQCoreClass->replace('source' . $dataSource . 'Rate', countpercent($dataSourceNum, $totalResponseNum));
	}

	unset($allDataSourceNum);
	$EnableQCoreClass->replace('dataSourcePageList', $EnableQCoreClass->parse('DataSourceList', 'DataSourceListFile'));
}

if ($flag == 1) {
	$EnableQCoreClass->set_CycBlock('QuestionListFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
	case '7':
		$rootUserId = $Sur_G_Row['projectOwner'];
		$viewFlag = 1;
		break;

	case '3':
		switch ($_SESSION['adminRoleGroupType']) {
		case 1:
			$rootUserId = $Sur_G_Row['projectOwner'];
			$viewFlag = 1;
			break;

		case 2:
			$rootUserId = $_SESSION['adminRoleGroupID'];
			$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
			$uRow = $DB->queryFirstRow($uSQL);

			if ($uRow['isLeaf'] == 1) {
				$viewFlag = 2;
			}
			else {
				$viewFlag = 1;
			}

			break;
		}

		break;
	}

	$taskNumTotal = $taskNumOverTotal = $taskNumNoOverTotal = 0;
	$appStat3Total = $appStat1Total = $appStat2Total = 0;

	if ($viewFlag == 1) {
		$SQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND fatherId = \'' . $rootUserId . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$hasShow = true;

			if ($Row['isLeaf'] == 1) {
				$hSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND taskID = \'' . $Row['userGroupID'] . '\' LIMIT 1 ';
				$hRow = $DB->queryFirstRow($hSQL);

				if (!$hRow) {
					$hasShow = false;
				}
			}

			if ($hasShow == true) {
				$EnableQCoreClass->replace('taskName', $Row['userGroupName']);
				$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR a.userGroupID = \'' . $Row['userGroupID'] . '\') ';
				$hSQL = ' SELECT a.userGroupID FROM ' . USERGROUP_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.groupType = 2 AND ' . $theSonSQL . ' AND a.isLeaf=1 AND b.taskID = a.userGroupID AND b.surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY a.absPath ASC,a.userGroupID ASC ';
				$hResult = $DB->query($hSQL);
				$taskNum = 0;
				$taskIdArray = array();

				while ($hRow = $DB->queryArray($hResult)) {
					$taskNum++;
					$taskIdArray[] = $hRow['userGroupID'];
				}

				$EnableQCoreClass->replace('taskNum', $taskNum);
				$taskNumTotal += $taskNum;

				if (count($taskIdArray) == 0) {
					$EnableQCoreClass->replace('taskNumOver', 0);
					$EnableQCoreClass->replace('taskNumNoOver', 0);
					$EnableQCoreClass->replace('appStat3', 0);
					$EnableQCoreClass->replace('appStat1', 0);
					$EnableQCoreClass->replace('appStat2', 0);
				}
				else {
					$hSQL = ' SELECT COUNT(*) as taskNumOver FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID IN (' . implode(',', $taskIdArray) . ') AND overFlag IN (1,3) LIMIT 1 ';
					$hRow = $DB->queryFirstRow($hSQL);
					$EnableQCoreClass->replace('taskNumOver', $hRow['taskNumOver']);
					$taskNumOverTotal += $hRow['taskNumOver'];
					$taskNumNoOver = $taskNum - $hRow['taskNumOver'];
					$taskNumNoOverTotal += $taskNumNoOver;
					$EnableQCoreClass->replace('taskNumNoOver', $taskNumNoOver);
					$hSQL = ' SELECT COUNT(*) as appStat3 FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID IN (' . implode(',', $taskIdArray) . ') AND authStat = 1 AND appStat = 3 LIMIT 1 ';
					$hRow = $DB->queryFirstRow($hSQL);
					$EnableQCoreClass->replace('appStat3', $hRow['appStat3']);
					$appStat3Total += $hRow['appStat3'];
					$hSQL = ' SELECT COUNT(*) as appStat1 FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID IN (' . implode(',', $taskIdArray) . ') AND authStat = 1 AND appStat = 1 LIMIT 1 ';
					$hRow = $DB->queryFirstRow($hSQL);
					$EnableQCoreClass->replace('appStat1', $hRow['appStat1']);
					$appStat1Total += $hRow['appStat1'];
					$hSQL = ' SELECT COUNT(*) as appStat2 FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID IN (' . implode(',', $taskIdArray) . ') AND authStat = 1 AND appStat = 2 LIMIT 1 ';
					$hRow = $DB->queryFirstRow($hSQL);
					$EnableQCoreClass->replace('appStat2', $hRow['appStat2']);
					$appStat2Total += $hRow['appStat2'];
				}

				$EnableQCoreClass->parse('list', 'LIST', true);
			}
		}
	}
	else {
		$hSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND taskID = \'' . $uRow['userGroupID'] . '\' LIMIT 1 ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$EnableQCoreClass->replace('taskName', $uRow['userGroupName']);
			$EnableQCoreClass->replace('taskNum', 1);
			$taskNumTotal = 1;
			$hSQL = ' SELECT COUNT(*) as taskNumOver FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID =\'' . $uRow['userGroupID'] . '\' AND overFlag IN (1,3) LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$EnableQCoreClass->replace('taskNumOver', $hRow['taskNumOver']);
			$taskNumOverTotal = $hRow['taskNumOver'];
			$taskNumNoOver = $taskNumNoOverTotal = 1 - $hRow['taskNumOver'];
			$EnableQCoreClass->replace('taskNumNoOver', $taskNumNoOver);
			$hSQL = ' SELECT appStat FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID =\'' . $uRow['userGroupID'] . '\' AND authStat = 1 LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			switch ($hRow['appStat']) {
			case '1':
				$EnableQCoreClass->replace('appStat1', 1);
				$EnableQCoreClass->replace('appStat2', 0);
				$EnableQCoreClass->replace('appStat3', 0);
				$appStat1Total += 1;
				break;

			case '2':
				$EnableQCoreClass->replace('appStat1', 0);
				$EnableQCoreClass->replace('appStat2', 1);
				$EnableQCoreClass->replace('appStat3', 0);
				$appStat2Total += 1;
				break;

			case '3':
				$EnableQCoreClass->replace('appStat1', 0);
				$EnableQCoreClass->replace('appStat2', 0);
				$EnableQCoreClass->replace('appStat3', 1);
				$appStat3Total += 1;
				break;

			default:
				$EnableQCoreClass->replace('appStat1', 0);
				$EnableQCoreClass->replace('appStat2', 0);
				$EnableQCoreClass->replace('appStat3', 0);
				break;
			}

			$EnableQCoreClass->parse('list', 'LIST', true);
		}
	}

	$EnableQCoreClass->replace('taskNumTotal', $taskNumTotal);
	$EnableQCoreClass->replace('taskNumOverTotal', $taskNumOverTotal);
	$EnableQCoreClass->replace('taskNumNoOverTotal', $taskNumNoOverTotal);
	$EnableQCoreClass->replace('appStat3Total', $appStat3Total);
	$EnableQCoreClass->replace('appStat2Total', $appStat2Total);
	$EnableQCoreClass->replace('appStat1Total', $appStat1Total);
}

$QuestionList = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);
$QuestionList = str_replace($All_Path, '', $QuestionList);
$QuestionList = str_replace('PerUserData/', '../PerUserData/', $QuestionList);
$QuestionList = str_replace('JS/', '../JS/', $QuestionList);
echo $QuestionList;
exit();

?>

<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
//判断角色类型  '1'
_checkroletype('1|2|5|6');
$thisProg = 'ShowSurveyList.php';
// $_SESSION['surveyListURL'] = ShowSurveyList.php?pageID=1
if ($_SESSION['surveyListURL'] != '') {
	$EnableQCoreClass->replace('surveyListURL', $_SESSION['surveyListURL']);
	$surveyListURL = $_SESSION['surveyListURL'];
}
else {
	$EnableQCoreClass->replace('surveyListURL', $thisProg);
	$surveyListURL = $thisProg;
}

if (in_array($_SESSION['adminRoleType'], array('2', '5'))) {
	$EnableQCoreClass->replace('isShowCate', 'none');
}
else {
	$EnableQCoreClass->replace('isShowCate', '');
}
// $_GET['surveyID'] = NULL ;
if (isset($_GET['surveyID'])) {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	$SQL = ' SELECT status,administratorsID,surveyID,surveyTitle,surveyName,isPublic,beginTime,endTime,lang,isCache,projectType,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Sure_Row = $DB->queryFirstRow($SQL);
}
//$Sure_Row['status'] = NULL ;
switch ($Sure_Row['status']) {
case '0':
	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&beginTime=' . urlencode($Sure_Row['beginTime']) . '&endTime=' . urlencode($Sure_Row['endTime']) . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	$EnableQCoreClass->replace('isDeployStat', 'none');
	break;

case '1':
	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']) . '&beginTime=' . $Sure_Row['beginTime'] . '&endTime=' . $Sure_Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isTrackCode', 'none');
		$EnableQCoreClass->replace('isDeployStat', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isTrackCode', '');
		$EnableQCoreClass->replace('isDeployStat', '');
	}

	break;

case '2':
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('planURL', '');
	$EnableQCoreClass->replace('haveTask', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;
}

switch ($_SESSION['adminRoleType']) {
case 6:
	$EnableQCoreClass->replace('isAdmin6', 'none');
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;

default:
	$EnableQCoreClass->replace('isAdmin6', '');
	break;
}
// $_GET['Action'] = NULL;
if ($_GET['Action'] == 'Delete') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);

	if ($Sure_Row['status'] == 1) {
		_showerror($lang['status_error'], $lang['deploy_no_delete']);
	}

	if ($License['isEvalUsers'] || ($Sure_Row['status'] == 0)) {
		$survey_ID = $_GET['surveyID'];
		require 'Survey.dele.php';
	}
	else {
		include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
		$SQL = ' DELETE FROM ' . ARCHIVING_TABLE . ' WHERE surveyID = \'' . $Sure_Row['surveyID'] . '\' ';
		$DB->query($SQL);

		if (file_exists('../PerUserData/arc/survey_' . $Sure_Row['surveyName'] . '.zip')) {
			@unlink('../PerUserData/arc/survey_' . $Sure_Row['surveyName'] . '.zip');
		}

		if ($Sure_Row['custDataPath'] == '') {
			$dataPathName = 'response_' . $Sure_Row['surveyID'];
			if (!createdir($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/')) {
				_showerror($lang['new_dir_error'], $lang['cannot_new_a_dir']);
			}
		}
		else {
			$dataPathName = 'user/' . $Sure_Row['custDataPath'];

			if (!createdir($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/')) {
				_showerror($lang['new_dir_error'], $lang['cannot_new_a_dir']);
			}
		}

		$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
		$SerialRow = $DB->queryFirstRow($SQL);
		$surveyPageURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25) . 'Archive/SurveyPageArchive.php?qname=' . $Sure_Row['surveyName'] . '&qlang=' . $Sure_Row['lang'] . '&hash_code=' . md5(trim($SerialRow['license']));
		$pageFileContent = get_url_content($surveyPageURL);
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_page_' . $Sure_Row['surveyName'] . '.html', 'w+');
		fwrite($fp, $pageFileContent);
		fclose($fp);
		include_once ROOT_PATH . 'Functions/Functions.export.inc.php';
		if (($Sure_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sure_Row['surveyID'] . '/' . md5('Qtn' . $Sure_Row['surveyID']) . '.php')) {
			$theSID = $Sure_Row['surveyID'];
			require ROOT_PATH . 'Includes/MakeCache.php';
		}

		require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sure_Row['surveyID'] . '/' . md5('Qtn' . $Sure_Row['surveyID']) . '.php';
		$txtFileContent = export_text($Sure_Row['surveyID']);
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_question_' . $Sure_Row['surveyName'] . '.txt', 'w+');
		fwrite($fp, $txtFileContent);
		fclose($fp);
		$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Sure_Row['surveyID'] . ' ';
		$R_Row = $DB->queryFirstRow($R_SQL);

		if ($R_Row['resultNum'] != 0) {
			$surveyCountURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25) . 'Archive/ResultPageArchive.php?qname=' . $Sure_Row['surveyName'] . '&printType=all&hash_code=' . md5(trim($SerialRow['license']));
			$pageFileContent = get_url_content($surveyCountURL);
			$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_result_' . $Sure_Row['surveyName'] . '.html', 'w+');
			fwrite($fp, $pageFileContent);
			fclose($fp);
			$labelFileContent = export_label($Sure_Row['surveyID']);
			$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_label_' . $Sure_Row['surveyName'] . '.csv', 'w+');
			fwrite($fp, $labelFileContent);
			fclose($fp);
			$E_SQL = '1= 1';
			$resultFileContent = export($Sure_Row['surveyID'], stripslashes($E_SQL));
			$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_result_' . $Sure_Row['surveyName'] . '.csv', 'w+');
			fwrite($fp, $resultFileContent);
			fclose($fp);
			$spssFileContent = export_spss($Sure_Row['surveyID'], stripslashes($E_SQL));
			$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_spss_' . $Sure_Row['surveyName'] . '.csv', 'w+');
			fwrite($fp, $spssFileContent);
			fclose($fp);
			$awardFileContent = export_award($Sure_Row['surveyID']);
			$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_award_' . $Sure_Row['surveyName'] . '.csv', 'w+');
			fwrite($fp, $awardFileContent);
			fclose($fp);
		}

		include_once ROOT_PATH . 'Includes/Zip.class.php';
		$Ziper = new zip_file('../PerUserData/arc/survey_' . $Sure_Row['surveyName'] . '.zip');
		$Ziper->set_options(array('inmemory' => 0, 'recurse' => 1, 'storepaths' => 0, 'overwrite' => 1, 'type' => 'zip'));
		$Ziper->add_files('../' . $Config['dataDirectory'] . '/' . $dataPathName . '/');
		$ZipSucc = $Ziper->create_archive();

		if ($ZipSucc) {
			$SQL = ' INSERT INTO ' . ARCHIVING_TABLE . ' SET surveyID=\'' . $Sure_Row['surveyID'] . '\',surveyTitle=\'' . $Sure_Row['surveyTitle'] . '\',surveyName=\'' . $Sure_Row['surveyName'] . '\',administratorsID=\'' . $Sure_Row['administratorsID'] . '\',isPublic=\'' . $Sure_Row['isPublic'] . '\', beginTime=\'' . $Sure_Row['beginTime'] . '\',endTime=\'' . $Sure_Row['endTime'] . '\',archivingFile=\'survey_' . $Sure_Row['surveyName'] . '.zip\',archivingTime=\'' . time() . '\',archivingOwner=\'' . $_SESSION['administratorsID'] . '\' ';
			$DB->query($SQL);

			if (file_exists('../PerUserData/arc/survey_' . $Sure_Row['surveyName'] . '.zip')) {
				$survey_ID = $Sure_Row['surveyID'];
				require 'Survey.dele.php';
			}
		}
		else {
			$errorInfo = 'Could not create zip file：<br/>';

			foreach ($Ziper->error as $theErrorInfo) {
				$errorInfo .= $theErrorInfo . '<br/>';
			}

			_showerror('System Error', $errorInfo);
		}
	}

	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['delete_survey'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['delete_survey'] . ':' . $_GET['surveyTitle'], $surveyListURL);
}

if ($_GET['Action'] == 'ClearCache') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);
	deletedir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $_GET['surveyID'] . '/');
	writetolog($lang['clear_cache'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['clear_cache'], $surveyListURL);
}

if ($_GET['Action'] == 'ClearStatus') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);

	if ($Sure_Row['status'] != 1) {
		_showerror($lang['status_error'], $lang['no_deploy_clear']);
	}

	$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Sure_Row['surveyID'] . ' ';
	$R_Row = $DB->queryFirstRow($R_SQL);
	if (($R_Row['resultNum'] != 0) && ($License['isEvalUsers'] != 1)) {
		include_once ROOT_PATH . 'Functions/Functions.export.inc.php';
		$labelFileContent = export_label($Sure_Row['surveyID']);
		$theLabelFileName = $Config['absolutenessPath'] . '/PerUserData/tmp/survey_label_' . $Sure_Row['surveyName'] . '.csv';

		if (file_exists($theLabelFileName)) {
			@unlink($theLabelFileName);
		}

		$fp = fopen($theLabelFileName, 'w+');
		fwrite($fp, $labelFileContent);
		fclose($fp);
		$E_SQL = '1= 1';
		$resultFileContent = export($Sure_Row['surveyID'], stripslashes($E_SQL));
		$theResultFileName = $Config['absolutenessPath'] . '/PerUserData/tmp/survey_result_' . $Sure_Row['surveyName'] . '.csv';

		if (file_exists($theResultFileName)) {
			@unlink($theResultFileName);
		}

		$fp = fopen($theResultFileName, 'w+');
		fwrite($fp, $resultFileContent);
		fclose($fp);
		$spssFileContent = export_spss($Sure_Row['surveyID'], stripslashes($E_SQL));
		$theSPSSFileName = $Config['absolutenessPath'] . '/PerUserData/tmp/survey_spss_' . $Sure_Row['surveyName'] . '.csv';

		if (file_exists($theSPSSFileName)) {
			@unlink($theSPSSFileName);
		}

		$fp = fopen($theSPSSFileName, 'w+');
		fwrite($fp, $spssFileContent);
		fclose($fp);
	}

	$SQL = ' DROP TABLE IF EXISTS ' . $table_prefix . 'response_' . $Sure_Row['surveyID'] . ' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . COUNTYEARNUM_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . COUNTMONTHNUM_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . COUNTDAYNUM_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET TotalNum = 0, StartDate = \'\',MonthNum = 0,MonthMaxNum = 0,OldMonth = \'\',MonthMaxDate = \'\',DayNum = 0,DayMaxNum = 0,OldDay =\'\',DayMaxDate = \'\',HourNum = 0,HourMaxNum = 0,OldHour = \'\',HourMaxTime = \'\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . AWARDPRODUCT_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . AWARDLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . QUERY_LIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . QUERY_COND_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ANDROID_LIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ANDROID_INFO_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ANDROID_PUSH_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . DATA_TRACE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . DATA_TASK_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	deletedir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $_GET['surveyID'] . '/');

	if ($Sure_Row['custDataPath'] == '') {
		$surveyPhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
		$vSurveyPhyPath = 'response_' . $_GET['surveyID'] . '/';
	}
	else {
		$surveyPhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sure_Row['custDataPath'] . '/';
		$vSurveyPhyPath = 'user/' . $Sure_Row['custDataPath'] . '/';
	}

	if (is_dir($surveyPhyPath)) {
		require_remote_service(2, base64_encode($vSurveyPhyPath));
		deletedir($surveyPhyPath);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=\'0\',indexTime=0,indexVersion=0,indexAdminId=0 WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['clear_survey'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['clear_survey'], $surveyListURL);
}

if ($_GET['Action'] == 'DeployStatus') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);

	if ($Sure_Row['status'] != '2') {
		_showerror('状态错误', '状态错误：问卷当前的状态尚不能重新转为执行状态，请确定您目前操作的正确？');
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=\'1\',endTime=\'' . date('Y-m-d', time() + (30 * 86400)) . '\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['deploy_return'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['deploy_return'], $surveyListURL);
}

if ($_GET['Action'] == 'Copy') {
	if ($License['Limited'] == 1) {
		$SQL = ' SELECT COUNT(*) AS surveyNum FROM ' . SURVEY_TABLE . ' LIMIT 1 ';
		$Row = $DB->queryFirstRow($SQL);

		if ($License['LimitedNum'] <= $Row['surveyNum']) {
			_showerror($lang['limited_soft'], $lang['limited_soft']);
		}
	}

	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);
	$lastSurveyID = $_GET['surveyID'];
	$isCheckSurveyName = 1;
	require 'Survey.copy.php';
	writetolog($lang['copy_survey'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['copy_survey'] . ':' . $_GET['surveyTitle'], $surveyListURL);
}

if ($_GET['Action'] == 'ChangeStatus') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);

	switch ($_GET['status']) {
	case '0':
		if ($Sure_Row['status'] != '1') {
			_showerror('状态错误', '状态错误：问卷当前的状态尚不能转为设计状态，请确定您目前操作的正确？');
		}

		break;

	case '1':
		if ($Sure_Row['status'] == '1') {
			_showerror('状态错误', '状态错误：问卷当前的状态已经是执行状态！');
		}

		break;

	case '2':
		if ($Sure_Row['status'] != '1') {
			_showerror('状态错误', '状态错误：问卷当前的状态尚不能转为结束状态，请确定您目前操作的正确？');
		}

		break;
	}

	if ($_GET['status'] == '1') {
		$theDeploySurveyID = $_GET['surveyID'];
		$isAjaxActionFlag = 0;
		require 'Survey.deploy.php';
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=\'' . $_GET['status'] . '\' ';

	if ($_GET['status'] == '2') {
		$SQL .= ' ,endTime = \'' . date('Y-m-d') . '\' ';
	}

	$SQL .= ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['changestatus_survey'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['changestatus_survey'], $surveyListURL);
}

if ($_POST['Action'] == 'AddSurveyStep2Submit') {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPublic=\'' . $_POST['isPublic'] . '\',tokenCode=\'' . $_POST['tokenCode'] . '\',exitMode=\'' . $_POST['exitMode'] . '\',exitPage=\'' . $_POST['exitPage'] . '\',exitTitleHead=\'' . $_POST['exitTitleHead'] . '\',exitTextBody=\'' . $_POST['exitTextBody'] . '\',isCheckIP=\'' . $_POST['isCheckIP'] . '\',maxIpTime=\'' . $_POST['maxIpTime'] . '\',updateTime=\'' . time() . '\' ';

	if ($_POST['exitMode'] == 4) {
		$SQL .= ' ,isViewResult=1 ';
	}

	$SQL .= ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['edit_survey'] . ':' . $_POST['surveyTitle']);
	$nextAddURL = 'DesignSurvey.php?surveyID=' . $_POST['surveyID'] . '&surveyTitle=' . urlencode($_POST['surveyTitle']);
	echo '<script>parent.hidePopWin();parent.referIframeSrc(\'' . $nextAddURL . '\');</script>';
	exit();
}

if ($_GET['Action'] == 'AddStep2') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('SurveyEditPageFile', 'SurveyNew.html');
	$EnableQCoreClass->replace('surveyName', $Row['surveyName']);
	$EnableQCoreClass->replace('exitPage', $Row['exitPage']);
	$EnableQCoreClass->replace('exitTitleHead', $Row['exitTitleHead']);
	$EnableQCoreClass->replace('exitTextBody', $Row['exitTextBody']);
	$EnableQCoreClass->replace('isCheckIP' . $Row['isCheckIP'], 'selected');
	$EnableQCoreClass->replace('maxIpTime', $Row['maxIpTime']);
	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$EnableQCoreClass->replace('userName', $_SESSION['administratorsName']);
	$EnableQCoreClass->replace('option_' . $Row['isPublic'], 'selected');
	$EnableQCoreClass->replace('exitMode_' . $Row['exitMode'], 'checked');
	$EnableQCoreClass->replace('tokenCode', $Row['tokenCode']);
	$EnableQCoreClass->replace('Action', 'AddSurveyStep2Submit');
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$EnableQCoreClass->replace('endTime', $Row['endTime']);
	$EnableQCoreClass->parse('SurveyEditPage', 'SurveyEditPageFile');
	$EnableQCoreClass->output('SurveyEditPage', false);
}

if ($_POST['Action'] == 'AddSurveySubmit') {
	if ($License['Limited'] == 1) {
		$SQL = ' SELECT COUNT(*) AS surveyNum FROM ' . SURVEY_TABLE . ' ';
		$Row = $DB->queryFirstRow($SQL);

		if ($License['LimitedNum'] <= $Row['surveyNum']) {
			_showerror($lang['limited_soft'], $lang['limited_soft']);
		}
	}

	if (!isset($_SESSION['PageToken0']) || ($_SESSION['PageToken0'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$SQL = ' INSERT INTO ' . SURVEY_TABLE . ' SET isPublic=\'1\',theme=\'Standard\',status=0,surveyTitle=\'' . strip_tags($_POST['surveyTitle']) . '\',surveySubTitle=\'' . $_POST['surveySubTitle'] . '\',surveyMaxOption=\'5\',surveyInfo=\'' . $_POST['surveyInfo'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',maxIpTime=\'1000\',isViewResult=\'0\',isShowResultBut=\'0\',beginTime=\'' . date('Y-m-d', time()) . '\',isProperty=0,endTime=\'' . date('Y-m-d', time() + (30 * 86400)) . '\',joinTime=\'' . time() . '\' ';
	$DB->query($SQL);
	$lastSurveyID = $DB->_GetInsertID();

	if ($_POST['surveyName'] != '') {
		$surveyName = $_POST['surveyName'];
	}
	else {
		$surveyName = 'diaocha_' . $lastSurveyID;
	}

	if ($_POST['isCheckIP'] != '') {
		$isCheckIP = $_POST['isCheckIP'];
		$maxIpTime = 10;
	}
	else {
		$isCheckIP = 2;
		$maxIpTime = 43200;
	}

	if ($_POST['isPublic'] != '') {
		$isPublic = $_POST['isPublic'];
	}
	else {
		$isPublic = 1;
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET surveyName=\'' . $surveyName . '\',lang=\'' . $_POST['language'] . '\',isCheckIP=\'' . $isCheckIP . '\',maxIpTime=\'' . $maxIpTime . '\',isPublic=\'' . $isPublic . '\',exitMode=2,isPanelFlag=0 WHERE surveyID=\'' . $lastSurveyID . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken0']);
	writetolog($lang['add_survey'] . ':' . $_POST['surveyTitle']);
	$nextAddURL = 'ShowSurveyList.php?Action=AddStep2&surveyID=' . $lastSurveyID . '&surveyTitle=' . urlencode($_POST['surveyTitle']);
	_showsucceed($lang['add_survey'] . ':' . $_POST['surveyTitle'], $nextAddURL);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('SurveyAddPageFile', 'SurveyAdd.html');
	$_SESSION['PageToken0'] = session_id();
	$EnableQCoreClass->replace('surveyName', $_GET['surveyName']);
	$EnableQCoreClass->replace('isCheckIP', $_GET['isCheckIP']);
	$EnableQCoreClass->replace('isPublic', $_GET['surveyType']);
	$EnableQCoreClass->replace('scriptPath', 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25));
	$EnableQCoreClass->parse('SurveyAddPage', 'SurveyAddPageFile');
	$EnableQCoreClass->output('SurveyAddPage', false);
}

if ($_POST['Action'] == 'EditSurveySubmit') {
	if ($License['Limited'] == 1) {
		$SQL = ' SELECT COUNT(*) AS surveyNum FROM ' . SURVEY_TABLE . ' ';
		$Row = $DB->queryFirstRow($SQL);

		if ($License['LimitedNum'] < $Row['surveyNum']) {
			_showerror($lang['limited_soft'], $lang['limited_soft']);
		}
	}

	$SQL = ' SELECT surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_POST['surveyName']) . '\' AND surveyName!=\'' . $_POST['ori_surveyName'] . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['surveyName_is_exist']);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET surveyName=\'' . trim($_POST['surveyName']) . '\',lang=\'' . $_POST['language'] . '\',isPublic=\'' . $_POST['isPublic'] . '\',tokenCode=\'' . $_POST['tokenCode'] . '\',theme=\'' . $_POST['theme'] . '\',uitheme=\'' . $_POST['uitheme'] . '\',panelID=\'' . $_POST['panelID'] . '\',surveyTitle=\'' . strip_tags($_POST['surveyTitle']) . '\',surveySubTitle=\'' . $_POST['surveySubTitle'] . '\',surveyInfo=\'' . $_POST['surveyInfo'] . '\',exitMode=\'' . $_POST['exitMode'] . '\',exitPage=\'' . $_POST['exitPage'] . '\',exitTitleHead=\'' . $_POST['exitTitleHead'] . '\',exitTextBody=\'' . $_POST['exitTextBody'] . '\',isShowProof=\'' . $_POST['isShowProof'] . '\',proofRate=\'' . $_POST['proofRate'] . '\',apiURL=\'' . $_POST['apiURL'] . '\',apiVarName=\'' . $_POST['apiVarName'] . '\',isCheckIP=\'' . $_POST['isCheckIP'] . '\',maxIpTime=\'' . $_POST['maxIpTime'] . '\',maxResponseNum = \'' . $_POST['maxResponseNum'] . '\',isProperty=\'' . $_POST['isProperty'] . '\',isPreStep=\'' . $_POST['isPreStep'] . '\',isProcessBar=\'' . $_POST['isProcessBar'] . '\',isViewResult=\'' . $_POST['isViewResult'] . '\',isShowResultBut=\'' . $_POST['isShowResultBut'] . '\',mainAttribute=\'' . implode(',', $_POST['mainAttribute']) . '\',isSecureImage=\'' . $_POST['isSecureImage'] . '\',isWaiting=\'' . $_POST['isWaiting'] . '\',waitingTime=\'' . $_POST['waitingTime'] . '\',isPanelFlag=\'' . $_POST['isPanelFlag'] . '\',isLimited=\'' . $_POST['isLimited'] . '\',limitedTime=\'' . $_POST['limitedTime'] . '\',isCheckStat0=\'' . $_POST['isCheckStat0'] . '\',isRelZero=\'' . $_POST['isRelZero'] . '\',isRateIndex=\'' . $_POST['isRateIndex'] . '\',isOfflineDele=\'' . $_POST['isOfflineDele'] . '\',isGpsEnable=\'' . $_POST['isGpsEnable'] . '\',isFingerDrawing=\'' . $_POST['isFingerDrawing'] . '\',isDisRefresh=\'' . $_POST['isDisRefresh'] . '\',isAllData=\'' . $_POST['isAllData'] . '\',beginTime=\'' . $_POST['beginTime'] . '\',endTime=\'' . $_POST['endTime'] . '\',updateTime=\'' . time() . '\',offlineCount=\'' . implode(',', $_POST['offlineCount']) . '\',forbidViewId=\'' . implode(',', $_POST['forbidViewId']) . '\',forbidAppId=\'' . implode(',', $_POST['forbidAppId']) . '\',isViewAuthData=\'' . $_POST['isViewAuthData'] . '\',isViewAuthInfo=\'' . $_POST['isViewAuthInfo'] . '\',isFailReApp=\'' . $_POST['isFailReApp'] . '\',isExportData=\'' . $_POST['isExportData'] . '\',isImportData=\'' . $_POST['isImportData'] . '\',isDeleteData=\'' . $_POST['isDeleteData'] . '\',isModiData=\'' . $_POST['isModiData'] . 

	'\',isOneData=\'' . $_POST['isOneData'] . 
	'\',task_scope=\'' . $_POST['task_scope'] . 
	'\',task_objectives=\'' . $_POST['task_objectives'] . 
	'\',task_price=\'' . $_POST['task_price'] . 
	'\',isGeolocation=\'' . $_POST['isGeolocation'] . '\' ';



	switch ($_POST['isRecord']) {
	case 1:
		$SQL .= ',isRecord=\'1\',isLowRecord=\'' . $_POST['isLowRecord'] . '\',isUploadRec=\'' . $_POST['isUploadRec'] . '\',isOfflineModi=\'' . $_POST['isOfflineModi'] . '\'';
		break;

	case 2:
		$SQL .= ',isRecord=\'2\',isLowRecord=\'' . $_POST['isLowRecord'] . '\',isUploadRec=\'0\',isOfflineModi=\'0\'';
		$hSQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND questionType = 11 LIMIT 1 ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			_showerror('一致性检查错误', '一致性检查错误：全程录像模式下问卷内存在文件上传题型！');
		}

		break;

	default:
		$SQL .= ',isRecord=\'0\',isLowRecord=\'0\',isUploadRec=\'0\',isOfflineModi=\'' . $_POST['isOfflineModi'] . '\'';
		break;
	}

	if (($_POST['isHongBao'] == 1) && ($_SESSION['adminRoleType'] == 1)) {
		$SQL .= ',isHongBao=1,isOnlyWeChat=1,getChatUserInfo=1,getChatUserMode=2';
	}

	$SQL .= ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['isPublic'] == '0') {
		switch ($_POST['passportType']) {
		case '1':
		default:
			break;

		case '3':
		case '5':
			if ($_POST['adUserName'] != $_POST['ori_adUserName']) {
				$SQL = ' DELETE FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
				$DB->query($SQL);
				$adUserName = explode(';', trim($_POST['adUserName']));
				$i = 0;

				for (; $i < count($adUserName); $i++) {
					if ($adUserName[$i] != '') {
						$SQL = ' INSERT INTO ' . RESPONSEGROUPLIST_TABLE . ' SET adUserName=\'' . strtolower(trim($adUserName[$i])) . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
						$DB->query($SQL);
					}
				}
			}

			break;
		}
	}
	else {
		$SQL = ' DELETE FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
		$DB->query($SQL);
	}

	writetolog($lang['edit_survey'] . ':' . $_POST['surveyTitle']);

	if ($_POST['isPre'] == 1) {
		_showmessage($lang['edit_survey'] . ':' . $_POST['surveyTitle'], true);
	}
	else {
		_showsucceed($lang['edit_survey'] . ':' . $_POST['surveyTitle'], 'ShowSurveyList.php?Action=Edit&surveyID=' . $_POST['surveyID']);
	}
}

if ($_GET['Action'] == 'Edit') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('SurveyEditPageFile', 'SurveyEdit.html');
	$EnableQCoreClass->replace('isPre', $_GET['isPre']);
	$EnableQCoreClass->replace('surveyName', $Row['surveyName']);
	$EnableQCoreClass->replace('surveyTitle', qnohtmltag($Row['surveyTitle']));
	$EnableQCoreClass->replace('surveyTitleURL', urlencode($Row['surveyTitle']));
	$EnableQCoreClass->replace('surveySubTitle', $Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $Row['surveyInfo']);
	$EnableQCoreClass->replace('surveyMaxOption', $Row['surveyMaxOption']);
	$EnableQCoreClass->replace('exitPage', $Row['exitPage']);
	$EnableQCoreClass->replace('exitTitleHead', $Row['exitTitleHead']);
	$EnableQCoreClass->replace('exitTextBody', $Row['exitTextBody']);
	$EnableQCoreClass->replace('apiURL', $Row['apiURL']);
	$EnableQCoreClass->replace('apiVarName', $Row['apiVarName']);
	$EnableQCoreClass->replace('isCheckIP' . $Row['isCheckIP'], 'selected');
	$EnableQCoreClass->replace('task_scope', $Row['task_scope']);//任务范围
	$EnableQCoreClass->replace('task_objectives', $Row['task_objectives']);//任务目标
	$EnableQCoreClass->replace('task_price', $Row['task_price']);//任务价钱
	if ($Row['lang'] == '') {
		$EnableQCoreClass->replace('surveyLang_cn', 'checked');
	}
	else {
		$EnableQCoreClass->replace('surveyLang_' . $Row['lang'], 'checked');
	}

	$EnableQCoreClass->replace('maxIpTime', $Row['maxIpTime']);
	$maxResponseNum = ($Row['maxResponseNum'] == 0 ? '' : $Row['maxResponseNum']);
	$EnableQCoreClass->replace('maxResponseNum', $maxResponseNum);
	$isShowProof = ($Row['isShowProof'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isShowProof', $isShowProof);
	$EnableQCoreClass->replace('proofRate', $Row['proofRate']);

	switch ($_SESSION['adminRoleType']) {
	case 1:
		$isHongBao = ($Row['isHongBao'] == '1' ? 'checked' : '');
		$EnableQCoreClass->replace('isHongBao', $isHongBao);
		break;

	default:
		$EnableQCoreClass->replace('isHongBao', 'disabled');
		break;
	}

	$EnableQCoreClass->replace('hongbaoRate', $Row['hongbaoRate']);

	switch ($Row['isRecord']) {
	case 1:
		$EnableQCoreClass->replace('isRecord_1', 'checked');
		$EnableQCoreClass->replace('isRecord_2', '');
		$isUploadRec = ($Row['isUploadRec'] == '1' ? 'checked' : '');
		$EnableQCoreClass->replace('isUploadRec', $isUploadRec);
		$isOfflineModi = ($Row['isOfflineModi'] == '1' ? 'checked' : '');
		$EnableQCoreClass->replace('isOfflineModi', $isOfflineModi);
		break;

	case 2:
		$EnableQCoreClass->replace('isRecord_1', '');
		$EnableQCoreClass->replace('isRecord_2', 'checked');
		$EnableQCoreClass->replace('isUploadRec', 'disabled');
		$EnableQCoreClass->replace('isOfflineModi', 'disabled');
		break;

	default:
		$EnableQCoreClass->replace('isRecord_1', '');
		$EnableQCoreClass->replace('isRecord_2', '');
		$EnableQCoreClass->replace('isUploadRec', 'disabled');
		break;
	}

	$isLowRecord = ($Row['isLowRecord'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isLowRecord', $isLowRecord);
	$isPanelFlag = ($Row['isPanelFlag'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isPanelFlag', $isPanelFlag);
	$isGpsEnable = ($Row['isGpsEnable'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isGpsEnable', $isGpsEnable);
	$isFingerDrawing = ($Row['isFingerDrawing'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isFingerDrawing', $isFingerDrawing);
	$EnableQCoreClass->replace('isCloseStatus', $Row['status'] == 2 ? 'disabled' : '');
	$theUniCodePath = ROOT_PATH . 'PerUserData/unicode/';
	$theCacheFile = $theUniCodePath . md5('uniCode' . $_GET['surveyID']) . '.php';

	if (file_exists($theCacheFile)) {
		require_once $theCacheFile;
		$EnableQCoreClass->replace('haveUniCodeNum', count($uniCodeQKeyArray));
		$EnableQCoreClass->replace('isHaveUniCode', '');
	}
	else {
		$EnableQCoreClass->replace('haveUniCodeNum', 0);
		$EnableQCoreClass->replace('isHaveUniCode', 'disabled');
	}

	$QSQL = '  SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND questionType !=8 ORDER BY orderByID ASC  ';
	$OResult = $DB->query($QSQL);
	$offlineCountList = $forbidViewIdList = $forbidAppIdList = '';
	$theOfflineCountValue = explode(',', $Row['offlineCount']);
	$forbidViewIdValue = explode(',', $Row['forbidViewId']);
	$forbidAppIdValue = explode(',', $Row['forbidAppId']);

	if (in_array('t1', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t1', 'selected');
	}
	else {
		$EnableQCoreClass->replace('t1', '');
	}

	if (in_array('t2', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t2', 'selected');
	}
	else {
		$EnableQCoreClass->replace('t2', '');
	}

	if (in_array('t3', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t3', 'selected');
	}
	else {
		$EnableQCoreClass->replace('t3', '');
	}

	if (in_array('t4', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t4', 'selected');
	}
	else {
		$EnableQCoreClass->replace('t4', '');
	}

	while ($ORow = $DB->queryArray($OResult)) {
		$questionName = qnohtmltag($ORow['questionName'], 1);

		if (in_array($ORow['questionType'], array(1, 2, 3, 24, 25))) {
			if (in_array($ORow['questionID'], $theOfflineCountValue)) {
				$offlineCountList .= '<option value=\'' . $ORow['questionID'] . '\' selected>' . $questionName . '(' . $lang['question_type_' . $ORow['questionType']] . ')</option>' . "\n" . '';
			}
			else {
				$offlineCountList .= '<option value=\'' . $ORow['questionID'] . '\'>' . $questionName . '(' . $lang['question_type_' . $ORow['questionType']] . ')</option>' . "\n" . '';
			}
		}

		if (in_array($ORow['questionID'], $forbidViewIdValue)) {
			$forbidViewIdList .= '<option value=\'' . $ORow['questionID'] . '\' selected>' . $questionName . '(' . $lang['question_type_' . $ORow['questionType']] . ')</option>' . "\n" . '';
		}
		else {
			$forbidViewIdList .= '<option value=\'' . $ORow['questionID'] . '\'>' . $questionName . '(' . $lang['question_type_' . $ORow['questionType']] . ')</option>' . "\n" . '';
		}

		if (in_array($ORow['questionID'], $forbidAppIdValue)) {
			$forbidAppIdList .= '<option value=\'' . $ORow['questionID'] . '\' selected>' . $questionName . '(' . $lang['question_type_' . $ORow['questionType']] . ')</option>' . "\n" . '';
		}
		else {
			$forbidAppIdList .= '<option value=\'' . $ORow['questionID'] . '\'>' . $questionName . '(' . $lang['question_type_' . $ORow['questionType']] . ')</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('offlineCountList', $offlineCountList);
	$EnableQCoreClass->replace('forbidViewIdList', $forbidViewIdList);
	$EnableQCoreClass->replace('forbidAppIdList', $forbidAppIdList);
	$isProperty = ($Row['isProperty'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isProperty', $isProperty);
	$isPreStep = ($Row['isPreStep'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isPreStep', $isPreStep);
	$isProcessBar = ($Row['isProcessBar'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isProcessBar', $isProcessBar);
	$isViewResult = ($Row['isViewResult'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isViewResult', $isViewResult);
	$isShowResultBut = ($Row['isShowResultBut'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isShowResultBut', $isShowResultBut);
	$isSecureImage = ($Row['isSecureImage'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isSecureImage', $isSecureImage);
	$isWaiting = ($Row['isWaiting'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isWaiting', $isWaiting);
	$waitingTime = ($Row['waitingTime'] == 0 ? 10 : $Row['waitingTime']);
	$EnableQCoreClass->replace('waitingTime', $waitingTime);
	$isLimited = ($Row['isLimited'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isLimited', $isLimited);
	$limitedTime = ($Row['limitedTime'] == 0 ? 2400 : $Row['limitedTime']);
	$EnableQCoreClass->replace('limitedTime', $limitedTime);
	$isDisRefresh = ($Row['isDisRefresh'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isDisRefresh', $isDisRefresh);
	$isCheckStat0 = ($Row['isCheckStat0'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isCheckStat0', $isCheckStat0);
	$isRelZero = ($Row['isRelZero'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isRelZero', $isRelZero);
	$isRateIndex = ($Row['isRateIndex'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isRateIndex', $isRateIndex);
	$isOfflineDele = ($Row['isOfflineDele'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isOfflineDele', $isOfflineDele);
	$isGeolocation = ($Row['isGeolocation'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isGeolocation', $isGeolocation);
	$isAllData = ($Row['isAllData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isAllData', $isAllData);
	$isViewAuthInfo = ($Row['isViewAuthInfo'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isViewAuthInfo', $isViewAuthInfo);
	$isViewAuthData = ($Row['isViewAuthData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isViewAuthData', $isViewAuthData);
	$isExportData = ($Row['isExportData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isExportData', $isExportData);
	$isImportData = ($Row['isImportData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isImportData', $isImportData);
	$isDeleteData = ($Row['isDeleteData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isDeleteData', $isDeleteData);
	$isModiData = ($Row['isModiData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isModiData', $isModiData);
	$isOneData = ($Row['isOneData'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isOneData', $isOneData);
	$isFailReApp = ($Row['isFailReApp'] == '1' ? 'checked' : '');
	$EnableQCoreClass->replace('isFailReApp', $isFailReApp);
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$EnableQCoreClass->replace('endTime', $Row['endTime']);
	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$EnableQCoreClass->replace('userName', urlencode($_SESSION['administratorsName']));
	$EnableQCoreClass->replace('option_' . $Row['isPublic'], 'selected');
	$EnableQCoreClass->replace('exitMode_' . $Row['exitMode'], 'checked');
	$EnableQCoreClass->replace('tokenCode', $Row['tokenCode']);
	$EnableQCoreClass->replace('theme_' . $Row['theme'], 'selected');
	$EnableQCoreClass->replace('uitheme_' . $Row['uitheme'], 'selected');
	$EnableQCoreClass->replace('Action', 'EditSurveySubmit');

	if (in_array($_SESSION['adminRoleType'], array('2', '5'))) {
		$SQL = ' SELECT panelID,tplTagName,isDefault FROM ' . TPL_TABLE . ' WHERE administratorsID IN ( 0,' . $_SESSION['administratorsID'] . ') ORDER BY panelID ASC ';
	}
	else {
		$SQL = ' SELECT panelID,tplTagName,isDefault FROM ' . TPL_TABLE . ' ORDER BY panelID ASC ';
	}

	$Result = $DB->query($SQL);
	$panelList = '';

	while ($TplRow = $DB->queryArray($Result)) {
		if (($Row['panelID'] == $TplRow['panelID']) || (($Row['panelID'] == 0) && ($TplRow['isDefault'] == '1'))) {
			$panelList .= '<option value=\'' . $TplRow['panelID'] . '\' selected>' . $TplRow['tplTagName'] . '</option>' . "\n" . '';
		}
		else {
			$panelList .= '<option value=\'' . $TplRow['panelID'] . '\'>' . $TplRow['tplTagName'] . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('panelList', $panelList);
	$SQL = ' SELECT * FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);

	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		$EnableQCoreClass->replace('isUseOriPassport', '');
		$EnableQCoreClass->replace('isUseOriPassportCheck', '1');
		$EnableQCoreClass->replace('isUseAjaxPassportCheck', '0');
		$EnableQCoreClass->replace('hashCode', '');
		$EnableQCoreClass->replace('isAjaxPassport', 'none');
		$EnableQCoreClass->replace('adUserName', '');
		$EnableQCoreClass->replace('ajaxResponseURL', '');
		$SQL = ' SELECT administratorsoptionID,optionFieldName FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE types IN (\'select\',\'radio\') ORDER BY orderByID ASC ';
		$Result = $DB->query($SQL);
		$mainAttribute = '';

		if ($Row['mainAttribute'] == '') {
			$SQL = ' SELECT mainAttribute FROM ' . ADMINISTRATORSCONFIG_TABLE . ' ';
			$ConfigRow = $DB->queryFirstRow($SQL);
			$theMainAttribute = explode(',', $ConfigRow['mainAttribute']);

			while ($OptionRow = $DB->queryArray($Result)) {
				if (in_array($OptionRow['administratorsoptionID'], $theMainAttribute)) {
					$mainAttribute .= '<option value=\'' . $OptionRow['administratorsoptionID'] . '\' selected>' . $OptionRow['optionFieldName'] . '</option>';
				}
				else {
					$mainAttribute .= '<option value=\'' . $OptionRow['administratorsoptionID'] . '\'>' . $OptionRow['optionFieldName'] . '</option>';
				}
			}
		}
		else {
			$theMainAttribute = explode(',', $Row['mainAttribute']);

			while ($OptionRow = $DB->queryArray($Result)) {
				if (in_array($OptionRow['administratorsoptionID'], $theMainAttribute)) {
					$mainAttribute .= '<option value=\'' . $OptionRow['administratorsoptionID'] . '\' selected>' . $OptionRow['optionFieldName'] . '</option>';
				}
				else {
					$mainAttribute .= '<option value=\'' . $OptionRow['administratorsoptionID'] . '\'>' . $OptionRow['optionFieldName'] . '</option>';
				}
			}
		}

		$EnableQCoreClass->replace('mainAttributeList', $mainAttribute);
		$EnableQCoreClass->replace('LDAPActionURL', '');
		break;

	case '2':
		$EnableQCoreClass->replace('isUseOriPassport', 'none');
		$EnableQCoreClass->replace('isUseOriPassportCheck', '0');
		$EnableQCoreClass->replace('isUseAjaxPassportCheck', '1');
		$EnableQCoreClass->replace('isAjaxPassport', 'block');

		if (strpos(trim($BaseRow['ajaxResponseURL']), '?') === false) {
			$EnableQCoreClass->replace('ajaxResponseURL', trim($BaseRow['ajaxResponseURL']) . '?');
		}
		else {
			$EnableQCoreClass->replace('ajaxResponseURL', trim($BaseRow['ajaxResponseURL']));
		}

		$EnableQCoreClass->replace('hashCode', md5($BaseRow['license']));
		$EnableQCoreClass->replace('mainAttributeList', '');
		$EnableQCoreClass->replace('adUserName', '');
		$EnableQCoreClass->replace('LDAPActionURL', '');
		break;

	case '4':
		$EnableQCoreClass->replace('isUseOriPassport', 'none');
		$EnableQCoreClass->replace('isUseOriPassportCheck', '0');
		$EnableQCoreClass->replace('isUseAjaxPassportCheck', '0');
		$EnableQCoreClass->replace('isAjaxPassport', 'none');
		$EnableQCoreClass->replace('ajaxResponseURL', '');
		$EnableQCoreClass->replace('hashCode', '');
		$EnableQCoreClass->replace('mainAttributeList', '');
		$EnableQCoreClass->replace('adUserName', '');
		$EnableQCoreClass->replace('LDAPActionURL', '');
		break;

	case '3':
		$EnableQCoreClass->replace('isUseOriPassport', '');
		$EnableQCoreClass->replace('isUseOriPassportCheck', '3');
		$EnableQCoreClass->replace('isUseAjaxPassportCheck', '0');
		$EnableQCoreClass->replace('isAjaxPassport', 'none');
		$EnableQCoreClass->replace('hashCode', '');
		$EnableQCoreClass->replace('mainAttributeList', '');
		$SQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND adUserName != \'\' ';
		$Result = $DB->query($SQL);

		while ($AdUserRow = $DB->queryArray($Result)) {
			$AdUser[] = trim($AdUserRow['adUserName']);
		}

		if (!empty($AdUser)) {
			$adUserName = implode(';', $AdUser);
		}

		if ($adUserName != '') {
			$EnableQCoreClass->replace('adUserName', $adUserName . ';');
		}
		else {
			$EnableQCoreClass->replace('adUserName', '');
		}

		$EnableQCoreClass->replace('LDAPActionURL', 'ShowSelLDAP.php');
		break;

	case '5':
		$EnableQCoreClass->replace('isUseOriPassport', '');
		$EnableQCoreClass->replace('isUseOriPassportCheck', '5');
		$EnableQCoreClass->replace('isUseAjaxPassportCheck', '0');
		$EnableQCoreClass->replace('isAjaxPassport', 'none');
		$EnableQCoreClass->replace('hashCode', '');
		$EnableQCoreClass->replace('mainAttributeList', '');
		$SQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND adUserName != \'\' ';
		$Result = $DB->query($SQL);

		while ($AdUserRow = $DB->queryArray($Result)) {
			$AdUser[] = trim($AdUserRow['adUserName']);
		}

		if (!empty($AdUser)) {
			$adUserName = implode(';', $AdUser);
		}

		if ($adUserName != '') {
			$EnableQCoreClass->replace('adUserName', $adUserName . ';');
		}
		else {
			$EnableQCoreClass->replace('adUserName', '');
		}

		$EnableQCoreClass->replace('LDAPActionURL', 'ShowSelOpenLDAP.php');
		break;
	}

	$EnableQCoreClass->replace('passportType', $BaseRow['isUseOriPassport']);
	$EnableQCoreClass->replace('scriptPath', 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25));
	$EnableQCoreClass->parse('SurveyEditPage', 'SurveyEditPageFile');
	$EnableQCoreClass->output('SurveyEditPage', false);
}

if ($_GET['Action'] == 'EditUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.user.php';
}

if ($_GET['Action'] == 'ImportViewUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.import.view.php';
}

if ($_GET['Action'] == 'EditViewser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.user.view.php';
}

if ($_GET['Action'] == 'ImportInputUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.import.input.php';
}

if ($_GET['Action'] == 'EditInputUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.user.input.php';
}

if ($_GET['Action'] == 'ImportAuthUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.import.auth.php';
}

if ($_GET['Action'] == 'EditAuthUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.user.auth.php';
}

if ($_GET['Action'] == 'ImportAppealUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.import.appeal.php';
}

if ($_GET['Action'] == 'EditAppealUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.user.appeal.php';
}

if ($_GET['Action'] == 'ImportAppealAuthUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.import.appauth.php';
}

if ($_GET['Action'] == 'EditAppealAuthUser') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.user.appauth.php';
}

if ($_GET['Action'] == 'ImportUniCode') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.import.unicode.php';
}

if ($_GET['Action'] == 'DownQRCode') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5', $_GET['surveyID']);
	require 'Survey.check.unicode.php';
}

if ($_GET['Action'] == 'CheckUniCode') {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	_checkpassport('1|2|5|6', $_GET['surveyID']);
	require 'Survey.check.unicode.php';
}

$EnableQCoreClass->setTemplateFile('MainPageFile', 'SurveyList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');
$EnableQCoreClass->replace('nick_Name', $_SESSION['administratorsNickName'] == '' ? $_SESSION['administratorsName'] : $_SESSION['administratorsNickName']);
$ConfigRow['topicNum'] = 10;
$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('cate_list', _getcateslist());


switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status != 4 ';
	$EnableQCoreClass->replace('users_list', _getuserslist(0));
	break;

case '2':
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND status != 4 ';
	$EnableQCoreClass->replace('users_list', '<option value=' . $_SESSION['administratorsID'] . ' selected>' . $_SESSION['administratorsName'] . '</option>');
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$MemberSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND isAdmin IN (2,5) ';
	$MemberResult = $DB->query($MemberSQL);

	while ($MemberRow = $DB->queryArray($MemberResult)) {
		$users_list .= '<option value=' . $MemberRow['administratorsID'] . '>' . $MemberRow['administratorsName'] . '</option>';
	}

	$EnableQCoreClass->replace('users_list', $users_list);
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ')';
	break;
}
//查询的 问卷的信息
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	switch ($_POST['t_searchType']) {
	case '1':
	default:
		$name = trim($_POST['t_name']);
		$SQL .= ' AND surveyTitle LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', '');
		$page_others = '&t_searchType=1&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;

	case '2':
		$name = trim($_POST['t_name']);
		$SQL .= ' AND surveyName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', 'selected');
		$page_others = '&t_searchType=2&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;
	}

	if ($_POST['t_status'] != '') {
		$SQL .= ' AND status = \'' . $_POST['t_status'] . '\' ';
		$EnableQCoreClass->replace('option_' . $_POST['t_status'], 'selected');
		$page_others .= '&t_status=' . $_POST['t_status'];
	}

	if ($_POST['t_cate'] != '') {
		$CateSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID = \'' . $_POST['t_cate'] . '\' ORDER BY surveyID ASC ';
		$CateResult = $DB->query($CateSQL);
		$CateArray = array();

		while ($CateRow = $DB->queryArray($CateResult)) {
			$CateArray[] = $CateRow['surveyID'];
		}

		if (empty($CateArray)) {
			$SQL .= ' AND surveyID= 0 ';
		}
		else {
			$CateIDList = implode(',', array_unique($CateArray));
			$SQL .= ' AND surveyID IN (' . $CateIDList . ')';
		}

		unset($CateArray);
		$EnableQCoreClass->replace('cate_list', _getcateslist($_POST['t_cate']));
		$page_others .= '&t_cate=' . $_POST['t_cate'];
	}

	if ($_POST['t_administratorsID'] != '0') {
		$SQL .= ' AND administratorsID = \'' . $_POST['t_administratorsID'] . '\' ';
		$page_others .= '&t_administratorsID=' . $_POST['t_administratorsID'];

		switch ($_SESSION['adminRoleType']) {
		case '1':
			$EnableQCoreClass->replace('users_list', _getuserslist($_POST['t_administratorsID']));
			break;

		case '2':
			break;

		case '5':
			$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
			$MemberSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND isAdmin IN (2,5) ';
			$MemberResult = $DB->query($MemberSQL);

			while ($MemberRow = $DB->queryArray($MemberResult)) {
				if ($MemberRow['administratorsID'] == $_POST['t_administratorsID']) {
					$users_list .= '<option value=' . $MemberRow['administratorsID'] . ' selected>' . $MemberRow['administratorsName'] . '</option>';
				}
				else {
					$users_list .= '<option value=' . $MemberRow['administratorsID'] . '>' . $MemberRow['administratorsName'] . '</option>';
				}
			}

			$EnableQCoreClass->replace('users_list', $users_list);
			break;
		}
	}
}

if (isset($_GET['t_name']) && !$_POST['Action'] && ($_GET['t_searchType'] != '')) {
	switch ($_GET['t_searchType']) {
	case '1':
	default:
		$name = trim($_GET['t_name']);
		$SQL .= ' AND surveyTitle LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', '');
		$page_others = '&t_searchType=1&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;

	case '2':
		$name = trim($_GET['t_name']);
		$SQL .= ' AND surveyName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', 'selected');
		$page_others = '&t_searchType=2&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;
	}
}

if (isset($_GET['t_administratorsID']) && ($_GET['t_administratorsID'] != '0') && !$_POST['Action']) {
	$SQL .= ' AND administratorsID = \'' . $_GET['t_administratorsID'] . '\' ';
	$page_others .= '&t_administratorsID=' . $_GET['t_administratorsID'];

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$EnableQCoreClass->replace('users_list', _getuserslist($_GET['t_administratorsID']));
		break;

	case '2':
		break;

	case '5':
		$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
		$MemberSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND isAdmin IN (2,5) ';
		$MemberResult = $DB->query($MemberSQL);

		while ($MemberRow = $DB->queryArray($MemberResult)) {
			if ($MemberRow['administratorsID'] == $_GET['t_administratorsID']) {
				$users_list .= '<option value=' . $MemberRow['administratorsID'] . ' selected>' . $MemberRow['administratorsName'] . '</option>';
			}
			else {
				$users_list .= '<option value=' . $MemberRow['administratorsID'] . '>' . $MemberRow['administratorsName'] . '</option>';
			}
		}

		$EnableQCoreClass->replace('users_list', $users_list);
		break;
	}
}

if (isset($_GET['t_status']) && ($_GET['t_status'] != '') && !$_POST['Action']) {
	$SQL .= ' AND status = \'' . $_GET['t_status'] . '\' ';
	$EnableQCoreClass->replace('option_' . $_GET['t_status'], 'selected');
	$page_others .= '&t_status=' . $_GET['t_status'];
}

if (isset($_GET['t_cate']) && ($_GET['t_cate'] != '') && !$_POST['Action']) {
	$CateSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID = \'' . $_GET['t_cate'] . '\' ORDER BY surveyID ASC ';
	$CateResult = $DB->query($CateSQL);
	$CateArray = array();

	while ($CateRow = $DB->queryArray($CateResult)) {
		$CateArray[] = $CateRow['surveyID'];
	}

	if (empty($CateArray)) {
		$SQL .= ' AND surveyID= 0 ';
	}
	else {
		$CateIDList = implode(',', array_unique($CateArray));
		$SQL .= ' AND surveyID IN (' . $CateIDList . ')';
	}

	unset($CateArray);
	$EnableQCoreClass->replace('cate_list', _getcateslist($_GET['t_cate']));
	$page_others .= '&t_cate=' . $_GET['t_cate'];
}

if ($License['Limited'] == 1) {
	$RSQL = $SQL . ' LIMIT 0, ' . $License['LimitedNum'];
	$RResult = $DB->query($RSQL);
	$recordCount = $DB->_getNumRows($RResult);
	$EnableQCoreClass->replace('recNum', $recordCount);
	$thePageNum = @ceil($recordCount / $ConfigRow['topicNum']);
}
else {
	// SELECT * FROM eq_survey WHERE status != 4 AND surveyTitle LIKE BINARY '%%' AND status = '0'
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	$EnableQCoreClass->replace('recNum', $recordCount);
}

if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$BackURL = $thisProg . '?pageID=' . $pageID . $page_others;
$_SESSION['surveyListURL'] = $BackURL;

if ($License['Limited'] == 1) {
	if (isset($_GET['pageID'])) {
		$_GET['pageID'] = (int) $_GET['pageID'];
		if (($_GET['pageID'] == $thePageNum) || ($_GET['pageID'] == 0)) {
			$leftNum = $License['LimitedNum'] - $start;
			$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $leftNum . ' ';
		}
		else {
			$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
		}
	}
	else if ($License['LimitedNum'] <= $ConfigRow['topicNum']) {
		$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $License['LimitedNum'] . ' ';
	}
	else {
		$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
	}
}
else {
	$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
}
//$SQL = SELECT * FROM eq_survey WHERE status != 4 ORDER BY surveyID DESC LIMIT 0,10 ;
$Result = $DB->query($SQL);
$isHaveCreateSite = 0;
//分页展示查询的数据  //class为d_table中的信息
while ($Row = $DB->queryArray($Result)) {
	if ($Row['projectType'] == 1) {
		$EnableQCoreClass->replace('projectType', 'background:#fff url(../Images/iblue.png) repeat-y top left');
	}
	else {
		$EnableQCoreClass->replace('projectType', '');
	}

	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$EnableQCoreClass->replace('endTime', substr($Row['endTime'], 2));
	$AdminSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$UserRow = $DB->queryFirstRow($AdminSQL);

	if (!$UserRow) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $UserRow['administratorsName']);
	}

	$EnableQCoreClass->replace('status', $lang['status_' . $Row['status']]);
	$EnableQCoreClass->replace('isPublic', $lang['isPublic_' . $Row['isPublic']]);

	switch ($Row['status']) {
	case '0':
		$EnableQCoreClass->replace('resultNum', '-');
		$preURL = '../d.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'];
		$EnableQCoreClass->replace('surveyTitle', '<a href=\'' . $preURL . '\' target=_blank>' . qnohtmltag($Row['surveyTitle']) . '</a>');
		$EnableQCoreClass->replace('status_color', 'background:#efefef url(../Images/iyellow.png) repeat-y top left');
		$EnableQCoreClass->replace('topMargin', 140);
		break;

	case '1':
		$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
		$R_Row = $DB->queryFirstRow($R_SQL);
		$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);
		$EnableQCoreClass->replace('surveyTitle', '<a href=\'../q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'] . '\' target=_blank>' . qnohtmltag($Row['surveyTitle']) . '</a>');
		$EnableQCoreClass->replace('status_color', 'background:#efefef url(../Images/igreen.png) repeat-y top left');
		$EnableQCoreClass->replace('topMargin', 80);
		break;

	case '2':
		$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
		$R_Row = $DB->queryFirstRow($R_SQL);
		$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);
		$EnableQCoreClass->replace('surveyTitle', '<a href=\'../p.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'] . '\' target=_blank>' . qnohtmltag($Row['surveyTitle']) . '</a>');
		$EnableQCoreClass->replace('status_color', 'background:#efefef url(../Images/ired.png) repeat-y top left');
		$EnableQCoreClass->replace('topMargin', 110);
		break;
	}

	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE 1=1 LIMIT 0,1 ';
	$DSQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE status != 0 LIMIT 0,1 ';
	break;

case '2':
	$SQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' LIMIT 0,1 ';
	$DSQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE status != 0 AND administratorsID= \'' . $_SESSION['administratorsID'] . '\' LIMIT 0,1 ';
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ')  LIMIT 0,1 ';
	$DSQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE status != 0 AND administratorsID IN (' . $UserIDList . ')  LIMIT 0,1 ';
	break;
}

$isHaveCreateSite = $DB->queryFirstRow($SQL);

if ($isHaveCreateSite) {
	$EnableQCoreClass->replace('isHaveSite', '');
	$EnableQCoreClass->replace('noneSite', 'none');
	$EnableQCoreClass->replace('haveQtnNum', 1);
}
else {
	$EnableQCoreClass->replace('isHaveSite', 'none');
	$EnableQCoreClass->replace('noneSite', '');
	$EnableQCoreClass->replace('haveQtnNum', 0);
}

$haveRunSurvey = $DB->queryFirstRow($DSQL);

if ($haveRunSurvey) {
	$EnableQCoreClass->replace('haveRunSurveyNum', 1);
}
else {
	$EnableQCoreClass->replace('haveRunSurveyNum', 0);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$MainPage = $EnableQCoreClass->parse('MainPage', 'MainPageFile');
if (in_array($_SESSION['adminRoleType'], array('1', '2', '5')) && ($License['isModiLogo'] != 1)) {
	if (preg_match('/' . $lang['company_name'] . '/i', $MainPage) && preg_match('/' . $lang['product_name'] . '/i', $MainPage)) {
		echo $MainPage;
	}
	else {
		_showerror($lang['error_system'], $lang['modify_flag_error']);
	}
}
else {
	echo $MainPage;
}

exit();

?>

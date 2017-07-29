<?php
//dezend by http://www.yunlu99.com/
function clearsession()
{
	global $_POST;
	global $_SESSION;

	if (base64_decode($_POST['allFields']) != '') {
		$thisSurveyFieldsList = substr(base64_decode($_POST['allFields']), 0, -1);
		$thisSurveyFields = explode('|', $thisSurveyFieldsList);

		foreach ($thisSurveyFields as $_obf_MHbIRHCV1BEmioWl) {
			unset($_SESSION[$_obf_MHbIRHCV1BEmioWl]);
		}
	}

	unset($_SESSION['overTime_' . $_POST['surveyID']]);
	unset($_SESSION['responseID_' . $_POST['surveyID']]);
	unset($_SESSION['joinTime_' . $_POST['surveyID']]);
	unset($_SESSION['sBeginTime_' . $_POST['surveyID']]);
	unset($_SESSION['referer_' . $_POST['surveyID']]);
	unset($_SESSION['paperFlag_' . $_POST['surveyID']]);
	unset($_SESSION['paperName_' . $_POST['surveyID']]);
	unset($_SESSION['gpsTime_' . $_POST['surveyID']]);

	if (isset($_SESSION['haveOpenSurveyFlag_' . $_POST['taskID']])) {
		unset($_SESSION['haveOpenSurveyFlag_' . $_POST['taskID']]);
	}
}

function clearstring($string)
{
	$string = str_replace('\\\'', '', $string);
	$string = str_replace('\\"', '', $string);
	$string = str_replace('\\', '', $string);
	$string = str_replace('&', '', $string);
	return $string;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.entry.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.fore.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.cons.inc.php';
include_once ROOT_PATH . 'Functions/Functions.api.inc.php';
$language = 'cn';

if ($License['isOnline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

_checkpassport('4', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] != '1') {
	_showerror($lang['system_error'], $lang['no_exe_survey']);
}

$nowTime = date('Y-m-d', time());

if ($nowTime < $S_Row['beginTime']) {
	_showerror($lang['system_error'], $lang['no_start_survey']);
}

if ($S_Row['endTime'] < $nowTime) {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
	$DB->query($SQL);
	_showerror($lang['system_error'], $lang['end_survey_now']);
}

if ($S_Row['maxResponseNum'] != 0) {
	$SQL = ' SELECT COUNT(*) AS maxResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag IN (1,3) ';
	$CountRow = $DB->queryFirstRow($SQL);

	if ($S_Row['maxResponseNum'] <= $CountRow['maxResponseNum']) {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
		$DB->query($SQL);
		_showerror($lang['system_error'], $lang['max_num_survey']);
	}
}

$isMobile = 1;
$valueLogicQtnList = array();
if (($S_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php';

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php';
$_SESSION['referer_' . $S_Row['surveyID']] = isset($_SESSION['referer_' . $S_Row['surveyID']]) ? $_SESSION['referer_' . $S_Row['surveyID']] : _getdomainfromurl($_SERVER['HTTP_REFERER']);
$BaseSQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
$BaseRow = $DB->queryFirstRow($BaseSQL);
if (($_POST['Action'] == 'SurveyOverSubmit') || ($_POST['Action'] == 'SurveyCacheSubmit')) {
	if ($_POST['thisStep'] != 0) {
		if (($_SESSION['responseID_' . $S_Row['surveyID']] == '') && !isset($_SESSION['responseID_' . $S_Row['surveyID']])) {
			_showerror($lang['auth_error'], $S_Row['surveyTitle'] . '：' . $lang['error_lost_session']);
		}
	}

	$thisPageFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
	$thisPageFields = explode('|', $thisPageFieldsList);

	foreach ($thisPageFields as $theField) {
		$_SESSION[$theField] = $_POST[$theField];
	}

	require ROOT_PATH . 'Process/Time.inc.php';
	require ROOT_PATH . 'Process/Hidden.inc.php';
	$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
	$thisSurveyFields = explode('|', $thisSurveyFieldsList);
	$isHaveSQL = ' SELECT responseID,overFlag FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE responseID = \'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' LIMIT 0,1 ';
	$isHaveRow = $DB->queryFirstRow($isHaveSQL);
	if (($_SESSION['responseID_' . $S_Row['surveyID']] == '') || !$isHaveRow) {
		if (isset($_POST['taskID']) && ($_POST['taskID'] != '0')) {
			$hSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND taskID = \'' . $_POST['taskID'] . '\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				_showerror($lang['auth_error'], $S_Row['surveyTitle'] . '：您确定对该任务拥有访员权限？请方便联系您的系统管理员');
			}

			$hSQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE taskID = \'' . $_POST['taskID'] . '\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				_showerror($lang['auth_error'], $S_Row['surveyTitle'] . '：系统检测到该任务已被人完成！请方便联系您的系统管理员');
			}
		}

		require ROOT_PATH . 'Process/AndroidFlag.inc.php';
		require ROOT_PATH . 'Process/File.inc.php';
		$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
		$SQL .= ' ipAddress =\'' . $ipAddress . '\',';
		$SQL .= ' administratorsName = \'' . $administrators_Name . '\',';
		$SQL .= ' area =\'' . $Area . '\',';
		$SQL .= ' joinTime = \'' . $time . '\',';
		$SQL .= ' submitTime = \'' . time() . '\',';
		$SQL .= ' overTime = \'' . $over_time . '\',';
		$SQL .= ' taskID = \'' . $_POST['taskID'] . '\',';
		$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';
		$SQL .= ' dataSource = \'5\',';

		if ($_POST['Action'] == 'SurveyOverSubmit') {
			$SQL .= ' overFlag =1,';
		}

		if (base64_decode($_POST['thisFields']) != '') {
			foreach ($thisSurveyFields as $surveyFields) {
				if (is_array($_POST[$surveyFields])) {
					asort($_POST[$surveyFields]);
					$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
				}
				else {
					$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
				}
			}
		}

		$InsertSQL = substr($SQL . $Hidden_SQL, 0, -1);
		$InsertSQL .= $File_SQL;
		$DB->query($InsertSQL);
		$new_responseID = $DB->_GetInsertID();
		require ROOT_PATH . 'Process/AndroidGps.inc.php';
		$GSSQL = ' INSERT INTO ' . ANDROID_INFO_TABLE . ' SET  surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $new_responseID . '\',currentCity=\'' . $theSimInfo[0] . '\',brand=\'' . $theSimInfo[1] . '\',model=\'' . $theSimInfo[2] . '\',deviceId=\'' . $theSimInfo[3] . '\',simOperatorName=\'' . $theSimInfo[4] . '\',simSerialNumber=\'' . $theSimInfo[5] . '\',line1Number=\'' . $theSimInfo[6] . '\',gpsTime=\'' . str_replace('time:', '', $theGpsInfo[0]) . '\',accuracy=\'' . str_replace('accuracy:', '', $theGpsInfo[1]) . '\',longitude=\'' . str_replace('longitude:', '', $theGpsInfo[2]) . '\',latitude=\'' . str_replace('latitude:', '', $theGpsInfo[3]) . '\',speed=\'' . str_replace('speed:', '', $theGpsInfo[4]) . '\',bearing=\'' . str_replace('bearing:', '', $theGpsInfo[5]) . '\',altitude=\'' . str_replace('altitude:', '', $theGpsInfo[6]) . '\' ';
		$DB->query($GSSQL);
	}
	else {
		if ($isHaveRow['overFlag'] != 0) {
			_showerror($lang['status_error'], $S_Row['surveyTitle'] . '：系统检测到该任务已被人完成！请方便联系您的系统管理员');
		}

		require ROOT_PATH . 'Process/File.inc.php';
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
		$SQL .= ' submitTime = \'' . time() . '\',';
		$SQL .= ' overTime = \'' . $over_time . '\',';
		$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';

		if ($_POST['Action'] == 'SurveyOverSubmit') {
			$SQL .= ' overFlag =1,';
		}

		if (base64_decode($_POST['thisFields']) != '') {
			foreach ($thisSurveyFields as $surveyFields) {
				if (is_array($_POST[$surveyFields])) {
					asort($_POST[$surveyFields]);
					$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
				}
				else {
					$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
				}
			}
		}

		$InsertSQL = substr($SQL . $Hidden_SQL, 0, -1);
		$InsertSQL .= $File_SQL;
		$InsertSQL .= ' WHERE responseID =\'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' ';
		$DB->query($InsertSQL);
		$new_responseID = $_SESSION['responseID_' . $S_Row['surveyID']];
		require ROOT_PATH . 'Process/AndroidGps.inc.php';
		$theGpsTime = str_replace('time:', '', $theGpsInfo[0]);
		if (($theGpsTime != '') && ($theGpsTime != $_SESSION['gpsTime_' . $S_Row['surveyID']])) {
			$GSSQL = ' UPDATE ' . ANDROID_INFO_TABLE . ' SET gpsTime=\'' . str_replace('time:', '', $theGpsInfo[0]) . '\',accuracy=\'' . str_replace('accuracy:', '', $theGpsInfo[1]) . '\',longitude=\'' . str_replace('longitude:', '', $theGpsInfo[2]) . '\',latitude=\'' . str_replace('latitude:', '', $theGpsInfo[3]) . '\',speed=\'' . str_replace('speed:', '', $theGpsInfo[4]) . '\',bearing=\'' . str_replace('bearing:', '', $theGpsInfo[5]) . '\',altitude=\'' . str_replace('altitude:', '', $theGpsInfo[6]) . '\' WHERE responseID =\'' . $new_responseID . '\' AND surveyID = \'' . $S_Row['surveyID'] . '\' ';
			$DB->query($GSSQL);
		}
	}

	if (($_POST['Action'] == 'SurveyOverSubmit') || ($_POST['surveyQuotaFlag'] == 2)) {
		$this_fields_list = '';

		foreach ($QtnListArray as $questionID => $theQtnArray) {
			if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '12')) {
				$surveyID = $_POST['surveyID'];
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
			}
		}

		$this_all_fields_list = substr($this_fields_list, 0, -1);
		$survey_fields_name = explode('|', $this_all_fields_list);
		$thisSurveyFieldsList = substr(base64_decode($_POST['allFields']), 0, -1);
		$thisSurveyFields = explode('|', $thisSurveyFieldsList);
		$this_diff_fields_list = arraydiff($survey_fields_name, $thisSurveyFields);
		unset($survey_fields_name);
		if (!empty($this_diff_fields_list) && (count($this_diff_fields_list) != 0)) {
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';

			foreach ($this_diff_fields_list as $surveyFields) {
				$SQL .= ' ' . $surveyFields . ' = \'\',';
			}

			$updateSQL = substr($SQL, 0, -1);
			$updateSQL .= ' WHERE responseID =\'' . $new_responseID . '\' ';
			$DB->query($updateSQL);
		}

		unset($this_diff_fields_list);
	}

	if (($_POST['Action'] == 'SurveyOverSubmit') && ($_POST['surveyQuotaFlag'] != 2)) {
		if (!isset($_SESSION['joinTime_' . $S_Row['surveyID']])) {
			$theTime = time();
		}
		else {
			$theTime = $_SESSION['joinTime_' . $S_Row['surveyID']];
		}

		dealcountinfo($_POST['surveyID'], $theTime);
	}

	$thePanelBeginTime = ($_SESSION['sBeginTime_' . $S_Row['surveyID']] == '' ? $_SESSION['joinTime_' . $S_Row['surveyID']] : $_SESSION['sBeginTime_' . $S_Row['surveyID']]);
	clearsession();
	$rtnURL = 'ShowInputerTask.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']);

	if ($_POST['surveyQuotaFlag'] == 2) {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET overFlag = 2 WHERE responseID = \'' . $new_responseID . '\' ';
		$DB->query($SQL);
		_writeapidata($S_Row, $new_responseID, $_POST['screeningFlag'], $thePanelBeginTime);

		if ($QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'] == '') {
			if (($_POST['taskID'] == '') || ($_POST['taskID'] == '0')) {
				_showerror($lang['result_to_quota'], $S_Row['surveyTitle'] . ': ' . $lang['num_to_quota'], 3, 'InputSurveyAnswer.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']));
			}
			else {
				_showerror($lang['result_to_quota'], $S_Row['surveyTitle'] . ': ' . $lang['num_to_quota'], 3, $rtnURL);
			}
		}
		else {
			if (($_POST['taskID'] == '') || ($_POST['taskID'] == '0')) {
				_showerror($lang['result_to_quota'], $S_Row['surveyTitle'] . ': ' . $QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'], 3, 'InputSurveyAnswer.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']));
			}
			else {
				_showerror($lang['result_to_quota'], $S_Row['surveyTitle'] . ': ' . $QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'], 3, $rtnURL);
			}
		}
	}

	_writeapidata($S_Row, $new_responseID, 1, $thePanelBeginTime);

	if ($_POST['nextStep'] == '1') {
		if (($_POST['taskID'] == '') || ($_POST['taskID'] == '0')) {
			_showsucceed($lang['survey_paper_submit'], 'InputSurveyAnswer.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']));
		}
		else {
			_showsucceed($lang['survey_paper_submit'], $rtnURL);
		}
	}
	else if ($_POST['Action'] == 'SurveyOverSubmit') {
		if (($_POST['taskID'] == '') || ($_POST['taskID'] == '0')) {
			_showsucceed($lang['survey_paper_submit'], 'InputIndex.php');
		}
		else {
			_showsucceed($lang['survey_paper_submit'], $rtnURL);
		}
	}
	else {
		if (($_POST['taskID'] == '') || ($_POST['taskID'] == '0')) {
			_showsucceed($lang['survey_cache_data'], 'InputIndex.php');
		}
		else {
			_showsucceed($lang['survey_cache_data'], $rtnURL);
		}
	}
}

$thePageSurveyID = $S_Row['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$thisPageStep = 0;
$surveyID = $S_Row['surveyID'];
$theActionPage = 1;
$theHaveRatingSlider = false;
$theHaveDatePicker = false;
if (($_POST['Action'] == 'SurveyPreSubmit') || ($_POST['Action'] == 'SurveyNextSubmit')) {
	if ($_POST['Action'] == 'SurveyPreSubmit') {
		$thisLastPageStep = $_POST['thisStep'] - 1;

		if ($thisLastPageStep <= 0) {
			$thisLastPageStep = 0;
		}

		$thisPageStep = isskippage($thisLastPageStep, 2);
	}

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		if ($_POST['thisStep'] != 0) {
			if (($_SESSION['responseID_' . $S_Row['surveyID']] == '') && !isset($_SESSION['responseID_' . $S_Row['surveyID']])) {
				_showerror($lang['auth_error'], $S_Row['surveyTitle'] . '：' . $lang['error_lost_session']);
			}
		}

		$thisPageFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
		$thisPageFields = explode('|', $thisPageFieldsList);

		foreach ($thisPageFields as $theFields) {
			$_SESSION[$theFields] = $_POST[$theFields];
		}

		foreach ($_SESSION as $theSessionKey => $theSessionValue) {
			if (!is_array($theSessionValue)) {
				$_SESSION[$theSessionKey] = stripslashes($theSessionValue);
			}
		}

		require ROOT_PATH . 'Process/Time.inc.php';
		require ROOT_PATH . 'Process/Hidden.inc.php';
		$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
		$thisSurveyFields = explode('|', $thisSurveyFieldsList);
		$isHaveSQL = ' SELECT responseID,overFlag FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE responseID = \'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' LIMIT 0,1 ';
		$isHaveRow = $DB->queryFirstRow($isHaveSQL);
		if (($_SESSION['responseID_' . $S_Row['surveyID']] == '') || !$isHaveRow) {
			if (isset($_POST['taskID']) && ($_POST['taskID'] != '0')) {
				$hSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND taskID = \'' . $_POST['taskID'] . '\' LIMIT 1 ';
				$hRow = $DB->queryFirstRow($hSQL);

				if (!$hRow) {
					_showerror($lang['auth_error'], $S_Row['surveyTitle'] . '：您确定对该任务拥有访员权限？请方便联系您的系统管理员');
				}

				$hSQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE taskID = \'' . $_POST['taskID'] . '\' LIMIT 1 ';
				$hRow = $DB->queryFirstRow($hSQL);

				if ($hRow) {
					_showerror($lang['auth_error'], $S_Row['surveyTitle'] . '：系统检测到该任务已被人完成！请方便联系您的系统管理员');
				}
			}

			require ROOT_PATH . 'Process/AndroidFlag.inc.php';
			require ROOT_PATH . 'Process/File.inc.php';
			$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
			$SQL .= ' administratorsName = \'' . $administrators_Name . '\',';
			$SQL .= ' ipAddress =\'' . $ipAddress . '\',';
			$SQL .= ' area =\'' . $Area . '\',';
			$SQL .= ' joinTime = \'' . $time . '\',';
			$SQL .= ' submitTime = \'' . time() . '\',';
			$SQL .= ' overTime = \'' . $over_time . '\',';
			$SQL .= ' taskID = \'' . $_POST['taskID'] . '\',';
			$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';
			$SQL .= ' dataSource = \'5\',';

			if (base64_decode($_POST['thisFields']) != '') {
				foreach ($thisSurveyFields as $surveyFields) {
					if (is_array($_POST[$surveyFields])) {
						asort($_POST[$surveyFields]);
						$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
					}
					else {
						$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
					}
				}
			}

			$InsertSQL = substr($SQL . $Hidden_SQL, 0, -1);
			$InsertSQL .= $File_SQL;
			$DB->query($InsertSQL);
			$new_responseID = $DB->_GetInsertID();
			$_SESSION['responseID_' . $S_Row['surveyID']] = $new_responseID;
			$_SESSION['joinTime_' . $S_Row['surveyID']] = $time;
			require ROOT_PATH . 'Process/AndroidGps.inc.php';
			$GSSQL = ' INSERT INTO ' . ANDROID_INFO_TABLE . ' SET  surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $new_responseID . '\',currentCity=\'' . $theSimInfo[0] . '\',brand=\'' . $theSimInfo[1] . '\',model=\'' . $theSimInfo[2] . '\',deviceId=\'' . $theSimInfo[3] . '\',simOperatorName=\'' . $theSimInfo[4] . '\',simSerialNumber=\'' . $theSimInfo[5] . '\',line1Number=\'' . $theSimInfo[6] . '\',gpsTime=\'' . str_replace('time:', '', $theGpsInfo[0]) . '\',accuracy=\'' . str_replace('accuracy:', '', $theGpsInfo[1]) . '\',longitude=\'' . str_replace('longitude:', '', $theGpsInfo[2]) . '\',latitude=\'' . str_replace('latitude:', '', $theGpsInfo[3]) . '\',speed=\'' . str_replace('speed:', '', $theGpsInfo[4]) . '\',bearing=\'' . str_replace('bearing:', '', $theGpsInfo[5]) . '\',altitude=\'' . str_replace('altitude:', '', $theGpsInfo[6]) . '\' ';
			$DB->query($GSSQL);
			$_SESSION['gpsTime_' . $S_Row['surveyID']] = str_replace('time:', '', $theGpsInfo[0]);
		}
		else {
			if ($isHaveRow['overFlag'] != 0) {
				_showerror($lang['status_error'], $S_Row['surveyTitle'] . '：系统检测到该任务已被人完成！请方便联系您的系统管理员');
			}

			require ROOT_PATH . 'Process/File.inc.php';
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
			$SQL .= ' submitTime = \'' . time() . '\',';
			$SQL .= ' overTime = \'' . $over_time . '\',';
			$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';

			if (base64_decode($_POST['thisFields']) != '') {
				foreach ($thisSurveyFields as $surveyFields) {
					if (is_array($_POST[$surveyFields])) {
						asort($_POST[$surveyFields]);
						$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
					}
					else {
						$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
					}
				}
			}

			$InsertSQL = substr($SQL . $Hidden_SQL, 0, -1);
			$InsertSQL .= $File_SQL;
			$InsertSQL .= ' WHERE responseID =\'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' ';
			$DB->query($InsertSQL);
			require ROOT_PATH . 'Process/AndroidGps.inc.php';
			$theGpsTime = str_replace('time:', '', $theGpsInfo[0]);
			if (($theGpsTime != '') && ($theGpsTime != $_SESSION['gpsTime_' . $S_Row['surveyID']])) {
				$GSSQL = ' UPDATE ' . ANDROID_INFO_TABLE . ' SET gpsTime=\'' . str_replace('time:', '', $theGpsInfo[0]) . '\',accuracy=\'' . str_replace('accuracy:', '', $theGpsInfo[1]) . '\',longitude=\'' . str_replace('longitude:', '', $theGpsInfo[2]) . '\',latitude=\'' . str_replace('latitude:', '', $theGpsInfo[3]) . '\',speed=\'' . str_replace('speed:', '', $theGpsInfo[4]) . '\',bearing=\'' . str_replace('bearing:', '', $theGpsInfo[5]) . '\',altitude=\'' . str_replace('altitude:', '', $theGpsInfo[6]) . '\' WHERE responseID =\'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' AND surveyID = \'' . $S_Row['surveyID'] . '\' ';
				$DB->query($GSSQL);
				$_SESSION['gpsTime_' . $S_Row['surveyID']] = $theGpsTime;
			}
		}

		$thisNextPageStep = $_POST['thisStep'] + 1;

		if ((count($pageBreak) - 1) <= $thisNextPageStep) {
			$thisNextPageStep = count($pageBreak) - 1;
		}

		$thisPageStep = isskippage($thisNextPageStep, 1);
	}
}

$theHaveFileUpload = false;
$theHaveFileCascade = false;
$haveBreakFlag = 0;
if (((isset($_SESSION['responseID_' . $S_Row['surveyID']]) && ($_SESSION['responseID_' . $S_Row['surveyID']] != '')) || (($_GET['taskID'] != '') && ($_GET['taskID'] != '0') && ($_SESSION['adminRoleType'] == '4'))) && !isset($_POST['Action'])) {
	$breakCateFlag = 0;
	if (($_GET['taskID'] != '') && ($_GET['taskID'] != '0') && ($_SESSION['adminRoleType'] == '4')) {
		$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE taskID =\'' . $_GET['taskID'] . '\' AND area = \'' . $_SESSION['administratorsName'] . '\' LIMIT 0,1 ';
		$breakCateFlag = 1;
	}

	if (($_SESSION['responseID_' . $S_Row['surveyID']] != '') && ($breakCateFlag == 0)) {
		$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE responseID =\'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' LIMIT 0,1 ';
	}

	$theRow = $DB->queryFirstRow($theSQL);

	if ($theRow) {
		$theColSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' ';
		$theColResult = $DB->query($theColSQL);
		$theColNameArray = array('responseID', 'recordFile', 'fingerFile', 'administratorsName', 'administratorsGroupID', 'ajaxRtnValue_1', 'ajaxRtnValue_2', 'ajaxRtnValue_3', 'ajaxRtnValue_4', 'ajaxRtnValue_5', 'ajaxRtnValue_6', 'ipAddress', 'area', 'joinTime', 'submitTime', 'overTime', 'cateID', 'taskID', 'overFlag', 'overFlag0', 'authStat', 'version', 'adminID', 'uniDataCode', 'replyPage', 'uniCode');

		while ($theColRow = $DB->queryArray($theColResult)) {
			if (!in_array($theColRow['Field'], $theColNameArray)) {
				$_SESSION[$theColRow['Field']] = $theRow[$theColRow['Field']];
			}

			if ($theColRow['Field'] == 'joinTime') {
				$_SESSION['joinTime_' . $S_Row['surveyID']] = $theRow['joinTime'];
				$_SESSION['sBeginTime_' . $S_Row['surveyID']] = $theRow['joinTime'];
				$_SESSION['overTime_' . $S_Row['surveyID']] = $theRow['joinTime'];
			}

			if ($theColRow['Field'] == 'responseID') {
				$_SESSION['responseID_' . $S_Row['surveyID']] = $theRow['responseID'];
			}
		}

		$thisPageStep = $theRow['replyPage'];
		$thisBreakAllHidden = '';
		$thisBreakAllFields = '';
		$this_fields_list = '';
		$this_hidden_list = '';

		foreach ($pageQtnList as $tmp => $thePageQtnList) {
			if ($thisPageStep <= $tmp) {
				break;
			}

			foreach ($thePageQtnList as $questionID) {
				if ($QtnListArray[$questionID]['questionType'] != '9') {
					$surveyID = $S_Row['surveyID'];
					$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
					$theQtnArray = $QtnListArray[$questionID];

					if ($QtnListArray[$questionID]['questionType'] != '12') {
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
					}
					else {
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';
					}
				}
			}
		}

		$thisBreakAllFields .= $this_fields_list;
		$this_fields_list = '';
		$thisBreakAllHidden .= $this_hidden_list;
		$this_hidden_list = '';
		$haveBreakFlag = 1;
	}
	else {
		$theColSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' ';
		$theColResult = $DB->query($theColSQL);
		$theColNameArray = array('responseID', 'recordFile', 'fingerFile', 'administratorsName', 'administratorsGroupID', 'ajaxRtnValue_1', 'ajaxRtnValue_2', 'ajaxRtnValue_3', 'ajaxRtnValue_4', 'ajaxRtnValue_5', 'ajaxRtnValue_6', 'ipAddress', 'area', 'joinTime', 'submitTime', 'overTime', 'cateID', 'taskID', 'overFlag', 'overFlag0', 'authStat', 'version', 'adminID', 'uniDataCode', 'replyPage', 'uniCode');

		while ($theColRow = $DB->queryArray($theColResult)) {
			if (!in_array($theColRow['Field'], $theColNameArray)) {
				unset($_SESSION[$theColRow['Field']]);
			}
		}

		unset($_SESSION['responseID_' . $S_Row['surveyID']]);
		unset($_SESSION['joinTime_' . $S_Row['surveyID']]);
		unset($_SESSION['sBeginTime_' . $S_Row['surveyID']]);
		unset($_SESSION['overTime_' . $S_Row['surveyID']]);
		$thisPageStep = 0;
	}
}

if ($thisPageStep != 0) {
	if (($_GET['taskID'] != '') && ($_GET['taskID'] != '0')) {
		$EnableQCoreClass->replace('taskID', $_GET['taskID']);
	}
	else {
		$EnableQCoreClass->replace('taskID', '0');
	}

	$ShowSurveyPageFile = 'ShowSurvey' . $thisPageStep . 'PageFile';
	$ShowSurveyPage = 'ShowSurvey' . $thisPageStep . 'Page';
	$ShowSurveyFile = 'ShowSurvey' . $thisPageStep . 'File';
	$question = 'question' . $thisPageStep;
	$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, 'uAndroidInputSurvey.html');
	$EnableQCoreClass->set_CycBlock($ShowSurveyPageFile, 'QUESTION', $question);
	$EnableQCoreClass->replace($question, '');
	$EnableQCoreClass->replace('paperFlagInfo', '');
	$EnableQCoreClass->replace('paperFlagInfoCheck', '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('survey_URLTitle', urlencode($S_Row['surveyTitle']));
	$EnableQCoreClass->replace('surveySubTitle', '');
	$EnableQCoreClass->replace('surveyInfo', '');
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('surveyLang', $language);
	$EnableQCoreClass->replace('isGpsEnable', $S_Row['isGpsEnable']);
	$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
	$processBarValue = $processValue * 2;
	$processBar = '<div id=\'processBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:0;margin-bottom:5px;padding:0;height:19px"><span style="width:' . $processBarValue . 'px;color:#FFF;float:left;background-color:#FF8D40;height:19px;font-size:16px;text-align:center;overflow:hidden;font-weight:bold;line-height:19px">' . $processValue . '%</span><span style="width:*;float:right;"></span></div>';
	$EnableQCoreClass->replace('processBar', $processBar);
	$EnableQCoreClass->replace('processValue', $processValue . '%');
	$check_survey_form_list = '';
	$check_survey_conditions_list = '';
	$this_fields_list = '';
	$this_file_list = '';
	$this_size_list = '';
	$this_hidden_list = '';
	$this_check_list = '';
	$survey_quota_list = '';
	$survey_empty_list = '';

	foreach ($pageQtnList[$thisPageStep] as $questionID) {
		$EnableQCoreClass->replace('questionID', $questionID);

		if (!empty($CondListArray[$questionID])) {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'none');
		}
		else {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'block');
		}

		$isHiddenFields = true;
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';

		if ($QtnListArray[$questionID]['questionType'] == '12') {
			$isHiddenFields = false;
		}

		if ($isHiddenFields == true) {
			$EnableQCoreClass->parse($question, 'QUESTION', true);
		}
	}

	$EnableQCoreClass->replace('thisFields', base64_encode($this_fields_list));
	$EnableQCoreClass->replace('thisHidden', $this_hidden_list);
	$EnableQCoreClass->replace('thisFiles', $this_file_list);
	$EnableQCoreClass->replace('thisSizes', $this_size_list);
	$EnableQCoreClass->replace('thisCheck', $this_check_list);

	if ($haveBreakFlag == 1) {
		$EnableQCoreClass->replace('allHidden', $thisBreakAllHidden . $this_hidden_list);
		$EnableQCoreClass->replace('allFields', base64_encode($thisBreakAllFields . $this_fields_list . $this_file_list));
		$hiddenFields = '';
		$lastPageFieldsList = substr($thisBreakAllFields, 0, -1);
		$lastPageFields = explode('|', $lastPageFieldsList);

		foreach ($lastPageFields as $lastFields) {
			if (in_array($lastFields, $valueLogicQtnList)) {
				if (is_array($_SESSION[$lastFields]) && !empty($_SESSION[$lastFields])) {
					$lastFieldsValue = implode(',', $_SESSION[$lastFields]);
				}
				else {
					$lastFieldsValue = $_SESSION[$lastFields];
				}

				$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
			}
		}

		$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
	}

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		$EnableQCoreClass->replace('allHidden', $_POST['thisHidden'] . $this_hidden_list);
		$EnableQCoreClass->replace('allFields', base64_encode(base64_decode($_POST['allFields']) . $this_fields_list . $this_file_list));
		$hiddenFields = '';
		$lastPageFieldsList = substr(base64_decode($_POST['allFields']), 0, -1);
		$lastPageFields = explode('|', $lastPageFieldsList);

		foreach ($lastPageFields as $lastFields) {
			if (in_array($lastFields, $valueLogicQtnList)) {
				if (is_array($_SESSION[$lastFields]) && !empty($_SESSION[$lastFields])) {
					$lastFieldsValue = implode(',', $_SESSION[$lastFields]);
				}
				else {
					$lastFieldsValue = $_SESSION[$lastFields];
				}

				$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
			}
		}

		$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
	}

	if ($_POST['Action'] == 'SurveyPreSubmit') {
		$EnableQCoreClass->replace('allHidden', str_replace($_POST['thisHidden'], '', $_POST['allHidden']));
		$thisAllFields = str_replace(base64_decode($_POST['thisFields']), '', base64_decode($_POST['allFields']));
		$EnableQCoreClass->replace('allFields', base64_encode($thisAllFields));
		$hiddenFields = '';
		$lastPageFieldsList = substr(str_replace($this_fields_list, '', $thisAllFields), 0, -1);
		$lastPageFields = explode('|', $lastPageFieldsList);

		foreach ($lastPageFields as $lastFields) {
			if (in_array($lastFields, $valueLogicQtnList)) {
				if (is_array($_SESSION[$lastFields]) && !empty($_SESSION[$lastFields])) {
					$lastFieldsValue = implode(',', $_SESSION[$lastFields]);
				}
				else {
					$lastFieldsValue = $_SESSION[$lastFields];
				}

				$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
			}
		}

		$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
	}

	$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
	$EnableQCoreClass->replace('passPortType', $BaseRow['isUseOriPassport']);
	$EnableQCoreClass->replace('survey_quota_list', $survey_quota_list);
	$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
	$EnableQCoreClass->replace('thisStep', $thisPageStep);

	if ($thisPageStep != count($pageBreak) - 1) {
		$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['cache_survey_over'] . '" name="SurveyCacheSubmit" id="SurveyCacheSubmit" onclick="javascript:if( window.confirm(\'' . $lang['cache_survey_over_info'] . '\')){document.Survey_Form.Action.value = \'SurveyCacheSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();}">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
	}
	else {
		$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['submit_survey_next'] . '" name="SurveyNextSubmit"  id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form.nextStep.value = \'1\';Survey_Form_Submit();">&nbsp;';
		$actionButton .= '&nbsp;<input class=btn type=button value="' . $lang['submit_survey_over'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$SurveyPage = $EnableQCoreClass->parse($ShowSurveyPage, $ShowSurveyPageFile);
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
	$SurveyPage = str_replace($All_Path, '', $SurveyPage);
	$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
	$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
	$SurveyPage = str_replace('Android/', '../Android/', $SurveyPage);
	$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
	$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);
	$SurveyPage = str_replace('按住Ctrl键点击鼠标进行多重选择', '多重选择', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#ffffff"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#f5f5f5"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#ffffff"', '<input type="checkbox"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#f5f5f5"', '<input type="checkbox"', $SurveyPage);
	echo $SurveyPage;

	if (!isset($_SESSION['overTime_' . $S_Row['surveyID']])) {
		$_SESSION['overTime_' . $S_Row['surveyID']] = time();
	}

	exit();
}

if ($thisPageStep == 0) {
	$EnableQCoreClass->setTemplateFile('ShowSurveyFile', 'uAndroidInputSurvey.html');
	$EnableQCoreClass->set_CycBlock('ShowSurveyFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('survey_URLTitle', urlencode($S_Row['surveyTitle']));
	$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('surveyLang', $language);
	$EnableQCoreClass->replace('isGpsEnable', $S_Row['isGpsEnable']);
	if (($_GET['taskID'] != '') && ($_GET['taskID'] != '0')) {
		$EnableQCoreClass->replace('taskID', $_GET['taskID']);
		$hSQL = ' SELECT userGroupName,userGroupDesc FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['taskID'] . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);
		$_SESSION['paperFlag_' . $S_Row['surveyID']] = $hRow['userGroupDesc'];
		$_SESSION['paperName_' . $S_Row['surveyID']] = $hRow['userGroupName'];
		$paperFlagInfo = '<tr><td height=5></td></tr><tr><td class="surveybegin">&nbsp;<span class=red>*</span>任务名称：<input name="name" id="name" type="text" size=30 value="' . $_SESSION['paperName_' . $S_Row['surveyID']] . '" readonly></td></tr>' . "\n" . '<tr><td height=5 style="border-bottom:1px dashed #cccccc;"></td></tr>' . "\n" . '<tr><td class="surveybegin">&nbsp;&nbsp;&nbsp;任务描述：<input name="biaoshi" id="biaoshi" type="text" value="' . $_SESSION['paperFlag_' . $S_Row['surveyID']] . '" size=40 readonly>&nbsp;&nbsp;</td></tr>' . "\n" . '';
		$EnableQCoreClass->replace('paperFlagInfo', $paperFlagInfo);
		$paperFlagInfoCheck = 'if (!CheckNotNull(document.Survey_Form.biaoshi, \'' . $lang['and_flag_check'] . '\')){return false;}' . "\n" . '';
		$EnableQCoreClass->replace('paperFlagInfoCheck', $paperFlagInfoCheck);
	}
	else {
		$EnableQCoreClass->replace('taskID', '0');

		if ($S_Row['isPanelFlag'] == 1) {
			$paperFlagInfo = '<tr><td height=5></td></tr><tr><td class="surveybegin">&nbsp;<span class=red>*</span>' . $lang['and_flag'] . '<input name="biaoshi" id="biaoshi" maxlength=15 onkeypress="checkIsAlphabetValid();" style="ime-mode:disabled" onPaste="return false;" type="text" value="' . $_SESSION['paperFlag_' . $S_Row['surveyID']] . '" size=30>&nbsp;&nbsp;</td></tr>' . "\n" . '<tr><td class="surveybegin">&nbsp;&nbsp;&nbsp;' . $lang['and_panel_flag'] . '<input name="name" id="name" type="text" size=30 value="' . $_SESSION['paperName_' . $S_Row['surveyID']] . '"></td></tr>' . "\n" . '<tr><td height=5 style="border-bottom:1px dashed #cccccc;"></td></tr>' . "\n" . '';
			$EnableQCoreClass->replace('paperFlagInfo', $paperFlagInfo);
			$paperFlagInfoCheck = 'if (!CheckNotNull(document.Survey_Form.biaoshi, \'' . $lang['and_flag_check'] . '\')){return false;}' . "\n" . '';
			$EnableQCoreClass->replace('paperFlagInfoCheck', $paperFlagInfoCheck);
		}
		else {
			$EnableQCoreClass->replace('paperFlagInfo', '');
			$EnableQCoreClass->replace('paperFlagInfoCheck', '');
		}
	}

	if (($S_Row['isOneData'] == 1) && ($S_Row['projectType'] != 1)) {
		$isHaveSQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE area = \'' . $_SESSION['administratorsName'] . '\' LIMIT 0,1 ';
		$isHaveRow = $DB->queryFirstRow($isHaveSQL);

		if ($isHaveRow) {
			_showerror('权限错误', '权限错误：您已被系统管理员设置仅能录入单份回复数据，您的操作无法继续！');
		}
	}

	if (1 < count($pageBreak)) {
		$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
		$processBarValue = $processValue * 2;
		$processBar = '<div id=\'processBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:0;margin-bottom:5px;padding:0;height:19px"><span style="width:' . $processBarValue . 'px;color:#FFF;float:left;background-color:#FF8D40;height:19px;font-size:16px;text-align:center;overflow:hidden;font-weight:bold;line-height:19px">' . $processValue . '%</span><span style="width:*;float:right;"></span></div>';
		$EnableQCoreClass->replace('processBar', $processBar);
		$EnableQCoreClass->replace('processValue', $processValue . '%');
	}
	else {
		$EnableQCoreClass->replace('processBar', '');
		$EnableQCoreClass->replace('processValue', '');
	}

	$check_survey_form_list = '';
	$check_survey_conditions_list = '';
	$this_fields_list = '';
	$this_file_list = '';
	$this_size_list = '';
	$this_hidden_list = '';
	$this_check_list = '';
	$survey_quota_list = '';
	$survey_empty_list = '';

	foreach ($pageQtnList[0] as $questionID) {
		$EnableQCoreClass->replace('questionID', $questionID);

		if (!empty($CondListArray[$questionID])) {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'none');
		}
		else {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'block');
		}

		$isHiddenFields = true;
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';

		if ($QtnListArray[$questionID]['questionType'] == '12') {
			$isHiddenFields = false;
		}

		if ($isHiddenFields == true) {
			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}

	$EnableQCoreClass->replace('thisFields', base64_encode($this_fields_list));
	$EnableQCoreClass->replace('thisFiles', $this_file_list);
	$EnableQCoreClass->replace('thisSizes', $this_size_list);
	$EnableQCoreClass->replace('thisHidden', $this_hidden_list);
	$EnableQCoreClass->replace('allHidden', $this_hidden_list);
	$EnableQCoreClass->replace('allFields', base64_encode($this_fields_list . $this_file_list));
	$EnableQCoreClass->replace('hiddenFields', '');
	$EnableQCoreClass->replace('thisCheck', $this_check_list);
	$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
	$EnableQCoreClass->replace('passPortType', $BaseRow['isUseOriPassport']);
	$EnableQCoreClass->replace('survey_quota_list', $survey_quota_list);
	$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
	$EnableQCoreClass->replace('thisStep', $thisPageStep);
	$actionButton = '';

	if (1 < count($pageBreak)) {
		$actionButton .= '<input class=btn type=button value="' . $lang['cache_survey_over'] . '" name="SurveyCacheSubmit" id="SurveyCacheSubmit" onclick="javascript:if( window.confirm(\'' . $lang['cache_survey_over_info'] . '\')){document.Survey_Form.Action.value = \'SurveyCacheSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();}">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
	}
	else {
		$actionButton .= '<input class=btn type=button value="' . $lang['submit_survey_next'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form.nextStep.value = \'1\';Survey_Form_Submit();">&nbsp;';
		$actionButton .= '&nbsp;<input class=btn type=button value="' . $lang['submit_survey_over'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$SurveyPage = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
	$SurveyPage = str_replace($All_Path, '', $SurveyPage);
	$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
	$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
	$SurveyPage = str_replace('Android/', '../Android/', $SurveyPage);
	$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
	$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);
	$SurveyPage = str_replace('按住Ctrl键点击鼠标进行多重选择', '多重选择', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#ffffff"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#f5f5f5"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#ffffff"', '<input type="checkbox"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#f5f5f5"', '<input type="checkbox"', $SurveyPage);
	echo $SurveyPage;

	if (!isset($_SESSION['overTime_' . $S_Row['surveyID']])) {
		$_SESSION['overTime_' . $S_Row['surveyID']] = time();
	}

	if (!isset($_SESSION['sBeginTime_' . $S_Row['surveyID']])) {
		$_SESSION['sBeginTime_' . $S_Row['surveyID']] = time();
	}

	exit();
}

?>

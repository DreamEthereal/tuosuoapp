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

	unset($_SESSION['cateID_' . $_POST['surveyID']]);
	unset($_SESSION['overTime_' . $_POST['surveyID']]);
	setcookie('overTime_' . $_POST['surveyID'], time(), time() - 1);
	unset($_SESSION['responseID_' . $_POST['surveyID']]);
	unset($_SESSION['joinTime_' . $_POST['surveyID']]);
	unset($_SESSION['sBeginTime_' . $_POST['surveyID']]);
	unset($_SESSION['referer_' . $_POST['surveyID']]);
	unset($_SESSION['replyNum_' . $_POST['surveyID']]);
	unset($_SESSION['haveOpenSurveyFlag_' . $_POST['surveyID']]);
	unset($_SESSION['gpsTime_' . $_POST['surveyID']]);
	unset($_SESSION['thisStep_' . $_POST['surveyID']]);

	switch ($_POST['isPublic']) {
	case '2':
		unset($_SESSION['passPort_' . $_POST['surveyID']]);
		break;

	case '0':
		unset($_SESSION['passPort_' . $_POST['surveyID']]);

		switch ($_POST['passPortType']) {
		case '1':
		default:
			unset($_SESSION['userGroupID']);
			unset($_SESSION['userGroupName']);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_SESSION['ajaxCount']; $_obf_7w__++) {
				$_obf_XA__ = $_obf_7w__ + 1;
				unset($_SESSION['ajaxRtnValue_' . $_obf_XA__]);
			}

			unset($_SESSION['ajaxCount']);
			break;

		case '2':
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_SESSION['ajaxCount']; $_obf_7w__++) {
				$_obf_XA__ = $_obf_7w__ + 1;
				unset($_SESSION['ajaxRtnValue_' . $_obf_XA__]);
			}

			unset($_SESSION['ajaxCount']);
			unset($_SESSION['hash']);

			if (isset($_SESSION['webArea'])) {
				unset($_SESSION['webArea']);
			}

			break;

		case '3':
		case '4':
		case '5':
			break;
		}

		break;
	}
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', './');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
require_once ROOT_PATH . 'Entry/Global.android.php';
include_once ROOT_PATH . 'Functions/Functions.cons.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
include_once ROOT_PATH . 'Functions/Functions.api.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
include_once ROOT_PATH . 'License/License.xml';

if ($License['isPanel'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

if (($_GET['qname'] == '') && ($_GET['qid'] == '')) {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}
else {
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' OR surveyID =\'' . trim($_GET['qid']) . '\' LIMIT 0,1 ';
	$S_Row = $DB->queryFirstRow($SQL);

	if (!$S_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

switch ($S_Row['status']) {
case '0':
	_shownotes($lang['status_error'], $lang['design_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	break;

case '2':
	_shownotes($lang['status_error'], $lang['end_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	break;

case '1':
	break;
}

$nowTime = date('Y-m-d', time());

if ($nowTime < $S_Row['beginTime']) {
	_shownotes($lang['status_error'], $lang['no_start_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

if ($S_Row['endTime'] < $nowTime) {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
	$DB->query($SQL);
	_shownotes($lang['status_error'], $lang['end_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

if (($S_Row['maxResponseNum'] != 0) && ($S_Row['maxResponseNum'] != '')) {
	$SQL = ' SELECT COUNT(*) AS maxResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag IN (1,3) ';
	$CountRow = $DB->queryFirstRow($SQL);

	if ($S_Row['maxResponseNum'] <= $CountRow['maxResponseNum']) {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
		$DB->query($SQL);
		_shownotes($lang['status_error'], $lang['max_num_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}
}

if ($S_Row['isAllowIP'] == 1) {
	$fromIPAddress = explode('.', _getip());
	$fullNum3IP = sprintf('%03s', $fromIPAddress[0]) . '.' . sprintf('%03s', $fromIPAddress[1]) . '.' . sprintf('%03s', $fromIPAddress[2]) . '.' . sprintf('%03s', $fromIPAddress[3]);
	$SQL = ' SELECT startIP FROM ' . ALLOWIP_TABLE . ' WHERE startIP<=\'' . $fullNum3IP . '\' AND endIP>=\'' . $fullNum3IP . '\' AND surveyID=\'' . $S_Row['surveyID'] . '\' ORDER BY startIP ASC LIMIT 0,1 ';
	$IPRow = $DB->queryFirstRow($SQL);

	if ($S_Row['isAllowIPMode'] == 1) {
		if (!$IPRow) {
			_shownotes($lang['status_error'], $lang['ip_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
	}
	else if ($IPRow) {
		_shownotes($lang['status_error'], $lang['ip_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}
}

switch ($S_Row['isCheckIP']) {
case '1':
default:
	$SQL = ' SELECT submitTime FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE ipAddress=\'' . _getip() . '\' AND overFlag !=0 ORDER BY responseID DESC LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if (datediff('n', $HaveRow['submitTime'], time()) < $S_Row['maxIpTime']) {
		_shownotes($lang['status_error'], $lang['time_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}

	break;

case '2':
	if (($_COOKIE['enableqcheck' . $S_Row['surveyID']] != '') && ($_COOKIE['enableqcheck' . $S_Row['surveyID']] == md5($S_Row['surveyName'] . $S_Row['surveyID']))) {
		_shownotes($lang['status_error'], $lang['cookie_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}

	break;

case '0':
	break;
}

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
$isMobile = 1;
$_SESSION['referer_' . $S_Row['surveyID']] = isset($_SESSION['referer_' . $S_Row['surveyID']]) ? $_SESSION['referer_' . $S_Row['surveyID']] : _getdomainfromurl($_SERVER['HTTP_REFERER']);
$BaseSQL = ' SELECT * FROM ' . BASESETTING_TABLE . ' ';
$BaseRow = $DB->queryFirstRow($BaseSQL);

switch ($BaseRow['isUseOriPassport']) {
case 4:
	if ($S_Row['isPublic'] == 0) {
		_shownotes($lang['status_error'], $lang['private_cookie_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}

	break;

default:
	break;
}

if ($_POST['Action'] == 'SurveyOverSubmit') {
	$_POST['surveyID'] = (int) $_POST['surveyID'];
	$_POST['thisStep'] = (int) $_POST['thisStep'];

	if ($_POST['isPublic'] == '0') {
		if (!isset($_SESSION['userName'])) {
			_shownotes($lang['auth_error'], $lang['error_lost_session'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
		else {
			$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE administratorsName = \'' . $_SESSION['userName'] . '\' AND overFlag !=0 LIMIT 0,1 ';
			$HaveRow = $DB->queryFirstRow($HaveSQL);

			if ($HaveRow) {
				_shownotes($lang['status_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
			}
		}
	}

	if ($_POST['thisStep'] != 0) {
		if (!isset($_SESSION['responseID_' . $S_Row['surveyID']]) || ($_SESSION['responseID_' . $S_Row['surveyID']] == '')) {
			_shownotes($lang['auth_error'], $lang['error_lost_session'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
	}

	if (!isset($_SESSION['thisStep_' . $S_Row['surveyID']]) || ($_POST['thisStep'] != $_SESSION['thisStep_' . $S_Row['surveyID']])) {
		_shownotes($lang['auth_error'], $lang['error_lost_pagenum'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}

	if ($S_Row['isDisRefresh'] == 1) {
		$SQL = ' SELECT submitTime FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE ipAddress=\'' . _getip() . '\' AND overFlag !=0 ORDER BY responseID DESC LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (datediff('s', $HaveRow['submitTime'], time()) < 20) {
			_shownotes($lang['status_error'], $lang['time_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
	}

	$thisPageFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
	$thisPageFields = explode('|', $thisPageFieldsList);

	foreach ($thisPageFields as $theField) {
		$_SESSION[$theField] = $_POST[$theField];
	}

	if ($_POST['isCheckCode'] == 1) {
		include ROOT_PATH . 'JS/CreateVerifyCode.class.php';
		$img = new Securimage();
		$valid = $img->check($_POST['verifyCode']);

		if ($valid == false) {
			_showerror($lang['system_error'], $lang['error_verifycode']);
		}
	}

	require ROOT_PATH . 'Process/Time.inc.php';
	require ROOT_PATH . 'Process/Hidden.inc.php';
	$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
	$thisSurveyFields = explode('|', $thisSurveyFieldsList);
	$isHaveSQL = ' SELECT responseID,overFlag FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE responseID = \'' . $_SESSION['responseID_' . $S_Row['surveyID']] . '\' LIMIT 0,1 ';
	$isHaveRow = $DB->queryFirstRow($isHaveSQL);
	if (($_SESSION['responseID_' . $S_Row['surveyID']] == '') || !$isHaveRow) {
		require ROOT_PATH . 'Process/File.inc.php';
		require ROOT_PATH . 'Process/Area.inc.php';
		require ROOT_PATH . 'Process/Passport.inc.php';
		$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
		$SQL .= $response_PassportSQL;
		$SQL .= ' ipAddress =\'' . $ipAddress . '\',';
		$SQL .= ' area =\'' . $Area . '\',';
		$SQL .= ' joinTime = \'' . $time . '\',';
		$SQL .= ' submitTime = \'' . time() . '\',';
		$SQL .= ' overTime = \'' . $over_time . '\',';
		$SQL .= ' cateID = \'' . $_SESSION['cateID_' . $_POST['surveyID']] . '\',';
		$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';
		$SQL .= ' overFlag =1,';
		$SQL .= ' dataSource = \'3\',';

		if (base64_decode($_POST['thisFields']) != '') {
			foreach ($thisSurveyFields as $surveyFields) {
				if (is_array($_POST[$surveyFields])) {
					asort($_POST[$surveyFields]);
					$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
				}
				else {
					$encode = mb_detect_encoding($_POST[$surveyFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

					if ($encode == 'UTF-8') {
						$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars(iconv('UTF-8', 'GBK', $_POST[$surveyFields])) . '\',';
					}
					else {
						$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
					}
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
			_shownotes($lang['status_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}

		require ROOT_PATH . 'Process/File.inc.php';
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
		$SQL .= ' submitTime = \'' . time() . '\',';
		$SQL .= ' overTime = \'' . $over_time . '\',';
		$SQL .= ' overFlag =1,';
		$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';

		if (base64_decode($_POST['thisFields']) != '') {
			foreach ($thisSurveyFields as $surveyFields) {
				if (is_array($_POST[$surveyFields])) {
					asort($_POST[$surveyFields]);
					$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
				}
				else {
					$encode = mb_detect_encoding($_POST[$surveyFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

					if ($encode == 'UTF-8') {
						$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars(iconv('UTF-8', 'GBK', $_POST[$surveyFields])) . '\',';
					}
					else {
						$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
					}
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

	if ($_POST['surveyQuotaFlag'] != 2) {
		if (!isset($_SESSION['joinTime_' . $S_Row['surveyID']])) {
			$theTime = time();
		}
		else {
			$theTime = $_SESSION['joinTime_' . $S_Row['surveyID']];
		}

		dealcountinfo($_POST['surveyID'], $theTime);
	}

	if (($_POST['isPublic'] == '0') && ($_POST['passPortType'] == '2') && ($BaseRow['ajaxOverURL'] != '') && ($License['AjaxPassport'] == 1)) {
		include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
		$hashCode = md5(trim($BaseRow['license']));
		$token = '';

		if (trim($BaseRow['ajaxTokenURL']) != '') {
			if ($BaseRow['isPostMethod'] == 1) {
				$postData = array();
				$postData['hash'] = $hashCode;
				$token = post_gbk_data_to_host(trim($BaseRow['ajaxTokenURL']), $postData);
			}
			else {
				if (strpos(trim($BaseRow['ajaxTokenURL']), '?') === false) {
					$ajaxURL = trim($BaseRow['ajaxTokenURL']) . '?hash=' . $hashCode;
				}
				else {
					$ajaxURL = trim($BaseRow['ajaxTokenURL']) . '&hash=' . $hashCode;
				}

				$token = get_url_content($ajaxURL);
			}
		}

		if ($BaseRow['isPostMethod'] == 1) {
			$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $S_Row['administratorsID'] . '\'';
			$UserRow = $DB->queryFirstRow($SQL);
			$ownername = base64_encode($UserRow['administratorsName']);
			$username = base64_encode($_SESSION['userName']);
			$postData = array();
			$postData['surveyID'] = $S_Row['surveyID'];
			$postData['surveyTitle'] = $S_Row['surveyTitle'];
			$postData['ownername'] = $ownername;
			$postData['username'] = $username;
			$postData['hash'] = $hashCode;

			if ($_POST['surveyQuotaFlag'] == 2) {
				if ($_POST['screeningFlag'] == 2) {
					$dataStat = 2;
				}
				else {
					$dataStat = 3;
				}
			}
			else {
				$dataStat = 1;
			}

			$postData['dataStat'] = $dataStat;
			$sign = md5('surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . $S_Row['surveyTitle'] . '&username=' . $username . '&dataStat=' . $dataStat . '&hash=' . $hashCode . '&' . $token);
			$postData['sign'] = $sign;
			post_gbk_data_to_host(trim($BaseRow['ajaxOverURL']), $postData);
		}
		else {
			$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $S_Row['administratorsID'] . '\'';
			$UserRow = $DB->queryFirstRow($SQL);

			if ($Config['data_base64_enable'] == 1) {
				$ownername = str_replace('+', '%2B', base64_encode($UserRow['administratorsName']));
				$username = str_replace('+', '%2B', base64_encode($_SESSION['userName']));
				$signname = base64_encode($_SESSION['userName']);
			}
			else {
				$ownername = urlencode($UserRow['administratorsName']);
				$username = urlencode($_SESSION['userName']);
				$signname = $_SESSION['userName'];
			}

			if (strpos(trim($BaseRow['ajaxOverURL']), '?') === false) {
				$ajaxURL = trim($BaseRow['ajaxOverURL']) . '?';
			}
			else {
				$ajaxURL = trim($BaseRow['ajaxOverURL']) . '&';
			}

			$ajaxURL .= 'surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']) . '&ownername=' . $ownername . '&username=' . $username . '&hash=' . $hashCode;
			$ajaxURL .= '&webArea=' . $_SESSION['webArea'] . '&responseID=' . $new_responseID;

			if ($_POST['surveyQuotaFlag'] == 2) {
				if ($_POST['screeningFlag'] == 2) {
					$ajaxURL .= '&dataStat=2';
					$dataStat = 2;
				}
				else {
					$ajaxURL .= '&dataStat=3';
					$dataStat = 3;
				}
			}
			else {
				$ajaxURL .= '&dataStat=1';
				$dataStat = 1;
			}

			if ($_GET['otherValue'] != '') {
				$otherValueArray = array();
				$otherValueArray = explode('|', trim($_GET['otherValue']));

				foreach ($otherValueArray as $otherValueItem) {
					$otherValueSign = array();
					$otherValueSign = explode('*', $otherValueItem);
					$ajaxURL .= '&' . $otherValueSign[0] . '=' . $otherValueSign[1];
				}
			}

			$sign = md5('surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . $S_Row['surveyTitle'] . '&username=' . $signname . '&dataStat=' . $dataStat . '&hash=' . $hashCode . '&' . $token);
			$ajaxURL .= '&sign=' . $sign;
			get_url_content($ajaxURL);
		}
	}

	setcookie('enableqcheck' . $S_Row['surveyID'], md5($S_Row['surveyName'] . $S_Row['surveyID']), time() + ($S_Row['maxIpTime'] * 60));
	$thePanelBeginTime = ($_SESSION['sBeginTime_' . $S_Row['surveyID']] == '' ? $_SESSION['joinTime_' . $S_Row['surveyID']] : $_SESSION['sBeginTime_' . $S_Row['surveyID']]);

	if ($_POST['surveyQuotaFlag'] == 2) {
		clearsession();
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET overFlag = 2 WHERE responseID = \'' . $new_responseID . '\' ';
		$DB->query($SQL);
		_writeapidata($S_Row, $new_responseID, $_POST['screeningFlag'], $thePanelBeginTime);

		if ($QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'] == '') {
			_shownotes($lang['survey_submit'], $lang['num_to_quota'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
		else {
			_shownotes($lang['survey_submit'], $QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
	}

	_writeapidata($S_Row, $new_responseID, 1, $thePanelBeginTime);

	switch ($S_Row['exitMode']) {
	case '1':
	case '3':
	case '4':
	case '5':
	default:
		clearsession();
		_shownotes('感谢您的回复', '非常感谢您对于我们调查项目的回复。您的意见对于我们的调查研究非常重要。并请关注我们其他的调查项目。', $lang['survey_gname'] . $S_Row['surveyTitle']);
		break;

	case '2':
		clearsession();
		$isShowProof = true;

		if ($S_Row['isShowProof'] == 1) {
			$theRandNumber = rand(1, 100);

			if ($S_Row['proofRate'] < $theRandNumber) {
				$isShowProof = false;
			}
		}
		else {
			$isShowProof = false;
		}

		if ($isShowProof == false) {
			_shownotes($S_Row['exitTitleHead'], nl2br($S_Row['exitTextBody']), $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
		else {
			$pSQL = ' SELECT * FROM ' . SURVEYPROOF_TABLE . ' WHERE surveyID = \'' . $S_Row['surveyID'] . '\' AND dataID =0 ORDER BY rand() LIMIT 1 ';
			$pRow = $DB->queryFirstRow($pSQL);

			if (!$pRow) {
				_shownotes($S_Row['exitTitleHead'], nl2br($S_Row['exitTextBody']), $lang['survey_gname'] . $S_Row['surveyTitle']);
			}
			else {
				$uSQL = ' UPDATE ' . SURVEYPROOF_TABLE . ' SET dataID = \'' . $new_responseID . '\' WHERE proofID = \'' . $pRow['proofID'] . '\' ';
				$DB->query($uSQL);
				$exitTextBody = nl2br($S_Row['exitTextBody']) . '<br/><br/><font color=red>' . $lang['proof_title'] . '</font><br/><br/><font color=red><b>' . $pRow['proofName'] . '</b>' . $lang['proof_name'] . '<b>' . $pRow['proofNum'] . '</b>' . $lang['proof_pass'] . '<b>' . $pRow['proofPass'] . '</b></font>';
				_shownotes($S_Row['exitTitleHead'], $exitTextBody, $lang['survey_gname'] . $S_Row['surveyTitle']);
			}
		}

		break;
	}
}

$thePageSurveyID = $S_Row['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$thisPageStep = 0;
$surveyID = $S_Row['surveyID'];
$theActionPage = 1;
$theHaveRatingSlider = false;
$theHaveDatePicker = false;
$EnableQCoreClass->replace('theme', 'SurveyAndroid');
$EnableQCoreClass->replace('secureImage', '');
$EnableQCoreClass->replace('isCheckCode', 0);
if (($_POST['Action'] == 'SurveyPreSubmit') || ($_POST['Action'] == 'SurveyNextSubmit')) {
	$_POST['surveyID'] = (int) $_POST['surveyID'];
	$_POST['thisStep'] = (int) $_POST['thisStep'];

	if ($_POST['Action'] == 'SurveyPreSubmit') {
		if (!isset($_SESSION['thisStep_' . $S_Row['surveyID']]) || ($_POST['thisStep'] != $_SESSION['thisStep_' . $S_Row['surveyID']])) {
			_shownotes($lang['auth_error'], $lang['error_lost_pagenum'], $lang['survey_gname'] . $S_Row['surveyTitle']);
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

		$thisLastPageStep = $_POST['thisStep'] - 1;

		if ($thisLastPageStep <= 0) {
			$thisLastPageStep = 0;
		}

		$thisPageStep = isskippage($thisLastPageStep, 2);
	}

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		if ($_POST['isPublic'] == '0') {
			if (!isset($_SESSION['userName'])) {
				_shownotes($lang['auth_error'], $lang['error_lost_session'], $lang['survey_gname'] . $S_Row['surveyTitle']);
			}
			else {
				$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE administratorsName = \'' . $_SESSION['userName'] . '\' AND overFlag !=0 LIMIT 0,1 ';
				$HaveRow = $DB->queryFirstRow($HaveSQL);

				if ($HaveRow) {
					_shownotes($lang['status_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
				}
			}
		}

		if ($_POST['thisStep'] != 0) {
			if (!isset($_SESSION['responseID_' . $S_Row['surveyID']]) || ($_SESSION['responseID_' . $S_Row['surveyID']] == '')) {
				_shownotes($lang['auth_error'], $lang['error_lost_session'], $lang['survey_gname'] . $S_Row['surveyTitle']);
			}
		}

		if (!isset($_SESSION['thisStep_' . $S_Row['surveyID']]) || ($_POST['thisStep'] != $_SESSION['thisStep_' . $S_Row['surveyID']])) {
			_shownotes($lang['auth_error'], $lang['error_lost_pagenum'], $lang['survey_gname'] . $S_Row['surveyTitle']);
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
			require ROOT_PATH . 'Process/File.inc.php';
			require ROOT_PATH . 'Process/Area.inc.php';
			require ROOT_PATH . 'Process/Passport.inc.php';
			$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
			$SQL .= $response_PassportSQL;
			$SQL .= ' ipAddress =\'' . $ipAddress . '\',';
			$SQL .= ' area =\'' . $Area . '\',';
			$SQL .= ' joinTime = \'' . $time . '\',';
			$SQL .= ' submitTime = \'' . time() . '\',';
			$SQL .= ' overTime = \'' . $over_time . '\',';
			$SQL .= ' cateID = \'' . $_SESSION['cateID_' . $_POST['surveyID']] . '\',';
			$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';
			$SQL .= ' dataSource = \'3\',';

			if (base64_decode($_POST['thisFields']) != '') {
				foreach ($thisSurveyFields as $surveyFields) {
					if (is_array($_POST[$surveyFields])) {
						asort($_POST[$surveyFields]);
						$SQL .= ' ' . $surveyFields . ' = \'' . implode(',', qhtmlspecialchars($_POST[$surveyFields])) . '\',';
					}
					else {
						$encode = mb_detect_encoding($_POST[$surveyFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

						if ($encode == 'UTF-8') {
							$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars(iconv('UTF-8', 'GBK', $_POST[$surveyFields])) . '\',';
						}
						else {
							$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
						}
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
				_shownotes($lang['status_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
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
						$encode = mb_detect_encoding($_POST[$surveyFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

						if ($encode == 'UTF-8') {
							$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars(iconv('UTF-8', 'GBK', $_POST[$surveyFields])) . '\',';
						}
						else {
							$SQL .= ' ' . $surveyFields . ' = \'' . qhtmlspecialchars($_POST[$surveyFields]) . '\',';
						}
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

if ($thisPageStep != 0) {
	$ShowSurveyPageFile = 'ShowSurvey' . $thisPageStep . 'PageFile';
	$ShowSurveyPage = 'ShowSurvey' . $thisPageStep . 'Page';
	$ShowSurveyFile = 'ShowSurvey' . $thisPageStep . 'File';
	$question = 'question' . $thisPageStep;
	$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, 'uSurveyAndroid.html');
	$EnableQCoreClass->set_CycBlock($ShowSurveyPageFile, 'QUESTION', $question);
	$EnableQCoreClass->replace($question, '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyShortTitle', cnsubstr($S_Row['surveyTitle'], 0, 16, 1));
	$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
	$EnableQCoreClass->replace('startInfo', '');
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('surveyLang', $language);
	$isWaiting = ($S_Row['isWaiting'] == '1' ? 'initWaiting();' : '');
	$EnableQCoreClass->replace('waitingScript', $isWaiting);
	$waitingTime = ($S_Row['waitingTime'] == 0 ? 10 : $S_Row['waitingTime']);
	$EnableQCoreClass->replace('waitingTime', $waitingTime);

	if ($S_Row['isLimited'] == 1) {
		$EnableQCoreClass->replace('limitedScript', 'initLimitedTime();');
		$theOverTime = (isset($_COOKIE['overTime_' . $S_Row['surveyID']]) ? $_COOKIE['overTime_' . $S_Row['surveyID']] : $_SESSION['overTime_' . $S_Row['surveyID']]);
		if (($theOverTime != 0) && ($theOverTime != '')) {
			$costTime = time() - $theOverTime;

			if (0 <= $S_Row['limitedTime'] - $costTime) {
				$leftLimitedTime = $S_Row['limitedTime'] - $costTime;
			}
			else {
				$leftLimitedTime = 0;
			}
		}
		else {
			$leftLimitedTime = $S_Row['limitedTime'];
		}

		$EnableQCoreClass->replace('limitedTime', $leftLimitedTime);
		$limitedTimeBar = '<div id=\'limitedBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:2px;padding:0;">&nbsp;' . $lang['survey_limit_time'] . '<b><font color=red>' . $leftLimitedTime . '</font></b>' . $lang['survey_limit_time_sec'] . '</div>';
		$EnableQCoreClass->replace('limitedTimeBar', $limitedTimeBar);
	}
	else {
		$EnableQCoreClass->replace('limitedScript', '');
		$EnableQCoreClass->replace('limitedTime', 0);
		$EnableQCoreClass->replace('limitedTimeBar', '');
	}

	if ($S_Row['isProcessBar'] == 1) {
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

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		$EnableQCoreClass->replace('allHidden', qhtmlspecialchars($_POST['thisHidden']) . $this_hidden_list);
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
		$EnableQCoreClass->replace('allHidden', str_replace(qhtmlspecialchars($_POST['thisHidden']), '', qhtmlspecialchars($_POST['allHidden'])));
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
	$_SESSION['thisStep_' . $S_Row['surveyID']] = $thisPageStep;

	if ($thisPageStep != count($pageBreak) - 1) {
		if ($S_Row['isPreStep'] == '1') {
			$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">';
		}
		else {
			$actionButton = '';
		}

		$actionButton .= ' <input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
	}
	else {
		if ($S_Row['isPreStep'] == '1') {
			$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">';
		}
		else {
			$actionButton = '';
		}

		$actionButton .= ' <input class=btn type=button value="' . $lang['submit_survey'] . '" name="SurveyOverSubmit"  id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$EnableQCoreClass->replace('androidScript', '<script type="text/javascript" src="JS/PubGpsScript.js.php"></script>');
	$EnableQCoreClass->replace('readGpsSimInfo', 'readGpsSimInfo();');
	$ShowSurvey = $EnableQCoreClass->parse($ShowSurveyPage, $ShowSurveyPageFile);
	echo $ShowSurvey;

	if (!isset($_SESSION['overTime_' . $S_Row['surveyID']])) {
		$_SESSION['overTime_' . $S_Row['surveyID']] = time();
	}

	if (!isset($_COOKIE['overTime_' . $S_Row['surveyID']])) {
		setcookie('overTime_' . $S_Row['surveyID'], time(), time() + 31536000);
	}

	exit();
}

if (isset($_SESSION['userName']) && ($_SESSION['userName'] != '')) {
	switch ($BaseRow['isUseOriPassport']) {
	case 3:
	case 5:
		$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE administratorsName = \'' . addslashes(strtolower($_SESSION['userName'])) . '\' AND overFlag !=0 LIMIT 0,1 ';
		break;

	default:
		$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE administratorsName = \'' . addslashes($_SESSION['userName']) . '\' AND overFlag !=0 LIMIT 0,1 ';
		break;
	}

	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if ($HaveRow) {
		_shownotes($lang['status_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}

	switch ($S_Row['isPublic']) {
	case '0':
		switch ($BaseRow['isUseOriPassport']) {
		case '1':
			$surveyID = $S_Row['surveyID'];
			require ROOT_PATH . 'System/PanelReg.inc.php';
			$SQL = ' SELECT administratorsGroupID,administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) = \'' . addslashes(strtolower($_SESSION['userName'])) . '\' AND isAdmin =0 LIMIT 0,1 ';
			$GroupRow = $DB->queryFirstRow($SQL);

			if ($GroupRow['administratorsGroupID'] == '0') {
				$administratorsGroupName = $lang['no_group'];
			}
			else {
				$SQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $GroupRow['administratorsGroupID'] . '\' ';
				$Row = $DB->queryFirstRow($SQL);
				$administratorsGroupName = $Row['administratorsGroupName'];
			}

			if (trim($administratorsIDList) != '') {
				if (!in_array($GroupRow['administratorsID'], explode(',', trim($administratorsIDList)))) {
					_shownotes($lang['status_error'], $lang['error_login_auth'], $lang['survey_gname'] . $S_Row['surveyTitle']);
				}
			}
			else {
				_shownotes($lang['status_error'], $lang['error_login_auth'], $lang['survey_gname'] . $S_Row['surveyTitle']);
			}

			$mainAttribute = explode(',', $S_Row['mainAttribute']);
			$ajaxRtnValueCate = array();
			$temp = 0;

			foreach ($mainAttribute as $theMainAttribute) {
				$SQL = ' SELECT optionFieldName,administratorsoptionID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE administratorsoptionID =\'' . $theMainAttribute . '\' ';
				$OptionRow = $DB->queryFirstRow($SQL);

				if ($OptionRow['optionFieldName'] != '') {
					$SQL = ' SELECT value FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE administratorsoptionID =\'' . $OptionRow['administratorsoptionID'] . '\' AND administratorsID = \'' . $GroupRow['administratorsID'] . '\' ';
					$ValueRow = $DB->queryFirstRow($SQL);
					$ajaxRtnValueCate[] = $OptionRow['optionFieldName'];
					$temp++;
					$_SESSION['ajaxRtnValue_' . $temp] = $ValueRow['value'];
				}
			}

			if ($S_Row['ajaxRtnValue'] == '') {
				$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' ';
				$DB->query($SQL);
			}

			unset($ajaxRtnValueCate);
			$_SESSION['ajaxCount'] = $temp;
			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$_SESSION['userGroupID'] = $GroupRow['administratorsGroupID'];
			$_SESSION['userGroupName'] = $administratorsGroupName;
			$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET loginNum=loginNum+1,lastVisitTime=\'' . time() . '\' WHERE administratorsID=\'' . $GroupRow['administratorsID'] . '\' ';
			$DB->query($SQL);
			break;

		case '2':
		case '4':
			break;

		case '3':
			$RepSQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' AND adUserName = \'' . addslashes(strtolower($_SESSION['userName'])) . '\' LIMIT 0,1 ';
			$haveRow = $DB->queryFirstRow($RepSQL);

			if (!$haveRow) {
				$options['account_suffix'] = trim($BaseRow['accountSuffix']);
				$domain_controllers = explode(',', trim($BaseRow['domainControllers']));
				$options['domain_controllers'] = $domain_controllers;
				$options['ad_username'] = trim($BaseRow['adUsername']);
				$options['ad_password'] = trim($BaseRow['adPassword']);
				$options['base_dn'] = trim($BaseRow['baseDN']);
				include_once ROOT_PATH . 'Includes/LDAP.class.php';
				$LDAP = new LDAPCLASS($options);
				$RepSQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' AND adUserName like \'group/%\' ';
				$Result = $DB->query($RepSQL);
				$haveKownGroup = false;

				while ($Row = $DB->queryArray($Result)) {
					$this_ad_user_name = trim($Row['adUserName']);

					if ($this_ad_user_name != '') {
						$groupFlag = substr($this_ad_user_name, 0, 6);

						if ($groupFlag == 'group/') {
							$this_group_name = str_replace('group/', '', trim($Row['adUserName']));
							$this_group_all_users = $LDAP->all_group_users($this_group_name);
							$the_group_all_users = array();

							foreach ($this_group_all_users as $this_all_users) {
								$the_group_all_users[] = strtolower($this_all_users);
							}

							if (in_array(strtolower($_SESSION['userName']), $the_group_all_users)) {
								$haveKownGroup = true;
								unset($this_group_all_users);
								unset($the_group_all_users);
								break;
							}
						}
					}
				}

				if ($haveKownGroup == false) {
					_shownotes($lang['status_error'], $lang['error_login_auth'], $lang['survey_gname'] . $S_Row['surveyTitle']);
				}
			}

			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$_SESSION['userName'] = trim(strtolower($_SESSION['userName']));
			break;

		case '5':
			$RepSQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' AND adUserName = \'' . addslashes(strtolower($_SESSION['userName'])) . '\' LIMIT 0,1 ';
			$haveRow = $DB->queryFirstRow($RepSQL);

			if (!$haveRow) {
				$domain_controllers = explode(',', trim($BaseRow['domainControllers']));
				$options['domain_controllers'] = $domain_controllers;
				$options['ad_username'] = trim($BaseRow['adUsername']);
				$options['ad_password'] = trim($BaseRow['adPassword']);
				$options['base_dn'] = trim($BaseRow['baseDN']);
				include_once ROOT_PATH . 'Includes/LDAPAuth.class.php';
				$ldap = new AuthLdap($options);
				$theUserNameUTF8 = iconv('gbk', 'UTF-8', strtolower($_SESSION['userName']));
				$RepSQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' AND adUserName like \'group/%\' ';
				$Result = $DB->query($RepSQL);
				$haveKownUnit = false;

				while ($Row = $DB->queryArray($Result)) {
					$theUnitFlage = explode('/', trim($Row['adUserName']));

					if ($ldap->checkInOU($theUserNameUTF8, $theUnitFlage[1])) {
						$haveKownUnit = true;
						break;
					}
				}

				if ($haveKownUnit == false) {
					_shownotes($lang['status_error'], $lang['error_login_auth'], $lang['survey_gname'] . $S_Row['surveyTitle']);
				}
			}

			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$_SESSION['userName'] = trim(strtolower($_SESSION['userName']));
			break;
		}

		break;

	case '1':
	case '2':
		break;
	}
}

$_SESSION['haveOpenSurveyFlag_' . $S_Row['surveyID']] = false;
if (($_SESSION['userName'] != '') && ($_SESSION['haveOpenSurveyFlag_' . $S_Row['surveyID']] == false)) {
	$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE administratorsName =\'' . $_SESSION['userName'] . '\' AND overFlag =0 LIMIT 0,1 ';
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

		$_SESSION['haveOpenSurveyFlag_' . $S_Row['surveyID']] = true;
	}
}

if ((($S_Row['isPublic'] == '1') && ($thisPageStep == 0)) || (($S_Row['isPublic'] != '1') && ($_SESSION['passPort_' . $S_Row['surveyID']] == true) && ($thisPageStep == 0))) {
	$EnableQCoreClass->setTemplateFile('ShowSurveyFile', 'uSurveyAndroid.html');
	$EnableQCoreClass->set_CycBlock('ShowSurveyFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$startInfo = '<div id="start_survey_page_0"><table width="100%" class="pertable" style="padding-bottom:10px"><tr><td style="padding-left:8px;padding-top:8px">' . $S_Row['surveyInfo'] . '</td></tr></table>';
	$startInfo .= '<table width="100%" style="margin-top:10px;margin-bottom:20px"><tr><td align="center"><input class=btn type=button value="' . $lang['survey_start'] . '" name="StartSurvey" id="StartSurvey" onclick="javascript:startSurvey();"></td></tr></table></div>';
	$EnableQCoreClass->replace('startInfo', $startInfo);
	$EnableQCoreClass->replace('surveyShortTitle', cnsubstr($S_Row['surveyTitle'], 0, 16, 1));
	$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('surveyLang', $language);
	$isWaiting = ($S_Row['isWaiting'] == '1' ? 'initWaiting();' : '');
	$EnableQCoreClass->replace('waitingScript', $isWaiting);
	$waitingTime = ($S_Row['waitingTime'] == 0 ? 10 : $S_Row['waitingTime']);
	$EnableQCoreClass->replace('waitingTime', $waitingTime);

	if ($S_Row['isLimited'] == 1) {
		$EnableQCoreClass->replace('limitedScript', 'initLimitedTime();');
		$theOverTime = (isset($_COOKIE['overTime_' . $S_Row['surveyID']]) ? $_COOKIE['overTime_' . $S_Row['surveyID']] : $_SESSION['overTime_' . $S_Row['surveyID']]);
		if (($theOverTime != 0) && ($theOverTime != '')) {
			$costTime = time() - $theOverTime;

			if (0 <= $S_Row['limitedTime'] - $costTime) {
				$leftLimitedTime = $S_Row['limitedTime'] - $costTime;
			}
			else {
				$leftLimitedTime = 0;
			}
		}
		else {
			$leftLimitedTime = $S_Row['limitedTime'];
		}

		$EnableQCoreClass->replace('limitedTime', $leftLimitedTime);
		$limitedTimeBar = '<div id=\'limitedBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:2px;padding:0;">&nbsp;' . $lang['survey_limit_time'] . '<b><font color=red>' . $leftLimitedTime . '</font></b>' . $lang['survey_limit_time_sec'] . '</div>';
		$EnableQCoreClass->replace('limitedTimeBar', $limitedTimeBar);
	}
	else {
		$EnableQCoreClass->replace('limitedScript', '');
		$EnableQCoreClass->replace('limitedTime', 0);
		$EnableQCoreClass->replace('limitedTimeBar', '');
	}

	if ((1 < count($pageBreak)) && ($S_Row['isProcessBar'] == 1)) {
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
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
	$EnableQCoreClass->replace('passPortType', $BaseRow['isUseOriPassport']);
	$EnableQCoreClass->replace('survey_quota_list', $survey_quota_list);
	$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
	$EnableQCoreClass->replace('thisStep', $thisPageStep);
	$_SESSION['thisStep_' . $S_Row['surveyID']] = $thisPageStep;
	$actionButton = '';

	if (1 < count($pageBreak)) {
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
	}
	else {
		$actionButton .= '<input class=btn type=button value="' . $lang['submit_survey'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
	$EnableQCoreClass->replace('androidScript', '<script type="text/javascript" src="JS/PubGpsScript.js.php"></script>');
	$EnableQCoreClass->replace('readGpsSimInfo', 'readGpsSimInfo();');
	$ShowSurvey = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
	echo $ShowSurvey;

	if (!isset($_SESSION['overTime_' . $S_Row['surveyID']])) {
		$_SESSION['overTime_' . $S_Row['surveyID']] = time();
	}

	if (!isset($_COOKIE['overTime_' . $S_Row['surveyID']])) {
		setcookie('overTime_' . $S_Row['surveyID'], time(), time() + 31536000);
	}

	if (!isset($_SESSION['sBeginTime_' . $S_Row['surveyID']])) {
		$_SESSION['sBeginTime_' . $S_Row['surveyID']] = time();
	}

	exit();
}

if ($_POST['Action'] == 'PassPortSubmit') {
	switch ($_POST['isPublic']) {
	case '2':
		if ($S_Row['tokenCode'] != $_POST['tokenCode']) {
			_shownotes($lang['system_error'], $lang['error_token_code'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		}
		else {
			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			_showsucceed($lang['input_token_code'], 'a.php?qname=' . $S_Row['surveyName'] . '&qlang=' . strtolower($language));
		}

		break;

	case '0':
		switch ($_POST['isUseOriPassport']) {
		case '1':
			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$ajaxRtnValueList = explode('***', $_POST['ajaxRtnValue']);
			$UserSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $ajaxRtnValueList[3] . '\' ';
			$UserRow = $DB->queryFirstRow($UserSQL);
			$_SESSION['userName'] = trim($UserRow['administratorsName']);

			if ($_POST['remberme'] == 1) {
				setcookie('membersName', escape(iconv('GBK', 'UTF-8', trim($UserRow['administratorsName']))), time() + 2592000);
			}

			$_SESSION['userGroupID'] = $ajaxRtnValueList[0];
			$_SESSION['userGroupName'] = $ajaxRtnValueList[1];
			$ajaxRtnValue = explode('#', $ajaxRtnValueList[2]);

			if (6 < count($ajaxRtnValue)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($ajaxRtnValue);
			}

			$_SESSION['ajaxCount'] = $ajaxCount;
			$ajaxRtnValueCate = array();
			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$j = $i + 1;
				$ajaxRtnValueSign = explode('=', $ajaxRtnValue[$i]);
				$ajaxRtnValueCate[] = $ajaxRtnValueSign[0];
				$_SESSION['ajaxRtnValue_' . $j] = $ajaxRtnValueSign[1];
			}

			if ($S_Row['ajaxRtnValue'] == '') {
				$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' ';
				$DB->query($SQL);
			}

			unset($ajaxRtnValueList);
			unset($ajaxRtnValueCate);
			break;

		case '2':
			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$_SESSION['userName'] = trim($_POST['username']);
			break;

		case '3':
		case '5':
			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$_SESSION['userName'] = trim($_POST['ajaxRtnValue']);
			break;
		}

		_showsucceed($lang['sure_passport'], 'a.php?qname=' . $S_Row['surveyName'] . '&qlang=' . strtolower($language));
		break;
	}
}

switch ($S_Row['isPublic']) {
case '2':
	if ($_SESSION['passPort_' . $S_Row['surveyID']] != true) {
		$EnableQCoreClass->setTemplateFile('ShowTokenFile', 'AjaxToken.html');
		$ShowToken = $EnableQCoreClass->parse('ShowToken', 'ShowTokenFile');
		$ShowPage = _getdftplfile($S_Row['panelID'], $S_Row['surveyTitle'], $ShowToken, 1);
		echo $ShowPage;
		exit();
	}

	break;

case '0':
	if ($_SESSION['passPort_' . $S_Row['surveyID']] != true) {
		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			$EnableQCoreClass->setTemplateFile('AjaxLoginFile', 'AjaxLogin.html');
			$ConfigSQL = ' SELECT isNotRegister FROM ' . ADMINISTRATORSCONFIG_TABLE . ' ';
			$ConfigRow = $DB->queryFirstRow($ConfigSQL);

			if ($ConfigRow['isNotRegister'] == 1) {
				$EnableQCoreClass->replace('regURL', 'javascript:void(0); onclick=javascript:$.notification(\'' . $lang['no_get_passport'] . '\');');
				$EnableQCoreClass->replace('forURL', 'javascript:void(0); onclick=javascript:$.notification(\'' . $lang['no_get_passport'] . '\');');
				$EnableQCoreClass->replace('target', '_self');
			}
			else {
				$EnableQCoreClass->replace('regURL', 'r.php?qlang=' . strtolower($language) . '&qid=' . $S_Row['surveyID']);
				$EnableQCoreClass->replace('forURL', 'g.php?qlang=' . strtolower($language) . '&qid=' . $S_Row['surveyID']);
				$EnableQCoreClass->replace('target', '_self');
			}

			$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
			break;

		case '2':
			$EnableQCoreClass->setTemplateFile('AjaxLoginFile', 'AjaxAjaxLogin.html');
			$EnableQCoreClass->replace('data_base64_enable', $Config['data_base64_enable']);
			$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
			$EnableQCoreClass->replace('is_post_method', $BaseRow['isPostMethod']);
			$EnableQCoreClass->replace('isMd5Pass', $BaseRow['isMd5Pass']);
			$_SESSION['hash'] = md5(trim($BaseRow['license']));
			break;

		case '3':
			$EnableQCoreClass->setTemplateFile('AjaxLoginFile', 'AjaxAdLogin.html');
			$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
			$EnableQCoreClass->replace('accountSuffix', $BaseRow['accountSuffix']);
			break;

		case '4':
			exit();
			break;

		case '5':
			$EnableQCoreClass->setTemplateFile('AjaxLoginFile', 'AjaxLDAPLogin.html');
			$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
			break;
		}

		$ShowToken = $EnableQCoreClass->parse('AjaxLogin', 'AjaxLoginFile');
		$ShowPage = _getdftplfile($S_Row['panelID'], $S_Row['surveyTitle'], $ShowToken, 1);
		echo $ShowPage;
		exit();
	}

	break;
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
$SQL = ' SELECT questionType,questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$QutResult = $DB->query($SQL);

while ($QutRow = $DB->queryArray($QutResult)) {
	if ($QutRow['questionType'] != '8') {
		$MoudleName = $Module[$QutRow['questionType']];
		$questionID = $QutRow['questionID'];

		if ($MoudleName != '') {
			require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.delete.inc.php';
		}
	}

	$SQL = ' DELETE FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT status FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$SurRow = $DB->queryFirstRow($SQL);

if ($SurRow['status'] != '0') {
	$SQL = ' DROP TABLE IF EXISTS ' . $table_prefix . 'response_' . $survey_ID . ' ';
	$DB->query($SQL);
}

$dSQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$dRow = $DB->queryFirstRow($dSQL);

if ($dRow['custDataPath'] == '') {
	$surveyPhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $survey_ID . '/';
	$vSurveyPhyPath = 'response_' . $survey_ID . '/';
}
else {
	$surveyPhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $dRow['custDataPath'] . '/';
	$vSurveyPhyPath = 'user/' . $dRow['custDataPath'] . '/';
}

if (is_dir($surveyPhyPath)) {
	require_remote_service(2, base64_encode($vSurveyPhyPath));
	deletedir($surveyPhyPath);
}

$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . QUOTA_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . COUNTYEARNUM_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . COUNTMONTHNUM_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . COUNTDAYNUM_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . VIEWUSERLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . AWARDPRODUCT_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . AWARDLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
$SQL = ' SELECT isPublic,custLogo,msgImage,AppId FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$D_S_Row = $DB->queryFirstRow($SQL);
$SQL = ' SELECT isUseOriPassport,ajaxDeleteURL,ajaxTokenURL,license,isPostMethod FROM ' . BASESETTING_TABLE . ' ';
$Base_Row = $DB->queryFirstRow($SQL);
if (($D_S_Row['isPublic'] == '0') && ($Base_Row['isUseOriPassport'] == '2') && (trim($Base_Row['ajaxDeleteURL']) != '')) {
	$hashCode = md5(trim($Base_Row['license']));
	$token = '';

	if (trim($Base_Row['ajaxTokenURL']) != '') {
		if ($Base_Row['isPostMethod'] == 1) {
			$postData = array();
			$postData['hash'] = $hashCode;
			$token = post_gbk_data_to_host(trim($Base_Row['ajaxTokenURL']), $postData);
		}
		else {
			if (strpos(trim($Base_Row['ajaxTokenURL']), '?') === false) {
				$ajaxURL = trim($Base_Row['ajaxTokenURL']) . '?hash=' . $hashCode;
			}
			else {
				$ajaxURL = trim($Base_Row['ajaxTokenURL']) . '&hash=' . $hashCode;
			}

			$token = get_url_content($ajaxURL);
		}
	}

	if ($Base_Row['isPostMethod'] == 1) {
		$postData = array();
		$postData['surveyID'] = $survey_ID;
		$postData['hash'] = $hashCode;
		$sign = md5('surveyID=' . $survey_ID . '&hash=' . $hashCode . '&' . $token);
		$postData['sign'] = $sign;
		post_gbk_data_to_host(trim($Base_Row['ajaxDeleteURL']), $postData);
	}
	else {
		$sign = md5('surveyID=' . $survey_ID . '&hash=' . $hashCode . '&' . $token);

		if (strpos(trim($Base_Row['ajaxDeleteURL']), '?') === false) {
			$ajaxURL = trim($Base_Row['ajaxDeleteURL']) . '?surveyID=' . $survey_ID . '&hash=' . $hashCode . '&sign=' . $sign;
		}
		else {
			$ajaxURL = trim($Base_Row['ajaxDeleteURL']) . '&surveyID=' . $survey_ID . '&hash=' . $hashCode . '&sign=' . $sign;
		}

		$File = fopen($ajaxURL, 'r');
		fclose($File);
	}
}

if (trim($D_S_Row['custLogo']) != '') {
	$logoPath = $Config['absolutenessPath'] . '/PerUserData/logo/';

	if (file_exists($logoPath . trim($D_S_Row['custLogo']))) {
		@unlink($logoPath . trim($D_S_Row['custLogo']));
	}
}

if (trim($D_S_Row['msgImage']) != '') {
	$imgPath = $Config['absolutenessPath'] . '/PerUserData/logo/';

	if (file_exists($imgPath . trim($D_S_Row['msgImage']))) {
		@unlink($imgPath . trim($D_S_Row['msgImage']));
	}
}

$SQL = ' DELETE FROM ' . ALLOWIP_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . GRADE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . SURVEYPROOF_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . SURVEYCATELIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . QUERY_LIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . QUERY_COND_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ANDROID_LIST_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ANDROID_INFO_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ANDROID_PUSH_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . DATA_TRACE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . DATA_TASK_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . TRACKCODE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ISSUERULE_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);
deletedir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $survey_ID . '/');
deletedir(ROOT_PATH . 'PerUserData/p/d' . $survey_ID . '/');
$theUniCodeFile = ROOT_PATH . 'PerUserData/unicode/' . md5('uniCode' . $survey_ID) . '.php';

if (file_exists($theUniCodeFile)) {
	@unlink($theUniCodeFile);
}

$theCookieFile = ROOT_PATH . 'PerUserData/cookie/' . md5('blackCookie' . $survey_ID) . '.php';

if (file_exists($theCookieFile)) {
	@unlink($theCookieFile);
}

$theCookieFile = ROOT_PATH . 'PerUserData/cookie/' . md5('whiteCookie' . $survey_ID) . '.php';

if (file_exists($theCookieFile)) {
	@unlink($theCookieFile);
}

$theHongBaoFile = ROOT_PATH . 'PerUserData/hongbao/' . md5('hongbao' . trim($D_S_Row['AppId']) . $survey_ID) . '.php';

if (file_exists($theHongBaoFile)) {
	@unlink($theHongBaoFile);
}

$theHongBaoFile = ROOT_PATH . 'PerUserData/hongbao/' . md5('apiclient_cert_' . trim($D_S_Row['AppId']) . $survey_ID) . '.pem';

if (file_exists($theHongBaoFile)) {
	@unlink($theHongBaoFile);
}

$theHongBaoFile = ROOT_PATH . 'PerUserData/hongbao/' . md5('apiclient_key_' . trim($D_S_Row['AppId']) . $survey_ID) . '.pem';

if (file_exists($theHongBaoFile)) {
	@unlink($theHongBaoFile);
}

$theHongBaoFile = ROOT_PATH . 'PerUserData/hongbao/' . md5('rootca_' . trim($D_S_Row['AppId']) . $survey_ID) . '.pem';

if (file_exists($theHongBaoFile)) {
	@unlink($theHongBaoFile);
}

$SQL = ' DELETE FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $survey_ID . '\' ';
$DB->query($SQL);

?>

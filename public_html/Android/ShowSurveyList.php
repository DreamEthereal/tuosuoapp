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
header('Content-Type:text/html; charset=gbk');

if ($License['isPanel'] != 1) {
	exit($lang['license_error'] . ':' . $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uAndroidList.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$BaseSQL = ' SELECT * FROM ' . BASESETTING_TABLE . ' ';
$BaseRow = $DB->queryFirstRow($BaseSQL);

switch ($BaseRow['isUseOriPassport']) {
case 4:
	$SQL = ' SELECT a.surveyID,a.surveyName,a.surveyTitle,a.beginTime,a.surveyInfo,a.surveySubTitle,a.lang FROM ' . SURVEY_TABLE . ' a,' . ANDROID_LIST_TABLE . ' b WHERE a.surveyID = b.surveyID AND a.status =1 AND a.endTime > \'' . date('Y-m-d') . '\' AND a.isPublic=1 ORDER BY a.beginTime DESC ';
	break;

default:
	$SQL = ' SELECT a.surveyID,a.surveyName,a.surveyTitle,a.beginTime,a.surveyInfo,a.surveySubTitle,a.lang FROM ' . SURVEY_TABLE . ' a,' . ANDROID_LIST_TABLE . ' b WHERE a.surveyID = b.surveyID AND a.status =1 AND a.endTime > \'' . date('Y-m-d') . '\' ORDER BY a.beginTime DESC ';
	break;
}

$Result = $DB->query($SQL);
$totalNum = 0;

while ($Row = $DB->queryArray($Result)) {
	$surveyURL = '../a.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'];
	$EnableQCoreClass->replace('surveyURL', $surveyURL);
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$HSQL = ' SELECT COUNT(*) as replyNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
	$HRow = $DB->queryFirstRow($HSQL);
	$EnableQCoreClass->replace('replyNum', $HRow['replyNum']);
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$HSQL = ' SELECT a.responseID FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' a,' . ANDROID_INFO_TABLE . ' b WHERE a.responseID = b.responseID AND b.deviceId = \'' . $_POST['theDeviceId'] . '\' AND b.surveyID = \'' . $Row['surveyID'] . '\' AND a.overFlag !=0 LIMIT 1 ';
	$HRow = $DB->queryFirstRow($HSQL);
	if (($HRow['responseID'] != 0) && ($HRow['responseID'] != '')) {
		continue;
	}
	else {
		$totalNum++;
		$EnableQCoreClass->parse('list', 'LIST', true);
	}
}

$EnableQCoreClass->replace('totalNum', $totalNum);
$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;
exit();

?>

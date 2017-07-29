<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(4);
$thisProg = 'DownSurveyList.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'OfflineDown.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$EnableQCoreClass->replace('siteTitle', $Config['siteName']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);
$EnableQCoreClass->replace('cacheDirectory', $Config['cacheDirectory']);
$EnableQCoreClass->replace('filePathName', hash('md5', date('ymdHis') . rand(1, 99999999) . $_SESSION['administratorsID'] . session_id()));
$SQL = ' SELECT a.surveyID,a.surveyTitle,a.beginTime FROM ' . SURVEY_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.surveyID = b.surveyID AND a.status =1 AND a.beginTime <= \'' . date('Y-m-d') . '\' AND b.administratorsID =\'' . $_SESSION['administratorsID'] . '\' ORDER BY a.beginTime DESC,a.surveyID DESC ';
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);
$thisSurveyID = '';
$theOptionOdNum = 0;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('theOptionOdNum', $theOptionOdNum);
	$theOptionOdNum++;
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$thisSurveyID .= $Row['surveyID'] . ',';
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$EnableQCoreClass->parse('list', 'LIST', true);
}

$EnableQCoreClass->replace('thisSurveyID', substr($thisSurveyID, 0, -1));
$EnableQCoreClass->replace('userId', $_SESSION['administratorsID']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);
$EnableQCoreClass->replace('nickUserName', $_SESSION['administratorsName']);

if ($License['isRealGps'] == 1) {
	$SQL = ' SELECT isRealGps FROM ' . BASESETTING_TABLE . ' ';
	$Row = $DB->queryFirstRow($SQL);
	if ($Row && ($Row['isRealGps'] == 1)) {
		$getNewLoctURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);
		$getNewLoctURL .= 'Android/GetNewLocation.php';
		$EnableQCoreClass->replace('getNewLoctURL', $getNewLoctURL);
	}
	else {
		$EnableQCoreClass->replace('getNewLoctURL', '');
	}
}
else {
	$EnableQCoreClass->replace('getNewLoctURL', '');
}

$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;
exit();

?>

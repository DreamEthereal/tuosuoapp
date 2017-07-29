<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(4);
$thisProg = 'CheckServerVersion.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'OfflineVersion.html');
$EnableQCoreClass->replace('userId', $_SESSION['administratorsID']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);
$EnableQCoreClass->replace('nickUserName', $_SESSION['administratorsName']);

if ($License['isRealGps'] == 1) {
	$SQL = ' SELECT isRealGps FROM ' . BASESETTING_TABLE . ' ';
	$Row = $DB->queryFirstRow($SQL);
	if ($Row && ($Row['isRealGps'] == 1)) {
		$getNewLoctURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -30);
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

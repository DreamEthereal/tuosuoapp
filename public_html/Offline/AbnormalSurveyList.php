<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(4);
$thisProg = 'AbnormalSurveyList.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->replace('fileNameUnit', hash('md5', date('ymdHis') . '_' . rand(1, 99999999) . '_' . $_SESSION['administratorsID'] . '_' . session_id()));
$EnableQCoreClass->setTemplateFile('SurveyListFile', 'OfflineAbnormal.html');
$EnableQCoreClass->replace('userId', $_SESSION['administratorsID']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);
$EnableQCoreClass->replace('userName', $_SESSION['administratorsName']);
$EnableQCoreClass->replace('absPath', str_replace('\\', '/', $Config['absolutenessPath']));
$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;
exit();

?>

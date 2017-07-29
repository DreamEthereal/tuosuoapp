<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(4);
$thisProg = 'DeleSurveyList.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'OfflineDelete.html');
$EnableQCoreClass->replace('userId', $_SESSION['administratorsID']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);
$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;
exit();

?>

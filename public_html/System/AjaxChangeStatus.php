<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$_GET['status'] = (int) $_GET['status'];
_checkpassport('1|2|5', $_GET['surveyID']);
header('Content-Type:text/html; charset=gbk');

if ($_GET['status'] == '1') {
	$theDeploySurveyID = $_GET['surveyID'];
	$isAjaxActionFlag = 1;
	require ROOT_PATH . 'System/Survey.deploy.php';
}

$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=\'' . $_GET['status'] . '\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$DB->query($SQL);
require ROOT_PATH . 'Export/Database.opti.sql.php';
if (isset($_GET['type']) && ($_GET['type'] == 1)) {
	writetolog($lang['changestatus_ajax'] . ':' . base64_decode($_GET['surveyTitle']));
	echo $lang['changestatus_ajax'] . ':' . base64_decode($_GET['surveyTitle']);
}
else {
	writetolog($lang['changestatus_ajax'] . ':' . $_GET['surveyTitle']);
	echo $lang['changestatus_ajax'] . ':' . $_GET['surveyTitle'];
}


?>

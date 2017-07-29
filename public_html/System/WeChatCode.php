<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.pic.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5|6', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sure_Row = $DB->queryFirstRow($SQL);

switch ($Sure_Row['status']) {
case '0':
	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&beginTime=' . urlencode($Sure_Row['beginTime']) . '&endTime=' . urlencode($Sure_Row['endTime']) . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
	}

	$EnableQCoreClass->replace('isDeployStat', 'none');
	break;

case '1':
	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']) . '&beginTime=' . $Sure_Row['beginTime'] . '&endTime=' . $Sure_Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
	}

	if ($Sure_Row['projectType'] == 1) {
		$EnableQCoreClass->replace('isDeployStat', 'none');
	}
	else {
		$EnableQCoreClass->replace('isDeployStat', '');
	}

	break;

case '2':
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('planURL', '');
	$EnableQCoreClass->replace('haveTask', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	break;
}

switch ($_SESSION['adminRoleType']) {
case 6:
	$EnableQCoreClass->replace('isAdmin6', 'none');
	$EnableQCoreClass->replace('havePlan', 'none');
	break;

default:
	$EnableQCoreClass->replace('isAdmin6', '');
	break;
}

$thisProg = 'WeChatCode.php?surveyID=' . $Sure_Row['surveyID'];
$EnableQCoreClass->replace('surveyID', $Sure_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sure_Row['surveyTitle']));
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('thisURL', $thisProg . '?' . $thisURLStr);

if ($_POST['Action'] == 'EditWeChatSubmit') {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET AppId = \'' . trim($_POST['AppId']) . '\',AppSecret = \'' . trim($_POST['AppSecret']) . '\',isOnlyWeChat = \'' . $_POST['isOnlyWeChat'] . '\',getChatUserInfo = \'' . $_POST['getChatUserInfo'] . '\',getChatUserMode = \'' . $_POST['getChatUserMode'] . '\' ';

	if ($_FILES['msgImage']['name'] != '') {
		$imgPath = $Config['absolutenessPath'] . '/PerUserData/logo/';
		createdir($imgPath);
		$SupportUploadFileType = 'jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG';
		$imgSQL = _picfileupload('msgImage', $SupportUploadFileType, $imgPath, $isEdit = true);
		$SQL .= $imgSQL;
	}

	$SQL .= ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	writetolog('问卷微信分发设置');
	_showsucceed('问卷微信分发设置', $thisProg);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'WeChatCode.html');
$SQL = ' SELECT AppId,AppSecret,isOnlyWeChat,getChatUserInfo,getChatUserMode,msgImage FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('AppId', $Row['AppId']);
$EnableQCoreClass->replace('AppSecret', $Row['AppSecret']);
$EnableQCoreClass->replace('isOnlyWeChat', $Row['isOnlyWeChat'] == 1 ? 'checked' : '');
$EnableQCoreClass->replace('getChatUserInfo', $Row['getChatUserInfo'] == 1 ? 'checked' : '');
$EnableQCoreClass->replace('getChatUserMode_2', $Row['getChatUserMode'] == 2 ? 'checked' : '');
$EnableQCoreClass->replace('getChatUserMode_1', $Row['getChatUserMode'] == 1 ? 'checked' : '');
$EnableQCoreClass->replace('msgImage', $Row['msgImage']);

if ($Row['msgImage'] == '') {
	$EnableQCoreClass->replace('msgImageFile', '<font color=red>无图片文件</font>');
}
else {
	$EnableQCoreClass->replace('msgImageFile', '<a href="../PerUserData/logo/' . trim($Row['msgImage']) . '" target=_blank>' . trim($Row['msgImage']) . '</a>');
}

$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
$EnableQCoreClass->output('SurveyList');

?>

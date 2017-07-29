<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,surveyID,surveyTitle,surveyName,beginTime,endTime,projectType,projectOwner,lang,isPublic FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sure_Row = $DB->queryFirstRow($SQL);

switch ($Sure_Row['status']) {
case '0':
	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&beginTime=' . urlencode($Sure_Row['beginTime']) . '&endTime=' . urlencode($Sure_Row['endTime']) . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	$EnableQCoreClass->replace('isDeployStat', 'none');
	break;

case '1':
	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']) . '&beginTime=' . $Sure_Row['beginTime'] . '&endTime=' . $Sure_Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isDeployStat', 'none');
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isDeployStat', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	break;

case '2':
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('planURL', '');
	$EnableQCoreClass->replace('haveTask', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;
}

$EnableQCoreClass->replace('isAdmin6', '');
$thisProg = 'TrackCode.php';
$EnableQCoreClass->replace('surveyID', $Sure_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sure_Row['surveyTitle']));
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('thisURL', $thisProg . '?' . $thisURLStr);

if ($_POST['Action'] == 'TrackControlSubmit') {
	$thePostNum = count($_POST);
	$theNewPostNum = count(array_unique($_POST));

	if ($thePostNum != $theNewPostNum) {
		_showerror('一致性错误', '一致性错误：您定义的曝光Cookie变量名称中存在重复的变量名称!');
	}

	$SQL = ' SELECT surveyID FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		$SQL = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET exposureDomain = \'' . trim($_POST['exposureDomain']) . '\',trackBeginTime = \'' . trim($_POST['trackBeginTime']) . '\',trackEndTime = \'' . trim($_POST['trackEndTime']) . '\',exposureCampaign = \'' . trim($_POST['exposureCampaign']) . '\',firstExposureCampaign = \'' . trim($_POST['firstExposureCampaign']) . '\',lastExposureCampaign = \'' . trim($_POST['lastExposureCampaign']) . '\',exposureControl = \'' . trim($_POST['exposureControl']) . '\',firstExposureControl = \'' . trim($_POST['firstExposureControl']) . '\',lastExposureControl = \'' . trim($_POST['lastExposureControl']) . '\',exposureNormal = \'' . trim($_POST['exposureNormal']) . '\',firstExposureNormal = \'' . trim($_POST['firstExposureNormal']) . '\',lastExposureNormal = \'' . trim($_POST['lastExposureNormal']) . '\' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	}
	else {
		$SQL = ' INSERT INTO ' . COUNTGENERALINFO_TABLE . ' SET surveyID = \'' . $_POST['surveyID'] . '\',exposureDomain = \'' . trim($_POST['exposureDomain']) . '\',trackBeginTime = \'' . trim($_POST['trackBeginTime']) . '\',trackEndTime = \'' . trim($_POST['trackEndTime']) . '\',exposureCampaign = \'' . trim($_POST['exposureCampaign']) . '\',firstExposureCampaign = \'' . trim($_POST['firstExposureCampaign']) . '\',lastExposureCampaign = \'' . trim($_POST['lastExposureCampaign']) . '\',exposureControl = \'' . trim($_POST['exposureControl']) . '\',firstExposureControl = \'' . trim($_POST['firstExposureControl']) . '\',lastExposureControl = \'' . trim($_POST['lastExposureControl']) . '\',exposureNormal = \'' . trim($_POST['exposureNormal']) . '\',firstExposureNormal = \'' . trim($_POST['firstExposureNormal']) . '\',lastExposureNormal = \'' . trim($_POST['lastExposureNormal']) . '\' ';
	}

	$DB->query($SQL);
	$surveyID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/ExposureCache.php';
	require ROOT_PATH . 'Includes/IssueCache.php';
	writetolog('曝光控制和曝光变量定义:' . trim($_POST['surveyTitle']));
	_showsucceed('曝光控制和曝光变量定义:' . trim($_POST['surveyTitle']), $thisProg . '?surveyID=' . $_GET['surveyID']);
}

if ($_GET['Action'] == 'DeleteTrack') {
	$SQL = ' DELETE FROM ' . TRACKCODE_TABLE . ' WHERE tagID=\'' . $_GET['tagID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ISSUERULE_TABLE . ' WHERE exposureVar=\'' . $_GET['tagID'] . '\' ';
	$DB->query($SQL);
	$surveyID = (int) $_GET['surveyID'];
	require ROOT_PATH . 'Includes/ExposureCache.php';
	require ROOT_PATH . 'Includes/IssueCache.php';
	writetolog('删除曝光代码定义:' . trim($_GET['tagName']));
	_showsucceed('删除曝光代码定义:' . trim($_GET['tagName']), $thisProg . '?surveyID=' . $_GET['surveyID']);
}

if ($_POST['Action'] == 'EditTrackCodeSumbit') {
	$SQL = ' SELECT tagID FROM ' . TRACKCODE_TABLE . ' WHERE (exposure = \'' . trim($_POST['exposure']) . '\' OR firstExposure = \'' . trim($_POST['firstExposure']) . '\' OR lastExposure = \'' . trim($_POST['lastExposure']) . '\') AND surveyID=\'' . $_POST['surveyID'] . '\' AND tagID != \'' . $_POST['tagID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror('一致性错误', '一致性错误：系统可能已存在您定义的用以记录该创意的Cookie变量名称!');
	}

	$SQL = ' UPDATE ' . TRACKCODE_TABLE . ' SET tagName = \'' . trim($_POST['tagName']) . '\',tagCate = \'' . trim($_POST['tagCate']) . '\',exposure = \'' . trim($_POST['exposure']) . '\',firstExposure = \'' . trim($_POST['firstExposure']) . '\',lastExposure = \'' . trim($_POST['lastExposure']) . '\' WHERE tagID=\'' . $_POST['tagID'] . '\' ';
	$DB->query($SQL);
	$surveyID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/ExposureCache.php';
	require ROOT_PATH . 'Includes/IssueCache.php';
	writetolog('编辑曝光代码定义:' . trim($_POST['tagName']));
	_showmessage('编辑曝光代码定义:' . trim($_POST['tagName']), true);
}

if ($_GET['Action'] == 'EditTrack') {
	$EnableQCoreClass->setTemplateFile('SurveyListFile', 'TrackCodeEdit.html');
	$SQL = ' SELECT * FROM ' . TRACKCODE_TABLE . ' WHERE tagID = \'' . $_GET['tagID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('tagName', $Row['tagName']);
	$EnableQCoreClass->replace('exposure', $Row['exposure']);
	$EnableQCoreClass->replace('firstExposure', $Row['firstExposure']);
	$EnableQCoreClass->replace('lastExposure', $Row['lastExposure']);
	$EnableQCoreClass->replace('tagID', $Row['tagID']);
	$EnableQCoreClass->replace('tagCate_' . $Row['tagCate'], 'selected');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('Action', 'EditTrackCodeSumbit');
	$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
	$EnableQCoreClass->output('SurveyList');
}

if ($_POST['Action'] == 'AddTrackCodeSumbit') {
	$SQL = ' SELECT tagID FROM ' . TRACKCODE_TABLE . ' WHERE (exposure = \'' . trim($_POST['exposure']) . '\' OR firstExposure = \'' . trim($_POST['firstExposure']) . '\' OR lastExposure = \'' . trim($_POST['lastExposure']) . '\') AND surveyID=\'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror('一致性错误', '一致性错误：系统可能已存在您定义的用以记录该创意的Cookie变量名称!');
	}

	$SQL = ' INSERT INTO ' . TRACKCODE_TABLE . ' SET tagName = \'' . trim($_POST['tagName']) . '\',tagCate = \'' . trim($_POST['tagCate']) . '\',exposure = \'' . trim($_POST['exposure']) . '\',firstExposure = \'' . trim($_POST['firstExposure']) . '\',lastExposure = \'' . trim($_POST['lastExposure']) . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	$surveyID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/ExposureCache.php';
	writetolog('新增曝光代码定义:' . trim($_POST['tagName']));
	_showmessage('新增曝光代码定义:' . trim($_POST['tagName']), true);
}

if ($_GET['Action'] == 'NewTrack') {
	$EnableQCoreClass->setTemplateFile('SurveyListFile', 'TrackCodeEdit.html');
	$EnableQCoreClass->replace('tagName', '');
	$EnableQCoreClass->replace('exposure', '');
	$EnableQCoreClass->replace('firstExposure', '');
	$EnableQCoreClass->replace('lastExposure', '');
	$EnableQCoreClass->replace('tagID', '');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('Action', 'AddTrackCodeSumbit');
	$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
	$EnableQCoreClass->output('SurveyList');
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'TrackCode.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$SQL = ' SELECT * FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['exposureDomain'] == '') {
	if (preg_match('/^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$/', $_SERVER['HTTP_HOST'])) {
		$exposureDomain = '';
	}
	else {
		$theExposureDomain = explode('.', $_SERVER['HTTP_HOST']);
		array_shift($theExposureDomain);
		$exposureDomain = '.' . implode('.', $theExposureDomain);
	}
}
else {
	$exposureDomain = $Row['exposureDomain'];
}

$EnableQCoreClass->replace('exposureDomain', $exposureDomain);
$EnableQCoreClass->replace('trackBeginTime', $Row['trackBeginTime']);
$EnableQCoreClass->replace('trackEndTime', $Row['trackEndTime']);
$EnableQCoreClass->replace('exposureCampaign', $Row['exposureCampaign']);
$EnableQCoreClass->replace('firstExposureCampaign', $Row['firstExposureCampaign']);
$EnableQCoreClass->replace('lastExposureCampaign', $Row['lastExposureCampaign']);
$EnableQCoreClass->replace('exposureControl', $Row['exposureControl']);
$EnableQCoreClass->replace('firstExposureControl', $Row['firstExposureControl']);
$EnableQCoreClass->replace('lastExposureControl', $Row['lastExposureControl']);
$EnableQCoreClass->replace('exposureNormal', $Row['exposureNormal']);
$EnableQCoreClass->replace('firstExposureNormal', $Row['firstExposureNormal']);
$EnableQCoreClass->replace('lastExposureNormal', $Row['lastExposureNormal']);
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -20);

if ($Config['dataDomainName'] != '') {
	$fullPath = 'http://' . $Config['dataDomainName'] . '/';
}
else {
	$fullPath = $All_Path;
}

$SQL = ' SELECT * FROM ' . TRACKCODE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY tagCate DESC,tagID DESC ';
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalRecNum', $totalNum);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('tagID', $Row['tagID']);
	$EnableQCoreClass->replace('tagCate', $Row['tagCate'] == 1 ? '曝光组' : '控制组');
	$EnableQCoreClass->replace('tagName', $Row['tagName']);
	$EnableQCoreClass->replace('tagNameURL', urlencode($Row['tagName']));
	$EnableQCoreClass->replace('exposure', 'c_' . $Row['surveyID'] . '_' . $Row['exposure']);
	$EnableQCoreClass->replace('firstExposure', 'c_' . $Row['surveyID'] . '_' . $Row['firstExposure']);
	$EnableQCoreClass->replace('lastExposure', 'c_' . $Row['surveyID'] . '_' . $Row['lastExposure']);
	$cid = $Row['surveyID'] . '|' . $Row['tagID'];
	$trackCode = $fullPath . 'RepData/Campaign.php?cid=' . $cid . '&ord=[randnum]';
	$EnableQCoreClass->replace('trackCode', $trackCode);
	$EnableQCoreClass->parse('list', 'LIST', true);
}

$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
$EnableQCoreClass->output('SurveyList');

?>

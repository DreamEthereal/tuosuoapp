<?php
//dezend by http://www.yunlu99.com/
function tag_2_html($html)
{
	$html = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $html);
	return $html;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
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

switch ($_SESSION['adminRoleType']) {
case 6:
	$EnableQCoreClass->replace('isAdmin6', 'none');
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;

default:
	$EnableQCoreClass->replace('isAdmin6', '');
	break;
}

$thisProg = 'GetSurveyCode.php';
$EnableQCoreClass->replace('surveyID', $Sure_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sure_Row['surveyTitle']));
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('thisURL', $thisProg . '?' . $thisURLStr);

if ($_GET['Action'] == 'DownloadHTMLFile') {
	$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
	$SerialRow = $DB->queryFirstRow($SQL);
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -24);
	$surveyPageURL = $All_Path . 'Archive/SurveyPageArchive.php?qname=' . $_GET['qname'] . '&qlang=' . $_GET['qlang'] . '&hash_code=' . md5(trim($SerialRow['license']));
	$pageFileContent = get_url_content($surveyPageURL);
	header('Content-type: application/octet-stream;charset=utf8');
	header('Accept-Ranges: bytes');
	header('Content-Disposition: attachment; filename=survey_page_html_' . $_GET['qname'] . '.html');
	echo $pageFileContent;
	exit();
}

if ($_GET['Action'] == 'GetCode') {
	if ($_GET['surveyID'] == '0') {
		exit('||');
	}

	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -24);

	if ($Config['dataDomainName'] != '') {
		$fullPath = 'http://' . $Config['dataDomainName'] . '/';
	}
	else {
		$fullPath = $All_Path;
	}

	if ($S_Row['lang'] != 'cn') {
		$Code0 = $fullPath . 'q.php?qname=' . $S_Row['surveyName'] . '&qlang=' . $S_Row['lang'];
	}
	else {
		$Code0 = $fullPath . 'q.php?qname=' . $S_Row['surveyName'];
	}

	$Code1 = '<iframe src="' . $Code0 . '" width="100%" height="100%" frameborder="0" scrolling="auto"></iframe>';

	if ($S_Row['isPublic'] == '1') {
		$Code2 = '<a href="?Action=DownloadHTMLFile&qname=' . $S_Row['surveyName'] . '&surveyID=' . $_GET['surveyID'] . '&qlang=' . $S_Row['lang'] . '"><b>' . $lang['down_html_survey'] . '</b></a>';
	}
	else {
		$Code2 = '<font color=red>' . $lang['no_html_survey'] . '</font>';
	}

	$Code3 = '<script type="text/javascript" src="' . $fullPath . 'JS/PopWindow.js.php"></script>';
	$Code4 = '<a href="javascript:void(0);" onclick="javascript:showPopWin(\'' . $Code0 . '\', 800, 500, null, null,\'' . $S_Row['surveyTitle'] . '\');">' . $S_Row['surveyTitle'] . '</a>';
	header('Content-Type:text/html; charset=gbk');
	exit(tag_2_html($Code0 . '|' . $Code1) . '|' . $Code2 . '|' . tag_2_html($Code3) . '<br/><br/>' . tag_2_html($Code4));
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'GetSurveyCode.html');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -24);

if ($Config['dataDomainName'] != '') {
	$fullPath = 'http://' . $Config['dataDomainName'] . '/';
}
else {
	$fullPath = $All_Path;
}

if ($Sure_Row['lang'] != 'cn') {
	$Code0 = $fullPath . 'q.php?qname=' . $Sure_Row['surveyName'] . '&qlang=' . $Sure_Row['lang'];
}
else {
	$Code0 = $fullPath . 'q.php?qname=' . $Sure_Row['surveyName'];
}

include_once ROOT_PATH . 'Includes/QRcode.php';
$QR_FileName_Path = $Config['absolutenessPath'] . '/PerUserData/tmp/';
createdir($QR_FileName_Path);
$QR_FileName = $QR_FileName_Path . 'QR_' . $Sure_Row['surveyName'] . '.png';

if (file_exists($QR_FileName)) {
	@unlink($QR_FileName);
}

include_once ROOT_PATH . 'Config/QRConfig.inc.php';
QRcode::png($Code0, $QR_FileName, $Config['correctionLevel'], $Config['matrixPointSize'], 2);
$EnableQCoreClass->replace('QR_Code', '<img src="' . ROOT_PATH . 'PerUserData/tmp/QR_' . $Sure_Row['surveyName'] . '.png" border=0>');
$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
$EnableQCoreClass->output('SurveyList');

?>

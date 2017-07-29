<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,isPublic,surveyName,ajaxRtnValue,mainShowQtn,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$thisProg = 'PanelArea.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->setTemplateFile('ResultAreaFile', 'ResultArea.html');
$EnableQCoreClass->set_CycBlock('ResultAreaFile', 'COUNTAREA', 'countarea');
$EnableQCoreClass->replace('countarea', '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

if (isset($_POST['dataSource'])) {
	$_SESSION['dataSource' . $_GET['surveyID']] = $_POST['dataSource'];
}

if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
	$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
}
else {
	$dataSource = getdatasourcesql(0, $_GET['surveyID']);
}

$SQL = ' SELECT COUNT(responseID) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
$Row = $DB->queryFirstRow($SQL);
$totalResponseNum = $Row['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $Row['totalResponseNum']);
$areaNumTotal = $authStat1NumTotal = $authStat2NumTotal = $authStat3NumTotal = $authStat0NumTotal = 0;
$SQL = ' SELECT area,count(*) AS areaNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
$SQL .= ' GROUP BY area ORDER BY areaNum DESC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if ($Row['area'] == '') {
		$EnableQCoreClass->replace('areaName', $lang['unknow_area']);
	}
	else {
		$EnableQCoreClass->replace('areaName', $Row['area']);
	}

	$EnableQCoreClass->replace('areaNum', $Row['areaNum']);
	$areaNumTotal += $Row['areaNum'];
	$areaPercent = countpercent($Row['areaNum'], $totalResponseNum);
	$EnableQCoreClass->replace('areaPercent', $areaPercent);
	$authStat0Num = $authStat1Num = $authStat2Num = $authStat3Num = 0;
	$hSQL = ' SELECT authStat,COUNT(*) as authStatNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE area = \'' . $Row['area'] . '\' AND ' . $dataSource;
	$hSQL .= ' GROUP BY authStat ORDER BY authStat DESC ';
	$hResult = $DB->query($hSQL);

	while ($hRow = $DB->queryArray($hResult)) {
		switch ($hRow['authStat']) {
		case '0':
			$authStat0Num += $hRow['authStatNum'];
			$authStat0NumTotal += $hRow['authStatNum'];
			break;

		case '1':
			$authStat1Num += $hRow['authStatNum'];
			$authStat1NumTotal += $hRow['authStatNum'];
			break;

		case '2':
			$authStat2Num += $hRow['authStatNum'];
			$authStat2NumTotal += $hRow['authStatNum'];
			break;

		default:
			$authStat3Num += $hRow['authStatNum'];
			$authStat3NumTotal += $hRow['authStatNum'];
			break;
		}
	}

	$EnableQCoreClass->replace('authStat0Num', $authStat0Num);
	$EnableQCoreClass->replace('authStat1Num', $authStat1Num);
	$EnableQCoreClass->replace('authStat2Num', $authStat2Num);
	$EnableQCoreClass->replace('authStat3Num', $authStat3Num);
	$EnableQCoreClass->parse('countarea', 'COUNTAREA', true);
}

$EnableQCoreClass->replace('areaNumTotal', $areaNumTotal);
$EnableQCoreClass->replace('authStat0NumTotal', $authStat0NumTotal);
$EnableQCoreClass->replace('authStat1NumTotal', $authStat1NumTotal);
$EnableQCoreClass->replace('authStat2NumTotal', $authStat2NumTotal);
$EnableQCoreClass->replace('authStat3NumTotal', $authStat3NumTotal);
$EnableQCoreClass->parse('ResultArea', 'ResultAreaFile');
$EnableQCoreClass->output('ResultArea');

?>

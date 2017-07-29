<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Config/DBConfig.inc.php';
$cidArray = explode('|', trim($_GET['cid']));
$surveyID = (int) $cidArray[0];
$tagID = (int) $cidArray[1];

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('exposure' . $surveyID) . '.php')) {
	require_once ROOT_PATH . 'Entry/Global.mysql.php';
	require ROOT_PATH . 'Includes/ExposureCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('exposure' . $surveyID) . '.php';
$nowTime = time();
$beginTime = explode('-', $exposureArray[$tagID]['trackBeginTime']);
$beginUnixTime = mktime($beginTime[3], $beginTime[4], 0, $beginTime[1], $beginTime[2], $beginTime[0]);
$endTime = explode('-', $exposureArray[$tagID]['trackEndTime']);
$endUnixTime = mktime($endTime[3], $endTime[4], 0, $endTime[1], $endTime[2], $endTime[0]);
if (($nowTime < $beginUnixTime) || ($endUnixTime < $nowTime)) {
	exit();
}

if (!isset($_COOKIE[$exposureArray[$tagID]['exposure']]) || ($_COOKIE[$exposureArray[$tagID]['exposure']] == 0)) {
	setcookie($exposureArray[$tagID]['exposure'], 1, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}
else {
	$newNum = $_COOKIE[$exposureArray[$tagID]['exposure']] + 1;
	setcookie($exposureArray[$tagID]['exposure'], $newNum, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}

if (!isset($_COOKIE[$exposureArray[$tagID]['firstExposure']]) || ($_COOKIE[$exposureArray[$tagID]['firstExposure']] == '')) {
	setcookie($exposureArray[$tagID]['firstExposure'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}

setcookie($exposureArray[$tagID]['lastExposure'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
if (!isset($_COOKIE[$exposureArray[$tagID]['exposureCampaign']]) || ($_COOKIE[$exposureArray[$tagID]['exposureCampaign']] == 0)) {
	setcookie($exposureArray[$tagID]['exposureCampaign'], 1, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}
else {
	$newNum = $_COOKIE[$exposureArray[$tagID]['exposureCampaign']] + 1;
	setcookie($exposureArray[$tagID]['exposureCampaign'], $newNum, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}

if (!isset($_COOKIE[$exposureArray[$tagID]['firstExposureCampaign']]) || ($_COOKIE[$exposureArray[$tagID]['firstExposureCampaign']] == '')) {
	setcookie($exposureArray[$tagID]['firstExposureCampaign'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}

setcookie($exposureArray[$tagID]['lastExposureCampaign'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);

if ($exposureArray[$tagID]['tagCate'] == 1) {
	if (!isset($_COOKIE[$exposureArray[$tagID]['exposureNormal']]) || ($_COOKIE[$exposureArray[$tagID]['exposureNormal']] == 0)) {
		setcookie($exposureArray[$tagID]['exposureNormal'], 1, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
	}
	else {
		$newNum = $_COOKIE[$exposureArray[$tagID]['exposureNormal']] + 1;
		setcookie($exposureArray[$tagID]['exposureNormal'], $newNum, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
	}

	if (!isset($_COOKIE[$exposureArray[$tagID]['firstExposureNormal']]) || ($_COOKIE[$exposureArray[$tagID]['firstExposureNormal']] == '')) {
		setcookie($exposureArray[$tagID]['firstExposureNormal'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
	}

	setcookie($exposureArray[$tagID]['lastExposureNormal'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}
else {
	if (!isset($_COOKIE[$exposureArray[$tagID]['exposureControl']]) || ($_COOKIE[$exposureArray[$tagID]['exposureControl']] == 0)) {
		setcookie($exposureArray[$tagID]['exposureControl'], 1, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
	}
	else {
		$newNum = $_COOKIE[$exposureArray[$tagID]['exposureControl']] + 1;
		setcookie($exposureArray[$tagID]['exposureControl'], $newNum, $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
	}

	if (!isset($_COOKIE[$exposureArray[$tagID]['firstExposureControl']]) || ($_COOKIE[$exposureArray[$tagID]['firstExposureControl']] == '')) {
		setcookie($exposureArray[$tagID]['firstExposureControl'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
	}

	setcookie($exposureArray[$tagID]['lastExposureControl'], date('Y-m-d H:i:s'), $endUnixTime, '/', $exposureArray[$tagID]['exposureDomain']);
}

exit();

?>

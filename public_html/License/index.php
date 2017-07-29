<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.free.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
$EnableQCoreClass->setTemplateFile('LicenseFile', 'LicenseIndex.html');
$EnableQCoreClass->replace('ServerAddress', _getserveripaddress());

if ($License['Limited'] == 0) {
	$EnableQCoreClass->replace('licenseNum', $lang['no_limited_soft']);
}
else {
	$EnableQCoreClass->replace('licenseNum', $License['LimitedNum']);
}

$EnableQCoreClass->replace('version', $Config['version']);
$EnableQCoreClass->replace('pubTime', $Config['pubTime']);
$EnableQCoreClass->replace('MasterAddress', $License['MasterAddress']);
$EnableQCoreClass->replace('MinorAddress', $License['MinorAddress']);
$EnableQCoreClass->replace('UserCompany', $License['UserCompany']);
$EnableQCoreClass->replace('platform', $lang['ver_Advance']);

if ($License['LimitedTime'] == 'N/A') {
	$EnableQCoreClass->replace('LimitedTime', $lang['no_limited_soft']);
}
else if ($License['isEvalUsers'] == 1) {
	$SQL = ' SELECT licensetime FROM ' . BASESETTING_TABLE . ' LIMIT 1 ';
	$SerialRow = $DB->queryFirstRow($SQL);

	if (!$SerialRow) {
		$SQL = ' SELECT joinTime FROM ' . SURVEY_TABLE . ' ORDER BY joinTime ASC LIMIT 0,1 ';
		$TimeRow = $DB->queryFirstRow($SQL);

		if (!$TimeRow) {
			$TimeNow = time();
		}
		else {
			$TimeNow = $TimeRow['joinTime'];
		}

		$AfterEncTime = base64_encode(base64_encode($TimeNow));
		$SQL = ' UPDATE ' . BASESETTING_TABLE . ' SET licensetime = \'' . $AfterEncTime . '\' ';
		$DB->query($SQL);
	}
	else {
		$TimeNow = base64_decode(base64_decode($SerialRow['licensetime']));
	}

	$EnableQCoreClass->replace('LimitedTime', date('Y-m-d', $TimeNow + (86400 * $License['LimitedTime'])));
}
else {
	$EnableQCoreClass->replace('LimitedTime', $License['LimitedTime']);
}

if ($License['ADPassport'] == 1) {
	$EnableQCoreClass->replace('activeLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('activeLicense', $lang['no_addon_license']);
}

if ($License['AjaxPassport'] == 1) {
	$EnableQCoreClass->replace('ajaxLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('ajaxLicense', $lang['no_addon_license']);
}

if ($License['isMobile'] == 1) {
	$EnableQCoreClass->replace('mobileLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('mobileLicense', $lang['no_addon_license']);
}

if ($License['isCustLogo'] == 1) {
	$EnableQCoreClass->replace('custLogoLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('custLogoLicense', $lang['no_addon_license']);
}

if ($License['isDistribution'] == 1) {
	$EnableQCoreClass->replace('distributionLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('distributionLicense', $lang['no_addon_license']);
}

if ($License['isPanel'] == 1) {
	$EnableQCoreClass->replace('panelLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('panelLicense', $lang['no_addon_license']);
}

if ($License['isOnline'] == 1) {
	$EnableQCoreClass->replace('onlineLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('onlineLicense', $lang['no_addon_license']);
}

if ($License['isOffline'] == 1) {
	$EnableQCoreClass->replace('offlineLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('offlineLicense', $lang['no_addon_license']);
}

if ($License['isMonitor'] == 1) {
	$EnableQCoreClass->replace('monitorLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('monitorLicense', $lang['no_addon_license']);
}

if ($License['isCell'] == 1) {
	$EnableQCoreClass->replace('cellLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('cellLicense', $lang['no_addon_license']);
}

if ($License['isRealGps'] == 1) {
	$EnableQCoreClass->replace('realGpsLicense', $lang['have_addon_license']);
}
else {
	$EnableQCoreClass->replace('realGpsLicense', $lang['no_addon_license']);
}

$EnableQCoreClass->parse('License', 'LicenseFile');
$EnableQCoreClass->output('License');

?>

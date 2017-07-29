<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
$thisProg = 'CheckNewVersion.php';
_checkroletype('1');
$EnableQCoreClass->setTemplateFile('NewVersionFile', 'NewVersion.html');
$EnableQCoreClass->replace('userVersion', str_replace('EnableQ ', '', $Config['version']));
$EnableQCoreClass->replace('pubTime', $Config['pubTime']);
$theNewVersionInfo = get_url_content('http://www.enableq.com/pub/version.txt');

if ($theNewVersionInfo == '') {
	$EnableQCoreClass->replace('userVersion0', '<font color=red>无法获取最新版本号</font>');
	$EnableQCoreClass->replace('pubTime0', '<font color=red>无法获取最新发布时间</font>');
	$EnableQCoreClass->replace('haveNewVersion', 'none');
	$EnableQCoreClass->replace('noHaveNewVersion', '');
}
else {
	$theNewVersionList = explode('######', trim($theNewVersionInfo));
	$EnableQCoreClass->replace('userVersion0', $theNewVersionList[0]);
	$EnableQCoreClass->replace('pubTime0', $theNewVersionList[1]);

	if (str_replace('EnableQ V', '', $Config['version']) < str_replace('V', '', $theNewVersionList[0])) {
		$EnableQCoreClass->replace('haveNewVersion', '');
		$EnableQCoreClass->replace('noHaveNewVersion', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveNewVersion', 'none');
		$EnableQCoreClass->replace('noHaveNewVersion', '');
	}
}

$NewVersionPage = $EnableQCoreClass->parse('NewVersionPage', 'NewVersionFile');
echo $NewVersionPage;
exit();

?>

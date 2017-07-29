<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(1);
$thisProg = 'BaseSetting.php';
$SQL = ' SELECT * FROM ' . BASESETTING_TABLE . ' ';
$Row = $DB->queryFirstRow($SQL);

if ($_POST['Action'] == 'MailSettingSubmit') {
	if ($Row) {
		$SQL = ' UPDATE ' . BASESETTING_TABLE . ' SET sendFrom=\'' . $_POST['sendFrom'] . '\',sendName=\'' . $_POST['sendName'] . '\',mailServer=\'' . $_POST['mailServer'] . '\',smtp25=\'' . $_POST['smtp25'] . '\',smtpName=\'' . $_POST['smtpName'] . '\',isSmtp=\'' . $_POST['isSmtp'] . '\' ';

		if ($_POST['smtpPassword'] != '') {
			$SQL .= ' ,smtpPassword=\'' . $_POST['smtpPassword'] . '\' ';
		}
	}
	else {
		$SQL = ' INSERT INTO ' . BASESETTING_TABLE . ' SET sendFrom=\'' . $_POST['sendFrom'] . '\',sendName=\'' . $_POST['sendName'] . '\',mailServer=\'' . $_POST['mailServer'] . '\',smtp25=\'' . $_POST['smtp25'] . '\',smtpName=\'' . $_POST['smtpName'] . '\',smtpPassword=\'' . $_POST['smtpPassword'] . '\',isSmtp=\'' . $_POST['isSmtp'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['setting_base']);
	_showsucceed($lang['setting_base'], $thisProg);
}

if ($_POST['Action'] == 'GpsSettingSubmit') {
	if ($Row) {
		$SQL = ' UPDATE ' . BASESETTING_TABLE . ' SET isRealGps=\'' . $_POST['isRealGps'] . '\' ';
	}
	else {
		$SQL = ' INSERT INTO ' . BASESETTING_TABLE . ' SET isRealGps=\'' . $_POST['isRealGps'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['setting_base']);
	_showsucceed($lang['setting_base'], $thisProg);
}

if ($_POST['Action'] == 'BaseSettingSubmit') {
	if ($Row) {
		$SQL = ' UPDATE ' . BASESETTING_TABLE . ' SET isUseOriPassport=\'' . $_POST['isUseOriPassport'] . '\',isUseCookie=\'' . $_POST['isUseCookie'] . '\',userID=\'' . $_POST['userID'] . '\',userName=\'' . $_POST['userName'] . '\',registerURL=\'' . $_POST['registerURL'] . '\',loginURL=\'' . $_POST['loginURL'] . '\' ';

		if ($_POST['isUseOriPassport'] == '2') {
			if ($License['AjaxPassport'] != '1') {
				_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['advance_license']);
			}

			$SQL .= ' ,ajaxResponseURL=\'' . $_POST['ajaxResponseURL'] . '\',ajaxCheckURL=\'' . $_POST['ajaxCheckURL'] . '\',ajaxOverURL=\'' . $_POST['ajaxOverURL'] . '\',ajaxLoginURL=\'' . $_POST['ajaxLoginURL'] . '\',ajaxDeleteURL=\'' . $_POST['ajaxDeleteURL'] . '\',isMd5Pass=\'' . $_POST['isMd5Pass'] . '\',isPostMethod=\'' . $_POST['isPostMethod'] . '\',ajaxTokenURL=\'' . $_POST['ajaxTokenURL'] . '\' ';
		}

		if ($_POST['isUseOriPassport'] == '3') {
			if ($License['ADPassport'] != '1') {
				_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['advance_license']);
			}

			$SQL .= ' ,domainControllers=\'' . $_POST['domainControllers'] . '\',adUsername=\'' . $_POST['adUsername'] . '\',accountSuffix=\'' . $_POST['accountSuffix'] . '\',baseDN=\'' . $_POST['baseDN'] . '\' ';

			if ($_POST['adPassword'] != '') {
				$SQL .= ' ,adPassword=\'' . $_POST['adPassword'] . '\' ';
			}
		}

		if ($_POST['isUseOriPassport'] == '5') {
			if ($License['ADPassport'] != '1') {
				_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['advance_license']);
			}

			$SQL .= ' ,domainControllers=\'' . $_POST['domainControllers'] . '\',adUsername=\'' . $_POST['adUsername'] . '\',baseDN=\'' . $_POST['baseDN'] . '\' ';

			if ($_POST['adPassword'] != '') {
				$SQL .= ' ,adPassword=\'' . $_POST['adPassword'] . '\' ';
			}
		}
	}
	else {
		$SQL = ' INSERT INTO ' . BASESETTING_TABLE . ' SET isUseOriPassport=\'' . $_POST['isUseOriPassport'] . '\',isUseCookie=\'' . $_POST['isUseCookie'] . '\',userID=\'' . $_POST['userID'] . '\',userName=\'' . $_POST['userName'] . '\',registerURL=\'' . $_POST['registerURL'] . '\',loginURL=\'' . $_POST['loginURL'] . '\' ';

		if ($_POST['isUseOriPassport'] == '2') {
			if ($License['AjaxPassport'] != '1') {
				_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['advance_license']);
			}

			$SQL .= ' ,ajaxResponseURL=\'' . $_POST['ajaxResponseURL'] . '\',ajaxCheckURL=\'' . $_POST['ajaxCheckURL'] . '\',ajaxOverURL=\'' . $_POST['ajaxOverURL'] . '\',ajaxLoginURL=\'' . $_POST['ajaxLoginURL'] . '\',ajaxDeleteURL=\'' . $_POST['ajaxDeleteURL'] . '\',isMd5Pass=\'' . $_POST['isMd5Pass'] . '\',isPostMethod=\'' . $_POST['isPostMethod'] . '\',ajaxTokenURL=\'' . $_POST['ajaxTokenURL'] . '\' ';
		}

		if ($_POST['isUseOriPassport'] == '3') {
			if ($License['ADPassport'] != '1') {
				_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['advance_license']);
			}

			$SQL .= ' ,domainControllers=\'' . $_POST['domainControllers'] . '\',adUsername=\'' . $_POST['adUsername'] . '\',accountSuffix=\'' . $_POST['accountSuffix'] . '\',adPassword=\'' . $_POST['adPassword'] . '\',baseDN=\'' . $_POST['baseDN'] . '\' ';
		}

		if ($_POST['isUseOriPassport'] == '5') {
			if ($License['ADPassport'] != '1') {
				_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['advance_license']);
			}

			$SQL .= ' ,domainControllers=\'' . $_POST['domainControllers'] . '\',adUsername=\'' . $_POST['adUsername'] . '\',adPassword=\'' . $_POST['adPassword'] . '\',baseDN=\'' . $_POST['baseDN'] . '\' ';
		}
	}

	$DB->query($SQL);
	writetolog($lang['setting_base']);
	_showsucceed($lang['setting_base'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('BaseSettingFile', 'BaseSetting.html');

if ($License['isRealGps'] == 1) {
	$isRealGps = ($Row['isRealGps'] == 1 ? 'checked' : '');
	$EnableQCoreClass->replace('isRealGps', $isRealGps);
}
else {
	$EnableQCoreClass->replace('isRealGps', 'disabled');
}

$EnableQCoreClass->replace('registerURL', $Row['registerURL']);
$EnableQCoreClass->replace('loginURL', $Row['loginURL']);
$EnableQCoreClass->replace('userID', $Row['userID']);
$EnableQCoreClass->replace('userName', $Row['userName']);
$EnableQCoreClass->replace('ajaxTokenURL', $Row['ajaxTokenURL']);
$EnableQCoreClass->replace('ajaxResponseURL', $Row['ajaxResponseURL']);
$EnableQCoreClass->replace('ajaxCheckURL', $Row['ajaxCheckURL']);
$EnableQCoreClass->replace('ajaxLoginURL', $Row['ajaxLoginURL']);
$isMd5Pass = ($Row['isMd5Pass'] == 1 ? 'checked' : '');
$EnableQCoreClass->replace('isMd5Pass', $isMd5Pass);
$isPostMethod = ($Row['isPostMethod'] == 1 ? 'checked' : '');
$EnableQCoreClass->replace('isPostMethod', $isPostMethod);
$EnableQCoreClass->replace('ajaxDeleteURL', $Row['ajaxDeleteURL']);
$EnableQCoreClass->replace('ajaxOverURL', $Row['ajaxOverURL']);
$EnableQCoreClass->replace('domainControllers', $Row['domainControllers']);
$EnableQCoreClass->replace('adUsername', $Row['adUsername']);
$EnableQCoreClass->replace('accountSuffix', $Row['accountSuffix']);
$EnableQCoreClass->replace('adPassword', '');
$EnableQCoreClass->replace('baseDN', $Row['baseDN']);
$EnableQCoreClass->replace('isUseOriPassport' . $Row['isUseOriPassport'], 'selected');

if ($Row['isUseCookie'] == '2') {
	$EnableQCoreClass->replace('isUseCookie', 'selected');
}

$EnableQCoreClass->replace('sendFrom', $Row['sendFrom']);
$EnableQCoreClass->replace('sendName', $Row['sendName']);
$EnableQCoreClass->replace('mailServer', $Row['mailServer']);

if ($Row['smtp25'] != '') {
	$EnableQCoreClass->replace('smtp25', $Row['smtp25']);
}
else {
	$EnableQCoreClass->replace('smtp25', '25');
}

$EnableQCoreClass->replace('smtpName', $Row['smtpName']);
$EnableQCoreClass->replace('smtpPassword', '');

if ($Row['isSmtp'] == 'n') {
	$EnableQCoreClass->replace('isSmtp', 'selected');
}

$EnableQCoreClass->replace('thisProg', $thisProg);

if ($Row) {
	$EnableQCoreClass->replace('password_notes', $lang['password_notes']);
}
else {
	$EnableQCoreClass->replace('password_notes', '');
}

$baseSettingPage = $EnableQCoreClass->parse('BaseSetting', 'BaseSettingFile');

if ($Row) {
	$baseSettingPage = preg_replace('/<!-- CHECK PASSWORD -->(.*)<!-- END PASSWORD -->/s', '', $baseSettingPage);
	$baseSettingPage = preg_replace('/<!-- CHECK PASSWORD1 -->(.*)<!-- END PASSWORD1 -->/s', '', $baseSettingPage);
	$baseSettingPage = preg_replace('/<!-- CHECK PASSWORD2 -->(.*)<!-- END PASSWORD2 -->/s', '', $baseSettingPage);
}

echo $baseSettingPage;
exit();

?>

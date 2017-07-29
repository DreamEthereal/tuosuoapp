<?php
//dezend by http://www.yunlu99.com/
function write_to_file($file_name, $data, $method = 'w')
{
	$_obf_wWFgG_EIcg__ = fopen($file_name, $method);
	flock($_obf_wWFgG_EIcg__, LOCK_EX);
	$_obf_fPX93OEFX6y0 = fwrite($_obf_wWFgG_EIcg__, $data);
	fclose($_obf_wWFgG_EIcg__);
	return $_obf_fPX93OEFX6y0;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxHongBao.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'];
_checkpassport('1|2|5', $_GET['surveyID']);
$EnableQCoreClass->replace('thisURL', $thisProg);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$theHongBaoPath = ROOT_PATH . 'PerUserData/hongbao/';

if (!is_dir($theHongBaoPath)) {
	mkdir($theHongBaoPath, 511);
}

if ($_POST['Action'] == 'HongBaoDataSumbit') {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET AppId = \'' . trim($_POST['wxappid']) . '\' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	$apiclient_cert_name = md5('apiclient_cert_' . trim($_POST['wxappid']) . $_POST['surveyID']) . '.pem';
	$apiclient_key_name = md5('apiclient_key_' . trim($_POST['wxappid']) . $_POST['surveyID']) . '.pem';
	$rootca_name = md5('rootca_' . trim($_POST['wxappid']) . $_POST['surveyID']) . '.pem';
	$filecontent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . '' . "\r\n" . '$WeChat[\'wxappid\'] = \'' . $_POST['wxappid'] . '\';' . "\r\n" . '$WeChat[\'mch_id\'] = \'' . $_POST['mch_id'] . '\';' . "\r\n" . '$WeChat[\'send_name\'] = \'' . $_POST['send_name'] . '\';' . "\r\n" . '$WeChat[\'nick_name\'] = \'' . $_POST['nick_name'] . '\';' . "\r\n" . '$WeChat[\'min_value\'] = \'' . $_POST['min_value'] . '\';' . "\r\n" . '$WeChat[\'max_value\'] = \'' . $_POST['max_value'] . '\';' . "\r\n" . '$WeChat[\'wishing\'] = \'' . $_POST['wishing'] . '\';' . "\r\n" . '$WeChat[\'act_name\'] = \'' . $_POST['act_name'] . '\';' . "\r\n" . '$WeChat[\'apikey\'] = \'' . $_POST['apikey'] . '\';' . "\r\n" . '$WeChat[\'apiclient_cert_name\'] = \'' . $apiclient_cert_name . '\';' . "\r\n" . '$WeChat[\'apiclient_key_name\'] = \'' . $apiclient_key_name . '\';' . "\r\n" . '$WeChat[\'rootca_name\'] = \'' . $rootca_name . '\';' . "\r\n" . '' . chr(13) . chr(63) . chr(62);
	write_to_file($theHongBaoPath . md5('hongbao' . trim($_POST['wxappid']) . $_POST['surveyID']) . '.php', $filecontent);

	if ($_FILES['apiclient_cert_file']['name'] != '') {
		$tmpExt = explode('.', $_FILES['apiclient_cert_file']['name']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		if (is_uploaded_file($_FILES['apiclient_cert_file']['tmp_name']) && ($extension == 'pem')) {
			copy($_FILES['apiclient_cert_file']['tmp_name'], $theHongBaoPath . $apiclient_cert_name);
		}
		else {
			_showerror($lang['error_system'], '您上传的API证书不是正确的pem文件扩展名');
		}
	}

	if ($_FILES['apiclient_key_file']['name'] != '') {
		$tmpExt = explode('.', $_FILES['apiclient_key_file']['name']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		if (is_uploaded_file($_FILES['apiclient_key_file']['tmp_name']) && ($extension == 'pem')) {
			copy($_FILES['apiclient_key_file']['tmp_name'], $theHongBaoPath . $apiclient_key_name);
		}
		else {
			_showerror($lang['error_system'], '您上传的API证书不是正确的pem文件扩展名');
		}
	}

	if ($_FILES['rootca_file']['name'] != '') {
		$tmpExt = explode('.', $_FILES['rootca_file']['name']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		if (is_uploaded_file($_FILES['rootca_file']['tmp_name']) && ($extension == 'pem')) {
			copy($_FILES['rootca_file']['tmp_name'], $theHongBaoPath . $rootca_name);
		}
		else {
			_showerror($lang['error_system'], '您上传的API证书不是正确的pem文件扩展名');
		}
	}

	if (trim($_POST['ori_appid']) != trim($_POST['wxappid'])) {
		if (file_exists($theHongBaoPath . md5('hongbao' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.php')) {
			@unlink($theHongBaoPath . md5('hongbao' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.php');
		}

		if (file_exists($theHongBaoPath . md5('apiclient_cert_' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.pem')) {
			@unlink($theHongBaoPath . md5('apiclient_cert_' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.pem');
		}

		if (file_exists($theHongBaoPath . md5('apiclient_key_' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.pem')) {
			@unlink($theHongBaoPath . md5('apiclient_key_' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.pem');
		}

		if (file_exists($theHongBaoPath . md5('rootca_' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.pem')) {
			@unlink($theHongBaoPath . md5('rootca_' . trim($_POST['ori_appid']) . $_POST['surveyID']) . '.pem');
		}
	}

	writetolog('预定义微信红包参数:' . trim($_GET['surveyTitle']));
	_showsucceed('预定义微信红包参数:' . trim($_GET['surveyTitle']), $thisProg);
}

$EnableQCoreClass->setTemplateFile('ProofFile', 'SurveyHongBao.html');
$SQL = ' SELECT AppId FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('wxappid', $Row['AppId']);
$theCacheFile = $theHongBaoPath . md5('hongbao' . $Row['AppId'] . $_GET['surveyID']) . '.php';

if (file_exists($theCacheFile)) {
	require $theCacheFile;
}

$EnableQCoreClass->replace('mch_id', $WeChat['mch_id']);
$EnableQCoreClass->replace('send_name', $WeChat['send_name']);
$EnableQCoreClass->replace('nick_name', $WeChat['nick_name']);
$EnableQCoreClass->replace('min_value', $WeChat['min_value']);
$EnableQCoreClass->replace('max_value', $WeChat['max_value']);
$EnableQCoreClass->replace('wishing', $WeChat['wishing']);
$EnableQCoreClass->replace('act_name', $WeChat['act_name']);
$EnableQCoreClass->replace('apikey', $WeChat['apikey']);

if (file_exists($theHongBaoPath . md5('apiclient_cert_' . $Row['AppId'] . $_GET['surveyID']) . '.pem')) {
	$EnableQCoreClass->replace('apiclient_cert_file', 1);
	$EnableQCoreClass->replace('apiclient_cert_info', '<font color=red>文件已存在，再次上传将覆盖</font>');
}
else {
	$EnableQCoreClass->replace('apiclient_cert_file', 0);
	$EnableQCoreClass->replace('apiclient_cert_info', '文件未上传');
}

if (file_exists($theHongBaoPath . md5('apiclient_key_' . $Row['AppId'] . $_GET['surveyID']) . '.pem')) {
	$EnableQCoreClass->replace('apiclient_key_file', 1);
	$EnableQCoreClass->replace('apiclient_key_info', '<font color=red>文件已存在，再次上传将覆盖</font>');
}
else {
	$EnableQCoreClass->replace('apiclient_key_file', 0);
	$EnableQCoreClass->replace('apiclient_key_info', '文件未上传');
}

if (file_exists($theHongBaoPath . md5('rootca_' . $Row['AppId'] . $_GET['surveyID']) . '.pem')) {
	$EnableQCoreClass->replace('rootca_file', 1);
	$EnableQCoreClass->replace('rootca_info', '<font color=red>文件已存在，再次上传将覆盖</font>');
}
else {
	$EnableQCoreClass->replace('rootca_file', 0);
	$EnableQCoreClass->replace('rootca_info', '文件未上传');
}

$EnableQCoreClass->parse('ProofPage', 'ProofFile');
$EnableQCoreClass->output('ProofPage');

?>

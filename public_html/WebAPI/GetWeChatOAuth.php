<?php
//dezend by http://www.yunlu99.com/
function get_access_token($app_id = '', $app_secret = '', $code = '')
{
	if ($app_id && $app_secret && $code) {
		$_obf_oWHDvmKuI4Wu = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $app_id . '&secret=' . $app_secret . '&code=' . $code . '&grant_type=authorization_code';
		$_obf_KjnX8fd_v_UBQg__ = https_request($_obf_oWHDvmKuI4Wu);
		return php_json_decode_main($_obf_KjnX8fd_v_UBQg__);
	}
}

function get_user_info($access_token = '', $open_id = '')
{
	if ($access_token && $open_id) {
		$_obf_vlTz7Xl233o_ = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $open_id . '&lang=zh_CN';
		$_obf_g3IWCiDT1cCs = https_request($_obf_vlTz7Xl233o_);
		return php_json_decode_main($_obf_g3IWCiDT1cCs);
	}
}

function https_request($url, $debug = false)
{
	$_obf_lZAO8Q__ = curl_init();
	curl_setopt($_obf_lZAO8Q__, CURLOPT_URL, $url);
	curl_setopt($_obf_lZAO8Q__, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($_obf_lZAO8Q__, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($_obf_lZAO8Q__, CURLOPT_RETURNTRANSFER, 1);
	$_obf_6RYLWQ__ = curl_exec($_obf_lZAO8Q__);
	curl_close($_obf_lZAO8Q__);
	return $_obf_6RYLWQ__;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$SQL = ' SELECT surveyID,AppId,AppSecret FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if (trim($_GET['code']) != '') {
	$access_token_array = get_access_token($S_Row['AppId'], $S_Row['AppSecret'], trim($_GET['code']));
	if (isset($access_token_array['errcode']) && ($access_token_array['errcode'] == '40029')) {
		header('Location:' . ROOT_PATH . 'q.php?' . $_SERVER['QUERY_STRING']);
		exit();
	}

	$access_token = $access_token_array['access_token'];
	$openid = $access_token_array['openid'];
	$userinfo = get_user_info($access_token, $openid);
	if (isset($userinfo['errcode']) && ($userinfo['errcode'] == '40003')) {
		header('Location:' . ROOT_PATH . 'q.php?' . $_SERVER['QUERY_STRING']);
		exit();
	}

	switch ($userinfo['sex']) {
	case 0:
	default:
		$usersex = 'Î´Öª';
		break;

	case 1:
		$usersex = 'ÄÐ';
		break;

	case 2:
		$usersex = 'Å®';
		break;
	}

	$_SESSION['userName'] = 'Î¢ÐÅ|' . iconv('UTF-8', 'gbk', $userinfo['nickname']) . '|' . $usersex . '|' . iconv('UTF-8', 'gbk', $userinfo['country']) . '|' . iconv('UTF-8', 'gbk', $userinfo['province']) . '|' . iconv('UTF-8', 'gbk', $userinfo['city']);
	$_SESSION['wechat_openid_' . $S_Row['surveyID']] = iconv('UTF-8', 'gbk', $userinfo['openid']);
	$_SESSION['WeChat_' . $S_Row['surveyID']] = true;
}

header('Location:' . ROOT_PATH . 'q.php?' . $_SERVER['QUERY_STRING']);
exit();

?>

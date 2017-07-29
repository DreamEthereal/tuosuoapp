<?php
//dezend by http://www.yunlu99.com/
function dateDiff($interval, $date1, $date2)
{
	$_obf_GikmP3L3bXixVsC0Ooo_ = $date2 - $date1;

	switch ($interval) {
	case 'w':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 604800);
		break;

	case 'd':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 86400);
		break;

	case 'h':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 3600);
		break;

	case 'n':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 60);
		break;

	case 's':
		$_obf_cG4B7L_a = $_obf_GikmP3L3bXixVsC0Ooo_;
		break;
	}

	return $_obf_cG4B7L_a;
}

function _getip()
{
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$_obf_zeUCF4yH = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$_obf_zeUCF4yH = $_SERVER['HTTP_CLIENT_IP'];
		}
		else {
			$_obf_zeUCF4yH = $_SERVER['REMOTE_ADDR'];
		}
	}
	else if (getenv('HTTP_X_FORWARDED_FOR')) {
		$_obf_zeUCF4yH = getenv('HTTP_X_FORWARDED_FOR');
	}
	else if (getenv('HTTP_CLIENT_IP')) {
		$_obf_zeUCF4yH = getenv('HTTP_CLIENT_IP');
	}
	else {
		$_obf_zeUCF4yH = getenv('REMOTE_ADDR');
	}

	return preg_replace('/&amp;((#(\\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), preg_replace('/%27/', '\\\'', addslashes($_obf_zeUCF4yH))));
}

function runcode($code)
{
	if ($code) {
		ob_start();
		eval ('echo ' . $code . ';');
		$contents = ob_get_contents();
		ob_end_clean();
	}

	return $contents;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Config/DBConfig.inc.php';
require_once ROOT_PATH . 'Entry/Global.mysql.php';
$surveyID = (int) trim($_GET['qid']);

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('issue' . $surveyID) . '.php')) {
	require ROOT_PATH . 'Includes/IssueCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('issue' . $surveyID) . '.php';
$issueFlag = true;
$nowTime = date('Y-m-d', time());
if (($nowTime < $issueArray['beginTime']) || ($issueArray['endTime'] < $nowTime)) {
	$issueFlag = false;
}

switch ($issueArray['isCheckIP']) {
case '1':
default:
	$SQL = ' SELECT submitTime FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE ipAddress=\'' . _getip() . '\' AND overFlag !=0 ORDER BY responseID DESC LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if (datediff('n', $HaveRow['submitTime'], time()) < $issueArray['maxIpTime']) {
		$issueFlag = false;
	}

	break;

case '2':
	if (($_COOKIE['enableqcheck' . $surveyID] != '') && ($_COOKIE['enableqcheck' . $surveyID] == md5($issueArray['surveyName'] . $surveyID))) {
		$issueFlag = false;
	}

	break;

case '0':
	break;
}

if ($issueArray['isOpen'] == 1) {
	if ($issueArray['rule'] != '') {
		if (!runcode(str_replace('_COOKIE', '$_COOKIE', $issueArray['rule']))) {
			$issueFlag = false;
		}
	}

	switch ($issueArray['issueMode']) {
	case '1':
		$theRandNumber = rand(1, 100);

		if ($issueArray['issueRate'] < $theRandNumber) {
			$issueFlag = false;
		}

		break;

	case '2':
		$theCookieFile = ROOT_PATH . 'PerUserData/cookie/' . md5('blackCookie' . $surveyID) . '.php';
		require $theCookieFile;

		if (in_array($_COOKIE[$issueArray['issueCookie']], $cookieKeyArray)) {
			$issueFlag = false;
		}
		else {
			$theRandNumber = rand(1, 100);

			if ($issueArray['issueRate'] < $theRandNumber) {
				$issueFlag = false;
			}
		}

		break;

	case '3':
		$theCookieFile = ROOT_PATH . 'PerUserData/cookie/' . md5('whiteCookie' . $surveyID) . '.php';
		require $theCookieFile;

		if (!in_array($_COOKIE[$issueArray['issueCookie']], $cookieKeyArray)) {
			$issueFlag = false;
		}
		else {
			$theRandNumber = rand(1, 100);

			if ($issueArray['issueRate'] < $theRandNumber) {
				$issueFlag = false;
			}
		}

		break;
	}
}

header('Content-Type:text/html; charset=gbk');

if ($issueFlag == true) {
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -17);

	if ($Config['dataDomainName'] != '') {
		$fullPath = 'http://' . $Config['dataDomainName'] . '/';
	}
	else {
		$fullPath = $All_Path;
	}

	echo 'document.write(\'<link href="';
	echo $fullPath;
	echo 'CSS/Window.css" rel="stylesheet" type="text/css"/>\');' . "\r\n" . 'document.write("<scr"+"ipt src=\\"';
	echo $fullPath;
	echo 'JS/Common.js.php\\"></sc"+"ript>");' . "\r\n" . 'document.write("<scr"+"ipt src=\\"';
	echo $fullPath;
	echo 'JS/Window.js.php?style=10\\"></sc"+"ript>");' . "\r\n" . 'document.write(\'';
	echo str_replace('\'', '\\\'', $issueArray['renderingCode']);
	echo '\');' . "\r\n" . '';
}

?>

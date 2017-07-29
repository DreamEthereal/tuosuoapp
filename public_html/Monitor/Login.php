<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();

if (version_compare(phpversion(), '5.3.0', '<')) {
	@set_magic_quotes_runtime(0);
}
else {
	ini_set('magic_quotes_runtime', 0);
}

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
	exit('EnableQ Security Violation');
}

require_once ROOT_PATH . 'Config/SystemConfig.inc.php';
require_once ROOT_PATH . 'Config/DBConfig.inc.php';
require_once ROOT_PATH . 'Config/LicenseConfig.inc.php';
include_once ROOT_PATH . 'Includes/EnableQCoreClass.class.php';
include_once ROOT_PATH . 'DB/DataBaseDefine.inc.php';
include_once ROOT_PATH . 'Functions/Functions.base.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
include_once ROOT_PATH . 'Functions/Functions.encrypt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.base.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
if (!MAGIC_QUOTES_GPC && $_GET) {
	$_GET = qaddslashes($_GET);
}

if (!MAGIC_QUOTES_GPC && $_COOKIE) {
	$_COOKIE = qaddslashes($_COOKIE);
}

if (!MAGIC_QUOTES_GPC && $_POST) {
	$_POST = qaddslashes($_POST);
}

$EnableQCoreClass = new EnableQCoreClass(ROOT_PATH . 'Templates/CN/', 'keep');

if ($Config['is_mysql_proxy'] == 1) {
	include_once ROOT_PATH . 'DB/Mysql.DBClass.proxy.php';
	$DB = new DB();
	$DB->connect($db_rw_server['db_host'], $db_rw_server['db_user'], $db_rw_server['db_pw'], $db_rw_server['db_name'], $db_rw_server['db_lang']);
	$DB->connect_ro($db_ro_server[0]['db_host'], $db_ro_server[0]['db_user'], $db_ro_server[0]['db_pw'], $db_ro_server[0]['db_name'], $db_ro_server[0]['db_lang']);
	$DB->set_ro_list($db_ro_server);
}
else {
	include_once ROOT_PATH . 'DB/Mysql.DBClass.php';
	if (isset($Config['encrypt']) && ($Config['encrypt'] == 1)) {
		$DB_password = decrypt($DB_password);
	}

	$DB = new DB($DB_host, $DB_user, $DB_password, $DB_name, $DB_lang);
}

if ($License['isMonitor'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

if (!isset($_SESSION['loginNum'])) {
	$_SESSION['loginNum'] = 0;
}

$fullIpAddress = _getip();
$Num = explode('.', $fullIpAddress);
$fullNum3IP = sprintf('%03s', $Num[0]) . '.' . sprintf('%03s', $Num[1]) . '.' . sprintf('%03s', $Num[2]) . '.' . sprintf('%03s', $Num[3]);
$SQL = ' SELECT DISTINCT ipAddress FROM  ' . BANNED_TABLE . ' ';
$Result = $DB->query($SQL);
$BannedList = array();

while ($bRow = $DB->queryArray($Result)) {
	$BannedList[] = '\'' . $bRow['ipAddress'] . '\'';
}

$SQL = ' SELECT DISTINCT ipAddress FROM  ' . WHITE_TABLE . ' ';
$Result = $DB->query($SQL);
$WhiteList = array();

while ($wRow = $DB->queryArray($Result)) {
	$WhiteList[] = '\'' . $wRow['ipAddress'] . '\'';
}

if (in_array('\'' . $fullIpAddress . '\'', $BannedList) && !in_array('\'' . $fullIpAddress . '\'', $WhiteList)) {
	_showerror($lang['error_system'], $lang['error_ip_banned'], false);
}

$SQL = ' SELECT isAllowIP FROM ' . BASESETTING_TABLE . ' ';
$BaseRow = $DB->queryFirstRow($SQL);

if ($BaseRow['isAllowIP'] == 1) {
	$SQL = ' SELECT startIP FROM ' . ALLOWIP_TABLE . ' WHERE \'' . $fullNum3IP . '\'>=startIP AND \'' . $fullNum3IP . '\'<=endIP AND surveyID=0 ORDER BY startIP ASC LIMIT 0,1 ';
	$IPRow = $DB->queryFirstRow($SQL);

	if (!$IPRow) {
		_showerror($lang['error_login'], $lang['error_ip_allow'], false);
	}
}

if ($_POST['Action'] == 'LoginSubmit') {
	header('Content-Type:text/html; charset=gbk');
	$administratorsName = iconv('UTF-8', 'GBK', trim($_POST['userName']));
	$passWord = trim($_POST['userPass']);
	$SQL = ' SELECT administratorsName,nickName,administratorsID,passWord,isActive,isAdmin,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . $administratorsName . '\' AND isAdmin !=0 LIMIT 0,1 ';
	$Login_Row = $DB->queryFirstRow($SQL);

	if (!$Login_Row) {
		$_SESSION['loginNum'] += 1;

		if ($Config['max_login_num'] < $_SESSION['loginNum']) {
			if (!in_array('\'' . $fullIpAddress . '\'', $BannedList) && !in_array('\'' . $fullIpAddress . '\'', $WhiteList)) {
				$SQL = ' INSERT INTO ' . BANNED_TABLE . ' SET ipAddress=\'' . $fullIpAddress . '\' ';
				$DB->query($SQL);
			}

			unset($_SESSION['loginNum']);
		}

		exit('false######' . $lang['error_login_username']);
	}
	else {
		if ($Login_Row['isActive'] != '1') {
			exit('false######' . $lang['error_login_active']);
		}

		if (($Login_Row['isAdmin'] == '6') || ($Login_Row['isAdmin'] == '4')) {
			exit('false######' . $lang['error_login_role']);
		}

		if ($passWord != $Login_Row['passWord']) {
			$_SESSION['loginNum'] += 1;

			if ($Config['max_login_num'] < $_SESSION['loginNum']) {
				$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive = 0 WHERE administratorsName = \'' . $Login_Row['administratorsName'] . '\' ';
				$DB->query($SQL);
				unset($_SESSION['loginNum']);
			}

			exit('false######' . $lang['error_login_password']);
		}
		else {
			$isLoginCheck = 1;
			require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

			if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
				exit('false######' . _skipitenablechar($lang['R82353783517DA1951018F2CE07568E40']));
			}

			$time = time();
			$timeDiff = $time - 7776000;
			$SQL = ' DELETE FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE createDate <=\'' . $timeDiff . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE actionTime <=\'' . $timeDiff . '\' ';
			$DB->query($SQL);
			require ROOT_PATH . 'Process/RenameFile.inc.php';
			$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET loginNum=loginNum+1,lastVisitTime=\'' . $time . '\',ipAddress = \'' . _getip() . '\' WHERE administratorsID=\'' . $Login_Row['administratorsID'] . '\' ';
			$DB->query($SQL);

			if ($_POST['remberme'] == 1) {
				setcookie('administratorsName', escape(iconv('GBK', 'UTF-8', $Login_Row['administratorsName'])), time() + 2592000);
			}

			$_SESSION['administratorsID'] = $Login_Row['administratorsID'];
			$_SESSION['administratorsName'] = $Login_Row['administratorsName'];
			$_SESSION['administratorsNickName'] = $Login_Row['nickName'];
			$_SESSION['adminRoleType'] = $Login_Row['isAdmin'];
			$_SESSION['adminRoleGroupID'] = $Login_Row['userGroupID'];
			$_SESSION['adminRoleGroupType'] = $Login_Row['groupType'];

			switch ($_SESSION['adminRoleType']) {
			case '5':
				if ($_SESSION['adminRoleGroupID'] == 0) {
					$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' ) ';
					$theSonGroup = array();
					$theSonGroup[] = 0;
					$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
					$sResult = $DB->query($sSQL);

					while ($sRow = $DB->queryArray($sResult)) {
						$theSonGroup[] = $sRow['userGroupID'];
					}

					$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin IN (2,5) AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 ';
					$thisSameGroupMembers = array();
					$Result = $DB->query($SQL);

					while ($UserRow = $DB->queryArray($Result)) {
						$thisSameGroupMembers[] = $UserRow['administratorsID'];
					}

					$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
				}
				else {
					$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
					$SQL = ' SELECT a.administratorsID FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin IN (2,5) AND a.userGroupID = b.userGroupID AND a.groupType=1 AND ' . $theSonSQL . ' ';
					$thisSameGroupMembers = array();
					$Result = $DB->query($SQL);

					while ($UserRow = $DB->queryArray($Result)) {
						$thisSameGroupMembers[] = $UserRow['administratorsID'];
					}

					$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
				}

				break;

			case '3':
			case '7':
				switch ($_SESSION['adminRoleGroupType']) {
				case 1:
					if ($_SESSION['adminRoleGroupID'] == 0) {
						$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' ) ';
						$theSonGroup = array();
						$theSonGroup[] = 0;
						$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
						$sResult = $DB->query($sSQL);

						while ($sRow = $DB->queryArray($sResult)) {
							$theSonGroup[] = $sRow['userGroupID'];
						}

						$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin NOT IN (0,3,6,7) AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 ';
						$thisSameGroupMembers = array();
						$Result = $DB->query($SQL);

						while ($UserRow = $DB->queryArray($Result)) {
							$thisSameGroupMembers[] = $UserRow['administratorsName'];
						}

						$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
					}
					else {
						$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
						$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,3,6,7) AND a.userGroupID = b.userGroupID AND a.groupType=1 AND ' . $theSonSQL . ' ';
						$thisSameGroupMembers = array();
						$Result = $DB->query($SQL);

						while ($UserRow = $DB->queryArray($Result)) {
							$thisSameGroupMembers[] = $UserRow['administratorsName'];
						}

						$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
					}

					break;

				case 2:
					$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
					$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND ' . $theSonSQL . ' ';
					$thisSameGroupMembers = array();
					$Result = $DB->query($SQL);

					while ($UserRow = $DB->queryArray($Result)) {
						$thisSameGroupMembers[] = $UserRow['userGroupID'];
					}

					$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
					break;
				}

				break;
			}

			if ($_POST['loginURL'] != '') {
				exit('true######' . $_POST['loginURL']);
			}
			else {
				exit('true######' . ROOT_PATH . 'Monitor/index.php');
			}
		}
	}
}

if (!isset($_GET['Action']) || empty($_GET['Action'])) {
	$pushURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -17) . 'Android/Push.php?type=1';
	echo '' . "\r\n" . '	<html>' . "\r\n" . '	<head>' . "\r\n" . '	<title>欢迎使用EnableQ在线问卷调查引擎 </title>' . "\r\n" . '	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">' . "\r\n" . '	<style>' . "\r\n" . '		@media screen and (max-device-width: 320px){body{-webkit-text-size-adjust:none}}' . "\r\n" . '		@media screen and (max-device-width: 480px){body{-webkit-text-size-adjust:none}}' . "\r\n" . '		@media only screen and (-webkit-min-device-pixel-ratio: 2){body{-webkit-text-size-adjust:none}}' . "\r\n" . '		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){body{-webkit-text-size-adjust:none}}' . "\r\n" . '	</style>' . "\r\n" . '	<META http-equiv=Content-Type content="text/html; charset=gbk">' . "\r\n" . '	<LINK rel="shortcut icon" href="../Images/enableq.ico" type="image/x-icon" />' . "\r\n" . '	<LINK href="../CSS/Android.css" type=text/css rel=stylesheet>' . "\r\n" . '	<LINK href="../CSS/LoginBox.css" rel=stylesheet>' . "\r\n" . '	<style>' . "\r\n" . '		td {margin:5px}' . "\r\n" . '	   input,textarea, select {' . "\r\n" . '		   font-size: 15px;' . "\r\n" . '		   border: 1px solid #555;' . "\r\n" . '		   padding: 0.5em;' . "\r\n" . '		   line-height: 1.2em;' . "\r\n" . '		   background: #e5e5e5;' . "\r\n" . '		   -webkit-appearance: none;' . "\r\n" . '		   -webkit-box-shadow: 1px 1px 1px #fff;' . "\r\n" . '		   -webkit-border-radius: 0.5em;' . "\r\n" . '	   }' . "\r\n" . '	   input[type=checkbox]{' . "\r\n" . '		   font-size: 15px;' . "\r\n" . '		   width: 1.25em;' . "\r\n" . '		   height: 1.25em;' . "\r\n" . '		   line-height: 1em;' . "\r\n" . '		   margin: 0 0.25em 0 0;' . "\r\n" . '		   padding: 0;' . "\r\n" . '		   border: 1px solid #555;' . "\r\n" . '		   -webkit-appearance: none;' . "\r\n" . '		   -webkit-border-radius: 0.25em;' . "\r\n" . '		   vertical-align: text-top;' . "\r\n" . '	   }' . "\r\n" . '	   input[type=checkbox]:checked {' . "\r\n" . '		   background: #7b807d url("data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%008%00%00%008%08%02%00%00%00\'%E4%ACI%00%00%00%19tEXtSoftware%00Adobe%20ImageReadyq%C9e%3C%00%00%04DIDATx%DA%EC%98%B9J%2CA%14%86%EF%F4%AC%8E%5B%24%F8%00%93%BA%83%89%89%A1%22n%A1%81%89%B8%22%82%2B%06%A2%E2%82%B8%20%1A%A9(%B8%BC%87%8F%E0%12%09%3E%82K%AA%20%EAu%EEo%FFw%0EEuO%2F3%A3%5C%2F%FD%07E%F5Lu%F7%D7%FF9u%BA%BA%22%A9T%EA%D7O%90%F1%EB%87(%00%0D%40%03%D0%004%00%0D%40%03%D0%FF%1B4%F2%3D%B7I%A7%D3%B6%BF%87B%A1%7F%05T%105V%22%F2G%2F%B8%91oC%D4%40qH%3E%B4%D2w%CA%D1%D0%D7H%F3%89%94%86aloo%0F%0C%0C%D8%BA%EB%7C%C1H8%1C%FE%22%2F%D3%19%FD%B5%C40677%BB%BB%BB%D1%8F%C5b%7B%7B%7B%B6%8F%975%F4%BE%40%ADs%C2zu%8D%F2%E3%E3%03-%EE%B2%B1%B1AJhjj%0A\'%EE%EF%EF%A3%C5%03xa%8D%409%F09g%95%20B%18%06%CA%F5%F5u%A1%A4%26\'\'qk%F8%8A%7FU%DC%AC%A0%D1h%D4c%1C%1D%1C%95%1Bp%00%11y%08%8E%B5%B5%B5%AE%AE.%EB%95%9B%9A%9A%8E%8F%8F1Re%CD%11T%8B%23%95L%26%CB%CA%CA%EE%EE%EE%D4y%23%E5F%CCF%07%9E%AD%AC%ACtvvZ%AF%7Cyy944%04J%8C%01%A8%9A%00%F6%A0%C8kWJ%E6%19%DBD%22%B1%BB%BB%5BYY988x%7F%7FouB%BCD%BB%B4%B4%D4%D1%D1a%BD%F2%C5%C5%C5%F0%F0%F0%EB%EB%2Bl%22(Mu%02upT%9D%10%BC%7D%3C%1E%DF%D9%D9%A9%A9%A9A%FF%F0%F0%10%96%3C%3C%3C%A8%09%A0V%C7%E5%E5%E5%F6%F6v%5BJ%3C%E4%DB%DB%1B))W%D0P%5B%5B%9B%83%9DD%FCm%0A%DEommUWW%CB%18D%1F%C6%3C%3E%3E%8A%A98%0B%23%D1.%2C%2C%B4%B6%B6%3AP%C6LEM1%F4%CE%F3)%A4MF%DB%99%FB%FE%FE%8E_%40YUU%A5%0DSY%25%EE%F3%F3%F3---%0E%94%88%8C%80%D2Q%D7%1C5bn%E2%85%00%7D%7D%7Dm%3D%1F%C9zppPQQA%D7%F1%0B%BC%CCF%89T%C1%18d9%40%13%A6%E2%A6x%23%B5oU%B8%BE%BE%3E%9CEj8%00zuu%05%C3jkk5%82%92%92%92%E6%E6%E6%F3%F3%F3%A7%A7%A7%C5%C5E%5BJ%CC%F1%91%91%11P%0A%A2J%C9%04%90d%B5U%04C%B3%85%9Eqg%9A%F3%F0%EC%EC%0Cm__%9F%D5%D7%A3%A3%A3%DB%DB%5B%10%5B%2F%85\'%1C%1D%1De%5DSm%23%9Cj%87S%1D%CDV%9E%98%A3%F0%80%C9%C7%FC%C3%E1%E9%E9)%3A%FD%FD%FDVV%C8%96rll%0C%97*..%8Eg%24%94%5E*%A8%0FP%89%3E%7D%3D99A%07%8B%20%D7w%2F%D2z%7C%7C%9C%94%92%9A%DAd%F7B%F9%09%EA%10zb%81U%EA%9C%FC%05_%D1qf%05%E5%C4%C4%04%93X%CDK%A1t%AD%9D%3E%0A%3EA%ADi%84%16%ACh%AD9%40%DD%DC%DC%90%12yYd%8A%94%B4%D3%2F%A5K%E8%A5%E0%AB%F3%D1%C8%88%AC%A8%FF%8D%8D%8D%DA%B9%CF%CF%CF%B3%B3%B3%18%03%3E%82%D2N%EB%04%F2%FE%D9%94%D5Qu9%C2%9A%2F%AC2%09%A0%E9%E9i%BCT%1B%1A%1AT%CA%9E%9E%9E%97%97%17D%5C%B33gJ%F7%D0%0B%2B%86%91U%2Bu8%9C%99%99%C1%07F%5D%5D%1D)%7B%7B%7B%B1%DA(--M%9AR%83.1%F1K%E9%BEpVYU8%E2%A2%C5%03%80%60nn%0EK%E3T*%85%E9%85%E7%C1%22%B0(%23%A1T%8B%91_JO%9F%22%C2%AA%BD%B1%84%18%10x%7D%AF%AE%AE%82%09%C3%CA%CB%CB%B5%D7%8FV%8Cr%A0%F4%FA%CD%24%AC%E4c%2B%8E%22%D0p%91%8B%26%D4K%B1Y%DE%40%EA%8A37%CAOP%2F%A7%C9BS-Ob*PdE%A2%FEH%E2%82P%FA%D8%80%10Vy%A3%0A%2Bhh\'%07%A8%95A%94\'%A5%BF%9D%12%8D%95%1D%88%B5V6g%C8*%E5%B6%20%94%BE%B7tlY%85R%C6%C8%87T%3E%B3\'%DF%BD\'%8D%953L%FD%92%B6%EE%EA%E4O%99%E3%26%99%F6%11%E7%BC%7DR%10%CA%DCw%F3%D4%95%94-J%A1%F8%0A%B6%EDXp%A0%60%0F%3F%00%0D%40%03%D0%00%F4k%F4G%80%01%00%E2%BB%B3%8D%BD%BE%0E%DE%00%00%00%00IEND%AEB%60%82") no-repeat center center;' . "\r\n" . '		   -webkit-background-size: 28px 28px;' . "\r\n" . '	   }' . "\r\n" . '	</style>' . "\r\n" . '' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.min.js.php"></script>' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.notification.js.php"></script>' . "\r\n" . '	<link type="text/css" rel="stylesheet" href="../CSS/Notification.css" />' . "\r\n" . '	<SCRIPT language=javascript src="../JS/CheckQuestion.js.php"></SCRIPT>' . "\r\n" . '	<SCRIPT language=javascript src="../JS/Md5.js.php"></SCRIPT>' . "\r\n" . '	<LINK href="../Offline/resources/phone.css" type=text/css rel=stylesheet>' . "\r\n" . '' . "\r\n" . '	<script>' . "\r\n" . '	function _GetCookies(){' . "\r\n" . '	  var administratorsName = getCookie("administratorsName");' . "\r\n" . '	  if (administratorsName != null)' . "\r\n" . '	  {' . "\r\n" . '		document.loginform.administratorsName.value = unescape(administratorsName);' . "\r\n" . '		//document.loginform.remberme.checked=true;' . "\r\n" . '	  }' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	function getCookie(name) {' . "\r\n" . '		var dc = document.cookie;' . "\r\n" . '		var prefix = name + "=";' . "\r\n" . '		var begin = dc.indexOf("; " + prefix);' . "\r\n" . '		if (begin == -1) {' . "\r\n" . '			begin = dc.indexOf(prefix);' . "\r\n" . '			if (begin != 0) return null;' . "\r\n" . '		} else {' . "\r\n" . '			begin += 2;' . "\r\n" . '		}' . "\r\n" . '		var end = document.cookie.indexOf(";", begin);' . "\r\n" . '		if (end == -1) {' . "\r\n" . '			end = dc.length;' . "\r\n" . '		}' . "\r\n" . '		return unescape(dc.substring(begin + prefix.length, end));' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	function Check_Form_Validator()' . "\r\n" . '	{' . "\r\n" . '		if (!CheckNotNull(document.loginform.administratorsName, "用户名")) {return false;}' . "\r\n" . '		if (!CheckNotNull(document.loginform.pass, "密码")) {return false;}' . "\r\n" . '	}' . "\r\n" . '	function submitLoginForm()' . "\r\n" . '	{' . "\r\n" . '		if( Check_Form_Validator() != false )' . "\r\n" . '		{' . "\r\n" . '			$.notification(\'正在努力为您登录系统...\');' . "\r\n" . '			var userName = Trim(document.getElementById(\'administratorsName\').value);' . "\r\n" . '			var userPass = hex_md5(Trim(document.getElementById(\'pass\').value));' . "\r\n" . '			var ParamString = "Action=LoginSubmit";' . "\r\n" . '			ParamString += "&userName="+userName;' . "\r\n" . '			ParamString += "&userPass="+userPass;' . "\r\n" . '			ParamString += "&remberme=1";' . "\r\n" . '			ajax_Submit(\'Login.php\',ParamString);' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	function ajax_Submit(url,postStr) {' . "\r\n" . '		var ajax=false; ' . "\r\n" . '		try { ajax = new ActiveXObject("Msxml2.XMLHTTP"); }' . "\r\n" . '		catch (e) { try { ajax = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { ajax = false; } }' . "\r\n" . '		if (!ajax && typeof XMLHttpRequest!=\'undefined\') ajax = new XMLHttpRequest(); ' . "\r\n" . '' . "\r\n" . '		ajax.open("POST", url, true); ' . "\r\n" . '		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); ' . "\r\n" . '		ajax.send(postStr);' . "\r\n" . '		ajax.onreadystatechange = function(){' . "\r\n" . '			if (ajax.readyState == 4)' . "\r\n" . '			{' . "\r\n" . '				if(ajax.status == 200)' . "\r\n" . '				{' . "\r\n" . '					if( Trim(ajax.responseText) == \'\' )' . "\r\n" . '					{' . "\r\n" . '						$.notification(\'网络传输问题\');' . "\r\n" . '					}' . "\r\n" . '					else' . "\r\n" . '					{' . "\r\n" . '						var theRtnText = ajax.responseText.split(\'######\');' . "\r\n" . '						switch(theRtnText[0])' . "\r\n" . '						{' . "\r\n" . '							case \'false\':' . "\r\n" . '							default:' . "\r\n" . '								if( theRtnText[1] == null ) $.notification(theRtnText);' . "\r\n" . '								else' . "\r\n" . '								{' . "\r\n" . '									if( Trim(theRtnText[1]) == \'\' ) $.notification(\'网络传输问题\');' . "\r\n" . '									else $.notification(theRtnText[1]);' . "\r\n" . '								}' . "\r\n" . '							break;' . "\r\n" . '							case \'true\':' . "\r\n" . '								//$.notification(\'成功登录，正在努力为您加载...\');' . "\r\n" . '							    if( Trim(theRtnText[1]) == \'\' ){' . "\r\n" . '									window.location.href = \'Monitor/index.php\';' . "\r\n" . '								}' . "\r\n" . '								else{' . "\r\n" . '									window.location.href = theRtnText[1];' . "\r\n" . '								}' . "\r\n" . '							break;' . "\r\n" . '						}' . "\r\n" . '					}' . "\r\n" . '				}' . "\r\n" . '				else' . "\r\n" . '				{' . "\r\n" . '					$.notification(\'网络传输问题\');' . "\r\n" . '				}' . "\r\n" . '			}	' . "\r\n" . '		} ' . "\r\n" . '	}' . "\r\n" . '	' . "\r\n" . '	window.onRexseeReady = function(){' . "\r\n" . '			//设置横屏' . "\r\n" . '			rexseeScreen.setScreenOrientation(\'landscape\');' . "\r\n" . '			//菜单' . "\r\n" . '			rexseeMenu.create("mainOptionsMenu","label:mainOptionsMenu");' . "\r\n" . '			rexseeMenu.addItem("mainOptionsMenu","rexsee:reload","label:刷新;");' . "\r\n" . '			rexseeMenu.addItem("mainOptionsMenu","rexsee:quit","label:退出;");' . "\r\n" . '			rexseeMenu.setOptionsMenuId("mainOptionsMenu");' . "\r\n" . '			rexseeSpecialKey.enableBackKeyListener(false);' . "\r\n" . '			if( rexseePushHttpListener.contains(\'';
	echo $pushURL;
	echo '\') )' . "\r\n" . '			{' . "\r\n" . '				rexseePushHttpListener.refresh(false,false);' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				rexseePushHttpListener.add(\'';
	echo $pushURL;
	echo '\',rexseeTelephony.getSimSerialNumber(),rexseeTelephony.getDeviceId());' . "\r\n" . '				rexseePushHttpListener.setDurationAndTimeout(3,10);' . "\r\n" . '			}' . "\r\n" . '			' . "\r\n" . '			rexseeTitleBar.setStyle(\'visibility:hidden;\');' . "\r\n" . '			rexseeStatusBar.setStyle(\'visibility:hidden;\');' . "\r\n" . '	}' . "\r\n" . '	</SCRIPT>' . "\r\n" . '	<style>' . "\r\n" . '		input[type=text] {font-size:16px;}' . "\r\n" . '		.titlebar {padding:3px;font-weight:bold;background: -webkit-gradient(linear, left top, right bottom, from(#014e82), to(#4d7496));}' . "\r\n" . '		table{border-collapse:collapse;border-spacing:0;}  ' . "\r\n" . '	</style>' . "\r\n" . '	<META content="MSHTML 6.00.3790.0" name=GENERATOR></HEAD>' . "\r\n" . '' . "\r\n" . '	<BODY style="background:#e5e5e5;color:#666" onload="javascript:_GetCookies();" oncontextmenu="return false">' . "\r\n" . '	' . "\r\n" . '	<div class="titlebar">' . "\r\n" . '	 <table width=100%>' . "\r\n" . '		<tr height=30>' . "\r\n" . '			<td width=30 valign=middle>&nbsp;</td>' . "\r\n" . '			<td align=center width=* class="pageTitle"><script>document.write(rexseeApplication.getLabel());</script></td>' . "\r\n" . '			<td width=30 valign=middle><a href="rexsee:quit"><img src="../Images/exit.png"></a></td>' . "\r\n" . '		</tr>' . "\r\n" . '	 </table>' . "\r\n" . '	</div>' . "\r\n" . '' . "\r\n" . '    <div class="notesInfo">【说明】需要进行系统登录，输入您的账号信息。</div>' . "\r\n" . '' . "\r\n" . '	<table width=100% cellpadding="0" cellspacing="0"><tr><td align=center>' . "\r\n" . '	<div id="menu-div" class="menu-div loginPadding" style="margin-top:20px;"><br/>' . "\r\n" . '	<div id="loginForm">' . "\r\n" . '		<form name="loginform" id="loginform" style="margin:0px;padding:0px" action="" method="post" onsubmit="return false;">' . "\r\n" . '		<table width="100%" border="0" cellspacing="2" cellpadding="0">' . "\r\n" . '		 <tr><td colspan=2 class="loginTitle" style="color: #0CF;"><b>登录</b></td></tr>' . "\r\n" . '		 <tr><td colspan=2 class="spanheight"></td></tr>' . "\r\n" . '		 <tr height=35><td nowrap="nowrap"><label for="administratorsName" class="redfont" style="font-size: 20px;">用户名：</label></td>' . "\r\n" . '			 <td nowrap><input name="administratorsName" type="text" class="text" id="administratorsName" value="" style="width:200px;border:#bfbfbf solid 2px; padding:5px;height:30px"/></td>' . "\r\n" . '		 </tr>' . "\r\n" . '		 <tr height=35><td nowrap="nowrap" class="redfont"><label for="password" style="font-size: 20px;">密&nbsp;&nbsp;&nbsp;&nbsp;码：</label></td>' . "\r\n" . '			 <td><input name="pass" type="password" class="text" id="pass" value="" style="width:200px;border:#bfbfbf solid 2px; padding:5px;height:30px"/></td>' . "\r\n" . '		 </tr>' . "\r\n" . '		 <tr height=35><td></td>' . "\r\n" . '			 <td><input type=Submit value=登录 name=Submit style="width:120; height:40px; color:#f09; font-size:18px; font-weight:bold;" onclick="javascript:submitLoginForm();">' . "\r\n" . '			 </td></tr>' . "\r\n" . '		</table>' . "\r\n" . '		</form>' . "\r\n" . '	</div>' . "\r\n" . '	</div>' . "\r\n" . '	</td></tr></table></BODY></HTML>' . "\r\n" . '' . "\r\n" . '';
}

?>

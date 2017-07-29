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
require_once ROOT_PATH . 'Includes/Crumb.class.php';
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

if ($License['isCustLogo'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

if (isset($_SESSION['administratorsID']) && ($_SESSION['administratorsID'] != '')) {
	_showerror($lang['error_system'], '登录错误：系统检查到您已使用' . $_SESSION['administratorsName'] . '账户身份登录过EnableQ系统', false);
}

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

	if (!Crumb::verifyCrumb(session_id(), $_POST['crumb'])) {
		exit('false######您确定用户登录行为来自EnableQ系统的合法页面?');
	}

	$administratorsName = iconv('UTF-8', 'GBK', trim($_POST['userName']));
	$passWord = trim($_POST['userPass']);
	$SQL = ' SELECT administratorsName,administratorsID,passWord,isActive,isAdmin,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . $administratorsName . '\' AND isAdmin !=0 LIMIT 0,1 ';
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

		if ($passWord != $Login_Row['passWord']) {
			$_SESSION['loginNum'] += 1;

			if ($Config['max_login_num'] < $_SESSION['loginNum']) {
				$hSQL = ' SELECT isInit FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . $Login_Row['administratorsName'] . '\' ';
				$hRow = $DB->queryFirstRow($hSQL);

				if ($hRow['isInit'] != '1') {
					$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive = 0 WHERE administratorsName = \'' . $Login_Row['administratorsName'] . '\' ';
					$DB->query($SQL);
					unset($_SESSION['loginNum']);
				}
			}

			exit('false######' . $lang['error_login_password']);
		}
		else {
			$isLoginCheck = 1;
			require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

			if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
				exit('false######' . _skipitenablechar($lang['R82353783517DA1951018F2CE07568E40']));
			}

			if (!in_array($Login_Row['isAdmin'], array('3', '4', '7'))) {
				exit('false######角色限制错误:您的用户身份类型不允许从本登录界面进入!请尝试EnableQ系统登录界面');
			}

			$time = time();
			$timeDiff = $time - 7776000;
			$SQL = ' DELETE FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE createDate <=\'' . $timeDiff . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE actionTime <=\'' . $timeDiff . '\' ';
			$DB->query($SQL);
			require ROOT_PATH . 'Process/RenameFile.inc.php';
			$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET loginNum=loginNum+1,lastVisitTime=\'' . $time . '\' WHERE administratorsID=\'' . $Login_Row['administratorsID'] . '\' ';
			$DB->query($SQL);

			if ($_POST['remberme'] == 1) {
				setcookie('administratorsName', escape(iconv('GBK', 'UTF-8', $Login_Row['administratorsName'])), time() + 2592000);
			}

			$_SESSION['administratorsID'] = $Login_Row['administratorsID'];
			$_SESSION['administratorsName'] = $Login_Row['administratorsName'];
			$_SESSION['adminRoleType'] = $Login_Row['isAdmin'];
			$_SESSION['adminRoleGroupID'] = $Login_Row['userGroupID'];
			$_SESSION['adminRoleGroupType'] = $Login_Row['groupType'];

			switch ($_SESSION['adminRoleType']) {
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
				exit('true######' . ROOT_PATH . 'index.php');
			}
		}
	}
}

$timeStamp = time();
if (($_SERVER['REQUEST_URI'] == $_SESSION['lastPath']) && (($timeStamp - $_SESSION['lastVisitTime']) < $Config['refresh_allowed'])) {
	$_SESSION['refreshNum'] += 1;

	if ($Config['max_refresh_num'] < $_SESSION['refreshNum']) {
		if (!in_array('\'' . $fullIpAddress . '\'', $BannedList) && !in_array('\'' . $fullIpAddress . '\'', $WhiteList)) {
			$SQL = ' INSERT INTO ' . BANNED_TABLE . ' SET ipAddress=\'' . $fullIpAddress . '\' ';
			$DB->query($SQL);
		}

		unset($_SESSION['refreshNum']);
		_showerror($lang['error_system'], $lang['error_max_refresh'], false);
	}

	_showerror($lang['error_system'], $lang['error_refresh'], false);
}

if (!isset($_SESSION['refreshNum'])) {
	$_SESSION['refreshNum'] = 0;
}

if (!isset($_SESSION['lastPath'])) {
	$_SESSION['lastPath'] = $_SERVER['REQUEST_URI'];
}

if (!isset($_SESSION['lastVisitTime'])) {
	$_SESSION['lastVisitTime'] = $timeStamp;
}

if ($License['isModiLogo'] != 1) {
	if (!file_exists('../Images/enableq.png') || (md5_file('../Images/enableq.png') != '3a27515a39d491cd565d7d1acfad56ba')) {
		_showerror($lang['error_system'], $lang['modify_flag_error']);
	}

	if (!file_exists('../Images/logo.png') || (md5_file('../Images/logo.png') != 'ede9ecf7877ad071b1d0c7600a891491')) {
		_showerror($lang['error_system'], $lang['modify_flag_error']);
	}

	if (!file_exists('../Images/company_logo.png') || (md5_file('../Images/company_logo.png') != '6669970805bb6b1bcaad0623fef2e8b4')) {
		_showerror($lang['error_system'], $lang['modify_flag_error']);
	}
}

header('Content-Type:text/html; charset=gbk');
if (!isset($_GET['Action']) || empty($_GET['Action'])) {
	if (isset($_GET['qid']) && ($_GET['qid'] != '')) {
		$SQL = ' SELECT custLogo,custTel FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['qid'] . '\' ';
		$sRow = $DB->queryFirstRow($SQL);

		if (trim($sRow['custLogo']) == '') {
			$logoFile = '../Images/enableq.png';
		}
		else {
			$logoFile = '../PerUserData/logo/' . trim($sRow['custLogo']);
		}
	}
	else {
		$logoFile = '../Images/enableq.png';
	}

	if ($sRow['custTel'] != '') {
		$custTel = $sRow['custTel'];
	}
	else {
		$custTel = '<font color=red>未给出技术服务电话</font>';
	}

	echo '' . "\r\n" . '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\r\n" . '<html xmlns="http://www.w3.org/1999/xhtml">' . "\r\n" . '<head>' . "\r\n" . '	<META http-equiv=Content-Language content=zh-cn>' . "\r\n" . '	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">' . "\r\n" . '	<META http-equiv=Content-Type content="text/html; charset=gbk">' . "\r\n" . '	<LINK rel="shortcut icon" href="../Images/enableq.ico" type="image/x-icon" />' . "\r\n" . '	<title>欢迎使用EnableQ在线问卷调查引擎</title>' . "\r\n" . '	<SCRIPT language=javascript src="../JS/HighLight.js.php"></SCRIPT>' . "\r\n" . '' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.min.js.php"></script>' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.notification.js.php"></script>' . "\r\n" . '	<link type="text/css" rel="stylesheet" href="../CSS/Notification.css" />' . "\r\n" . '	<SCRIPT language=javascript src="../JS/CheckQuestion.js.php"></SCRIPT>' . "\r\n" . '	<SCRIPT language=javascript src="../JS/Md5.js.php"></SCRIPT>' . "\r\n" . '' . "\r\n" . '	<link type="text/css" rel="stylesheet" href="../CSS/Tips.css" />' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.poshytip.js.php"></script>' . "\r\n" . '' . "\r\n" . '	<SCRIPT language=JavaScript type=text/Javascript>' . "\r\n" . '	function _GetCookies(){' . "\r\n" . '	  var administratorsName = getCookie("administratorsName");' . "\r\n" . '	  if (administratorsName != null)' . "\r\n" . '	  {' . "\r\n" . '		document.loginform.administratorsName.value = unescape(administratorsName);' . "\r\n" . '		document.loginform.remberme.checked=true;' . "\r\n" . '	  }' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	function getCookie(name) {' . "\r\n" . '		var dc = document.cookie;' . "\r\n" . '		var prefix = name + "=";' . "\r\n" . '		var begin = dc.indexOf("; " + prefix);' . "\r\n" . '		if (begin == -1) {' . "\r\n" . '			begin = dc.indexOf(prefix);' . "\r\n" . '			if (begin != 0) return null;' . "\r\n" . '		} else {' . "\r\n" . '			begin += 2;' . "\r\n" . '		}' . "\r\n" . '		var end = document.cookie.indexOf(";", begin);' . "\r\n" . '		if (end == -1) {' . "\r\n" . '			end = dc.length;' . "\r\n" . '		}' . "\r\n" . '		return unescape(dc.substring(begin + prefix.length, end));' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	function Check_Form_Validator()' . "\r\n" . '	{' . "\r\n" . '		if (!CheckNotNull(document.loginform.administratorsName, "用户名")) {return false;}' . "\r\n" . '		if (!CheckNotNull(document.loginform.pass, "密码")) {return false;}' . "\r\n" . '	}' . "\r\n" . '	function submitLoginForm()' . "\r\n" . '	{' . "\r\n" . '		if( Check_Form_Validator() != false )' . "\r\n" . '		{' . "\r\n" . '			var userName = Trim(document.getElementById(\'administratorsName\').value);' . "\r\n" . '			var userPass = hex_md5(Trim(document.getElementById(\'pass\').value));' . "\r\n" . '			var crumb = Trim(document.getElementById(\'crumb\').value);' . "\r\n" . '			if( document.getElementById(\'remberme\').checked )' . "\r\n" . '			{' . "\r\n" . '				var remberme = 1;' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				var remberme = 0;' . "\r\n" . '			}' . "\r\n" . '			var ParamString = "Action=LoginSubmit";' . "\r\n" . '			ParamString += "&userName="+userName;' . "\r\n" . '			ParamString += "&userPass="+userPass;' . "\r\n" . '			ParamString += "&remberme="+remberme;' . "\r\n" . '			ParamString += "&crumb="+crumb;' . "\r\n" . '			ajax_Submit(\'index.php\',ParamString);' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	function ajax_Submit(url,postStr) {' . "\r\n" . '		var ajax=false; ' . "\r\n" . '		try { ajax = new ActiveXObject("Msxml2.XMLHTTP"); }' . "\r\n" . '		catch (e) { try { ajax = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { ajax = false; } }' . "\r\n" . '		if (!ajax && typeof XMLHttpRequest!=\'undefined\') ajax = new XMLHttpRequest(); ' . "\r\n" . '' . "\r\n" . '		ajax.open("POST", url, true); ' . "\r\n" . '		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); ' . "\r\n" . '		ajax.send(postStr);' . "\r\n" . '		ajax.onreadystatechange = function(){' . "\r\n" . '			if (ajax.readyState == 4)' . "\r\n" . '			{' . "\r\n" . '				if(ajax.status == 200)' . "\r\n" . '				{' . "\r\n" . '					if( Trim(ajax.responseText) == \'\' )' . "\r\n" . '					{' . "\r\n" . '						$.notification(\'网络传输问题\');' . "\r\n" . '					}' . "\r\n" . '					else' . "\r\n" . '					{' . "\r\n" . '						var theRtnText = ajax.responseText.split(\'######\');' . "\r\n" . '						switch(theRtnText[0])' . "\r\n" . '						{' . "\r\n" . '							case \'false\':' . "\r\n" . '							default:' . "\r\n" . '								if( theRtnText[1] == null ) $.notification(theRtnText);' . "\r\n" . '								else' . "\r\n" . '								{' . "\r\n" . '									if( Trim(theRtnText[1]) == \'\' ) $.notification(\'网络传输问题\');' . "\r\n" . '									else $.notification(theRtnText[1]);' . "\r\n" . '								}' . "\r\n" . '							break;' . "\r\n" . '							case \'true\':' . "\r\n" . '								$.notification(\'成功登录，正在努力为您加载...\');' . "\r\n" . '								if( Trim(theRtnText[1]) == \'\' ){' . "\r\n" . '									window.location.href = \'index.php\';' . "\r\n" . '								}' . "\r\n" . '								else{' . "\r\n" . '									window.location.href = theRtnText[1];' . "\r\n" . '								}' . "\r\n" . '							break;' . "\r\n" . '						}' . "\r\n" . '					}' . "\r\n" . '				}' . "\r\n" . '				else' . "\r\n" . '				{' . "\r\n" . '					$.notification(\'网络传输问题\');' . "\r\n" . '				}' . "\r\n" . '			}	' . "\r\n" . '		} ' . "\r\n" . '	}' . "\r\n" . '	</SCRIPT>' . "\r\n" . '	<style>' . "\r\n" . '	#jquery-notification-message {width:400px;}' . "\r\n" . '	</style>' . "\r\n" . '</head>' . "\r\n" . '' . "\r\n" . '<body style="margin:0px auto" onload="javascript:_GetCookies();" oncontextmenu="return false">' . "\r\n" . '<noscript><table width=100%><tr><td style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">系统检查到您的浏览器可能未开启JavaScript支持，系统绝大多数功能可能会受影响！</td></tr></table></noscript>' . "\r\n" . '' . "\r\n" . '<!--Logo-->' . "\r\n" . '<table width="490" border="0" align="center" cellpadding="0" cellspacing="0">' . "\r\n" . '	<tr><td style="padding-top:80px;padding-bottom:5px;padding-left:2px"><img src="';
	echo $logoFile;
	echo '"/></td></tr>' . "\r\n" . '</table>' . "\r\n" . '<!--Logo-->' . "\r\n" . '<table width="490" border="0" align="center" cellpadding="0" cellspacing="0" background="../Images/clogin_header.png" >' . "\r\n" . '	<FORM class="loginform" name=loginform action="" method=post onsubmit="return false;">' . "\r\n" . '    <tr><td valign="top" style="padding-left:25px;padding-right:25px;">' . "\r\n" . '      <table width="100%" border="0" cellspacing="0" cellpadding="0" >' . "\r\n" . '        <tr>' . "\r\n" . '          <td style="padding-top:22px;*padding-top:18px;padding-bottom:7px;">' . "\r\n" . '		      <span style="font-size:12px;font-weight:bold;color:#525252;font-family:Calibri;float:left">用户名:</span>' . "\r\n" . '		      <span style="float:right"><a id="tip_info" title="账户登录有问题？联系您的项目组：<br/>';
	echo $custTel;
	echo '" href="javascript:void(0)"><img src="../Images/info.gif" border=0></a></span>' . "\r\n" . '		  </td>' . "\r\n" . '        </tr>' . "\r\n" . '        <tr>' . "\r\n" . '          <td><input name="administratorsName" type="text" id="administratorsName" style="font-family:Calibri;height:25px;width:435px;font-size:16px;background-repeat: no-repeat;"/></td>' . "\r\n" . '        </tr>' . "\r\n" . '        <tr>' . "\r\n" . '          <td style="padding-top:22px;*padding-top:18px;padding-bottom:7px;"><span style="font-size: 12px;font-weight:bold;color:#525252;font-family:Calibri;">密码:</span></td>' . "\r\n" . '        </tr>' . "\r\n" . '        <tr>' . "\r\n" . '          <td><input name="pass" type="password" id="pass" style="font-family:Calibri;height:25px;width:435px;background-repeat:no-repeat;font-size:16px;"/></td>' . "\r\n" . '        </tr>' . "\r\n" . '        <tr>' . "\r\n" . '          <td style="padding-top:10px;padding-bottom:5px;" align=right valign=center>' . "\r\n" . '			<span style="padding-right:15px;font-size: 12px;color:#999999;"><input type="checkbox" name="remberme" id="remberme" value="1" style="vertical-align:text-bottom;margin-bottom:2px; margin-bottom:-2px\\9;"/>记住我的用户名</span>' . "\r\n" . '            <input type="image" src="../Images/clogin_btn.png" name="btnSubmit" value="登录" id="btnSubmit" style="border:0;" onclick="javascript:submitLoginForm();"/><INPUT type=hidden value=LoginSubmit name="Action"> <input type="hidden" name="crumb" id="crumb" value="';
	echo Crumb::issueCrumb(session_id());
	echo '"></td>' . "\r\n" . '        </tr>' . "\r\n" . '      </table>' . "\r\n" . '  </td></tr>' . "\r\n" . '  <tr>' . "\r\n" . '    <td height="50" background="../Images/clogin_bottom.png" style="font-size:12px;font-family:Calibri;" align=right>&copy 2012-2018 <A href="http://www.enableq.com" target=_blank style="text-decoration: none">北京科维能动信息技术有限公司</A>&nbsp;版权所有&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' . "\r\n" . '  </tr>' . "\r\n" . '</form>' . "\r\n" . '</table>' . "\r\n" . '' . "\r\n" . '	<script type="text/javascript">initInputHighlightScript();</script>' . "\r\n" . '	<script>' . "\r\n" . '		$(\'#tip_info\').poshytip({' . "\r\n" . '		className: \'tip-skyblue\',' . "\r\n" . '		bgImageFrameSize: 9,' . "\r\n" . '		offsetX: 0,' . "\r\n" . '		offsetY: 20' . "\r\n" . '	});' . "\r\n" . '	</script>' . "\r\n" . '' . "\r\n" . '	<link href="../CSS/Notifier.css" type=text/css rel=stylesheet>' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.notifier.min.js.php"></script>' . "\r\n" . '	<script>' . "\r\n" . '		  var mOptions = {};' . "\r\n" . '		  //浏览器兼容' . "\r\n" . '		  mOptions.lifeTime = 8000;' . "\r\n" . '		  var userAgent = navigator.userAgent.toLowerCase();' . "\r\n" . '		  var is_opera = userAgent.indexOf(\'opera\') != -1 && opera.version();' . "\r\n" . '		  var is_moz = (navigator.product == \'Gecko\') && userAgent.substr(userAgent.indexOf(\'firefox\') + 8, 3);' . "\r\n" . '		  var is_ie = (userAgent.indexOf(\'msie\') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf(\'msie\') + 5, 3);' . "\r\n" . '		  var is_mac = userAgent.indexOf(\'mac\') != -1;' . "\r\n" . '		  if( is_ie ) {' . "\r\n" . '				var v = is_ie ? /\\d+/.exec(userAgent.split(\';\')[1]) : \'no ie\';' . "\r\n" . '				if( v < 8 )  ' . "\r\n" . '				$.jnotify(\'浏览器兼容\', \'EnableQ强烈建议您使用IE浏览器8.0以及8.0以上版本\', \'../Images/admin.png\', mOptions);' . "\r\n" . '		  }' . "\r\n" . '		  else { ' . "\r\n" . '			  $.jnotify(\'浏览器兼容\', \'EnableQ建议您使用IE浏览器以获得更好的操作体验\', \'../Images/admin.png\', mOptions);' . "\r\n" . '		  }' . "\r\n" . '	</script>' . "\r\n" . '</body>' . "\r\n" . '</html>' . "\r\n" . '';
}

?>

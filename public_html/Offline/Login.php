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

if ($License['isOffline'] != 1) {
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

		if ($Login_Row['isAdmin'] != '4') {
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

			if ($_POST['loginURL'] != '') {
				exit('true######' . $_POST['loginURL']);
			}
			else {
				exit('true######' . ROOT_PATH . 'Offline/DownSurveyList.php');
			}
		}
	}
}

if (!isset($_GET['Action']) || empty($_GET['Action'])) {
	$pushURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -17) . 'Android/Push.php?type=1';
	echo '' . "\r\n" . '	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">' . "\r\n" . '	<HEAD>' . "\r\n" . '	<TITLE>欢迎使用EnableQ在线问卷调查引擎 </TITLE>' . "\r\n" . '	<META http-equiv=Content-Type content="text/html; charset=gbk">' . "\r\n" . '	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">' . "\r\n" . '	<SCRIPT language=javascript src="../JS/HighLight.js.php"></SCRIPT>' . "\r\n" . '    <script type="text/javascript" src="../Offline/script/animation.js"></script>' . "\r\n" . '	<LINK href="../CSS/Android.css" type=text/css rel=stylesheet>' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.min.js.php"></script>' . "\r\n" . '	<script type="text/javascript" src="../JS/Jquery.notification.js.php"></script>' . "\r\n" . '    <script type="text/javascript" src="../Offline/common/string.js.php"></script>' . "\r\n" . '	<link type="text/css" rel="stylesheet" href="../CSS/Notification.css" />' . "\r\n" . '	<SCRIPT language=javascript src="../JS/CheckQuestion.js.php"></SCRIPT>' . "\r\n" . '	<SCRIPT language=javascript src="../JS/Md5.js.php"></SCRIPT>' . "\r\n" . '	<SCRIPT language=JavaScript type="text/javascript">' . "\r\n" . '	var img = 0;' . "\r\n" . '	var smallFontSize = false;' . "\r\n" . '	if( ! rexseeDatabase.tableExists(\'eq_font\',\'rexsee:enableq.db\') )' . "\r\n" . '	{' . "\r\n" . '		var ftsql = "CREATE TABLE eq_font (id int(1) NOT NULL default \'1\',fontId int(1) NOT NULL default \'1\');";' . "\r\n" . '		rexseeDatabase.exec(ftsql,\'rexsee:enableq.db\');' . "\r\n" . '		ftsql = " INSERT INTO eq_font (id,fontId) values (\'1\',\'1\');";' . "\r\n" . '		rexseeDatabase.exec(ftsql,\'rexsee:enableq.db\');' . "\r\n" . '		if( rexseeScreen.getScreenHeight() < 500 || rexseeScreen.getScreenWidth() < 500 )' . "\r\n" . '		{' . "\r\n" . '			rexseeScreen.setScreenOrientation(\'landscape\');' . "\r\n" . '			document.write(\'<LINK href="../Offline/resources/minipad.css" type=text/css rel=stylesheet>\');' . "\r\n" . '			smallFontSize = true;' . "\r\n" . '			img = 0;' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			document.write(\'<LINK href="../Offline/resources/pad10.css" type=text/css rel=stylesheet>\');' . "\r\n" . '			img = 1;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		var ftsql = " SELECT fontId FROM eq_font WHERE id=\'1\';";' . "\r\n" . '		var ftRow = eval(\'(\'+getDbRows(ftsql,\'rexsee:enableq.db\')+\')\');' . "\r\n" . '		if( parseInt(ftRow.rows[0][0] ) == 1)  //大字体' . "\r\n" . '		{' . "\r\n" . '			document.write(\'<LINK href="../Offline/resources/pad10.css" type=text/css rel=stylesheet>\');' . "\r\n" . '			img = 1;' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			document.write(\'<LINK href="../Offline/resources/minipad.css" type=text/css rel=stylesheet>\');' . "\r\n" . '			smallFontSize = true;' . "\r\n" . '			img = 0;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	rexseeScreen.setFullScreen(true);' . "\r\n" . '	function _GetCookies(){' . "\r\n" . '	  var administratorsName = getCookie("administratorsName");' . "\r\n" . '	  if (administratorsName != null)' . "\r\n" . '	  {' . "\r\n" . '		document.loginform.administratorsName.value = unescape(administratorsName);' . "\r\n" . '		document.loginform.remberme.checked=true;' . "\r\n" . '	  }' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	function getCookie(name) {' . "\r\n" . '		var dc = document.cookie;' . "\r\n" . '		var prefix = name + "=";' . "\r\n" . '		var begin = dc.indexOf("; " + prefix);' . "\r\n" . '		if (begin == -1) {' . "\r\n" . '			begin = dc.indexOf(prefix);' . "\r\n" . '			if (begin != 0) return null;' . "\r\n" . '		} else {' . "\r\n" . '			begin += 2;' . "\r\n" . '		}' . "\r\n" . '		var end = document.cookie.indexOf(";", begin);' . "\r\n" . '		if (end == -1) {' . "\r\n" . '			end = dc.length;' . "\r\n" . '		}' . "\r\n" . '		return unescape(dc.substring(begin + prefix.length, end));' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	function Check_Form_Validator()' . "\r\n" . '	{' . "\r\n" . '		if (!CheckNotNull(document.loginform.administratorsName, "用户名")) {return false;}' . "\r\n" . '		if (!CheckNotNull(document.loginform.pass, "密码")) {return false;}' . "\r\n" . '	}' . "\r\n" . '	function submitLoginForm()' . "\r\n" . '	{' . "\r\n" . '		if( Check_Form_Validator() != false )' . "\r\n" . '		{' . "\r\n" . '			$.notification(\'正在努力为您登录系统...\');' . "\r\n" . '			var userName = Trim(document.getElementById(\'administratorsName\').value);' . "\r\n" . '			var userPass = hex_md5(Trim(document.getElementById(\'pass\').value));' . "\r\n" . '			var ParamString = "Action=LoginSubmit";' . "\r\n" . '			ParamString += "&userName="+userName;' . "\r\n" . '			ParamString += "&userPass="+userPass;' . "\r\n" . '			ParamString += "&remberme=1";' . "\r\n" . '			ajax_Submit(\'Login.php\',ParamString);' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	function ajax_Submit(url,postStr) {' . "\r\n" . '		var ajax=false; ' . "\r\n" . '		try { ajax = new ActiveXObject("Msxml2.XMLHTTP"); }' . "\r\n" . '		catch (e) { try { ajax = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { ajax = false; } }' . "\r\n" . '		if (!ajax && typeof XMLHttpRequest!=\'undefined\') ajax = new XMLHttpRequest(); ' . "\r\n" . '' . "\r\n" . '		ajax.open("POST", url, true); ' . "\r\n" . '		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); ' . "\r\n" . '		ajax.send(postStr);' . "\r\n" . '		ajax.onreadystatechange = function(){' . "\r\n" . '			if (ajax.readyState == 4)' . "\r\n" . '			{' . "\r\n" . '				if(ajax.status == 200)' . "\r\n" . '				{' . "\r\n" . '					if( Trim(ajax.responseText) == \'\' )' . "\r\n" . '					{' . "\r\n" . '						$.notification(\'网络传输问题\');' . "\r\n" . '					}' . "\r\n" . '					else' . "\r\n" . '					{' . "\r\n" . '						var theRtnText = ajax.responseText.split(\'######\');' . "\r\n" . '						switch(theRtnText[0])' . "\r\n" . '						{' . "\r\n" . '							case \'false\':' . "\r\n" . '							default:' . "\r\n" . '								if( theRtnText[1] == null ) $.notification(theRtnText);' . "\r\n" . '								else' . "\r\n" . '								{' . "\r\n" . '									if( Trim(theRtnText[1]) == \'\' ) $.notification(\'网络传输问题\');' . "\r\n" . '									else $.notification(theRtnText[1]);' . "\r\n" . '								}' . "\r\n" . '							break;' . "\r\n" . '							case \'true\':' . "\r\n" . '								//$.notification(\'成功登录，正在努力为您加载...\');' . "\r\n" . '							    if( Trim(theRtnText[1]) == \'\' ){' . "\r\n" . '									window.location.href = \'Offline/DownSurveyList.php\';' . "\r\n" . '								}' . "\r\n" . '								else{' . "\r\n" . '									window.location.href = theRtnText[1];' . "\r\n" . '								}' . "\r\n" . '							break;' . "\r\n" . '						}' . "\r\n" . '					}' . "\r\n" . '				}' . "\r\n" . '				else' . "\r\n" . '				{' . "\r\n" . '					$.notification(\'网络传输问题\');' . "\r\n" . '				}' . "\r\n" . '			}	' . "\r\n" . '		} ' . "\r\n" . '	}' . "\r\n" . '	' . "\r\n" . '	window.onRexseeReady = function(){' . "\r\n" . '			//设置横屏' . "\r\n" . '			rexseeMenu.create("mainOptionsMenu","label:mainOptionsMenu");' . "\r\n" . '			if( smallFontSize == false )  //大字体' . "\r\n" . '			{' . "\r\n" . '				rexseeMenu.addItem("mainOptionsMenu","javascript:setFont(2);","label:小字体;");' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				rexseeMenu.addItem("mainOptionsMenu","javascript:setFont(1);","label:大字体;");' . "\r\n" . '			}' . "\r\n" . '			rexseeMenu.addItem("mainOptionsMenu","rexsee:reload","label:刷新;");' . "\r\n" . '			rexseeMenu.addItem("mainOptionsMenu","rexsee:quit","label:退出;");' . "\r\n" . '			rexseeMenu.setOptionsMenuId("mainOptionsMenu");' . "\r\n" . '			//setTabBar();' . "\r\n" . '			rexseeSpecialKey.enableBackKeyListener(false);' . "\r\n" . '			//rexseeSpecialKey.enableVolumeKeyListener(false);' . "\r\n" . '			//rexseeBrowserStyle.setStyle(\'browser-zoom-button:visible;\');' . "\r\n" . '' . "\r\n" . '			if( rexseePushHttpListener.contains(\'';
	echo $pushURL;
	echo '\') )' . "\r\n" . '			{' . "\r\n" . '				rexseePushHttpListener.refresh(false,false);' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				rexseePushHttpListener.add(\'';
	echo $pushURL;
	echo '\',rexseeTelephony.getSimSerialNumber(),rexseeTelephony.getDeviceId());' . "\r\n" . '				rexseePushHttpListener.setDurationAndTimeout(3,10);' . "\r\n" . '			}' . "\r\n" . '			' . "\r\n" . '			rexseeTitleBar.setStyle(\'visibility:hidden;\');' . "\r\n" . '			rexseeStatusBar.setStyle(\'visibility:hidden;\');' . "\r\n" . '' . "\r\n" . '			//rexseeBrowser.clearFormData();' . "\r\n" . '	}' . "\r\n" . '	</SCRIPT>' . "\r\n" . '	<style>' . "\r\n" . '		td {margin:5px}' . "\r\n" . '		*{ margin:0px; padding:0px;}' . "\r\n" . '		ul,li{list-style:none;}' . "\r\n" . '		a,button,label,div{' . "\r\n" . '			-webkit-tap-highlight-color: rgba(0, 0, 0, 0);' . "\r\n" . '		}' . "\r\n" . '	</style>' . "\r\n" . '	' . "\r\n" . '	</HEAD>' . "\r\n" . '' . "\r\n" . '	<BODY style="background:#fff;color:#666" onload="javascript:_GetCookies();" oncontextmenu="return false">' . "\r\n" . '	' . "\r\n" . '	<script>' . "\r\n" . '	 if(img == 0 ) ' . "\r\n" . '	 {' . "\r\n" . '		document.write(\'<div class="titlebar"><table width=100%><tr><td width=51><a id="rtnURL"><img src="../Images/rtn.png" border=0 width=51></a></td><td align=center width=* class="pageTitle">\'+rexseeApplication.getLabel()+\'</td><td width=51>&nbsp;</td></table></div>\');' . "\r\n" . '	 }' . "\r\n" . '	 else' . "\r\n" . '	 {' . "\r\n" . '		document.write(\'<div class="titlebar"><table width=100%><tr><td width=75><a id="rtnURL"><img src="../Images/hrtn.png" border=0 width=75></a></td><td align=center width=* class="pageTitle">\'+rexseeApplication.getLabel()+\'</td><td width=75>&nbsp;</td></table></div>\');' . "\r\n" . '	 }' . "\r\n" . '    </script>' . "\r\n" . '' . "\r\n" . '    <div class="notesInfo" style="background:#e5e5e5;">【说明】同步功能需要身份登录，输入您的账号信息</div>' . "\r\n" . '' . "\r\n" . '	<table width=100% cellpadding="0" cellspacing="0"><tr><td align=center>' . "\r\n" . '	<div class="content">' . "\r\n" . '		<form name="loginform" id="loginform" action="" method="post" onsubmit="return false;">' . "\r\n" . '		<ul class="unstyled">' . "\r\n" . '		<li>' . "\r\n" . '			<div class="topic_input">' . "\r\n" . '				<div class="topic_bel"> <i class="ico_u"></i></div>' . "\r\n" . '				<div class="topic_ipt">' . "\r\n" . '					<input id="administratorsName" type="text" name="administratorsName" placeholder="用户名" />' . "\r\n" . '				</div>' . "\r\n" . '			</div>' . "\r\n" . '		</li>' . "\r\n" . '		<li>' . "\r\n" . '			<div class="topic_input">' . "\r\n" . '				<div class="topic_bel"> <i class="ico_p"></i></div>' . "\r\n" . '				<div class="topic_ipt">' . "\r\n" . '					<input id="pass" type="password" name="pass" placeholder="密码" />' . "\r\n" . '				</div>' . "\r\n" . '			</div>' . "\r\n" . '		</li>' . "\r\n" . '		</ul>' . "\r\n" . '		<a href="javascript:submitLoginForm();" class="button blue">登 录</a>' . "\r\n" . '		<input type="hidden" value=1 name="remberme" id="remberme">' . "\r\n" . '		</form>' . "\r\n" . '	</div>' . "\r\n" . '	</td></tr></table>	' . "\r\n" . '		' . "\r\n" . '	<script type="text/javascript">initInputHighlightScript();</script> ' . "\r\n" . '	<script>' . "\r\n" . '		var theHomeURL = (rexseeApplication.getHome() == \'\' ) ? rexseeApplication.getDeveloperHome() : rexseeApplication.getHome();' . "\r\n" . '		var remoteXMLURL = str_replace(\'default.html\',\'bulidClient.xml\',theHomeURL);' . "\r\n" . '		$(\'#rtnURL\').attr(\'href\',"javascript:animationLoad(\'"+rexseeClient.getRoot(remoteXMLURL)+"index.html\');");' . "\r\n" . '	</script>' . "\r\n" . '	</BODY></HTML>' . "\r\n" . '' . "\r\n" . '';
}

?>

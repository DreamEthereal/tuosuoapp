<?php

//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');  //"违反安全"
}

error_reporting(E_ALL || ~E_NOTICE);
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
require_once ROOT_PATH . 'Config/DBConfig.inc.php';
require_once ROOT_PATH . 'Config/SystemConfig.inc.php';
require_once ROOT_PATH . 'Config/PlugIn.inc.php';

include_once ROOT_PATH . 'Includes/EnableQCoreClass.class.php';
include_once ROOT_PATH . 'DB/DataBaseDefine.inc.php';
include_once ROOT_PATH . 'Functions/Functions.base.inc.php';
include_once ROOT_PATH . 'Functions/Functions.encrypt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.base.inc.php';
include_once ROOT_PATH . 'License/License.xml';
require_once ROOT_PATH . 'Config/LicenseConfig.inc.php';
if ($_GET['test'] == 1){
	$lastVisitTime = time();
	$overTime_19 = $lastVisitTime+100;
	

	$_SESSION = Array
	(
	    'loginNum' => 0,
	    'refreshNum' => 0,
	    'lastPath' => '/System/Login.php',
	    'lastVisitTime' => $lastVisitTime,
	    'administratorsID' => 1,
	    'administratorsName' => 'wangyicheng',
	    'administratorsNickName' => 'wangyicheng',
	    'adminRoleType' => 1,
	    'adminRoleGroupID' => 0,
	    'adminRoleGroupType' => 1,
	    'surveyListURL' => 'ShowSurveyList.php?pageID=1',
	    'overTime_19' => $overTime_19,
	    'haveCheckValidate' => 1,
	    'ViewBackURL' => 'DataList.php?surveyID=19&surveyTitle=%D0%A1%C3%D7%D6%AE%BC%D2%CA%D6%BB%FA%CA%DB%BA%F3%B7%FE%CE%F1%B3%C9%BC%A8%BF%A82015%C4%EA%B5%DA2%C6%DA%A3%A8%D7%DC%B5%DA2%C6%DA%A3%A9&authStat=1&pageID=1&t_responseID=22',
	    'dataSQL' => 'KGIub3ZlckZsYWcgIT0gMiBhbmQgYi5hdXRoU3RhdCAhPSAyKSAgQU5EIGIuYXV0aFN0YXQgPTEgIEFORCBiLnJlc3BvbnNlSUQgPSAnMjIn',
	    'dataSource19' => 0,
	);
}

if (!isset($_SESSION['administratorsID']) || ($_SESSION['administratorsID'] == '')) {
	header('location:' . ROOT_PATH . 'System/Login.php');
	exit();
}

if (!MAGIC_QUOTES_GPC && $_GET) {
	$_GET = qaddslashes($_GET);
}

if (!MAGIC_QUOTES_GPC && $_COOKIE) {
	$_COOKIE = qaddslashes($_COOKIE);
}

if (!MAGIC_QUOTES_GPC && $_POST) {
	$_POST = qaddslashes($_POST);
}

if ($Config['xssClean'] == 1) {
	include_once ROOT_PATH . 'Functions/Functions.security.inc.php';

	if ($_GET) {
		$_GET = eq_xss_clean_get($_GET);
	}

	if ($_POST) {
		$_POST = eq_xss_clean_post($_POST);
	}

	if ($_SERVER['PHP_SELF']) {
		$_SERVER['PHP_SELF'] = eq_xss_clean_post($_SERVER['PHP_SELF']);
	}

	if ($_SERVER['QUERY_STRING']) {
		$_SERVER['QUERY_STRING'] = eq_xss_clean_post($_SERVER['QUERY_STRING']);
	}
}

$EnableQCoreClass = new EnableQCoreClass(ROOT_PATH . 'Templates/CN/', 'keep');


if ($_GET['Action'] == 'Logout') {
	session_destroy();
	header('location:' . ROOT_PATH . 'System/Login.php');
	exit();
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

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

//error_reporting(0);
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

include_once ROOT_PATH . 'Includes/EnableQCoreClass.class.php';
include_once ROOT_PATH . 'Functions/Functions.base.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.base.inc.php';
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
if (!MAGIC_QUOTES_GPC && $_GET) {
	$_GET = qaddslashes($_GET);
}

if (!MAGIC_QUOTES_GPC && $_COOKIE) {
	$_COOKIE = qaddslashes($_COOKIE);
}

if (!MAGIC_QUOTES_GPC && $_POST) {
	$_POST = qaddslashes($_POST);
}

$EnableQCoreClass = new EnableQCoreClass(ROOT_PATH . 'Templates/Install/', 'keep');

?>

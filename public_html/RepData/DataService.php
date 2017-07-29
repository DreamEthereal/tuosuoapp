<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/SystemConfig.inc.php';
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

set_magic_quotes_runtime(0);
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
	exit('EnableQ Security Violation');
}

$g_key = trim($_POST['key']);
$o_key = $Config['socket_token'];

if ($g_key != $o_key) {
	echo '';
	exit();
}

$sign = md5($_POST['type'] . '-' . $_POST['fileName'] . '-' . $g_key . '-' . date('Y-m-d'));

if (trim($_POST['sign']) != $sign) {
	echo '';
	exit();
}

switch ($_POST['type']) {
case 1:
default:
	$theFileName = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . trim(base64_decode($_POST['fileName']));

	if (file_exists($theFileName)) {
		@unlink($theFileName);
	}

	break;

case 2:
	$theDirName = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . trim(base64_decode($_POST['fileName']));

	if (is_dir($theDirName)) {
		deletedir($theDirName);
	}

	break;
}

exit();

?>

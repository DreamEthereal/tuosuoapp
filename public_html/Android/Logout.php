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
$_SESSION['administratorsID'] = '';
$_SESSION['administratorsName'] = '';
$_SESSION['administratorsNickName'] = '';
$_SESSION['haveCheckValidate'] = false;
$_SESSION['adminRoleType'] = '';
$_SESSION['adminRoleGroupID'] = '';
$_SESSION['adminRoleGroupType'] = '';
$_SESSION['adminSameGroupUsers'] = '';
unset($_SESSION['administratorsID']);
unset($_SESSION['administratorsName']);
unset($_SESSION['administratorsNickName']);
unset($_SESSION['haveCheckValidate']);
unset($_SESSION['adminRoleType']);
unset($_SESSION['adminRoleGroupID']);
unset($_SESSION['adminRoleGroupType']);
unset($_SESSION['adminSameGroupUsers']);
unset($_SESSION);
session_unset();
session_destroy();

if ($_GET['loginOutURL'] != '') {
	header('location:' . $_GET['loginOutURL']);
}
else {
	header('location:./InputIndex.php');
}

exit();

?>

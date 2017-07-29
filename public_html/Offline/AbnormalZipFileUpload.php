<?php
//dezend by http://www.yunlu99.com/
set_time_limit(0);
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$thisFiles = 'uploadedfile';
$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
createdir($filePhyPath);
header('Content-Type:text/html; charset=gbk');
$time = $_GET['fileTime'];
if (!isset($_FILES[$thisFiles]) || !is_uploaded_file($_FILES[$thisFiles]['tmp_name']) || ($_FILES[$thisFiles]['error'] != 0) || ($_SESSION['adminRoleType'] != '4')) {
	if (isset($_FILES[$thisFiles])) {
		echo 'false|' . $_FILES[$thisFiles]['error'];
	}

	exit();
}
else {
	$noAllowFileType = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'wml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'dhtml', 'phtm', 'asa', 'cer', 'chm', 'bmp', 'jpg', 'jpeg', 'gif', 'png');
	$tmpExt = explode('.', $_FILES[$thisFiles]['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);

	if (!in_array($extension, $noAllowFileType)) {
		if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $_FILES[$thisFiles]['name'])) {
			echo 'true';
			exit();
		}
	}
}

?>

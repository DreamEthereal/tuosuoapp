<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');

if (isset($_POST['PHPSESSID'])) {
	session_id($_POST['PHPSESSID']);
}

require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
_checkroletype('1|2|5');
$thisFiles = $_POST['uploadFileName'];
$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
createdir($filePhyPath);
$time = time();

if (is_dir($filePhyPath)) {
	if ($tmpFilePath = opendir($filePhyPath)) {
		while (($tmpFile = readdir($tmpFilePath)) !== false) {
			$theFileTime = filectime($filePhyPath . $tmpFile);
			if (($theFileTime <= $time - 86400) && ($tmpFile != 'index.html')) {
				@unlink($filePhyPath . $tmpFile);
			}
		}

		closedir($tmpFilePath);
	}
}

if (!isset($_FILES[$thisFiles]) || !is_uploaded_file($_FILES[$thisFiles]['tmp_name']) || ($_FILES[$thisFiles]['error'] != 0)) {
	header('HTTP/1.1 500 File Upload Error');

	if (isset($_FILES[$thisFiles])) {
		echo $_FILES[$thisFiles]['error'];
	}

	exit();
}
else {
	$theFileName = trim($_FILES[$thisFiles]['name']);
	$tmpExt = explode('.', $theFileName);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$tmpFileName = basename($theFileName, '.' . $extension);
	$newFileName = $tmpFileName . '_' . date('YmdHis', $time) . rand(1, 999) . '.' . $extension;
	$theUploadMd5FileName = md5(iconv('UTF-8', 'gbk', $newFileName));
	$noAllowFileType = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'wml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'dhtml', 'phtm', 'asa', 'cer', 'chm', 'bmp', 'jpg', 'jpeg', 'gif', 'png');

	if (!in_array($extension, $noAllowFileType)) {
		if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $theUploadMd5FileName)) {
			header('Content-Type:text/html; charset=gbk');
			echo $newFileName;
			exit();
		}
	}
}

?>

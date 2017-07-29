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
$SQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\'';
$sRow = $DB->queryFirstRow($SQL);

if ($sRow['custDataPath'] == '') {
	$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
}
else {
	$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $sRow['custDataPath'] . '/';
}

createdir($filePhyPath);
header('Content-Type:text/html; charset=gbk');
$time = $_GET['fileTime'];
if (!isset($_FILES[$thisFiles]) || !is_uploaded_file($_FILES[$thisFiles]['tmp_name']) || ($_FILES[$thisFiles]['error'] != 0) || ($_SESSION['adminRoleType'] != '4')) {
	if (isset($_FILES[$thisFiles])) {
		echo 'false|' . $_GET['optionID'] . '|' . $_FILES[$thisFiles]['error'];
	}

	exit();
}
else {
	$theFileNameArray = explode('.', $_FILES[$thisFiles]['name']);

	if (count($theFileNameArray) == 3) {
		$theFileName = substr($_FILES[$thisFiles]['name'], 0, -3);
	}
	else {
		$theFileName = $_FILES[$thisFiles]['name'];
	}

	$tmpExt = explode('.', $theFileName);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$tmpFileName = basename($theFileName, '.' . $extension);
	$noAllowFileType = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'wml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'dhtml', 'phtm', 'asa', 'cer', 'chm');

	if (!in_array($extension, $noAllowFileType)) {
		if ($_GET['optionID'] == 'recFile') {
			$newFileName = $tmpFileName . '_' . date('ymdHis', $_GET['fileTime']) . '.' . $extension;

			if (!file_exists($filePhyPath . $newFileName)) {
				if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $newFileName)) {
					echo 'true|' . $_GET['optionID'] . '|' . $newFileName;
					exit();
				}
			}
			else {
				echo 'true|' . $_GET['optionID'] . '|' . $newFileName;
				exit();
			}
		}
		else {
			if ($sRow['custDataPath'] == '') {
				$filePhyPath .= date('Y-m', $_GET['fileTime']) . '/' . date('d', $_GET['fileTime']) . '/';
				createdir($filePhyPath);
			}

			$newFileName = $tmpFileName . '_' . date('ymdHis', $_GET['fileTime']) . '.' . $extension;

			if (!file_exists($filePhyPath . $newFileName)) {
				if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $newFileName)) {
					echo 'true|' . $_GET['optionID'] . '|' . $newFileName;
					exit();
				}
			}
			else {
				echo 'true|' . $_GET['optionID'] . '|' . $newFileName;
				exit();
			}
		}
	}
}

?>

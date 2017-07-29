<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');

if (isset($_POST['PHPSESSID'])) {
	session_id($_POST['PHPSESSID']);
}

require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.bmp.inc.php';
$thisFiles = $_POST['uploadFileName'];

if ($Config['dataDomainName'] != '') {
	$theFileTime = trim($_POST['uploadFileTime']);
	$SQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['theSurveyID'] . '\'';
	$sRow = $DB->queryFirstRow($SQL);

	if ($sRow['custDataPath'] == '') {
		$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_POST['theSurveyID'] . '/' . date('Y-m', $theFileTime) . '/' . date('d', $theFileTime) . '/';
	}
	else {
		$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $sRow['custDataPath'] . '/';
	}
}
else {
	$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
}

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
	$question_File_ID = explode('_', $thisFiles);
	$tmpExt = explode('.', $_FILES[$thisFiles]['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);

	if ($question_File_ID['1'] != '') {
		$newFileName = $question_File_ID['1'] . '_' . date('YmdHis', $time) . rand(1, 9999) . '.' . $extension;
		$dscFile = $question_File_ID['1'] . '_' . date('YmdHis', $time) . rand(1, 9999) . '_n.' . $extension;
	}
	else {
		$newFileName = date('YmdHis', $time) . rand(1, 999) . '.' . $extension;
		$dscFile = date('YmdHis', $time) . rand(1, 999) . '_n.' . $extension;
	}

	$noAllowFileType = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'wml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'dhtml', 'phtm', 'asa', 'cer', 'chm');

	if (!in_array($extension, $noAllowFileType)) {
		switch ($extension) {
		case 'bmp':
			if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $newFileName)) {
				$im = imagecreatefrombmp($filePhyPath . $newFileName);

				if ($im == false) {
					$data = getimagesize($filePhyPath . $newFileName);

					switch ($data[2]) {
					case 1:
						$im = @imagecreatefromgif($filePhyPath . $newFileName);
						@imagegif($im, $filePhyPath . $dscFile);
						break;

					case 2:
					default:
						$im = @imagecreatefromjpeg($filePhyPath . $newFileName);
						@imagejpeg($im, $filePhyPath . $dscFile, 100);
						break;

					case 3:
						$im = @imagecreatefrompng($filePhyPath . $newFileName);
						@imagepng($im, $filePhyPath . $dscFile);
						break;
					}
				}
				else {
					@imagejpeg($im, $filePhyPath . $dscFile, 100);
				}

				@unlink($filePhyPath . $newFileName);
				imagedestroy($im);
				unset($data);
				echo $dscFile;
				exit();
			}

			break;

		case 'jpg':
		case 'jpeg':
		case 'gif':
		case 'png':
			if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $newFileName)) {
				$data = getimagesize($filePhyPath . $newFileName);

				switch ($data[2]) {
				case 1:
					$im = @imagecreatefromgif($filePhyPath . $newFileName);
					@imagegif($im, $filePhyPath . $dscFile);
					break;

				case 2:
				default:
					$im = @imagecreatefromjpeg($filePhyPath . $newFileName);
					@imagejpeg($im, $filePhyPath . $dscFile, 100);
					break;

				case 3:
					$im = @imagecreatefrompng($filePhyPath . $newFileName);
					@imagepng($im, $filePhyPath . $dscFile);
					break;
				}

				@unlink($filePhyPath . $newFileName);
				imagedestroy($im);
				unset($data);
				echo $dscFile;
				exit();
			}

			break;

		default:
			if (copy($_FILES[$thisFiles]['tmp_name'], $filePhyPath . $newFileName)) {
				echo $newFileName;
				exit();
			}

			break;
		}
	}
}

?>

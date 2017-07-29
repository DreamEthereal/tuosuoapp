<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$thisProg = 'ShowSurveyList.php?Action=CheckUniCode&surveyID=' . $_GET['surveyID'];

if ($_GET['Action'] == 'DownQRCode') {
	include_once ROOT_PATH . 'Includes/QRcode.php';
	$QR_FileName_Path = $Config['absolutenessPath'] . '/PerUserData/tmp/' . $_GET['surveyID'] . '/';
	createdir($QR_FileName_Path);
	include_once ROOT_PATH . 'Config/QRConfig.inc.php';
	$SQL = ' SELECT surveyTitle,surveyName,lang,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
	$sRow = $DB->queryFirstRow($SQL);
	$theUniCodePath = ROOT_PATH . 'PerUserData/unicode/';
	$theCacheFile = $theUniCodePath . md5('uniCode' . $_GET['surveyID']) . '.php';
	$uniCodeQKeyArray = array();

	if (file_exists($theCacheFile)) {
		require_once $theCacheFile;
	}
	else {
		_showerror($lang['error_system'], '加载缓存文件异常：无法提供批量二维码下载');
	}

	if ($Config['dataDomainName'] != '') {
		$All_Path = 'http://' . $Config['dataDomainName'] . '/';
	}
	else {
		$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
	}

	$theQRFileArray = array();

	foreach ($uniCodeQKeyArray as $thisRandNumber) {
		$qURL = $All_Path . 'q.php?qname=' . $sRow['surveyName'] . '&qlang=' . $sRow['lang'] . '&qkey=' . $thisRandNumber;
		$QR_FileName = $QR_FileName_Path . $thisRandNumber . '.png';
		$theQRFileArray[] = $thisRandNumber . '.png';

		if (file_exists($QR_FileName)) {
			@unlink($QR_FileName);
		}

		$matrixPointSize = $Config['matrixPointSize'] + 3;

		if (10 < $matrixPointSize) {
			$matrixPointSize = 10;
		}

		QRcode::png($qURL, $QR_FileName, $Config['correctionLevel'], $matrixPointSize, 2);
	}

	include_once ROOT_PATH . 'Includes/Tar.class.php';
	$zip = new Zip();
	$haveFile = 0;

	foreach ($theQRFileArray as $theQRFile) {
		$zip->addFile(file_get_contents($QR_FileName_Path . $theQRFile), $theQRFile, filectime($QR_FileName_Path . $theQRFile));
		$haveFile++;
	}

	if ($haveFile == 0) {
		_showerror($lang['error_system'], '生成二维码文件异常：无法提供批量二维码下载');
	}

	if (ini_get('zlib.output_compression')) {
		ini_set('zlib.output_compression', 'Off');
	}

	$zip->finalize();
	$zipData = $zip->getZipData();
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename="survey_qr_png_' . $sRow['surveyName'] . '_' . date('Y-m-d') . '.zip";');
	echo $zipData;
	deletedir($QR_FileName_Path);
	exit();
}

if ($_GET['Action'] == 'CheckUniCode') {
	$EnableQCoreClass->setTemplateFile('UsersCheckFile', 'SurveyUnicodeCheck.html');
	$EnableQCoreClass->set_CycBlock('UsersCheckFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$SQL = ' SELECT surveyTitle,surveyName,lang,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
	$sRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('surveyTitle', $sRow['surveyTitle']);
	$EnableQCoreClass->replace('surveyID', $sRow['surveyID']);
	$EnableQCoreClass->replace('isAdmin6', $_SESSION['adminRoleType'] == 6 ? 'none' : '');
	$theUniCodePath = ROOT_PATH . 'PerUserData/unicode/';
	$theCacheFile = $theUniCodePath . md5('uniCode' . $_GET['surveyID']) . '.php';
	$uniCodeQKeyArray = array();

	if (file_exists($theCacheFile)) {
		require_once $theCacheFile;
	}

	if ($Config['dataDomainName'] != '') {
		$All_Path = 'http://' . $Config['dataDomainName'] . '/';
	}
	else {
		$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
	}

	foreach ($uniCodeQKeyArray as $thisRandNumber) {
		$qURL = $All_Path . 'q.php?qname=' . $sRow['surveyName'] . '&qlang=' . $sRow['lang'] . '&qkey=' . $thisRandNumber;
		$EnableQCoreClass->replace('qURL', $qURL);
		$EnableQCoreClass->replace('qkey', $thisRandNumber);
		$hSQL = ' SELECT overFlag,responseID FROM ' . $table_prefix . 'response_' . $sRow['surveyID'] . ' WHERE uniCode = \'' . $thisRandNumber . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$EnableQCoreClass->replace('isHaveData', '');
			$EnableQCoreClass->replace('dataURL', ROOT_PATH . 'Analytics/DataList.php?surveyID=' . $sRow['surveyID'] . '&surveyTitle=' . urlencode($sRow['surveyTitle']) . '&Does=View&responseID=' . $hRow['responseID']);

			switch ($hRow['overFlag']) {
			case '0':
				$EnableQCoreClass->replace('statusColor', '#ffe000');
				$EnableQCoreClass->replace('status', '正在进行');
				break;

			case '1':
				$EnableQCoreClass->replace('statusColor', '#ffffff');
				$EnableQCoreClass->replace('status', '已完成');
				break;

			case '2':
				$EnableQCoreClass->replace('statusColor', '#cc0000');
				$EnableQCoreClass->replace('status', '<font color=white>已完成</font>');
				break;

			case '3':
				$EnableQCoreClass->replace('statusColor', '#339933');
				$EnableQCoreClass->replace('status', '<font color=white>已完成</font>');
				break;
			}
		}
		else {
			$EnableQCoreClass->replace('statusColor', '#cc0000');
			$EnableQCoreClass->replace('status', '<font color=white>未使用</font>');
			$EnableQCoreClass->replace('isHaveData', 'none');
			$EnableQCoreClass->replace('dataURL', '');
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	$EnableQCoreClass->parse('UsersCheck', 'UsersCheckFile');
	$EnableQCoreClass->output('UsersCheck');
}

?>

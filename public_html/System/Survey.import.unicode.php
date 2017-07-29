<?php
//dezend by http://www.yunlu99.com/
function _getvalidstring($str)
{
	$_obf_hfJJz4EUOS_xrLU_ = preg_replace('#[^A-z0-9]#', '', $str);
	return strtolower($_obf_hfJJz4EUOS_xrLU_);
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$thisProg = 'ShowSurveyList.php?Action=ImportUniCode&surveyID=' . $_GET['surveyID'];
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';

if ($_POST['Action'] == 'ImportUnicodeSubmit') {
	@set_time_limit(0);
	$SQL = ' SELECT status,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['status'] == '2') {
		_showerror($lang['system_error'], $lang['close_survey_now']);
	}

	$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if (!is_dir($File_DIR_Name)) {
		mkdir($File_DIR_Name, 511);
	}

	$tmpExt = explode('.', $_FILES['csvFile']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$newFileName = 'CSV_' . date('YmdHis', time()) . rand(1, 999) . '.csv';
	$newFullName = $File_DIR_Name . $newFileName;
	if (is_uploaded_file($_FILES['csvFile']['tmp_name']) && ($extension == 'csv')) {
		copy($_FILES['csvFile']['tmp_name'], $newFullName);
	}
	else {
		_showerror($lang['error_system'], $lang['csv_file_type_error']);
	}

	$theUniCodePath = ROOT_PATH . 'PerUserData/unicode/';
	createdir($theUniCodePath);
	$theCacheFile = $theUniCodePath . md5('uniCode' . $_POST['surveyID']) . '.php';
	$uniCodeQKeyArray = array();

	if (file_exists($theCacheFile)) {
		require_once $theCacheFile;
	}

	$cacheContent = '<?php' . "\r\n" . '	/**************************************************************************' . "\r\n" . '	 *                                                                        *' . "\r\n" . '	 *    EnableQ System                                                      *' . "\r\n" . '	 *    ----------------------------------------------------------------    *' . "\r\n" . '	 *                                                                        *' . "\r\n" . '	 *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . '	 *        WebSite: itenable.com.cn                                        *' . "\r\n" . '	 *                                                                        *' . "\r\n" . '	 *        Last Modified: 2013/06/30                                       *' . "\r\n" . '	 *        Scriptversion: 8.xx                                             *' . "\r\n" . '	 *                                                                        *' . "\r\n" . '	 **************************************************************************/' . "\r\n" . '	if (!defined(\'ROOT_PATH\'))' . "\r\n" . '	{' . "\r\n" . '		die(\'EnableQ Security Violation\');' . "\r\n" . '	}';
	$cacheContent .= "\r\n";
	$cacheContent .= '$uniCodeQKeyArray = array( ';

	if ($Config['dataDomainName'] != '') {
		$All_Path = 'http://' . $Config['dataDomainName'] . '/';
	}
	else {
		$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
	}

	$csvFileContent = '';
	$theExistKeyArray = array();

	foreach ($uniCodeQKeyArray as $thisRandNumber) {
		if (!in_array($thisRandNumber, $theExistKeyArray)) {
			$csvFileContent .= '"' . $All_Path . 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'] . '&qkey=' . $thisRandNumber;
			$csvFileContent .= '"' . "\r\n" . '';
			$cacheContent .= '\'' . $thisRandNumber . '\',';
			$theExistKeyArray[] = $thisRandNumber;
		}
	}

	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newFullName, 'r');
	$recNum = 0;

	while ($csvData = _fgetcsv($File)) {
		$thisRandNumber = _getvalidstring(trim($csvData[0]));

		if (!in_array($thisRandNumber, $theExistKeyArray)) {
			$csvFileContent .= '"' . $All_Path . 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'] . '&qkey=' . $thisRandNumber;
			$csvFileContent .= '"' . "\r\n" . '';
			$cacheContent .= '\'' . $thisRandNumber . '\',';
			$theExistKeyArray[] = $thisRandNumber;
			$recNum++;
		}
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	$cacheContent = substr($cacheContent, 0, -1) . '' . "\r\n" . ');' . "\r\n" . '';
	$cacheContent .= chr(63) . chr(62);

	if (file_exists($theCacheFile)) {
		@unlink($theCacheFile);
	}

	write_to_file($theCacheFile, $cacheContent);
	writetolog($lang['new_import'] . $recNum . $lang['import_num']);
	ob_start();
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename=UniURL_' . $Row['surveyName'] . '_List_' . date('Y-m-d') . '.csv');
	echo $csvFileContent;
	exit();
}

if ($_GET['Action'] == 'ImportUniCode') {
	$EnableQCoreClass->setTemplateFile('UsersImportFile', 'SurveyUnicodeImport.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
	$EnableQCoreClass->output('UsersImport');
}

?>

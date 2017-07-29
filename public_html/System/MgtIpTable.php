<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
$thisProg = 'MgtIpTable.php';
_checkroletype(1);

if ($_POST['Action'] == 'ImportSubmit') {
	@set_time_limit(0);
	$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if (!is_dir($File_DIR_Name)) {
		mkdir($File_DIR_Name, 511);
	}

	$tmpExt = explode('.', $_FILES['csvFile']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$newFileName = 'CSV_' . date('YmdHis', time()) . rand(1, 99) . '.csv';
	$newFullName = $File_DIR_Name . $newFileName;
	if (is_uploaded_file($_FILES['csvFile']['tmp_name']) && ($extension == 'csv')) {
		copy($_FILES['csvFile']['tmp_name'], $newFullName);
	}
	else {
		_showerror($lang['error_system'], $lang['csv_file_type_error']);
	}

	$SQL = ' TRUNCATE TABLE ' . IPDATABASE_TABLE . ' ';
	$DB->query($SQL);
	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newFullName, 'r');
	$recNum = 0;
	$csvLineNum = 0;

	while ($csvData = _fgetcsv($File)) {
		if ($csvLineNum != 0) {
			$csvData = qaddslashes($csvData, 1);
			$csv_startIP = trim($csvData[0]);
			$csv_endIP = trim($csvData[1]);
			$csv_area = trim($csvData[2]);
			if (($csv_startIP == '') || ($csv_endIP == '') || ($csv_area == '')) {
				continue;
			}

			$start_IP = explode('.', $csv_startIP);
			$userStart = sprintf('%03s', $start_IP[0]) . '.' . sprintf('%03s', $start_IP[1]) . '.' . sprintf('%03s', $start_IP[2]) . '.' . sprintf('%03s', $start_IP[3]);
			$end_IP = explode('.', $csv_endIP);
			$userEnd = sprintf('%03s', $end_IP[0]) . '.' . sprintf('%03s', $end_IP[1]) . '.' . sprintf('%03s', $end_IP[2]) . '.' . sprintf('%03s', $end_IP[3]);
			$SQL = ' INSERT INTO ' . IPDATABASE_TABLE . ' SET  StartIp=\'' . $userStart . '\',EndIp=\'' . $userEnd . '\',Area=\'' . $csv_area . '\' ';
			$DB->query($SQL);
			$recNum++;
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	$SQL = ' OPTIMIZE TABLE ' . IPDATABASE_TABLE . ' ';
	$DB->query($SQL);
	writetolog($lang['new_import'] . $recNum . $lang['import_address']);
	_showsucceed($lang['new_import'] . $recNum . $lang['import_address'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('UsersImportFile', 'FileImport.html');
$SQL = ' SELECT COUNT(*) FROM ' . IPDATABASE_TABLE . ' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('recCount', $Row[0]);
$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
$EnableQCoreClass->output('UsersImport');

?>

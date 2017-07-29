<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$thisProg = 'ShowSurveyList.php?Action=ImportAppealAuthUser&surveyID=' . $_GET['surveyID'];
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';

if ($_POST['Action'] == 'ImportAppealAuthUserSubmit') {
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

	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newFullName, 'r');
	$recNum = 0;
	$csvLineNum = 0;

	while ($csvData = _fgetcsv($File)) {
		if ($csvLineNum != 0) {
			$csvData = qaddslashes($csvData, 1);
			$userName = trim($csvData[0]);
			$isCancelTag = (int) trim($csvData[1]);

			if ($userName == '') {
				continue;
			}

			$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . $userName . '\' AND isAdmin =7 AND groupType=1 ';
			$Row = $DB->queryFirstRow($SQL);

			if (!$Row) {
				continue;
			}

			$theUserId = $Row['administratorsID'];
			$hSQL = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND administratorsID = \'' . $theUserId . '\' AND isAuth=1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				if ($isCancelTag == 1) {
					$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND administratorsID = \'' . $theUserId . '\' AND isAuth=1 ';
					$DB->query($SQL);
					$recNum++;
				}
				else {
					continue;
				}
			}
			else {
				$SQL = ' INSERT INTO ' . APPEALUSERLIST_TABLE . ' SET administratorsID=\'' . $theUserId . '\',surveyID=\'' . $_POST['surveyID'] . '\',isAuth=1 ';
				$DB->query($SQL);
				$recNum++;
			}
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	writetolog($lang['new_import'] . $recNum . $lang['import_num']);
	_showmessage($lang['new_import'] . $recNum . $lang['import_num'], true);
}

if ($_GET['Action'] == 'ImportAppealAuthUser') {
	$EnableQCoreClass->setTemplateFile('UsersImportFile', 'SurveyUserImport.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('projectOwner', '');
	$EnableQCoreClass->replace('userType', 'Êý¾ÝÉóºË');
	$EnableQCoreClass->replace('actionName', 'ImportAppealAuthUserSubmit');
	$EnableQCoreClass->replace('csvFileName', str_replace('+', '%2B', base64_encode('CSV_Sample_AppealAuth.csv')));
	$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
	$EnableQCoreClass->output('UsersImport');
}

?>

<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxSurveyProof.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'];
_checkpassport('1|2|5', $_GET['surveyID']);
$EnableQCoreClass->replace('thisURL', $thisProg);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

if ($_GET['DO'] == 'Delete') {
	$SQL = ' SELECT * FROM ' . SURVEYPROOF_TABLE . ' WHERE proofID=\'' . $_GET['proofID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['dataID'] == 0) {
		$SQL = ' DELETE FROM ' . SURVEYPROOF_TABLE . ' WHERE proofID=\'' . $_GET['proofID'] . '\' ';
		$DB->query($SQL);
		writetolog('删除问卷回复完成凭证：' . $Row['proofName'] . ':' . $Row['proofNum']);
		_showsucceed('删除问卷回复完成凭证：' . $Row['proofName'] . ':' . $Row['proofNum'], $thisProg);
	}
	else {
		_showerror('状态错误', '状态错误：请求删除的完成凭证已被使用，不能被删除');
	}
}

if ($_POST['Action'] == 'ImportProofSumbit') {
	include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
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
			$proofName = trim($csvData[0]);
			$proofNum = trim($csvData[1]);
			$proofPass = trim($csvData[2]);
			if (($proofName == '') || ($proofNum == '')) {
				continue;
			}

			$SQL = ' SELECT proofID FROM ' . SURVEYPROOF_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND proofNum=\'' . $proofNum . '\' LIMIT 1 ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				continue;
			}

			$SQL = ' INSERT INTO ' . SURVEYPROOF_TABLE . ' SET proofName=\'' . $proofName . '\',proofNum=\'' . $proofNum . '\',proofPass=\'' . $proofPass . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
			$DB->query($SQL);
			$recNum++;
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	writetolog($lang['new_import'] . $recNum . $lang['import_num'] . '(完成凭证)');
	_showsucceed($lang['new_import'] . $recNum . $lang['import_num'] . '(完成凭证)', $thisProg);
}

$EnableQCoreClass->setTemplateFile('ProofFile', 'SurveyProof.html');
$EnableQCoreClass->set_CycBlock('ProofFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$EnableQCoreClass->replace('t_proofNum', '');
$SQL = ' SELECT * FROM ' . SURVEYPROOF_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';

if ($_POST['Action'] == 'querySubmit') {
	if (trim($_POST['t_proofNum']) != '') {
		$SQL .= ' AND proofNum LIKE BINARY \'%' . trim($_POST['t_proofNum']) . '%\' ';
		$EnableQCoreClass->replace('t_proofNum', trim($_POST['t_proofNum']));
	}
}

$SQL .= ' ORDER BY proofID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('proofName', $Row['proofName']);
	$EnableQCoreClass->replace('proofNum', $Row['proofNum']);
	$EnableQCoreClass->replace('proofPass', $Row['proofPass']);

	if ($Row['dataID'] != 0) {
		$EnableQCoreClass->replace('statusColor', '#ffffff');
		$EnableQCoreClass->replace('status', '已使用');
		$hSQL = ' SELECT responseID,authStat FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID = \'' . $Row['dataID'] . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$dataID = '<a href="' . ROOT_PATH . 'Analytics/DataList.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&Does=View&responseID=' . $hRow['responseID'] . '" target=_blank>查看</a>';
			$EnableQCoreClass->replace('dataID', $dataID);
			$EnableQCoreClass->replace('dataStat', $lang['authStat_' . $hRow['authStat']]);

			switch ($hRow['authStat']) {
			case '0':
				$EnableQCoreClass->replace('authColor', '#ffffff');
				break;

			case '1':
				$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/igreen.png) repeat-y top left');
				break;

			case '2':
				$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/ired.png) repeat-y top left');
				break;

			case '3':
				$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iyellow.png) repeat-y top left');
				break;
			}
		}
		else {
			$EnableQCoreClass->replace('dataID', $Row['dataID']);
			$EnableQCoreClass->replace('dataStat', '已删除');
			$EnableQCoreClass->replace('authColor', '#ffffff');
		}

		$EnableQCoreClass->replace('isHaveData', 'none');
	}
	else {
		$EnableQCoreClass->replace('statusColor', '#cc0000');
		$EnableQCoreClass->replace('status', '<font color=white>未使用</font>');
		$EnableQCoreClass->replace('dataID', '-');
		$EnableQCoreClass->replace('dataStat', '-');
		$EnableQCoreClass->replace('authColor', '#ffffff');
		$deleteURL = $thisProg . '&DO=Delete&proofID=' . $Row['proofID'] . '&proofName=' . urlencode($Row['proofName']) . '&proofNum=' . urlencode($Row['proofNum']);
		$EnableQCoreClass->replace('deleteURL', $deleteURL);
		$EnableQCoreClass->replace('isHaveData', '');
	}

	$EnableQCoreClass->parse('list', 'LIST', true);
}

$EnableQCoreClass->replace('csvFileName', str_replace('+', '%2B', base64_encode('CSV_Sample_Proof.csv')));
$EnableQCoreClass->parse('ProofPage', 'ProofFile');
$EnableQCoreClass->output('ProofPage');

?>

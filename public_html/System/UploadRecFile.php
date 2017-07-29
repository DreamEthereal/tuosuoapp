<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$thisProg = 'UploadRecFile.php?surveyID=' . $_GET['surveyID'] . '&responseID=' . $_GET['responseID'];
_checkroletype('4');
$SQL = ' SELECT authStat,recordFile FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
$R_Row = $DB->queryFirstRow($SQL);
$dSQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$dRow = $DB->queryFirstRow($dSQL);

if ($dRow['custDataPath'] == '') {
	$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
}
else {
	$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $dRow['custDataPath'] . '/';
}

$fileName = $filePath . trim($R_Row['recordFile']);
if (($R_Row['authStat'] == 1) || (($R_Row['recordFile'] != '') && file_exists($fileName))) {
	_showerror('状态错误', '状态错误：该回复数据已被审核通过或已存在全程录音|录像文件');
}

if ($_POST['Action'] == 'AddRecFileSubmit') {
	$tmpFilePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if ($dRow['custDataPath'] == '') {
		$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_POST['surveyID'] . '/';
	}
	else {
		$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $dRow['custDataPath'] . '/';
	}

	createdir($filePhyPath);
	$theUploadFileName = trim($_POST['recordFile']);

	if (file_exists($tmpFilePhyPath . $theUploadFileName)) {
		if (copy($tmpFilePhyPath . $theUploadFileName, $filePhyPath . 'r_' . $theUploadFileName)) {
			@unlink($tmpFilePhyPath . $theUploadFileName);
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET recordFile = \'r_' . $theUploadFileName . '\' WHERE responseID=\'' . $_POST['responseID'] . '\' ';
			$DB->query($SQL);
		}
		else {
			_showerror('系统错误', '系统错误：复制全程录音|录像文件到目标文件夹出现错误，请重新上传');
		}
	}
	else {
		_showerror('系统错误', '系统错误：存储在临时文件夹的全程录音|录像文件不存在，请重新上传');
	}

	writetolog('重新上传全程录音|录像文件，数据序号:' . $_POST['responseID']);
	_showmessage('重新上传全程录音|录像文件', true);
}

$EnableQCoreClass->setTemplateFile('UploadRecFilePage', 'UploadRecFile.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('responseID', $_GET['responseID']);
$EnableQCoreClass->replace('session_id', session_id());
$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

if ($POST_MAX_SIZE) {
	$thePostMaxSize = (int) ($multiplier * (int) $POST_MAX_SIZE) / 1048576;
	$EnableQCoreClass->replace('maxSize', $thePostMaxSize);
}
else {
	$EnableQCoreClass->replace('maxSize', 2);
}

$EnableQCoreClass->replace('allowType', '*.zip;*.rar;*.flv;*.mp4;*.mp3;*.wav;*.dat;*.amr;*.mpg;*.mpeg;*.wmv;*.asf');
$EnableQCoreClass->parse('UploadRecFile', 'UploadRecFilePage');
$EnableQCoreClass->output('UploadRecFile');

?>

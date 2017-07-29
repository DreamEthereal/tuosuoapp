<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
@set_time_limit(0);

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'11\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC  ';
$Result = $DB->query($SQL);
$theUploadFileId = array();

while ($Row = $DB->queryArray($Result)) {
	$theUploadFileId[] = 'option_' . $Row['questionID'];
}

$hSQL = ' SELECT isRecord,isUploadRec,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$hRow = $DB->queryFirstRow($hSQL);

if (count($theUploadFileId) == 0) {
	if ($hRow['isRecord'] == 0) {
		_showerror($lang['error_system'], $lang['have_no_upload_type']);
	}
}

if (!isset($_GET['responseID']) || ($_GET['responseID'] == '')) {
	_showerror($lang['error_system'], $lang['have_no_upload_type']);
}

include_once ROOT_PATH . 'Includes/Tar.class.php';
$zip = new Zip();

if (count($theUploadFileId) == 0) {
	$SQL = ' SELECT recordFile,joinTime FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE responseID = \'' . $_GET['responseID'] . '\' ';
}
else {
	$SQL = ' SELECT recordFile,joinTime,' . implode(',', $theUploadFileId) . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE responseID = \'' . $_GET['responseID'] . '\'';
}

$R_Row = $DB->queryFirstRow($SQL);
$haveFile = 0;

if ($R_Row['recordFile'] != '') {
	if ($hRow['custDataPath'] == '') {
		$filePath = '../' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
	}
	else {
		$filePath = '../' . $Config['dataDirectory'] . '/user/' . $hRow['custDataPath'] . '/';
	}

	if (($R_Row['recordFile'] != '') && file_exists($filePath . $R_Row['recordFile'])) {
		$haveFile++;
		$zip->addFile(file_get_contents($filePath . $R_Row['recordFile']), $R_Row['recordFile'], filectime($filePath . $R_Row['recordFile']));
	}
}

foreach ($theUploadFileId as $theQtnId) {
	if ($hRow['custDataPath'] == '') {
		$filePath = '../' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
	}
	else {
		$filePath = '../' . $Config['dataDirectory'] . '/user/' . $hRow['custDataPath'] . '/';
	}

	if (($R_Row[$theQtnId] != '') && file_exists($filePath . $R_Row[$theQtnId])) {
		$haveFile++;
		$zip->addFile(file_get_contents($filePath . $R_Row[$theQtnId]), $R_Row[$theQtnId], filectime($filePath . $R_Row[$theQtnId]));
	}
}

if ($haveFile == 0) {
	_showerror($lang['error_system'], $lang['no_download_file']);
}

if (ini_get('zlib.output_compression')) {
	ini_set('zlib.output_compression', 'Off');
}

$zip->finalize();
$zipData = $zip->getZipData();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename="survey_uploadfile_' . $_GET['surveyName'] . '_data_' . $_GET['responseID'] . '_' . date('Y-m-d') . '.zip";');
echo $zipData;
exit();

?>

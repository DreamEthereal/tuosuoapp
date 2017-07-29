<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.export.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$hSQL = ' SELECT status,administratorsID,surveyID,isCache,isRecord,isUploadRec,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$hRow = $DB->queryFirstRow($hSQL);

if ($hRow['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$theSID = $hRow['surveyID'];
if (($hRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $hRow['surveyID'] . '/' . md5('Qtn' . $hRow['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if ($_POST['Action'] == 'ExportDataSubmit') {
	$exportSQL = base64_decode($_SESSION['dataSQL']);
	$surveyID = (int) $_POST['surveyID'];
	$theExportQtnList = $_POST['exportQtnList'];
	$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'11\' AND surveyID = \'' . $surveyID . '\' ORDER BY orderByID ASC  ';
	$Result = $DB->query($SQL);
	$theUploadFileId = array();

	while ($Row = $DB->queryArray($Result)) {
		$theUploadFileId[] = 'option_' . $Row['questionID'];
	}

	if (count($theUploadFileId) == 0) {
		if ($hRow['isRecord'] == 0) {
			_showerror($lang['error_system'], $lang['have_no_upload_type']);
		}
	}

	include_once ROOT_PATH . 'Includes/Tar.class.php';
	$zip = new Zip();

	if (count($theUploadFileId) == 0) {
		$SQL = ' SELECT recordFile,joinTime FROM ' . $table_prefix . 'response_' . $surveyID . ' b ';
	}
	else {
		$SQL = ' SELECT recordFile,joinTime,' . implode(',', $theUploadFileId) . ' FROM ' . $table_prefix . 'response_' . $surveyID . ' b ';
	}

	if (trim($exportSQL) == '') {
		$SQL .= ' WHERE ' . getdatasourcesql('all', $surveyID);
	}
	else {
		$SQL .= ' WHERE ' . $exportSQL . ' ';
	}

	$SQL .= ' ORDER BY responseID DESC ';
	$Result = $DB->query($SQL);
	$haveFile = 0;

	while ($R_Row = $DB->queryArray($Result)) {
		if (0 < count($theExportQtnList)) {
			if (in_array('rec', $theExportQtnList)) {
				if ($R_Row['recordFile'] != '') {
					if ($hRow['custDataPath'] == '') {
						$filePath = '../' . $Config['dataDirectory'] . '/response_' . $surveyID . '/';
					}
					else {
						$filePath = '../' . $Config['dataDirectory'] . '/user/' . $hRow['custDataPath'] . '/';
					}

					if (($R_Row['recordFile'] != '') && file_exists($filePath . $R_Row['recordFile'])) {
						$haveFile++;
						$zip->addFile(file_get_contents($filePath . $R_Row['recordFile']), $R_Row['recordFile'], filectime($filePath . $R_Row['recordFile']));
					}
				}
			}
		}
		else if ($R_Row['recordFile'] != '') {
			if ($hRow['custDataPath'] == '') {
				$filePath = '../' . $Config['dataDirectory'] . '/response_' . $surveyID . '/';
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
				$filePath = '../' . $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
			}
			else {
				$filePath = '../' . $Config['dataDirectory'] . '/user/' . $hRow['custDataPath'] . '/';
			}

			if (0 < count($theExportQtnList)) {
				$theQtnArray = explode('_', $theQtnId);

				if (in_array($theQtnArray[1], $theExportQtnList)) {
					if (($R_Row[$theQtnId] != '') && file_exists($filePath . $R_Row[$theQtnId])) {
						$haveFile++;
						$zip->addFile(file_get_contents($filePath . $R_Row[$theQtnId]), $R_Row[$theQtnId], filectime($filePath . $R_Row[$theQtnId]));
					}
				}

				unset($theQtnArray);
			}
			else {
				if (($R_Row[$theQtnId] != '') && file_exists($filePath . $R_Row[$theQtnId])) {
					$haveFile++;
					$zip->addFile(file_get_contents($filePath . $R_Row[$theQtnId]), $R_Row[$theQtnId], filectime($filePath . $R_Row[$theQtnId]));
				}
			}
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
	header('Content-Disposition: attachment; filename="survey_uploadfile_' . $surveyID . '_' . date('Y-m-d') . '.zip";');
	echo $zipData;
	exit();
}

$EnableQCoreClass->setTemplateFile('ExportVariableFile', 'ExportUpload.html');
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('exportQtnList', '');
$EnableQCoreClass->replace('exportSQL', base64_decode($_SESSION['dataSQL']));
$questionList = '';

if ($hRow['isRecord'] != 0) {
	$questionList .= '<option value=\'rec\'>全程录音|录像文件</option>' . "\n" . '';
}

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if ($theQtnArray['questionType'] == 11) {
		$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . '_' . $questionID . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>' . "\n" . '';
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$EnableQCoreClass->parse('ExportVariablePage', 'ExportVariableFile');
$EnableQCoreClass->output('ExportVariablePage');

?>

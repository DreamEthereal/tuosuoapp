<?php
//dezend by http://www.yunlu99.com/
function qconversionstring($string)
{
	$string = strip_tags($string);
	$string = str_replace('"', '""', $string);
	$string = str_replace('&quot;', '""', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$SQL = ' SELECT status,surveyName,surveyID,isCache,surveyTitle,projectType,projectOwner,isRecord,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$SRow = $DB->queryFirstRow($SQL);

if ($SRow['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if ($SRow['custDataPath'] == '') {
	_showerror($lang['system_error'], '“更新附件数据”功能仅在问卷回复数据附属文件的物理存储路径自定义模式下开放！');
}

$theSID = $SRow['surveyID'];
if (($SRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $SRow['surveyID'] . '/' . md5('Qtn' . $SRow['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if ($_POST['Action'] == 'ImportSubmit') {
	@set_time_limit(0);
	$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if (!file_exists($File_DIR_Name . $_POST['csvFile'])) {
		_showerror($lang['error_system'], '检查错误：进行数据导入的文件在物理磁盘中不存在：' . $_POST['csvFile']);
	}

	$tmpExt = explode('.', $_POST['csvFile']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);

	if ($extension == 'csv') {
		$newCsvFullName = $File_DIR_Name . trim($_POST['csvFile']);
	}
	else {
		_showerror($lang['error_system'], '选择上传的数据导入文件不是.csv格式，请重新上传');
	}

	if ($SRow['isRecord'] != 0) {
		$theCsvColNum = $theRecColNum = 7;
	}

	$this_fields_list = '';
	$option_tran_array = array();

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if ($theQtnArray['questionType'] == '11') {
			$surveyID = $theSID;
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.import.header.php';
		}
	}

	$this_fields_list = substr($this_fields_list, 0, -1);
	$thisSurveyFields = explode('|', $this_fields_list);
	$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $SRow['custDataPath'] . '/';
	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newCsvFullName, 'r');
	$recNum = 0;

	while ($csvData = _fgetcsv($File)) {
		$csvData = qaddslashes($csvData, 1);

		if (trim($csvData[0]) == '') {
			continue;
		}
		else {
			$hSQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $SRow['surveyID'] . ' WHERE responseID = \'' . trim($csvData[0]) . '\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				continue;
			}
		}

		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $SRow['surveyID'] . ' SET ';

		if ($SRow['isRecord'] != 0) {
			$this_file_name = trim($csvData[$theRecColNum]);

			if ($_POST['fileUploaded'] == 1) {
				$SQL .= ' recordFile=\'' . $this_file_name . '\',';
			}
			else {
				if (($this_file_name != '') && file_exists($repFilePath . $this_file_name)) {
					$SQL .= ' recordFile=\'' . $this_file_name . '\',';
				}
				else {
					$SQL .= ' recordFile=\'\',';
				}
			}
		}

		foreach ($thisSurveyFields as $theFields) {
			$option_array = explode('#', $theFields);

			switch ($option_array[1]) {
			case 11:
				$this_file_name = trim($csvData[$option_array[0]]);

				if ($_POST['fileUploaded'] == 1) {
					$SQL .= $option_array[2] . '=\'' . $this_file_name . '\',';
				}
				else {
					if (($this_file_name != '') && file_exists($repFilePath . $this_file_name)) {
						$SQL .= $option_array[2] . '=\'' . $this_file_name . '\',';
					}
					else {
						$SQL .= $option_array[2] . '=\'\',';
					}
				}

				break;
			}
		}

		$SQL .= ' responseID = \'' . trim($csvData[0]) . '\' WHERE responseID = \'' . trim($csvData[0]) . '\' ';
		$DB->query($SQL);
		$recNum++;
	}

	fclose($File);

	if (file_exists($File_DIR_Name . trim($_POST['csvFile']))) {
		@unlink($File_DIR_Name . trim($_POST['csvFile']));
	}

	$SQL = ' OPTIMIZE TABLE ' . $table_prefix . 'response_' . $SRow['surveyID'] . ' ';
	$DB->query($SQL);
	unset($option_tran_array);
	writetolog($lang['update_import'] . $recNum . $lang['import_survey_data']);
	_showmessage($lang['update_import'] . $recNum . $lang['import_survey_data'], true);
}

if ($_GET['Action'] == 'ExportSample') {
	$content = '';
	$header = '"' . $lang['export_responseID'] . '"';
	$header .= ',"' . $lang['export_joinTime'] . '"';
	$header .= ',"' . $lang['export_overFlag'] . '"';
	$header .= ',"' . $lang['export_server'] . '"';
	$header .= ',"' . $lang['export_ipAddress'] . '"';
	$header .= ',"' . $lang['export_area'] . '"';
	$header .= ',"' . $lang['export_administratorsName'] . '"';

	if ($SRow['isRecord'] != 0) {
		$header .= ',"' . $lang['export_recFile'] . '"';
	}

	$this_fields_list = '';
	$option_tran_array = array();

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if ($theQtnArray['questionType'] == '11') {
			$surveyID = $SRow['surveyID'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
		}
	}

	$header .= "\r\n";
	$content .= $header;
	$this_fields_list = substr($this_fields_list, 0, -1);
	$thisSurveyFields = explode('|', $this_fields_list);
	$ValueSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $SRow['surveyID'] . ' ORDER BY responseID DESC ';
	$ValueResult = $DB->query($ValueSQL);

	while ($ListRow = $DB->queryArray($ValueResult)) {
		$content .= '"' . $ListRow['responseID'] . '"';
		$content .= ',"' . date('Y-m-d H:i:s', $ListRow['joinTime']) . '	"';

		switch ($ListRow['overFlag']) {
		case '0':
		default:
			$content .= ',"' . $lang['result_no_all'] . '"';
			break;

		case '1':
			$content .= ',"' . $lang['result_have_all'] . '"';
			break;

		case '2':
			$content .= ',"' . $lang['result_to_quota'] . '"';
			break;

		case '3':
			$content .= ',"' . $lang['result_in_export'] . '"';
			break;
		}

		switch ($ListRow['dataSource']) {
		case '0':
		default:
			$dataForm = '未知数据来源';
			break;

		case '1':
			$dataForm = 'PC浏览器';
			break;

		case '2':
			$dataForm = '移动浏览器';
			break;

		case '3':
			$dataForm = '安卓样本App';
			break;

		case '4':
			$dataForm = 'PC访员录入';
			break;

		case '5':
			$dataForm = '在线访员App';
			break;

		case '6':
			$dataForm = '离线访员App';
			break;

		case '7':
			$dataForm = 'Excel数据导入';
			break;

		case '8':
			$dataForm = '问卷数据迁移';
			break;
		}

		$content .= ',"' . $dataForm . '"';
		$content .= ',"' . qconversionstring($ListRow['ipAddress']) . '"';
		$content .= ',"' . qconversionstring($ListRow['area']) . '"';
		$content .= ',"' . qconversionstring($ListRow['administratorsName']) . '"';

		if ($SRow['isRecord'] != 0) {
			if ($ListRow['recordFile'] != '') {
				$filePath = $Config['dataDirectory'] . '/user/' . $SRow['custDataPath'] . '/';

				if (file_exists($Config['absolutenessPath'] . '/' . $filePath . $ListRow['recordFile'])) {
					$content .= ',"' . $ListRow['recordFile'] . '"';
				}
				else {
					$content .= ',""';
				}
			}
			else {
				$content .= ',""';
			}

			foreach ($thisSurveyFields as $theFields) {
				if (strpos($theFields, '#') === false) {
				}
				else {
					$option_array = explode('#', $theFields);

					switch ($option_array[0]) {
					case '11':
						if ($ListRow[$option_array[1]] != '') {
							$filePath = $Config['dataDirectory'] . '/user/' . $SRow['custDataPath'] . '/';

							if (file_exists($Config['absolutenessPath'] . '/' . $filePath . $ListRow[$option_array[1]])) {
								$content .= ',"' . $ListRow[$option_array[1]] . '"';
							}
						}
						else {
							$content .= ',""';
						}

						break;
					}
				}
			}

			$content .= "\r\n";
		}
	}

	unset($option_tran_array);
	ob_start();
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment;filename=UpdateUpload_Sample_' . $SRow['surveyName'] . '_' . date('Y-m-d') . '.csv');
	echo $content;
	exit();
}

$EnableQCoreClass->setTemplateFile('UsersImportFile', 'ImportUpload.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('absPath', $Config['absolutenessPath']);
$EnableQCoreClass->replace('custDataPath', $SRow['custDataPath']);
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

$EnableQCoreClass->replace('allowType', '*.csv');
$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
$EnableQCoreClass->output('UsersImport');

?>

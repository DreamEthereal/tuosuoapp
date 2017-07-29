<?php
//dezend by http://www.yunlu99.com/
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
_checkpassport('1|2|4|5', $_GET['surveyID']);

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$SQL = ' SELECT status,surveyName,surveyID,isCache,surveyTitle,projectType,projectOwner,isRecord,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$SRow = $DB->queryFirstRow($SQL);

if ($SRow['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

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
		$tmpDataFilePath = $File_DIR_Name;
		$newCsvFullName = $File_DIR_Name . trim($_POST['csvFile']);
	}
	else if ($extension == 'zip') {
		include_once ROOT_PATH . 'Includes/Unzip.class.php';
		$zip = new PHPUnzip();
		$open = $zip->Open($File_DIR_Name . $_POST['csvFile']);

		if (!$open) {
			_showerror($lang['error_system'], '检查错误：进行数据导入的文件在物理磁盘中不存在：' . $_POST['csvFile']);
		}

		$tmpDataFilePath = '../PerUserData/tmp/un' . time() . '/';
		createdir($tmpDataFilePath);

		if ($SRow['custDataPath'] == '') {
			$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $SRow['surveyID'] . '/';
		}
		else {
			$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $SRow['custDataPath'] . '/';
		}

		createdir($repFilePath);
		$zip->SetOption(ZIPOPT_FILE_OUTPUT, false);
		$zip->SetOption(ZIPOPT_OUTPUT_PATH, $tmpDataFilePath);
		$zip->SetOption(ZIPOPT_OVERWRITE_EXISTING, true);
		$success = $zip->Read();

		if (!$success) {
			_showerror($lang['error_system'], 'Error ' . $zip->error . ' encountered: ' . $zip->error_str . '.');
		}

		if (0 < sizeof($zip->files)) {
			foreach ($zip->files as $file) {
				if ($file->error != E_NO_ERROR) {
					_showerror($lang['error_system'], 'Error ' . $file->error . ' while extracting "' . $file->name . '"');
				}
				else if (0 < $file->size) {
					$zip->WriteDataToFile($file->data, $file->name, $file->path);
				}
			}
		}

		$newCsvFullName = $tmpDataFilePath . 'survey_data_list.csv';

		if (!file_exists($newCsvFullName)) {
			_showerror($lang['error_system'], '检查错误：进行数据导入的压缩文件中不存在文件名为survey_data_list.csv的数据文件');
		}
	}
	else {
		_showerror($lang['error_system'], '选择上传的数据导入文件不是.csv|.zip格式，请重新上传');
	}

	if (($SRow['projectType'] == 1) && ($_SESSION['adminRoleType'] == '4')) {
		$theCsvColNum = $theRecColNum = 2;
	}
	else {
		$theCsvColNum = $theRecColNum = 3;
	}

	if ($SRow['isRecord'] != 0) {
		$theCsvColNum++;
		$theRecColNum++;
	}

	$this_fields_list = '';
	$option_tran_array = array();
	$theSID = $SRow['surveyID'];
	if (($SRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (!in_array($theQtnArray['questionType'], array('8', '9', '12', '13'))) {
			$surveyID = $theSID;
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.import.header.php';
		}
	}

	$this_fields_list = substr($this_fields_list, 0, -1);
	$thisSurveyFields = explode('|', $this_fields_list);
	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newCsvFullName, 'r');
	$recNum = 0;

	while ($csvData = _fgetcsv($File)) {
		$csvData = qaddslashes($csvData, 1);

		if ($SRow['projectType'] == 1) {
			if ($_SESSION['adminRoleType'] != '4') {
				if ($SRow['isRecord'] != 0) {
					if ((trim($csvData[0]) == '') && (trim($csvData[1]) == '') && (trim($csvData[2]) == '') && (trim($csvData[3]) == '') && (trim($csvData[5]) == '')) {
						continue;
					}
				}
				else {
					if ((trim($csvData[0]) == '') && (trim($csvData[1]) == '') && (trim($csvData[2]) == '') && (trim($csvData[3]) == '') && (trim($csvData[4]) == '')) {
						continue;
					}
				}
			}
			else if ($SRow['isRecord'] != 0) {
				if ((trim($csvData[0]) == '') && (trim($csvData[1]) == '') && (trim($csvData[2]) == '') && (trim($csvData[4]) == '')) {
					continue;
				}
			}
			else {
				if ((trim($csvData[0]) == '') && (trim($csvData[1]) == '') && (trim($csvData[2]) == '') && (trim($csvData[3]) == '')) {
					continue;
				}
			}
		}
		else if ($SRow['isRecord'] != 0) {
			if ((trim($csvData[0]) == '') && (trim($csvData[1]) == '') && (trim($csvData[5]) == '')) {
				continue;
			}
		}
		else {
			if ((trim($csvData[0]) == '') && (trim($csvData[1]) == '') && (trim($csvData[4]) == '')) {
				continue;
			}
		}

		if (trim($csvData[0]) == '') {
			$joinTime = time();
		}
		else {
			$theJoinTime = split(' ', trim($csvData[0]));

			if (strrpos($theJoinTime[0], '-') != false) {
				$theDate = explode('-', $theJoinTime[0]);
			}
			else if (strrpos($theJoinTime[0], '/') != false) {
				$theDate = explode('/', $theJoinTime[0]);
			}
			else {
				$theDate[0] = substr($theJoinTime[0], 0, 4);
				$theDate[1] = substr($theJoinTime[0], 4, 2);
				$theDate[2] = substr($theJoinTime[0], 6, 2);
			}

			$theTime = explode(':', $theJoinTime[1]);
			$joinTime = mktime($theTime[0], $theTime[1], $theTime[2], $theDate[1], $theDate[2], $theDate[0]);
		}

		$overTime = (trim($csvData[1]) == '' ? 0 : trim($csvData[1]));

		if ($SRow['projectType'] == 1) {
			$taskID = trim($csvData[2]);

			if ($_SESSION['adminRoleType'] != '4') {
				$area = trim($csvData[3]);
				$uSQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =4 AND administratorsName = \'' . trim($csvData[3]) . '\' ';
				$uRow = $DB->queryFirstRow($uSQL);

				if (!$uRow) {
					continue;
				}

				$theUserId = $uRow['administratorsID'];
			}
			else {
				$area = $_SESSION['administratorsName'];
				$theUserId = $_SESSION['administratorsID'];
			}

			$hSQL = ' SELECT administratorsID FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID=\'' . $theUserId . '\' AND surveyID=\'' . $SRow['surveyID'] . '\' LIMIT 0,1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				continue;
			}

			$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $SRow['projectOwner'] . '-%\' OR userGroupID = \'' . $SRow['projectOwner'] . '\') ';
			$hSQL = ' SELECT userGroupID,userGroupName,userGroupDesc FROM ' . USERGROUP_TABLE . ' WHERE groupType = 2 AND ' . $theSonSQL . ' AND userGroupID = \'' . $taskID . '\' AND isLeaf=1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				continue;
			}

			$administrators_Name = $hRow['userGroupName'];
			$ipAddress = $hRow['userGroupDesc'];
			$hSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $SRow['surveyID'] . '\' AND administratorsID = \'' . $theUserId . '\' AND taskID = \'' . $taskID . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				continue;
			}

			$hSQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $SRow['surveyID'] . ' WHERE taskID = \'' . $taskID . '\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				continue;
			}
		}
		else {
			$ipAddress = (trim($csvData[2]) == '' ? _getip() : trim($csvData[2]));
			$administrators_Name = trim($csvData[3]);
			$area = $_SESSION['administratorsName'];
		}

		$submitTime = $joinTime + $overTime;
		$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET joinTime=\'' . $joinTime . '\',overTime=\'' . $overTime . '\',submitTime=\'' . $submitTime . '\',ipAddress=\'' . $ipAddress . '\',administratorsName=\'' . $administrators_Name . '\',area=\'' . $area . '\',dataSource = \'7\',';

		if ($SRow['projectType'] == 1) {
			$SQL .= ' taskID=\'' . $taskID . '\',';
		}

		if ($SRow['isRecord'] != 0) {
			$this_file_name = trim($csvData[$theRecColNum]);

			if ($_POST['fileUploaded'] == 1) {
				$SQL .= ' recordFile=\'' . $this_file_name . '\',';
			}
			else {
				if (($this_file_name != '') && file_exists($tmpDataFilePath . $this_file_name)) {
					$t_tmpExt = explode('.', $this_file_name);
					$t_tmpNum = count($t_tmpExt) - 1;
					$t_extension = strtolower($t_tmpExt[$t_tmpNum]);
					$t_tmpFileName = basename($this_file_name, '.' . $t_extension);
					$t_newFileName = 'r_' . $t_tmpFileName . '_' . date('ymdHis') . rand(1, 999) . '.' . $t_extension;

					if (@copy($tmpDataFilePath . $this_file_name, $repFilePath . $t_newFileName)) {
						$SQL .= ' recordFile=\'' . $t_newFileName . '\',';
					}
					else {
						$SQL .= ' recordFile=\'\',';
					}
				}
				else {
					$SQL .= ' recordFile=\'\',';
				}
			}
		}

		foreach ($thisSurveyFields as $theFields) {
			$option_array = explode('#', $theFields);

			switch ($option_array[1]) {
			case 1:
				if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[2]])) {
					$SQL .= $option_array[3] . '=0,';
				}
				else {
					$SQL .= $option_array[3] . '=' . $option_tran_array[$option_array[2]][trim($csvData[$option_array[0]])] . ',';
				}

				break;

			case 2:
				if ($option_array[2] == 0) {
					$theRadioColNum = $option_array[0] - 1;

					if ($QtnListArray[$option_array[4]]['otherCode'] != 0) {
						$validCode = $QtnListArray[$option_array[4]]['otherCode'];
					}
					else {
						$validCode = '99';
					}

					if (trim($csvData[$theRadioColNum]) != $validCode) {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[2]])) {
					$SQL .= $option_array[3] . '=0,';
				}
				else {
					$SQL .= $option_array[3] . '=' . $option_tran_array[$option_array[2]][trim($csvData[$option_array[0]])] . ',';
				}

				break;

			case 3:
				if ($option_array[5] == 1) {
					if ($option_array[2] == 0) {
						$theOtherOptionColNum = $option_array[4] - 1;

						if (trim($csvData[$theOtherOptionColNum]) != '1') {
							$SQL .= $option_array[3] . '=\'\',';
						}
						else {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
					}
					else if (trim($csvData[$option_array[4]]) == '1') {
						$SQL .= $option_array[3] . '=\'99999\',';
					}
					else {
						$theOptionValueList = '';
						$temp = $option_array[0];

						for (; $temp < $option_array[4]; $temp++) {
							if (trim($csvData[$temp]) == '1') {
								$theOptionValueList .= $option_tran_array[$option_array[2]][$temp] . ',';
							}
						}

						$theOptionValueList = substr($theOptionValueList, 0, -1);
						$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
					}
				}
				else if ($option_array[2] == 0) {
					if (trim($csvData[$option_array[4]]) != '1') {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else {
					$theOptionValueList = '';
					$temp = $option_array[0];

					for (; $temp <= $option_array[4]; $temp++) {
						if (trim($csvData[$temp]) == '1') {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$temp] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
					$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
				}

				break;

			case 4:
			case 23:
				if ($option_array[3] == 2) {
					if (trim($csvData[$option_array[0]]) == '99999') {
						$SQL .= $option_array[2] . '=\'\',' . $option_array[4] . '=\'1\',';
					}
					else {
						$SQL .= $option_array[2] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else {
					$SQL .= $option_array[2] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
				}

				break;

			case 30:
				switch ((int) trim($csvData[$option_array[0]])) {
				case 1:
					$SQL .= $option_array[2] . '=\'1\',';
					break;

				default:
					$SQL .= $option_array[2] . '=\'0\',';
					break;
				}

				break;

			case 11:
				$this_file_name = trim($csvData[$option_array[0]]);

				if ($_POST['fileUploaded'] == 1) {
					$SQL .= $option_array[2] . '=\'' . $this_file_name . '\',';
				}
				else {
					if (($this_file_name != '') && file_exists($tmpDataFilePath . $this_file_name)) {
						if ($SRow['custDataPath'] == '') {
							$uploadPath = $repFilePath . date('Y-m', $joinTime) . '/' . date('d', $joinTime) . '/';
							createdir($uploadPath);
						}
						else {
							$uploadPath = $repFilePath;
						}

						$theQtnTemp = explode('_', $option_array[2]);
						$theQtnId = $theQtnTemp[1];
						$t_tmpExt = explode('.', $this_file_name);
						$t_tmpNum = count($t_tmpExt) - 1;
						$t_extension = strtolower($t_tmpExt[$t_tmpNum]);
						$t_tmpFileName = basename($this_file_name, '.' . $t_extension);
						$t_newFileName = $theQtnId . '_' . $t_tmpFileName . '_' . date('ymdHis') . rand(1, 999) . '.' . $t_extension;

						if (@copy($tmpDataFilePath . $this_file_name, $uploadPath . $t_newFileName)) {
							$SQL .= $option_array[2] . '=\'' . $t_newFileName . '\',';
						}
						else {
							$SQL .= $option_array[2] . '=\'\',';
						}

						unset($theQtnTemp);
					}
					else {
						$SQL .= $option_array[2] . '=\'\',';
					}
				}

				break;

			case 5:
			case 14:
			case 22:
			case 27:
			case 29:
				$SQL .= $option_array[2] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
				break;

			case 31:
				if ($option_array[4] == 1) {
					if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[3]])) {
						$SQL .= $option_array[2] . '=\'0\',';
					}
					else {
						$SQL .= $option_array[2] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[3]])) {
					$SQL .= $option_array[2] . '=\'0\',';
				}
				else {
					$theFatherColNum = $option_array[0] - 1;

					if ($option_tran_array[$option_array[3]][trim($csvData[$option_array[0]])] != trim($csvData[$theFatherColNum])) {
						$SQL .= $option_array[2] . '=\'0\',';
					}
					else {
						$SQL .= $option_array[2] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}

				break;

			case 16:
				if ($option_array[2] == 0) {
					$theOtherColNum = $option_array[0] - 1;
					if ((trim($csvData[$theOtherColNum]) == '') || (trim($csvData[$theOtherColNum]) == '0')) {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else {
					$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
				}

				break;

			case 6:
				if ($option_array[2] == 0) {
					$theOtherColNum = $option_array[0] - 1;
					if ((trim($csvData[$theOtherColNum]) == '') || (trim($csvData[$theOtherColNum]) == '0')) {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[2]])) {
					$SQL .= $option_array[3] . '=0,';
				}
				else {
					$SQL .= $option_array[3] . '=' . $option_tran_array[$option_array[2]][trim($csvData[$option_array[0]])] . ',';
				}

				break;

			case 26:
			case 19:
				if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[2]])) {
					$SQL .= $option_array[3] . '=0,';
				}
				else {
					$SQL .= $option_array[3] . '=' . $option_tran_array[$option_array[2]][trim($csvData[$option_array[0]])] . ',';
				}

				break;

			case 7:
				if ($option_array[2] == 0) {
					$isHaveSelect = false;
					$temp = $option_array[5];

					for (; $temp <= $option_array[4]; $temp++) {
						if (trim($csvData[$temp]) == '1') {
							$isHaveSelect = true;
							break;
						}
					}

					if ($isHaveSelect == false) {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else {
					$theOptionValueList = '';
					$temp = $option_array[0];

					for (; $temp <= $option_array[4]; $temp++) {
						if (trim($csvData[$temp]) == '1') {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$temp] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
					$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
				}

				break;

			case 28:
				$theOptionValueList = '';
				$temp = $option_array[0];

				for (; $temp <= $option_array[4]; $temp++) {
					if (trim($csvData[$temp]) == '1') {
						$theOptionValueList .= $option_tran_array[$option_array[2]][$temp] . ',';
					}
				}

				$theOptionValueList = substr($theOptionValueList, 0, -1);
				$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
				break;

			case 10:
			case 20:
				if ($option_array[2] == 0) {
					if ($option_array[4] == 0) {
						$theRankColNum = $option_array[0] - 1;
						if ((trim($csvData[$theRankColNum]) == '') || (trim($csvData[$theRankColNum]) == '0')) {
							$SQL .= $option_array[3] . '=\'\',';
						}
						else {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else if (trim($csvData[$option_array[0]]) <= $option_array[4]) {
					$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
				}
				else {
					$SQL .= $option_array[3] . '=\'0\',';
				}

				break;

			case 24:
				if ($option_array[2] == 0) {
					if (trim($csvData[$option_array[4]]) != $option_array[5]) {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[2]])) {
					$SQL .= $option_array[3] . '=0,';
				}
				else {
					$SQL .= $option_array[3] . '=' . $option_tran_array[$option_array[2]][trim($csvData[$option_array[0]])] . ',';
				}

				break;

			case 25:
				if ($option_array[2] == 0) {
					$theCheckBoxColNum = $option_array[0] - 1;

					if (trim($csvData[$theCheckBoxColNum]) != '1') {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
				}
				else {
					$theOptionValueList = '';
					$temp = $option_array[0];

					for (; $temp <= $option_array[4]; $temp++) {
						if (trim($csvData[$temp]) == '1') {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$temp] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
					$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
				}

				break;

			case 15:
				switch ($option_array[4]) {
				case '1':
					if ($option_array[2] == 0) {
						if ($option_array[5] == '2') {
							if ($option_array[6] == '1') {
								$theOtherColNum = $option_array[0] - 2;
							}
							else {
								$theOtherColNum = $option_array[0] - 1;
							}

							if ((trim($csvData[$theOtherColNum]) == '') || (trim($csvData[$theOtherColNum]) == '0')) {
								$SQL .= $option_array[3] . '=\'\',';
							}
							else {
								$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
							}
						}
						else {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
					}
					else {
						if (($option_array[5] <= trim($csvData[$option_array[0]])) && (trim($csvData[$option_array[0]]) <= $option_array[6])) {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
						else {
							$SQL .= $option_array[3] . '=\'0\',';
						}
					}

					break;

				case '0':
				default:
					if ($option_array[2] == 0) {
						if ($option_array[5] == '2') {
							if ($option_array[6] == '1') {
								$theOtherColNum = $option_array[0] - 2;
							}
							else {
								$theOtherColNum = $option_array[0] - 1;
							}

							if ((trim($csvData[$theOtherColNum]) == '') || (trim($csvData[$theOtherColNum]) == '0')) {
								$SQL .= $option_array[3] . '=\'\',';
							}
							else {
								$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
							}
						}
						else {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
					}
					else {
						$theValueList = explode('*', $option_array[5]);

						if (in_array(trim($csvData[$option_array[0]]), $theValueList)) {
							if (trim($csvData[$option_array[0]]) != 99) {
								$theValue = trim($csvData[$option_array[0]]) / $option_array[6];
								$SQL .= $option_array[3] . '=\'' . $theValue . '\',';
							}
							else {
								$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
							}
						}
					}

					break;

				case '2':
					if ($option_array[2] == 0) {
						$theOtherColNum = $option_array[0] - 1;
						if ((trim($csvData[$theOtherColNum]) == '') || (trim($csvData[$theOtherColNum]) == '0')) {
							$SQL .= $option_array[3] . '=\'\',';
						}
						else {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
					}
					else {
						if (($option_array[5] <= trim($csvData[$option_array[0]])) && (trim($csvData[$option_array[0]]) <= $option_array[6])) {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
						else {
							$SQL .= $option_array[3] . '=\'0\',';
						}
					}

					break;
				}

				break;

			case 21:
				switch ($option_array[4]) {
				case '1':
					if ($option_array[2] == 0) {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
					else {
						if (($option_array[5] <= trim($csvData[$option_array[0]])) && (trim($csvData[$option_array[0]]) <= $option_array[6])) {
							$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
						}
						else {
							$SQL .= $option_array[3] . '=\'0\',';
						}
					}

					break;

				case '0':
				default:
					if ($option_array[2] == 0) {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
					else {
						$theValueList = explode('*', $option_array[5]);

						if (in_array(trim($csvData[$option_array[0]]), $theValueList)) {
							if (trim($csvData[$option_array[0]]) != 99) {
								$theValue = trim($csvData[$option_array[0]]) / $option_array[6];
								$SQL .= $option_array[3] . '=\'' . $theValue . '\',';
							}
							else {
								$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
							}
						}
					}

					break;

				case '2':
					if (($option_array[5] <= trim($csvData[$option_array[0]])) && (trim($csvData[$option_array[0]]) <= $option_array[6])) {
						$SQL .= $option_array[3] . '=\'' . trim($csvData[$option_array[0]]) . '\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'0\',';
					}

					break;
				}

				break;

			case 17:
			case 18:
				if ($option_array[4] == 1) {
					if (!array_key_exists(trim($csvData[$option_array[0]]), $option_tran_array[$option_array[2]])) {
						$SQL .= $option_array[3] . '=\'\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'' . $option_tran_array[$option_array[2]][trim($csvData[$option_array[0]])] . '\',';
					}
				}
				else {
					$theOptionValueList = '';
					$temp = $option_array[0];

					for (; $temp <= $option_array[5]; $temp++) {
						if (trim($csvData[$temp]) == '1') {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$temp] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
					$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
				}

				break;
			}
		}

		$SQL .= ' overFlag =3';
		$DB->query($SQL);
		$recNum++;
	}

	fclose($File);

	if (file_exists($File_DIR_Name . trim($_POST['csvFile']))) {
		@unlink($File_DIR_Name . trim($_POST['csvFile']));
	}

	if ($extension == 'zip') {
		deletedir($tmpDataFilePath);
	}

	$SQL = ' OPTIMIZE TABLE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' ';
	$DB->query($SQL);
	unset($option_tran_array);
	writetolog($lang['new_import'] . $recNum . $lang['import_survey_data']);
	_showmessage($lang['new_import'] . $recNum . $lang['import_survey_data'], true);
}

if ($_GET['Action'] == 'ExportSample') {
	include_once ROOT_PATH . 'Functions/Functions.excel.inc.php';
	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Content-Type: application/force-download');
	header('Content-Type: application/octet-stream');
	header('Content-Type: application/download');
	header('Content-Disposition: attachment;filename=Import_Sample_' . $SRow['surveyName'] . '.xls');
	header('Content-Transfer-Encoding: binary');
	xlsbof();
	$xlsRow = 0;
	xlswritelabel($xlsRow, 0, $SRow['surveyTitle'] . ':' . $lang['import_file_notes']);
	$xlsRow++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $lang['import_cols_1']);
	xlswritelabel($xlsRow, 1, $lang['import_cols_2']);
	xlswritelabel($xlsRow, 2, $lang['import_cols_3']);
	$theCsvColNum = 1;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, $lang['import_joinTime']);
	xlswritelabel($xlsRow, 2, $lang['import_joinTime_notes']);
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, $lang['import_overTime']);
	xlswritelabel($xlsRow, 2, $lang['import_overTime_notes']);

	if ($SRow['projectType'] == 1) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, $lang['import_userGroupName']);
		xlswritelabel($xlsRow, 2, $lang['import_userGroupName_notes']);

		if ($_SESSION['adminRoleType'] != '4') {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, $lang['import_inputerName']);
			xlswritelabel($xlsRow, 2, $lang['import_inputerName_notes']);
		}
	}
	else {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, $lang['import_ipAddress']);
		xlswritelabel($xlsRow, 2, $lang['import_ipAddress_notes']);
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, $lang['import_administratorsName']);
		xlswritelabel($xlsRow, 2, $lang['import_administratorsName_notes']);
	}

	if ($SRow['isRecord'] != 0) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, '全程录音|录像文件');
		xlswritelabel($xlsRow, 2, '录音|录像文件名，须与压缩包内物理文件的文件名保持一致，可为空');
	}

	$theSID = $SRow['surveyID'];
	if (($SRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (!in_array($theQtnArray['questionType'], array('8', '9', '12', '13'))) {
			$surveyID = $theSID;
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.import.sample.php';
		}
	}

	xlseof();
	exit();
}

$EnableQCoreClass->setTemplateFile('UsersImportFile', 'ImportData.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
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

$EnableQCoreClass->replace('allowType', '*.zip;*.csv');

if ($SRow['custDataPath'] == '') {
	$EnableQCoreClass->replace('fileUploaded', 'disabled');
}
else {
	$EnableQCoreClass->replace('fileUploaded', '');
}

$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
$EnableQCoreClass->output('UsersImport');

?>

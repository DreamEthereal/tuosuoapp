<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theSID == 0) {
	exit();
}

$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$cacheContent .= ' $licenseName = \'' . $License['UserCompany'] . '\';' . "\r\n" . ' ';
$all_survey_fields = 0;
$theColSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $theSID . ' ';
$theColResult = $DB->query($theColSQL);

while ($theColRow = $DB->queryArray($theColResult)) {
	$all_survey_fields++;
}

$cacheContent .= '$all_survey_fields = \'' . $all_survey_fields . '\';' . "\r\n" . ' ';
unset($all_survey_fields);
$valueLogicQtnList = array();

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
$this_fields_list = '';
$this_upload_list = 'recordFile,fingerFile,';
$option_tran_array = array();

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if ($theQtnArray['questionType'] != '9') {
		$surveyID = $theSID;
		$ModuleName = $Module[$theQtnArray['questionType']];

		switch ($theQtnArray['questionType']) {
		case '3':
		case '7':
		case '25':
		case '28':
		case '17':
		case '18':
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.dist.header.php';
			break;

		default:
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.spss.header.php';
			break;
		}
	}
}

$destinationPath = $Config['absolutenessPath'] . '/PerUserData/tmp/f' . $theSID . '/';
createdir($destinationPath);

if ($custDataPath == '') {
	$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $theSID . '/';
}
else {
	$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $custDataPath . '/';
}

createdir($repFilePath);
$this_fields_list = substr($this_fields_list, 0, -1);
$thisSurveyFields = explode('|', $this_fields_list);
$dataCacheContent = ' $DataListArray = array( ';
$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $theSID . ' ORDER BY responseID ASC ';
$Result = $DB->query($SQL);

while ($ListRow = $DB->queryArray($Result)) {
	$ListRow = qaddslashes(qquotetostring(qnl2br($ListRow), 1), 1);
	$dataCacheContent .= "\r\n" . '    ' . $ListRow['responseID'] . ' => ' . 'array(' . "\r\n" . '' . '\'administratorsGroupID\' => \'\',' . "\r\n" . '	 \'ajaxRtnValue_1\' => \'\',' . "\r\n" . '	 \'ajaxRtnValue_2\' => \'\',' . "\r\n" . '	 \'ajaxRtnValue_3\' => \'\',' . "\r\n" . '	 \'ajaxRtnValue_4\' => \'\',' . "\r\n" . '	 \'ajaxRtnValue_5\' => \'\',' . "\r\n" . '	 \'ajaxRtnValue_6\' => \'\',' . "\r\n" . '	 \'cateID\' => \'\',' . "\r\n" . '	 \'administratorsName\' => "' . $ListRow['administratorsName'] . '",' . "\r\n" . '	 \'ipAddress\' => "' . $ListRow['ipAddress'] . '",' . "\r\n" . '	 \'area\' => "' . $ListRow['area'] . '",' . "\r\n" . '	 \'joinTime\' => "' . $ListRow['joinTime'] . '",' . "\r\n" . '	 \'submitTime\' => "' . $ListRow['submitTime'] . '",' . "\r\n" . '	 \'uploadTime\' => "' . $ListRow['uploadTime'] . '",' . "\r\n" . '	 \'overTime\' => "' . $ListRow['overTime'] . '",' . "\r\n" . '	 \'overFlag\' => "' . $ListRow['overFlag'] . '",' . "\r\n" . '	 \'overFlag0\' => "' . $ListRow['overFlag0'] . '",' . "\r\n" . '	 \'authStat\' => "' . $ListRow['authStat'] . '",' . "\r\n" . '	 \'version\' => "' . $ListRow['version'] . '",' . "\r\n" . '';
	if (($ListRow['recordFile'] != '') && file_exists($repFilePath . $ListRow['recordFile'])) {
		$tmpExt = explode('.', $ListRow['recordFile']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		$tmpFileName = basename($ListRow['recordFile'], '.' . $extension);
		$newFileName = $tmpFileName . '_' . rand(1, 999) . '.' . $extension;

		if (@copy($repFilePath . $ListRow['recordFile'], $destinationPath . $newFileName)) {
			$recordFile = $newFileName;
		}
		else {
			$recordFile = '';
		}
	}
	else {
		$recordFile = '';
	}

	$dataCacheContent .= '\'recordFile\' => "' . $recordFile . '",' . "\r\n" . '';

	if ($custDataPath == '') {
		$uploadPath = $repFilePath . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
	}
	else {
		$uploadPath = $repFilePath;
	}

	if (($ListRow['fingerFile'] != '') && file_exists($uploadPath . $ListRow['fingerFile'])) {
		$tmpExt = explode('.', $ListRow['fingerFile']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		$tmpFileName = basename($ListRow['fingerFile'], '.' . $extension);
		$newFileName = $tmpFileName . '_' . rand(1, 999) . '.' . $extension;

		if (@copy($uploadPath . $ListRow['fingerFile'], $destinationPath . $newFileName)) {
			$fingerFile = $newFileName;
		}
		else {
			$fingerFile = '';
		}
	}
	else {
		$fingerFile = '';
	}

	$dataCacheContent .= '\'fingerFile\' => "' . $fingerFile . '",' . "\r\n" . '';

	if ($ListRow['uniDataCode'] == '') {
		$uniDataCode = str_replace('+', '%2B', base64_encode($License['UserCompany'] . '######' . $ListRow['responseID']));
	}
	else {
		$uniDataCode = $ListRow['uniDataCode'];
	}

	$dataCacheContent .= '\'uniDataCode\' => "' . $uniDataCode . '",' . "\r\n" . '';
	$hSQL = ' SELECT * FROM ' . ANDROID_INFO_TABLE . ' WHERE surveyID=\'' . $theSID . '\' AND responseID=\'' . $ListRow['responseID'] . '\' ';
	$hRow = $DB->queryFirstRow($hSQL);
	$dataCacheContent .= '\'line1Number\' => "' . $hRow['line1Number'] . '",' . "\r\n" . '		 \'deviceId\' => "' . $hRow['deviceId'] . '",' . "\r\n" . '		 \'brand\' => "' . $hRow['brand'] . '",' . "\r\n" . '		 \'model\' => "' . $hRow['model'] . '",' . "\r\n" . '		 \'currentCity\' => "' . $hRow['currentCity'] . '",' . "\r\n" . '		 \'simOperatorName\' => "' . $hRow['simOperatorName'] . '",' . "\r\n" . '		 \'simSerialNumber\' => "' . $hRow['simSerialNumber'] . '",' . "\r\n" . '';
	$this_fields_order = 22;
	$the_upload_map_array = array();

	foreach ($thisSurveyFields as $theFields) {
		$this_fields_name = 'q' . $this_fields_order;

		if (strpos($theFields, '#') === false) {
			$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $ListRow[$theFields] . '",' . "\r\n" . '';
		}
		else {
			$option_array = explode('#', $theFields);

			switch ($option_array[0]) {
			case '1':
			case '2':
			case '24':
			case '6':
			case '26':
			case '19':
				if ($ListRow[$option_array[2]] == '0') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "0",' . "\r\n" . '';
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '",' . "\r\n" . '';
				}

				break;

			case '4':
			case '23':
				if ($ListRow[$option_array[2]] == 1) {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "99999",' . "\r\n" . '';
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $ListRow[$option_array[1]] . '",' . "\r\n" . '';
				}

				break;

			case '31':
				if ($ListRow[$option_array[2]] == '0') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "0",' . "\r\n" . '';
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $CascadeArray[$option_array[1]][$ListRow[$option_array[2]]]['nodeID'] . '",' . "\r\n" . '';
				}

				break;

			case '3':
			case '7':
			case '25':
			case '28':
				if ($ListRow[$option_array[2]] == '') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
				}
				else {
					$option_value_array = explode(',', $ListRow[$option_array[2]]);
					$option_value_list = '';

					foreach ($option_value_array as $option_value) {
						if ($option_value == 0) {
							$option_value_list .= '0,';
						}
						else {
							$option_value_list .= $option_tran_array[$option_array[1]][$option_value] . ',';
						}
					}

					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . substr($option_value_list, 0, -1) . '",' . "\r\n" . '';
				}

				break;

			case '10':
			case '20':
			case '16':
			case '22':
				if ($ListRow[$option_array[1]] == '0') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "0",' . "\r\n" . '';
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $ListRow[$option_array[1]] . '",' . "\r\n" . '';
				}

				break;

			case '11':
				$this_upload_list = $this_fields_order . ',';

				if ($custDataPath == '') {
					$uploadPath = $repFilePath . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
				}
				else {
					$uploadPath = $repFilePath;
				}

				if (($ListRow[$option_array[1]] != '') && file_exists($uploadPath . $ListRow[$option_array[1]])) {
					$tmpExt = explode('.', $ListRow[$option_array[1]]);
					$tmpNum = count($tmpExt) - 1;
					$extension = strtolower($tmpExt[$tmpNum]);
					$tmpFileName = basename($ListRow[$option_array[1]], '.' . $extension);
					$newFileName = $tmpFileName . '_' . rand(1, 999) . '.' . $extension;

					if (@copy($uploadPath . $ListRow[$option_array[1]], $destinationPath . $newFileName)) {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $newFileName . '",' . "\r\n" . '';
					}
					else {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
					}
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
				}

				$theSplitTmp = explode('_', $option_array[1]);
				$the_upload_map_array[$theSplitTmp[1]] = $this_fields_order;
				unset($theSplitTmp);
				break;

			case '13':
				if ($ListRow[$option_array[2]] == '') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $ListRow[$option_array[2]] . '",' . "\r\n" . '';
				}

				break;

			case '15':
			case '21':
				if ($ListRow[$option_array[2]] != 99) {
					if ($ListRow[$option_array[2]] == '0') {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "0",' . "\r\n" . '';
					}
					else {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $ListRow[$option_array[2]] . '",' . "\r\n" . '';
					}
				}
				else {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $ListRow[$option_array[2]] . '",' . "\r\n" . '';
				}

				break;

			case '17':
				if ($option_array[3] == 1) {
					if ($ListRow[$option_array[2]] == '') {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "0",' . "\r\n" . '';
					}
					else {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '",' . "\r\n" . '';
					}
				}
				else if ($ListRow[$option_array[2]] == '') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
				}
				else {
					$option_value_array = explode(',', $ListRow[$option_array[2]]);
					$option_value_list = '';

					foreach ($option_value_array as $option_value) {
						if ($option_value == 0) {
							$option_value_list .= '0,';
						}
						else {
							$option_value_list .= $option_tran_array[$option_array[1]][$option_value] . ',';
						}
					}

					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . substr($option_value_list, 0, -1) . '",' . "\r\n" . '';
				}

				break;

			case '18':
				if ($option_array[3] == 0) {
					if ($ListRow[$option_array[2]] == '') {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
					}
					else {
						$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '",' . "\r\n" . '';
					}
				}
				else if ($ListRow[$option_array[2]] == '') {
					$dataCacheContent .= '\'' . $this_fields_name . '\' => "",' . "\r\n" . '';
				}
				else {
					$option_value_array = explode(',', $ListRow[$option_array[2]]);
					$option_value_list = '';

					foreach ($option_value_array as $option_value) {
						if ($option_value == 0) {
							$option_value_list .= '0,';
						}
						else {
							$option_value_list .= $option_tran_array[$option_array[1]][$option_value] . ',';
						}
					}

					$dataCacheContent .= '\'' . $this_fields_name . '\' => "' . substr($option_value_list, 0, -1) . '",' . "\r\n" . '';
				}

				break;
			}
		}

		$this_fields_order++;
	}

	$gSQL = ' SELECT * FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID=\'' . $theSID . '\' AND responseID=\'' . $ListRow['responseID'] . '\' ';
	$gResult = $DB->query($gSQL);
	$theGpsTraceList = '';

	while ($gRow = $DB->queryArray($gResult)) {
		$theGpsTraceList .= $gRow['gpsTime'] . '$$$';
		$theGpsTraceList .= $gRow['accuracy'] . '$$$';
		$theGpsTraceList .= $gRow['longitude'] . '$$$';
		$theGpsTraceList .= $gRow['latitude'] . '$$$';
		$theGpsTraceList .= $gRow['speed'] . '$$$';
		$theGpsTraceList .= $gRow['bearing'] . '$$$';
		$theGpsTraceList .= $gRow['altitude'] . '$$$';
		$theGpsTraceList .= $gRow['isCell'] . '###';
	}

	$dataCacheContent .= '\'gpsTrace\' => "' . substr($theGpsTraceList, 0, -3) . '",' . "\r\n" . '';
	$gSQL = ' SELECT * FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE surveyID=\'' . $theSID . '\' AND responseID=\'' . $ListRow['responseID'] . '\' ';
	$gResult = $DB->query($gSQL);
	$theGpsTraceList = '';

	while ($gRow = $DB->queryArray($gResult)) {
		$theGpsTraceList .= $gRow['gpsTime'] . '$$$';
		$theGpsTraceList .= $gRow['accuracy'] . '$$$';
		$theGpsTraceList .= $gRow['longitude'] . '$$$';
		$theGpsTraceList .= $gRow['latitude'] . '$$$';
		$theGpsTraceList .= $gRow['speed'] . '$$$';
		$theGpsTraceList .= $gRow['bearing'] . '$$$';
		$theGpsTraceList .= $gRow['altitude'] . '$$$';
		$theGpsTraceList .= $gRow['isCell'] . '$$$';
		$theGpsTraceList .= $the_upload_map_array[$gRow['qtnID']] . '###';
	}

	$dataCacheContent .= '\'gpsTraceUpload\' => "' . substr($theGpsTraceList, 0, -3) . '",' . "\r\n" . '';
	$dataCacheContent .= '\'replyPage\' => "' . $ListRow['replyPage'] . '",' . "\r\n" . '';
	$dataCacheContent = substr($dataCacheContent, 0, -3) . '' . "\r\n" . '   ),';
}

$dataCacheContent = substr($dataCacheContent, 0, -1) . '' . "\r\n" . ');';
unset($option_tran_array);
unset($the_upload_map_array);
$this_upload_list = substr($this_upload_list, 0, -1);
$cacheContent .= '$this_upload_list = \'' . $this_upload_list . '\';' . "\r\n" . ' ';
write_to_file($destinationPath . 'surveydata.qdata', $cacheContent . "\r\n" . $dataCacheContent);

?>

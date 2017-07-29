<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$this_fields_list = '';
$option_tran_array = array();
if (($SRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (!in_array($theQtnArray['questionType'], array('8', '9'))) {
		$surveyID = $theSID;
		$ModuleName = $Module[$theQtnArray['questionType']];

		switch ($theQtnArray['questionType']) {
		case '3':
		case '7':
		case '25':
		case '28':
		case '17':
		case '18':
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.import.dist.php';
			break;

		default:
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.import.header.php';
			break;
		}
	}
}

$this_fields_list = substr($this_fields_list, 0, -1);
$thisSurveyFields = explode('|', $this_fields_list);

if ($custDataPath == '') {
	$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $theSID . '/';
}
else {
	$repFilePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $custDataPath . '/';
}

createdir($repFilePath);

foreach ($DataListArray as $responseID => $ListRow) {
	$ListRow = qbr2nl(qquoteconvertstring($ListRow));
	$hSQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $theSID . ' WHERE uniDataCode = \'' . $ListRow['uniDataCode'] . '\' LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow) {
		continue;
	}

	$theCateID = 0;

	if ($ListRow['uniDataCode'] != '') {
		$this_uniDataCode = explode('######', base64_decode($ListRow['uniDataCode']));
		$cSQL = ' SELECT cateID FROM ' . SURVEYCATE_TABLE . ' WHERE cateName = \'' . $this_uniDataCode[0] . '\' LIMIT 1 ';
		$cRow = $DB->queryFirstRow($cSQL);

		if ($cRow) {
			$theCateID = $cRow['cateID'];
			$chSQL = ' SELECT cateID FROM ' . SURVEYCATELIST_TABLE . ' WHERE surveyID =\'' . $theSID . '\' and cateID = \'' . $theCateID . '\' LIMIT 1 ';
			$chRow = $DB->queryFirstRow($chSQL);

			if (!$chRow) {
				$cSQL = ' INSERT INTO ' . SURVEYCATELIST_TABLE . ' SET surveyID =\'' . $theSID . '\',cateID = \'' . $theCateID . '\' ';
				$DB->query($cSQL);
			}
		}
		else {
			$cSQL = ' INSERT INTO ' . SURVEYCATE_TABLE . ' SET cateName = \'' . $this_uniDataCode[0] . '\' ';
			$DB->query($cSQL);
			$theCateID = $DB->_GetInsertID();
			$cSQL = ' UPDATE ' . SURVEYCATE_TABLE . ' SET cateTag =\'cate_' . $theCateID . '\' WHERE cateID = \'' . $theCateID . '\' ';
			$DB->query($cSQL);
			$cSQL = ' INSERT INTO ' . SURVEYCATELIST_TABLE . ' SET surveyID =\'' . $theSID . '\',cateID = \'' . $theCateID . '\' ';
			$DB->query($cSQL);
		}
	}

	$ListRow = qaddslashes($ListRow, 1);
	$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $theSID . ' SET administratorsName =\'' . $ListRow['administratorsName'] . '\',ipAddress =\'' . $ListRow['ipAddress'] . '\',area =\'' . $ListRow['area'] . '\',joinTime =\'' . $ListRow['joinTime'] . '\',submitTime =\'' . $ListRow['submitTime'] . '\',uploadTime =\'' . $ListRow['uploadTime'] . '\',overTime =\'' . $ListRow['overTime'] . '\',overFlag =\'' . $ListRow['overFlag'] . '\',overFlag0 =\'' . $ListRow['overFlag0'] . '\',authStat =\'' . $ListRow['authStat'] . '\',replyPage =\'' . $ListRow['replyPage'] . '\',version =\'0\',adminID=\'0\',appStat=\'0\',uniDataCode =\'' . $ListRow['uniDataCode'] . '\',cateID=\'' . $theCateID . '\',dataSource = \'8\',';

	if ($ListRow['recordFile'] != '') {
		if (@copy($tmpDataFilePath . $ListRow['recordFile'], $repFilePath . $ListRow['recordFile'])) {
			$SQL .= ' recordFile = \'' . $ListRow['recordFile'] . '\',';
			@unlink($tmpDataFilePath . $ListRow['recordFile']);
		}
	}

	if ($ListRow['fingerFile'] != '') {
		if ($custDataPath == '') {
			$uploadPath = $repFilePath . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
			createdir($uploadPath);
		}
		else {
			$uploadPath = $repFilePath;
		}

		if (@copy($tmpDataFilePath . $ListRow['fingerFile'], $uploadPath . $ListRow['fingerFile'])) {
			$SQL .= ' fingerFile = \'' . $ListRow['fingerFile'] . '\',';
			@unlink($tmpDataFilePath . $ListRow['fingerFile']);
		}
	}

	$this_fields_order = 22;
	$the_upload_map_array = array();

	foreach ($thisSurveyFields as $theFields) {
		$this_fields_name = 'q' . $this_fields_order;
		$this_fields_value = $ListRow[$this_fields_name];
		$option_array = explode('#', $theFields);

		switch ($option_array[1]) {
		case 1:
		case 26:
		case 19:
			if (!array_key_exists($this_fields_value, $option_tran_array[$option_array[2]])) {
				$SQL .= $option_array[3] . '=\'0\',';
			}
			else {
				$SQL .= $option_array[3] . '=\'' . $option_tran_array[$option_array[2]][$this_fields_value] . '\',';
			}

			break;

		case 6:
			if ($option_array[2] == 0) {
				$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			}
			else if (!array_key_exists($this_fields_value, $option_tran_array[$option_array[2]])) {
				$SQL .= $option_array[3] . '=\'0\',';
			}
			else {
				$SQL .= $option_array[3] . '=\'' . $option_tran_array[$option_array[2]][$this_fields_value] . '\',';
			}

			break;

		case 11:
			if (($this_fields_value != '') && file_exists($tmpDataFilePath . $this_fields_value)) {
				if ($custDataPath == '') {
					$uploadPath = $repFilePath . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
					createdir($uploadPath);
				}
				else {
					$uploadPath = $repFilePath;
				}

				if (@copy($tmpDataFilePath . $this_fields_value, $uploadPath . $this_fields_value)) {
					$SQL .= $option_array[2] . '=\'' . $this_fields_value . '\',';
				}
				else {
					$SQL .= $option_array[2] . '=\'\',';
				}
			}
			else {
				$SQL .= $option_array[2] . '=\'\',';
			}

			$theSplitTmp = explode('_', $option_array[2]);
			$the_upload_map_array[$this_fields_order] = $theSplitTmp[1];
			unset($theSplitTmp);
			break;

		case 2:
		case 24:
			if ($option_array[2] == 0) {
				$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			}
			else if (!array_key_exists($this_fields_value, $option_tran_array[$option_array[2]])) {
				$SQL .= $option_array[3] . '=\'0\',';
			}
			else {
				$SQL .= $option_array[3] . '=\'' . $option_tran_array[$option_array[2]][$this_fields_value] . '\',';
			}

			break;

		case 3:
			if ($option_array[2] == 0) {
				$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			}
			else {
				$theOptionValueList = '';
				$otherValidCode = ($QtnListArray[$option_array[2]]['otherCode'] != 0 ? $QtnListArray[$option_array[2]]['otherCode'] : 99);
				$negValidCode = ($QtnListArray[$option_array[2]]['negCode'] != 0 ? $QtnListArray[$option_array[2]]['otherCode'] : 99999);

				if ($this_fields_value != '') {
					$theValueList = explode(',', $this_fields_value);

					foreach ($theValueList as $theValue) {
						if (($theValue == 0) || ($theValue == $otherValidCode)) {
							$theOptionValueList .= '0,';
						}
						else if ($theValue == $negValidCode) {
							$theOptionValueList .= '99999,';
						}
						else if (array_key_exists($theValue, $option_tran_array[$option_array[2]])) {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$theValue] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
				}

				$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
			}

			break;

		case 25:
		case 7:
		case 28:
			if ($option_array[2] == 0) {
				$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			}
			else {
				$theOptionValueList = '';

				if ($this_fields_value != '') {
					$theValueList = explode(',', $this_fields_value);

					foreach ($theValueList as $theValue) {
						if ($theValue == 0) {
							$theOptionValueList .= '0,';
						}
						else if (array_key_exists($theValue, $option_tran_array[$option_array[2]])) {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$theValue] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
				}

				$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
			}

			break;

		case 4:
		case 23:
			if ($option_array[3] == 2) {
				if (trim($this_fields_value) == '99999') {
					$SQL .= $option_array[2] . '=\'\',' . $option_array[4] . '=\'1\',';
				}
				else {
					$SQL .= $option_array[2] . '=\'' . trim($this_fields_value) . '\',';
				}
			}
			else {
				$SQL .= $option_array[2] . '=\'' . trim($this_fields_value) . '\',';
			}

			break;

		case 5:
		case 14:
		case 22:
		case 27:
		case 29:
		case 12:
		case 13:
		case 30:
			$SQL .= $option_array[2] . '=\'' . $this_fields_value . '\',';
			break;

		case 31:
			if ($option_array[4] == 1) {
				if (!array_key_exists(trim($this_fields_value), $option_tran_array[$option_array[3]])) {
					$SQL .= $option_array[2] . '=\'0\',';
				}
				else {
					$SQL .= $option_array[2] . '=\'' . trim($this_fields_value) . '\',';
				}
			}
			else if (!array_key_exists(trim($this_fields_value), $option_tran_array[$option_array[3]])) {
				$SQL .= $option_array[2] . '=\'0\',';
			}
			else {
				$the_father_order = $this_fields_order - 1;
				$the_father_fields_name = 'q' . $the_father_order;

				if ($option_tran_array[$option_array[3]][trim($this_fields_value)] != trim($ListRow[$the_father_fields_name])) {
					$SQL .= $option_array[2] . '=\'0\',';
				}
				else {
					$SQL .= $option_array[2] . '=\'' . trim($this_fields_value) . '\',';
				}
			}

			break;

		case 16:
			$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			break;

		case 10:
		case 20:
			if ($option_array[2] == 0) {
				$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			}
			else if ($this_fields_value <= $option_array[4]) {
				$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
			}
			else {
				$SQL .= $option_array[3] . '=\'0\',';
			}

			break;

		case 15:
		case 21:
			switch ($option_array[4]) {
			case '1':
				if ($option_array[2] == 0) {
					$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
				}
				else {
					if (($option_array[5] <= $this_fields_value) && ($this_fields_value <= $option_array[6])) {
						$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'0\',';
					}
				}

				break;

			case '0':
			default:
				if ($option_array[2] == 0) {
					$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
				}
				else {
					$theValueList = explode('*', $option_array[5]);

					if (in_array($this_fields_value * $option_array[6], $theValueList)) {
						$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'0\',';
					}
				}

				break;

			case '2':
				if ($option_array[2] == 0) {
					$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
				}
				else {
					if (($option_array[5] <= $this_fields_value) && ($this_fields_value <= $option_array[6])) {
						$SQL .= $option_array[3] . '=\'' . $this_fields_value . '\',';
					}
					else {
						$SQL .= $option_array[3] . '=\'0\',';
					}
				}

				break;
			}

			break;

		case 17:
		case 18:
			if ($option_array[4] == 1) {
				if (!array_key_exists($this_fields_value, $option_tran_array[$option_array[2]])) {
					$SQL .= $option_array[3] . '=\'\',';
				}
				else {
					$SQL .= $option_array[3] . '=\'' . $option_tran_array[$option_array[2]][$this_fields_value] . '\',';
				}
			}
			else {
				$theOptionValueList = '';

				if ($this_fields_value != '') {
					$theValueList = explode(',', $this_fields_value);

					foreach ($theValueList as $theValue) {
						if ($theValue == 0) {
							$theOptionValueList .= '0,';
						}
						else if (array_key_exists($theValue, $option_tran_array[$option_array[2]])) {
							$theOptionValueList .= $option_tran_array[$option_array[2]][$theValue] . ',';
						}
					}

					$theOptionValueList = substr($theOptionValueList, 0, -1);
				}

				$SQL .= $option_array[3] . '=\'' . $theOptionValueList . '\',';
			}

			break;
		}

		$this_fields_order++;
	}

	$SQL = substr($SQL, 0, -1);
	$DB->query($SQL);
	$recNum++;
	$newResponseID = $DB->_GetInsertID();
	$SQL = ' INSERT INTO ' . ANDROID_INFO_TABLE . ' SET surveyID=\'' . $theSID . '\',responseID=\'' . $newResponseID . '\',line1Number=\'' . $ListRow['line1Number'] . '\',deviceId=\'' . $ListRow['deviceId'] . '\',brand=\'' . $ListRow['brand'] . '\',model=\'' . $ListRow['model'] . '\',currentCity=\'' . $ListRow['currentCity'] . '\',simOperatorName=\'' . $ListRow['simOperatorName'] . '\',simSerialNumber=\'' . $ListRow['simSerialNumber'] . '\' ';
	$DB->query($SQL);

	if (trim($ListRow['gpsTrace']) != '') {
		$theGpsTraceArray = explode('###', $ListRow['gpsTrace']);

		foreach ($theGpsTraceArray as $theGpsTraceList) {
			$thisGpsTrace = explode('$$$', $theGpsTraceList);
			$SQL = ' INSERT INTO ' . GPS_TRACE_TABLE . ' SET surveyID=\'' . $theSID . '\',responseID=\'' . $newResponseID . '\',gpsTime=\'' . $thisGpsTrace[0] . '\',accuracy=\'' . $thisGpsTrace[1] . '\',longitude=\'' . $thisGpsTrace[2] . '\',latitude=\'' . $thisGpsTrace[3] . '\',speed=\'' . $thisGpsTrace[4] . '\',bearing=\'' . $thisGpsTrace[5] . '\',altitude=\'' . $thisGpsTrace[6] . '\',isCell=\'' . $thisGpsTrace[7] . '\' ';
			$DB->query($SQL);
		}
	}

	if (trim($ListRow['gpsTraceUpload']) != '') {
		$theGpsTraceArray = explode('###', $ListRow['gpsTraceUpload']);

		foreach ($theGpsTraceArray as $theGpsTraceList) {
			$thisGpsTrace = explode('$$$', $theGpsTraceList);
			$SQL = ' INSERT INTO ' . GPS_TRACE_UPLOAD_TABLE . ' SET surveyID=\'' . $theSID . '\',responseID=\'' . $newResponseID . '\',gpsTime=\'' . $thisGpsTrace[0] . '\',accuracy=\'' . $thisGpsTrace[1] . '\',longitude=\'' . $thisGpsTrace[2] . '\',latitude=\'' . $thisGpsTrace[3] . '\',speed=\'' . $thisGpsTrace[4] . '\',bearing=\'' . $thisGpsTrace[5] . '\',altitude=\'' . $thisGpsTrace[6] . '\',isCell=\'' . $thisGpsTrace[7] . '\',qtnID=\'' . $the_upload_map_array[$thisGpsTrace[8]] . '\' ';
			$DB->query($SQL);
		}
	}

	if ($ListRow['overFlag'] == 1) {
		docountinfo($theSID, $ListRow['joinTime']);
	}
}

$SQL = ' OPTIMIZE TABLE ' . $table_prefix . 'response_' . $theSID . ' ';
$DB->query($SQL);
unset($option_tran_array);
unset($the_upload_map_array);

?>

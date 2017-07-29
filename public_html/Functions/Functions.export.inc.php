<?php
//dezend by http://www.yunlu99.com/
function export($surveyID, $E_SQL, $theExportQtnList = array())
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $table_prefix;
	global $Module;
	global $Config;
	global $License;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	global $CascadeArray;
	$SQL = ' SELECT isPublic,ajaxRtnValue,isRecord,isUploadRec,forbidViewId,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);
	$isViewAreaInfo = $isViewPanelInfo = $isViewRecFile = true;

	if ($_SESSION['adminRoleType'] == '3') {
		$forbidViewIdValue = explode(',', $S_Row['forbidViewId']);

		if (in_array('t1', $forbidViewIdValue)) {
			$isViewAreaInfo = false;
		}

		if (in_array('t2', $forbidViewIdValue)) {
			$isViewPanelInfo = false;
		}

		if (in_array('t3', $forbidViewIdValue)) {
			$isViewRecFile = false;
		}
	}

	$content = '';
	$header = '"' . $lang['export_responseID'] . '"';
	$header .= ',"' . $lang['export_joinTime'] . '"';
	$header .= ',"' . $lang['export_submitTime'] . '"';
	if (($S_Row['isRecord'] != 0) && ($isViewRecFile == true)) {
		$header .= ',"' . $lang['export_recFile'] . '"';
	}

	$header .= ',"' . $lang['export_overFlag'] . '"';
	$header .= ',"' . $lang['export_authStat'] . '"';
	$header .= ',"' . $lang['export_overTime'] . '"';
	$header .= ',"' . $lang['export_server'] . '"';
	$header .= ',"' . $lang['export_ipAddress'] . '"';

	if ($isViewAreaInfo == true) {
		$header .= ',"' . $lang['export_area'] . '"';
	}

	if ($isViewPanelInfo == true) {
		$header .= ',"' . $lang['export_administratorsName'] . '"';
	}

	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
	if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			$header .= ',"' . $lang['export_userGroup'] . '"';

			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$header .= ',"' . qconversionstring($ajaxRtnValueName[$i]) . '"';
				}
			}

			break;

		case '3':
		case '4':
		case '5':
			break;

		case '2':
			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$header .= ',"' . qconversionstring($ajaxRtnValueName[$i]) . '"';
				}
			}

			break;
		}
	}

	$this_fields_list = '';
	$option_tran_array = array();

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
			if ($_SESSION['adminRoleType'] == '3') {
				if (!in_array($questionID, $forbidViewIdValue)) {
					if (0 < count($theExportQtnList)) {
						if (in_array($questionID, $theExportQtnList)) {
							$surveyID = $surveyID;
							$ModuleName = $Module[$theQtnArray['questionType']];
							require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
						}
					}
					else {
						$surveyID = $surveyID;
						$ModuleName = $Module[$theQtnArray['questionType']];
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
					}
				}
			}
			else if (0 < count($theExportQtnList)) {
				if (in_array($questionID, $theExportQtnList)) {
					$surveyID = $surveyID;
					$ModuleName = $Module[$theQtnArray['questionType']];
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
				}
			}
			else {
				$surveyID = $surveyID;
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
			}
		}
	}

	$header .= "\r\n";
	$content .= $header;
	$this_fields_list = substr($this_fields_list, 0, -1);
	$thisSurveyFields = explode('|', $this_fields_list);
	$ValueSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $surveyID . ' b ';
	
	if (trim($E_SQL) == '') {
		$ValueSQL .= ' WHERE ' . getdatasourcesql('all', $surveyID);
	}
	else {
		$ValueSQL .= ' WHERE ' . $E_SQL . ' ';
	}
	// print_r($ValueSQL);
	// exit();
	$ValueSQL .= ' ORDER BY responseID DESC ';
	$ValueResult = $DB->query($ValueSQL);

	while ($ListRow = $DB->queryArray($ValueResult)) {

		if ($_GET['surveyID'] == 17 and $_GET['test'] == 1){
			$ListRow['option_1060'] = '';
			$ListRow['option_1061'] = '';
		}

		$content .= '"' . $ListRow['responseID'] . '"';
		$content .= ',"' . date('Y-m-d H:i:s', $ListRow['joinTime']) . '	"';
		$submitTime = ($ListRow['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $ListRow['submitTime']) . '	');
		$content .= ',"' . $submitTime . '"';
		if (($S_Row['isRecord'] != 0) && ($isViewRecFile == true)) {
			if ($ListRow['recordFile'] != '') {
				$systemPath = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -28);

				if ($S_Row['custDataPath'] == '') {
					$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/';
				}
				else {
					$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
				}

				if (file_exists($Config['absolutenessPath'] . '/' . $filePath . $ListRow['recordFile'])) {
					$content .= ',"' . $systemPath . $filePath . $ListRow['recordFile'] . '"';
				}
				else {
					$content .= ',"' . $ListRow['recordFile'] . '"';
				}
			}
			else {
				$content .= ',""';
			}
		}

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

		if ($ListRow['authStat'] != 1) {
			$content .= ',"' . $lang['authStat_' . $ListRow['authStat']] . '"';
		}
		else {
			switch ($ListRow['appStat']) {
			case '0':
				$content .= ',"ͨ��"';
				break;

			case '1':
				$content .= ',"����ͨ��"';
				break;

			case '2':
				$content .= ',"����ʧ��"';
				break;

			case '3':
				$content .= ',"������"';
				break;
			}
		}

		$content .= ',"' . $ListRow['overTime'] . '"';

		switch ($ListRow['dataSource']) {
		case '0':
		default:
			$dataForm = 'δ֪������Դ';
			break;

		case '1':
			$dataForm = 'PC�����?';
			break;

		case '2':
			$dataForm = '�ƶ������?';
			break;

		case '3':
			$dataForm = '��׿����App';
			break;

		case '4':
			$dataForm = 'PC��Ա¼��';
			break;

		case '5':
			$dataForm = '���߷�ԱApp';
			break;

		case '6':
			$dataForm = '���߷�ԱApp';
			break;

		case '7':
			$dataForm = 'Excel���ݵ���';
			break;

		case '8':
			$dataForm = '�ʾ�����Ǩ��';
			break;
		}

		if ($ListRow['uniDataCode'] == '') {
			$content .= ',"' . $dataForm . '"';
		}
		else {
			$fromServer = explode('######', base64_decode($ListRow['uniDataCode']));
			$content .= ',"' . $fromServer[0] . '(' . $dataForm . ')"';
		}

		$content .= ',"' . qconversionstring($ListRow['ipAddress']) . '"';

		if ($isViewAreaInfo == true) {
			$content .= ',"' . qconversionstring($ListRow['area']) . '"';
		}

		if ($isViewPanelInfo == true) {
			$content .= ',"' . qconversionstring($ListRow['administratorsName']) . '"';
		}

		if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
			switch ($BaseRow['isUseOriPassport']) {
			case '1':
			default:
				if ($ListRow['administratorsGroupID'] == '0') {
					$administratorsGroupName = $lang['no_group'];
				}
				else {
					$GroupSQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $ListRow['administratorsGroupID'] . '\' ';
					$GroupRow = $DB->queryFirstRow($GroupSQL);
					$administratorsGroupName = $GroupRow['administratorsGroupName'];
				}

				$content .= ',"' . qconversionstring($administratorsGroupName) . '"';

				if ($S_Row['ajaxRtnValue'] != '') {
					$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

					if (6 < count($ajaxRtnValueName)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValueName);
					}

					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$content .= ',"' . qconversionstring($ListRow['ajaxRtnValue_' . $j]) . '"';
					}
				}

				break;

			case '3':
			case '4':
			case '5':
				break;

			case '2':
				if ($S_Row['ajaxRtnValue'] != '') {
					$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

					if (6 < count($ajaxRtnValueName)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValueName);
					}

					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$content .= ',"' . qconversionstring($ListRow['ajaxRtnValue_' . $j]) . '"';
					}
				}

				break;
			}
		}

		foreach ($thisSurveyFields as $theFields) {
			if (strpos($theFields, '#') === false) {
				$theQtnArray = explode('_', $theFields);
				$theQtnId = $theQtnArray[1];

				switch ($QtnListArray[$theQtnId]['questionType']) {
				case '23':
					$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
					break;

				case '24':
					$theCheckType = $RadioListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
					break;

				case '25':
					$theCheckType = $CheckBoxListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
					break;

				case '27':
				case '29':
					$theCheckType = $LabelListArray[$theQtnId][$theQtnArray[3]]['isCheckType'];
					break;

				default:
					$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
					break;
				}

				switch ($theCheckType) {
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
				case '11':
					if (trim($ListRow[$theFields]) != '') {
						$content .= ',"' . qconversionstring($ListRow[$theFields]) . '	"';
					}
					else {
						$content .= ',""';
					}

					break;

				default:
					$encode = mb_detect_encoding($ListRow[$theFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

					if ($encode == 'UTF-8') {
						$content .= ',"' . qconversionstring(iconv('UTF-8', 'GBK', $ListRow[$theFields])) . '"';
					}
					else {
						$content .= ',"' . qconversionstring($ListRow[$theFields]) . '"';
					}

					break;
				}
			}
			else {
				$option_array = explode('#', $theFields);

				switch ($option_array[0]) {
				case '1':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
					}

					break;

				case '2':
					if ($ListRow[$option_array[2]] == '0') {
						if ($ListRow[$option_array[3]] == '') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . qconversionstring($option_array[4]) . '"';
						}
					}
					else {
						$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$option_array[2]]]) . '"';
					}

					break;

				case '24':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$option_array[2]]]) . '"';
					}

					break;

				case '31':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . qconversionstring($CascadeArray[$option_array[1]][$ListRow[$option_array[2]]]['nodeName']) . '"';
					}

					break;

				case '3':
				case '25':
				case '7':
				case '28':
					if ($ListRow[$option_array[1]] == '') {
						$content .= ',""';
					}
					else {
						$option_value_array = explode(',', $ListRow[$option_array[1]]);

						if (in_array($option_array[2], $option_value_array)) {
							$content .= ',"1"';
						}
						else {
							$content .= ',"0"';
						}

						unset($option_value_array);
					}

					break;

				case '4':
				case '23':
					if ($ListRow[$option_array[2]] == 1) {
						$content .= ',"' . $lang['rating_unknow'] . '"';
					}
					else {
						$theQtnArray = explode('_', $option_array[1]);
						$theQtnId = $theQtnArray[1];

						switch ($option_array[0]) {
						case '4':
							$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
							break;

						case '23':
							$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
							break;
						}

						switch ($theCheckType) {
						case '5':
						case '6':
						case '7':
						case '8':
						case '9':
						case '11':
							if (trim($ListRow[$option_array[1]]) != '') {
								$content .= ',"' . qconversionstring($ListRow[$option_array[1]]) . '	"';
							}
							else {
								$content .= ',""';
							}

							break;

						default:
							$encode = mb_detect_encoding($ListRow[$option_array[1]], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

							if ($encode == 'UTF-8') {
								$content .= ',"' . qconversionstring(iconv('UTF-8', 'GBK', $ListRow[$option_array[1]])) . '"';
							}
							else {
								$content .= ',"' . qconversionstring($ListRow[$option_array[1]]) . '"';
							}

							break;
						}
					}

					break;

				case '6':
				case '26':
				case '19':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$option_array[2]]]) . '"';
					}

					break;

				case '10':
				case '20':
				case '16':
				case '22':
					if ($ListRow[$option_array[1]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $ListRow[$option_array[1]] . '"';
					}

					break;

				case '11':
					if ($ListRow[$option_array[1]] != '') {
						$systemPath = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -28);

						if ($S_Row['custDataPath'] == '') {
							$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
						}
						else {
							$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
						}

						$content .= ',"' . $systemPath . $filePath . $ListRow[$option_array[1]] . '"';
					}
					else {
						$content .= ',""';
					}

					break;

				case '13':
					if ($ListRow[$option_array[2]] == '') {
						$content .= ',""';
					}
					else {
						$D_Row = $QtnListArray[$option_array[1]];
						$Conn = odbc_connect(trim($D_Row['DSNConnect']), trim($D_Row['DSNUser']), trim($D_Row['DSNPassword']));

						if (!$Conn) {
							_showerror('System Error', 'Connection Failed:' . trim($D_Row['DSNConnect']) . '-' . trim($D_Row['DSNUser']) . '-' . trim($D_Row['DSNPassword']));
						}

						$ODBC_Result = odbc_exec($Conn, _getsql($D_Row['DSNSQL']));

						if (!$ODBC_Result) {
							_showerror('System Error', 'Error in SQL:' . trim($D_Row['DSNSQL']));
						}

						$isOver = false;

						while (odbc_fetch_row($ODBC_Result)) {
							$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
							$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
							if (($ItemValue == $ListRow[$option_array[2]]) && ($isOver == false)) {
								$content .= ',"' . qconversionstring($ItemDisplay) . '"';
								$isOver = true;
							}
						}
					}

					break;

				case '15':
				case '21':
					if ($ListRow[$option_array[2]] != 99) {
						if ($ListRow[$option_array[2]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . ($ListRow[$option_array[2]] * $option_array[1]) . '"';
						}
					}
					else {
						$content .= ',"' . $ListRow[$option_array[2]] . '"';
					}

					break;

				case '17':
					if ($option_array[1] == 1) {
						if ($ListRow[$option_array[3]] == '') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . qconversionstring($option_tran_array[$option_array[2]][$ListRow[$option_array[3]]]) . '"';
						}
					}
					else if ($ListRow[$option_array[2]] == '') {
						$content .= ',""';
					}
					else {
						$option_value_array = explode(',', $ListRow[$option_array[2]]);

						if (in_array($option_array[3], $option_value_array)) {
							$content .= ',"1"';
						}
						else {
							$content .= ',"0"';
						}
					}

					break;

				case '18':
					if ($option_array[1] == 0) {
						if ($ListRow[$option_array[3]] == '') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . qconversionstring($option_tran_array[$option_array[2]][$ListRow[$option_array[3]]]) . '"';
						}
					}
					else if ($ListRow[$option_array[2]] == '') {
						$content .= ',""';
					}
					else {
						$option_value_array = explode(',', $ListRow[$option_array[2]]);

						if (in_array($option_array[3], $option_value_array)) {
							$content .= ',"1"';
						}
						else {
							$content .= ',"0"';
						}
					}

					break;
				}
			}
		}

		$content .= "\r\n";
	}

	unset($option_tran_array);
	return $content;
}

function export_award($surveyID)
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $table_prefix;
	global $Module;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	global $CascadeArray;
	$SQL = ' SELECT isPublic,ajaxRtnValue,forbidViewId,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);
	$isViewAreaInfo = $isViewPanelInfo = true;

	if ($_SESSION['adminRoleType'] == '3') {
		$forbidViewIdValue = explode(',', $S_Row['forbidViewId']);

		if (in_array('t1', $forbidViewIdValue)) {
			$isViewAreaInfo = false;
		}

		if (in_array('t2', $forbidViewIdValue)) {
			$isViewPanelInfo = false;
		}
	}

	$content = '';
	$header = '"' . $lang['export_responseID'] . '"';
	$header .= ',"' . $lang['export_joinTime'] . '"';
	$header .= ',"' . $lang['export_submitTime'] . '"';
	$header .= ',"' . $lang['export_overFlag'] . '"';
	$header .= ',"' . $lang['export_authStat'] . '"';
	$header .= ',"' . $lang['export_overTime'] . '"';
	$header .= ',"' . $lang['export_ipAddress'] . '"';

	if ($isViewAreaInfo == true) {
		$header .= ',"' . $lang['export_area'] . '"';
	}

	if ($isViewPanelInfo == true) {
		$header .= ',"' . $lang['export_administratorsName'] . '"';
	}

	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
	if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			$header .= ',"' . $lang['export_userGroup'] . '"';

			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$header .= ',"' . qconversionstring($ajaxRtnValueName[$i]) . '"';
				}
			}

			break;

		case '3':
		case '4':
		case '5':
			break;

		case '2':
			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$header .= ',"' . qconversionstring($ajaxRtnValueName[$i]) . '"';
				}
			}

			break;
		}
	}

	$this_fields_list = '';
	$option_tran_array = array();

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
			if ($_SESSION['adminRoleType'] == '3') {
				if (!in_array($questionID, $forbidViewIdValue)) {
					$ModuleName = $Module[$theQtnArray['questionType']];
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
				}
			}
			else {
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
			}
		}
	}

	$header .= "\r\n";
	$SQL = ' SELECT * FROM ' . AWARDPRODUCT_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ORDER BY awardListID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$CountSQL = ' SELECT COUNT(*) AS awardOverNum FROM ' . AWARDLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND awardListID=\'' . $Row['awardListID'] . '\' LIMIT 0,1 ';
		$CountRow = $DB->queryFirstRow($CountSQL);
		$content .= '"' . $Row['awardType'] . $Row['awardProduct'] . '(' . $Row['awardNum'] . $lang['award_unit'] . ',' . $lang['award_over'] . $CountRow['awardOverNum'] . $lang['award_unit'] . ')' . '"' . "\r\n" . '';
		$content .= $header;
		$this_fields_list = substr($this_fields_list, 0, -1);
		$thisSurveyFields = explode('|', $this_fields_list);
		$ListSQL = ' SELECT a.responseID,a.awardID,b.* FROM ' . AWARDLIST_TABLE . ' a, ' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.awardListID=\'' . $Row['awardListID'] . '\' AND a.surveyID=\'' . $surveyID . '\'  AND a.responseID = b.responseID ORDER BY awardID ASC ';
		$ListResult = $DB->query($ListSQL);

		while ($ListRow = $DB->queryArray($ListResult)) {
			$content .= '"' . $ListRow['responseID'] . '"';
			$content .= ',"' . date('Y-m-d H:i:s', $ListRow['joinTime']) . '	"';
			$submitTime = ($ListRow['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $ListRow['submitTime']) . '	');
			$content .= ',"' . $submitTime . '"';

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

			if ($ListRow['authStat'] != 1) {
				$content .= ',"' . $lang['authStat_' . $ListRow['authStat']] . '"';
			}
			else {
				switch ($ListRow['appStat']) {
				case '0':
					$content .= ',"ͨ��"';
					break;

				case '1':
					$content .= ',"����ͨ��"';
					break;

				case '2':
					$content .= ',"����ʧ��"';
					break;

				case '3':
					$content .= ',"������"';
					break;
				}
			}

			$content .= ',"' . $ListRow['overTime'] . '"';
			$content .= ',"' . qconversionstring($ListRow['ipAddress']) . '"';

			if ($isViewAreaInfo == true) {
				$content .= ',"' . qconversionstring($ListRow['area']) . '"';
			}

			if ($isViewPanelInfo == true) {
				$content .= ',"' . qconversionstring($ListRow['administratorsName']) . '"';
			}

			if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
				switch ($BaseRow['isUseOriPassport']) {
				case '1':
				default:
					if ($ListRow['administratorsGroupID'] == '0') {
						$administratorsGroupName = $lang['no_group'];
					}
					else {
						$GroupSQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $ListRow['administratorsGroupID'] . '\' ';
						$GroupRow = $DB->queryFirstRow($GroupSQL);
						$administratorsGroupName = $GroupRow['administratorsGroupName'];
					}

					$content .= ',"' . qconversionstring($administratorsGroupName) . '"';

					if ($S_Row['ajaxRtnValue'] != '') {
						$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

						if (6 < count($ajaxRtnValueName)) {
							$ajaxCount = 6;
						}
						else {
							$ajaxCount = count($ajaxRtnValueName);
						}

						$i = 0;

						for (; $i < $ajaxCount; $i++) {
							$j = $i + 1;
							$content .= ',"' . qconversionstring($ListRow['ajaxRtnValue_' . $j]) . '"';
						}
					}

					break;

				case '3':
				case '4':
				case '5':
					break;

				case '2':
					if ($S_Row['ajaxRtnValue'] != '') {
						$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

						if (6 < count($ajaxRtnValueName)) {
							$ajaxCount = 6;
						}
						else {
							$ajaxCount = count($ajaxRtnValueName);
						}

						$i = 0;

						for (; $i < $ajaxCount; $i++) {
							$j = $i + 1;
							$content .= ',"' . qconversionstring($ListRow['ajaxRtnValue_' . $j]) . '"';
						}
					}

					break;
				}
			}

			foreach ($thisSurveyFields as $theFields) {
				if (strpos($theFields, '#') === false) {
					$theQtnArray = explode('_', $theFields);
					$theQtnId = $theQtnArray[1];

					switch ($QtnListArray[$theQtnId]['questionType']) {
					case '23':
						$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
						break;

					case '24':
						$theCheckType = $RadioListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
						break;

					case '25':
						$theCheckType = $CheckBoxListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
						break;

					case '27':
					case '29':
						$theCheckType = $LabelListArray[$theQtnId][$theQtnArray[3]]['isCheckType'];
						break;

					default:
						$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
						break;
					}

					switch ($theCheckType) {
					case '5':
					case '6':
					case '7':
					case '8':
					case '9':
					case '11':
						if (trim($ListRow[$theFields]) != '') {
							$content .= ',"' . qconversionstring($ListRow[$theFields]) . '	"';
						}
						else {
							$content .= ',""';
						}

						break;

					default:
						$encode = mb_detect_encoding($ListRow[$theFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

						if ($encode == 'UTF-8') {
							$content .= ',"' . qconversionstring(iconv('UTF-8', 'GBK', $ListRow[$theFields])) . '"';
						}
						else {
							$content .= ',"' . qconversionstring($ListRow[$theFields]) . '"';
						}

						break;
					}
				}
				else {
					$option_array = explode('#', $theFields);

					switch ($option_array[0]) {
					case '1':
						if ($ListRow[$option_array[2]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
						}

						break;

					case '2':
						if ($ListRow[$option_array[2]] == '0') {
							if ($ListRow[$option_array[3]] == '') {
								$content .= ',""';
							}
							else {
								$content .= ',"' . qconversionstring($option_array[4]) . '"';
							}
						}
						else {
							$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$option_array[2]]]) . '"';
						}

						break;

					case '24':
						if ($ListRow[$option_array[2]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$option_array[2]]]) . '"';
						}

						break;

					case '31':
						if ($ListRow[$option_array[2]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . qconversionstring($CascadeArray[$option_array[1]][$ListRow[$option_array[2]]]['nodeName']) . '"';
						}

						break;

					case '3':
					case '25':
					case '7':
					case '28':
						if ($ListRow[$option_array[1]] == '') {
							$content .= ',""';
						}
						else {
							$option_value_array = explode(',', $ListRow[$option_array[1]]);

							if (in_array($option_array[2], $option_value_array)) {
								$content .= ',"1"';
							}
							else {
								$content .= ',"0"';
							}

							unset($option_value_array);
						}

						break;

					case '4':
					case '23':
						if ($ListRow[$option_array[2]] == 1) {
							$content .= ',"' . $lang['rating_unknow'] . '"';
						}
						else {
							$theQtnArray = explode('_', $option_array[1]);
							$theQtnId = $theQtnArray[1];

							switch ($option_array[0]) {
							case '4':
								$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
								break;

							case '23':
								$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
								break;
							}

							switch ($theCheckType) {
							case '5':
							case '6':
							case '7':
							case '8':
							case '9':
							case '11':
								if (trim($ListRow[$option_array[1]]) != '') {
									$content .= ',"' . qconversionstring($ListRow[$option_array[1]]) . '	"';
								}
								else {
									$content .= ',""';
								}

								break;

							default:
								$encode = mb_detect_encoding($ListRow[$option_array[1]], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

								if ($encode == 'UTF-8') {
									$content .= ',"' . qconversionstring(iconv('UTF-8', 'GBK', $ListRow[$option_array[1]])) . '"';
								}
								else {
									$content .= ',"' . qconversionstring($ListRow[$option_array[1]]) . '"';
								}

								break;
							}
						}

						break;

					case '6':
					case '26':
					case '19':
						if ($ListRow[$option_array[2]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$option_array[2]]]) . '"';
						}

						break;

					case '10':
					case '20':
					case '16':
					case '22':
						if ($ListRow[$option_array[1]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . $ListRow[$option_array[1]] . '"';
						}

						break;

					case '11':
						if ($ListRow[$option_array[1]] != '') {
							$systemPath = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -27);

							if ($S_Row['custDataPath'] == '') {
								$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
							}
							else {
								$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
							}

							$content .= ',"' . $systemPath . $filePath . $ListRow[$option_array[1]] . '"';
						}
						else {
							$content .= ',""';
						}

						break;

					case '13':
						if ($ListRow[$option_array[2]] == '') {
							$content .= ',""';
						}
						else {
							$D_Row = $QtnListArray[$option_array[1]];
							$Conn = odbc_connect(trim($D_Row['DSNConnect']), trim($D_Row['DSNUser']), trim($D_Row['DSNPassword']));

							if (!$Conn) {
								_showerror('System Error', 'Connection Failed:' . trim($D_Row['DSNConnect']) . '-' . trim($D_Row['DSNUser']) . '-' . trim($D_Row['DSNPassword']));
							}

							$ODBC_Result = odbc_exec($Conn, _getsql($D_Row['DSNSQL']));

							if (!$ODBC_Result) {
								_showerror('System Error', 'Error in SQL:' . trim($D_Row['DSNSQL']));
							}

							$isOver = false;

							while (odbc_fetch_row($ODBC_Result)) {
								$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
								$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
								if (($ItemValue == $ListRow[$option_array[2]]) && ($isOver == false)) {
									$content .= ',"' . qconversionstring($ItemDisplay) . '"';
									$isOver = true;
								}
							}
						}

						break;

					case '15':
					case '21':
						if ($ListRow[$option_array[2]] != 99) {
							if ($ListRow[$option_array[2]] == '0') {
								$content .= ',""';
							}
							else {
								$content .= ',"' . ($ListRow[$option_array[2]] * $option_array[1]) . '"';
							}
						}
						else {
							$content .= ',"' . $ListRow[$option_array[2]] . '"';
						}

						break;

					case '17':
						if ($option_array[1] == 1) {
							if ($ListRow[$option_array[3]] == '') {
								$content .= ',""';
							}
							else {
								$content .= ',"' . qconversionstring($option_tran_array[$option_array[2]][$ListRow[$option_array[3]]]) . '"';
							}
						}
						else if ($ListRow[$option_array[2]] == '') {
							$content .= ',""';
						}
						else {
							$option_value_array = explode(',', $ListRow[$option_array[2]]);

							if (in_array($option_array[3], $option_value_array)) {
								$content .= ',"1"';
							}
							else {
								$content .= ',"0"';
							}
						}

						break;

					case '18':
						if ($option_array[1] == 0) {
							if ($ListRow[$option_array[3]] == '') {
								$content .= ',""';
							}
							else {
								$content .= ',"' . qconversionstring($option_tran_array[$option_array[2]][$ListRow[$option_array[3]]]) . '"';
							}
						}
						else if ($ListRow[$option_array[2]] == '') {
							$content .= ',""';
						}
						else {
							$option_value_array = explode(',', $ListRow[$option_array[2]]);

							if (in_array($option_array[3], $option_value_array)) {
								$content .= ',"1"';
							}
							else {
								$content .= ',"0"';
							}
						}

						break;
					}
				}
			}

			$content .= "\r\n";
		}
	}

	unset($option_tran_array);
	return $content;
}

function export_spss($surveyID, $E_SQL, $theExportQtnList = array())
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $table_prefix;
	global $Module;
	global $Config;
	global $License;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	global $CascadeArray;
	$SQL = ' SELECT isPublic,ajaxRtnValue,isRecord,isUploadRec,forbidViewId,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);
	$isViewAreaInfo = $isViewPanelInfo = $isViewRecFile = true;

	if ($_SESSION['adminRoleType'] == '3') {
		$forbidViewIdValue = explode(',', $S_Row['forbidViewId']);

		if (in_array('t1', $forbidViewIdValue)) {
			$isViewAreaInfo = false;
		}

		if (in_array('t2', $forbidViewIdValue)) {
			$isViewPanelInfo = false;
		}

		if (in_array('t3', $forbidViewIdValue)) {
			$isViewRecFile = false;
		}
	}

	$content = '';
	$header = '"responseNo"';
	$header .= ',"joinTime"';
	$header .= ',"submitTime"';
	if (($S_Row['isRecord'] != 0) && ($isViewRecFile == true)) {
		$header .= ',"recordFile"';
	}

	$header .= ',"submitFlag"';
	$header .= ',"dataAuthStat"';
	$header .= ',"timeSpent"';
	$header .= ',"dataSource"';
	$header .= ',"ipAddress"';

	if ($isViewAreaInfo == true) {
		$header .= ',"fromArea"';
	}

	if ($isViewPanelInfo == true) {
		$header .= ',"userName"';
	}

	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
	if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			$header .= ',"userGroup"';

			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$header .= ',"PanelProperties_' . $i . '"';
				}
			}

			break;

		case '3':
		case '4':
		case '5':
			break;

		case '2':
			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$header .= ',"PanelProperties_' . $i . '"';
				}
			}

			break;
		}
	}

	$this_fields_list = '';
	$option_tran_array = array();

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
			if ($_SESSION['adminRoleType'] == '3') {
				if (!in_array($questionID, $forbidViewIdValue)) {
					if (0 < count($theExportQtnList)) {
						if (in_array($questionID, $theExportQtnList)) {
							$surveyID = $surveyID;
							$ModuleName = $Module[$theQtnArray['questionType']];
							require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.spss.header.php';
						}
					}
					else {
						$surveyID = $surveyID;
						$ModuleName = $Module[$theQtnArray['questionType']];
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.spss.header.php';
					}
				}
			}
			else if (0 < count($theExportQtnList)) {
				if (in_array($questionID, $theExportQtnList)) {
					$surveyID = $surveyID;
					$ModuleName = $Module[$theQtnArray['questionType']];
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.spss.header.php';
				}
			}
			else {
				$surveyID = $surveyID;
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.spss.header.php';
			}
		}
	}

	$header .= "\r\n";
	$content .= $header;
	$this_fields_list = substr($this_fields_list, 0, -1);
	$thisSurveyFields = explode('|', $this_fields_list);
	$ValueSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $surveyID . ' b ';

	if (trim($E_SQL) == '') {
		$ValueSQL .= ' WHERE ' . getdatasourcesql('all', $surveyID);
	}
	else {
		$ValueSQL .= ' WHERE ' . $E_SQL . ' ';
	}

	$ValueSQL .= ' ORDER BY responseID DESC ';
	$ValueResult = $DB->query($ValueSQL);

	while ($ListRow = $DB->queryArray($ValueResult)) {
		$content .= '"' . $ListRow['responseID'] . '"';
		$content .= ',"' . date('Y-m-d H:i:s', $ListRow['joinTime']) . '	"';
		$submitTime = ($ListRow['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $ListRow['submitTime']) . '	');
		$content .= ',"' . $submitTime . '"';
		if (($S_Row['isRecord'] != 0) && ($isViewRecFile == true)) {
			if ($ListRow['recordFile'] != '') {
				$systemPath = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);

				if ($S_Row['custDataPath'] == '') {
					$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/';
				}
				else {
					$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
				}

				if (file_exists($Config['absolutenessPath'] . '/' . $filePath . $ListRow['recordFile'])) {
					$content .= ',"' . $systemPath . $filePath . $ListRow['recordFile'] . '"';
				}
				else {
					$content .= ',"' . $ListRow['recordFile'] . '"';
				}
			}
			else {
				$content .= ',""';
			}
		}

		$content .= ',"' . $ListRow['overFlag'] . '"';

		if ($ListRow['authStat'] != 1) {
			$content .= ',"' . $lang['authStat_' . $ListRow['authStat']] . '"';
		}
		else {
			switch ($ListRow['appStat']) {
			case '0':
				$content .= ',"ͨ��"';
				break;

			case '1':
				$content .= ',"����ͨ��"';
				break;

			case '2':
				$content .= ',"����ʧ��"';
				break;

			case '3':
				$content .= ',"������"';
				break;
			}
		}

		$content .= ',"' . $ListRow['overTime'] . '"';

		switch ($ListRow['dataSource']) {
		case '0':
		default:
			$dataForm = 'δ֪������Դ';
			break;

		case '1':
			$dataForm = 'PC�����?';
			break;

		case '2':
			$dataForm = '�ƶ������?';
			break;

		case '3':
			$dataForm = '��׿����App';
			break;

		case '4':
			$dataForm = 'PC��Ա¼��';
			break;

		case '5':
			$dataForm = '���߷�ԱApp';
			break;

		case '6':
			$dataForm = '���߷�ԱApp';
			break;

		case '7':
			$dataForm = 'Excel���ݵ���';
			break;

		case '8':
			$dataForm = '�ʾ�����Ǩ��';
			break;
		}

		if ($ListRow['uniDataCode'] == '') {
			$content .= ',"' . $dataForm . '"';
		}
		else {
			$fromServer = explode('######', base64_decode($ListRow['uniDataCode']));
			$content .= ',"' . $fromServer[0] . '(' . $dataForm . ')"';
		}

		$content .= ',"' . qconversionstring($ListRow['ipAddress']) . '"';

		if ($isViewAreaInfo == true) {
			$content .= ',"' . qconversionstring($ListRow['area']) . '"';
		}

		if ($isViewPanelInfo == true) {
			$content .= ',"' . qconversionstring($ListRow['administratorsName']) . '"';
		}

		if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
			switch ($BaseRow['isUseOriPassport']) {
			case '1':
			default:
				if ($ListRow['administratorsGroupID'] == '0') {
					$administratorsGroupName = $lang['no_group'];
				}
				else {
					$GroupSQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $ListRow['administratorsGroupID'] . '\' ';
					$GroupRow = $DB->queryFirstRow($GroupSQL);
					$administratorsGroupName = $GroupRow['administratorsGroupName'];
				}

				$content .= ',"' . qconversionstring($administratorsGroupName) . '"';

				if ($S_Row['ajaxRtnValue'] != '') {
					$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

					if (6 < count($ajaxRtnValueName)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValueName);
					}

					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$content .= ',"' . qconversionstring($ListRow['ajaxRtnValue_' . $j]) . '"';
					}
				}

				break;

			case '3':
			case '4':
			case '5':
				break;

			case '2':
				if ($S_Row['ajaxRtnValue'] != '') {
					$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

					if (6 < count($ajaxRtnValueName)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValueName);
					}

					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$content .= ',"' . qconversionstring($ListRow['ajaxRtnValue_' . $j]) . '"';
					}
				}

				break;
			}
		}

		foreach ($thisSurveyFields as $theFields) {
			if (strpos($theFields, '#') === false) {
				$theQtnArray = explode('_', $theFields);
				$theQtnId = $theQtnArray[1];

				switch ($QtnListArray[$theQtnId]['questionType']) {
				case '23':
					$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
					break;

				case '24':
					$theCheckType = $RadioListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
					break;

				case '25':
					$theCheckType = $CheckBoxListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
					break;

				case '27':
				case '29':
					$theCheckType = $LabelListArray[$theQtnId][$theQtnArray[3]]['isCheckType'];
					break;

				default:
					$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
					break;
				}

				switch ($theCheckType) {
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
				case '11':
					if (trim($ListRow[$theFields]) != '') {
						$content .= ',"' . qconversionstring($ListRow[$theFields]) . '	"';
					}
					else {
						$content .= ',""';
					}

					break;

				default:
					$encode = mb_detect_encoding($ListRow[$theFields], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

					if ($encode == 'UTF-8') {
						$content .= ',"' . qconversionstring(iconv('UTF-8', 'GBK', $ListRow[$theFields])) . '"';
					}
					else {
						$content .= ',"' . qconversionstring($ListRow[$theFields]) . '"';
					}

					break;
				}
			}
			else {
				$option_array = explode('#', $theFields);

				switch ($option_array[0]) {
				case '1':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
					}

					break;

				case '2':
					if ($ListRow[$option_array[2]] == '0') {
						if ($ListRow[$option_array[3]] == '') {
							$content .= ',""';
						}
						else if ($QtnListArray[$option_array[1]]['otherCode'] != 0) {
							$content .= ',"' . $QtnListArray[$option_array[1]]['otherCode'] . '"';
						}
						else {
							$content .= ',"99"';
						}
					}
					else {
						$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
					}

					break;

				case '24':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
					}

					break;

				case '31':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $CascadeArray[$option_array[1]][$ListRow[$option_array[2]]]['nodeID'] . '"';
					}

					break;

				case '3':
				case '25':
				case '7':
				case '28':
					if ($ListRow[$option_array[1]] == '') {
						$content .= ',""';
					}
					else {
						$option_value_array = explode(',', $ListRow[$option_array[1]]);

						if (in_array($option_array[2], $option_value_array)) {
							$content .= ',"1"';
						}
						else {
							$content .= ',"0"';
						}

						unset($option_value_array);
					}

					break;

				case '4':
				case '23':
					$theQtnArray = explode('_', $option_array[1]);
					$theQtnId = $theQtnArray[1];

					if ($ListRow[$option_array[2]] == 1) {
						if ($QtnListArray[$theQtnId]['negCode'] != 0) {
							$content .= ',"' . $QtnListArray[$theQtnId]['negCode'] . '"';
						}
						else {
							$content .= ',"99999"';
						}
					}
					else {
						switch ($option_array[0]) {
						case '4':
							$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
							break;

						case '23':
							$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
							break;
						}

						switch ($theCheckType) {
						case '5':
						case '6':
						case '7':
						case '8':
						case '9':
						case '11':
							if (trim($ListRow[$option_array[1]]) != '') {
								$content .= ',"' . qconversionstring($ListRow[$option_array[1]]) . '	"';
							}
							else {
								$content .= ',""';
							}

							break;

						default:
							$encode = mb_detect_encoding($ListRow[$option_array[1]], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

							if ($encode == 'UTF-8') {
								$content .= ',"' . qconversionstring(iconv('UTF-8', 'GBK', $ListRow[$option_array[1]])) . '"';
							}
							else {
								$content .= ',"' . qconversionstring($ListRow[$option_array[1]]) . '"';
							}

							break;
						}
					}

					break;

				case '6':
				case '26':
				case '19':
					if ($ListRow[$option_array[2]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
					}

					break;

				case '10':
				case '20':
				case '16':
				case '22':
					if ($ListRow[$option_array[1]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . $ListRow[$option_array[1]] . '"';
					}

					break;

				case '11':
					if ($ListRow[$option_array[1]] != '') {
						$systemPath = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);

						if ($S_Row['custDataPath'] == '') {
							$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $ListRow['joinTime']) . '/' . date('d', $ListRow['joinTime']) . '/';
						}
						else {
							$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
						}

						$content .= ',"' . $systemPath . $filePath . $ListRow[$option_array[1]] . '"';
					}
					else {
						$content .= ',""';
					}

					break;

				case '13':
					if ($ListRow[$option_array[2]] == '') {
						$content .= ',""';
					}
					else {
						$D_Row = $QtnListArray[$option_array[1]];
						$Conn = odbc_connect(trim($D_Row['DSNConnect']), trim($D_Row['DSNUser']), trim($D_Row['DSNPassword']));

						if (!$Conn) {
							_showerror('System Error', 'Connection Failed:' . trim($D_Row['DSNConnect']) . '-' . trim($D_Row['DSNUser']) . '-' . trim($D_Row['DSNPassword']));
						}

						$ODBC_Result = odbc_exec($Conn, _getsql($D_Row['DSNSQL']));

						if (!$ODBC_Result) {
							_showerror('System Error', 'Error in SQL:' . trim($D_Row['DSNSQL']));
						}

						$isOver = false;

						while (odbc_fetch_row($ODBC_Result)) {
							$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
							$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
							if (($ItemValue == $ListRow[$option_array[2]]) && ($isOver == false)) {
								$content .= ',"' . qconversionstring($ItemValue) . '"';
								$isOver = true;
							}
						}
					}

					break;

				case '15':
				case '21':
					if ($ListRow[$option_array[2]] != 99) {
						if ($ListRow[$option_array[2]] == '0') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . ($ListRow[$option_array[2]] * $option_array[1]) . '"';
						}
					}
					else {
						$theQtnArray = explode('_', $option_array[2]);
						$theQtnId = $theQtnArray[1];

						if ($QtnListArray[$theQtnId]['negCode'] != 0) {
							$content .= ',"' . $QtnListArray[$theQtnId]['negCode'] . '"';
						}
						else {
							$content .= ',"99"';
						}
					}

					break;

				case '17':
					if ($option_array[3] == 1) {
						if ($ListRow[$option_array[2]] == '') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
						}
					}
					else if ($ListRow[$option_array[1]] == '') {
						$content .= ',""';
					}
					else {
						$option_value_array = explode(',', $ListRow[$option_array[1]]);

						if (in_array($option_array[2], $option_value_array)) {
							$content .= ',"1"';
						}
						else {
							$content .= ',"0"';
						}
					}

					break;

				case '18':
					if ($option_array[3] == 0) {
						if ($ListRow[$option_array[2]] == '') {
							$content .= ',""';
						}
						else {
							$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$option_array[2]]] . '"';
						}
					}
					else if ($ListRow[$option_array[1]] == '') {
						$content .= ',""';
					}
					else {
						$option_value_array = explode(',', $ListRow[$option_array[1]]);

						if (in_array($option_array[2], $option_value_array)) {
							$content .= ',"1"';
						}
						else {
							$content .= ',"0"';
						}
					}

					break;
				}
			}
		}

		$content .= "\r\n";
	}

	unset($option_tran_array);
	return $content;
}

function export_label($surveyID, $theExportQtnList = array())
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $table_prefix;
	global $Module;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	global $CascadeArray;
	$SQL = ' SELECT isPublic,ajaxRtnValue,isRecord,forbidViewId,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);
	$isViewAreaInfo = $isViewPanelInfo = $isViewRecFile = true;

	if ($_SESSION['adminRoleType'] == '3') {
		$forbidViewIdValue = explode(',', $S_Row['forbidViewId']);

		if (in_array('t1', $forbidViewIdValue)) {
			$isViewAreaInfo = false;
		}

		if (in_array('t2', $forbidViewIdValue)) {
			$isViewPanelInfo = false;
		}

		if (in_array('t3', $forbidViewIdValue)) {
			$isViewRecFile = false;
		}
	}

	$content = ' VARIABLE LABELS responseNo \'' . $lang['export_responseID'] . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS joinTime \'' . $lang['export_joinTime'] . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS submitTime \'' . $lang['export_submitTime'] . '\'.' . "\r\n" . '';
	if (($S_Row['isRecord'] != 0) && ($isViewRecFile == true)) {
		$content .= ' VARIABLE LABELS recordFile \'' . $lang['export_recFile'] . '\'.' . "\r\n" . '';
	}

	$content .= ' VARIABLE LABELS submitFlag \'' . $lang['export_overFlag'] . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS dataAuthStat \'' . $lang['export_authStat'] . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS timeSpent \'' . $lang['export_overTime'] . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS dataSource \'' . $lang['export_server'] . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS ipAddress \'' . $lang['export_ipAddress'] . '\'.' . "\r\n" . '';

	if ($isViewAreaInfo == true) {
		$content .= ' VARIABLE LABELS fromArea \'' . $lang['export_area'] . '\'.' . "\r\n" . '';
	}

	if ($isViewPanelInfo == true) {
		$content .= ' VARIABLE LABELS userName \'' . $lang['export_administratorsName'] . '\'.' . "\r\n" . '';
	}

	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
	if (($S_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			$content .= ' VARIABLE LABELS userGroup \'' . $lang['export_userGroup'] . '\'.' . "\r\n" . '';

			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$content .= ' VARIABLE LABELS PanelProperties_' . $i . ' \'' . qconverionlabel($ajaxRtnValueName[$i]) . '\'.' . "\r\n" . '';
				}
			}

			break;

		case '3':
		case '4':
		case '5':
			break;

		case '2':
			if ($S_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($S_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$content .= ' VARIABLE LABELS PanelProperties_' . $i . ' \'' . qconverionlabel($ajaxRtnValueName[$i]) . '\'.' . "\r\n" . '';
				}
			}

			break;
		}
	}

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
			if ($_SESSION['adminRoleType'] == '3') {
				if (!in_array($questionID, $forbidViewIdValue)) {
					if (0 < count($theExportQtnList)) {
						if (in_array($questionID, $theExportQtnList)) {
							$surveyID = $surveyID;
							$ModuleName = $Module[$theQtnArray['questionType']];
							require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.label.inc.php';
						}
					}
					else {
						$surveyID = $surveyID;
						$ModuleName = $Module[$theQtnArray['questionType']];
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.label.inc.php';
					}
				}
			}
			else if (0 < count($theExportQtnList)) {
				if (in_array($questionID, $theExportQtnList)) {
					$surveyID = $surveyID;
					$ModuleName = $Module[$theQtnArray['questionType']];
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.label.inc.php';
				}
			}
			else {
				$surveyID = $surveyID;
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.label.inc.php';
			}
		}
	}

	return $content;
}

function export_text($surveyID)
{
	global $DB;
	global $lang;
	global $Module;
	global $table_prefix;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	global $InfoListArray;
	$content = '';
	$SQL = ' SELECT questionType,questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionType NOT IN (12,13,17,18,19,20,21,22,28,29,31) ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$questionID = $Row['questionID'];
		$theQtnArray = $QtnListArray[$questionID];
		$ModuleName = $Module[$Row['questionType']];

		if ($Row['questionType'] == 8) {
			$content .= $lang['txt_page'] . "\r\n";
		}
		else {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.text.inc.php';
		}

		$content .= "\r\n";
	}

	return $content;
}

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

function qconverionlabel($string)
{
	$string = strip_tags($string);
	$string = str_replace('.', '', $string);
	$string = str_replace('&quot;', '"', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace('\'', '\'\'', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

?>

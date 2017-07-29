<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisURL = 'DefineReport.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

if ($_POST['Action'] == 'DataAnalysisSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$theSID = $Row['surveyID'];
	if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
	$DataHTML = '';

	if ($_POST['defineID'] != '') {
		$SQL = ' SELECT * FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND defineID IN (' . $_POST['defineID'] . ') ORDER BY defineID ASC ';
	}
	else {
		$SQL = ' SELECT * FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND defineID =0 ORDER BY defineID ASC ';
		$DataHTML .= $lang['select_check_data'];
	}

	$Result = $DB->query($SQL);
	$dataSource = getdatasourcesql($_POST['dataSource'], $_POST['surveyID']);

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	if (isset($_POST['defineReportType']) && ($_POST['defineReportType'] == 1)) {
		if ($_POST['dataSource'] == 0) {
			$dataSource .= ' and b.overFlag IN (1,3) ';
		}
	}

	$CountSQL = ' SELECT COUNT(*) AS totalRepAnswerNum FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $dataSource;
	$CountRow = $DB->queryFirstRow($CountSQL);
	$totalRepAnswerNum = $CountRow['totalRepAnswerNum'];

	while ($Row = $DB->queryArray($Result)) {
		$theDeQuestionID = $Row['questionID'];
		$theDefineReportText = $theDeQuestionID . $Row['defineID'];
		$theCoeffReportId = $theDeQuestionID . '3' . $Row['defineID'];
		$theDefineType = $Row['defineType'];

		switch ($theDefineType) {
		case 1:
			$EnableQCoreClass->replace('totalRepAnswerNum', $totalRepAnswerNum);
			$questionID = $Row['questionID'];
			$theQtnArray = $QtnListArray[$questionID];
			$surveyID = $_POST['surveyID'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.dcount.inc.php';
			$DataHTML .= $DataCrossHTML;
			break;

		case 3:
			$EnableQCoreClass->replace('totalResponseNum', $totalRepAnswerNum);
			$totalResponseNum = $totalRepAnswerNum;
			$questionID = $Row['questionID'];
			$theQtnArray = $QtnListArray[$questionID];
			$surveyID = $_POST['surveyID'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.dcoeff.inc.php';
			$DataHTML .= $DataCrossHTML;
			break;

		case 2:
		case 4:
			$theXArray = array();
			$theXFields = array();
			$theXFather = array();
			$theXSelf = array();
			$theGetQuestionID1 = array();
			$k = 0;
			$theGetQuestionID1[0] = $Row['condOnID'] . '*' . $Row['qtnID'] . '*' . $Row['optionID'];
			$theGetQuestionID1[1] = $Row['condOnID2'] . '*' . $Row['qtnID2'] . '*' . $Row['optionID2'];

			foreach ($theGetQuestionID1 as $theXQuestionIDText) {
				$theXQuestionIDArray = explode('*', $theXQuestionIDText);
				$theXQuestionID = $theXQuestionIDArray[0];
				$theOptionID = $theXQuestionIDArray[1];
				$theLabelID = $theXQuestionIDArray[2];

				switch ($QtnListArray[$theXQuestionID]['questionType']) {
				case '1':
					foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
						$theXArray[$k][] = qnospecialchar($theQuestionArray['optionName']);
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  ' . $question_yesnoID . ' ';
					}

					break;

				case '2':
					foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
						$theXArray[$k][] = qnospecialchar($theQuestionArray['optionName']);
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  ' . $question_radioID . ' ';
					}

					if (($QtnListArray[$theXQuestionID]['isSelect'] != '1') && ($QtnListArray[$theXQuestionID]['isHaveOther'] == '1')) {
						$theXArray[$k][] = qnospecialchar($QtnListArray[$theXQuestionID]['otherText']);
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = 0 AND b.TextOtherValue_' . $theXQuestionID . ' != \'\'  ';
					}

					break;

				case '3':
					if ($theOptionID != 0) {
						if ($theOptionID == '99999') {
							$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$theXQuestionID]['allowType']));
							$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
							$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_no_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
						}
						else {
							$theXArray[$k][] = qnospecialchar($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
							$theXArray[$k][] = qnospecialchar($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
						}
					}
					else {
						$theXArray[$k][] = qnospecialchar($QtnListArray[$theXQuestionID]['otherText']) . ' - ' . $lang['checkbox_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') AND b.TextOtherValue_' . $theXQuestionID . ' != \'\' ';
						$theXArray[$k][] = qnospecialchar($QtnListArray[$theXQuestionID]['otherText']) . ' - ' . $lang['checkbox_no_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') =0 AND b.TextOtherValue_' . $theXQuestionID . ' = \'\' AND b.option_' . $theXQuestionID . ' != \'\' ';
					}

					break;

				case '6':
				case '19':
					foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
						$theXArray[$k][] = qnospecialchar($theAnswerArray['optionAnswer']);
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' = ' . $question_range_answerID . ' ';
					}

					break;

				case '7':
					$theXArray[$k][] = qnospecialchar($OptionListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') ';
					$theXArray[$k][] = qnospecialchar($OptionListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_no_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') =0 AND b.option_' . $theXQuestionID . '_' . $theOptionID . ' != \'\' ';
					break;

				case '10':
					$optionOrderNum = count($RankListArray[$theXQuestionID]);

					if ($QtnListArray[$theXQuestionID]['isHaveOther'] == '1') {
						$optionOrderNum++;
					}

					$l = 1;

					for (; $l <= $optionOrderNum; $l++) {
						$theXArray[$k][] = $l;
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' = ' . $l . ' ';
					}

					break;

				case '13':
					$Conn = odbc_connect(trim($QtnListArray[$theXQuestionID]['DSNConnect']), trim($QtnListArray[$theXQuestionID]['DSNUser']), trim($QtnListArray[$theXQuestionID]['DSNPassword']));
					$ODBC_Result = odbc_exec($Conn, _getsql($QtnListArray[$theXQuestionID]['DSNSQL']));

					while (odbc_fetch_row($ODBC_Result)) {
						$theItemValue = odbc_result($ODBC_Result, 'ItemValue');
						$theItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
						$theXArray[$k][] = $theItemDisplay;
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  \'' . $theItemValue . '\' ';
					}

					break;

				case '15':
				case '21':
					$l = $QtnListArray[$theXQuestionID]['endScale'];

					for (; $QtnListArray[$theXQuestionID]['startScale'] <= $l; $l--) {
						$theXArray[$k][] = $QtnListArray[$theXQuestionID]['weight'] * $l;
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' = ' . $l . ' ';
					}

					if ($QtnListArray[$theXQuestionID]['isHaveUnkown'] == 1) {
						$theXArray[$k][] = $lang['rating_unknow'];
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' = 99';
					}

					break;

				case '17':
					$theBaseID = $QtnListArray[$theXQuestionID]['baseID'];
					$theBaseQtnArray = $QtnListArray[$theBaseID];
					$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

					if ($QtnListArray[$theXQuestionID]['isSelect'] == 1) {
						foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
							$theXArray[$k][] = qnospecialchar($theQuestionArray['optionName']);
							$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'' . $question_checkboxID . '\' ';
						}

						if ($theBaseQtnArray['isHaveOther'] == '1') {
							$theXArray[$k][] = qnospecialchar($theBaseQtnArray['otherText']);
							$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'0\' ';
						}

						if ($QtnListArray[$theXQuestionID]['isCheckType'] == '1') {
							$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$theXQuestionID]['allowType']));
							$theXArray[$k][] = $negText;
							$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'99999\' ';
						}
					}
					else if ($theOptionID != 0) {
						if ($theOptionID == '99999') {
							$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$theXQuestionID]['allowType']));
							$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
							$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_no_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
						}
						else {
							$theXArray[$k][] = qnospecialchar($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
							$theXArray[$k][] = qnospecialchar($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
							$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
						}
					}
					else {
						$theXArray[$k][] = qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . $lang['checkbox_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') ';
						$theXArray[$k][] = qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . $lang['checkbox_no_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
					}

					break;

				case '18':
					if ($QtnListArray[$theXQuestionID]['isSelect'] == 1) {
						$theXArray[$k][] = qnospecialchar($YesNoListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
						$theXArray[$k][] = qnospecialchar($YesNoListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
					}
					else {
						foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
							$theXArray[$k][] = qnospecialchar($theQuestionArray['optionName']);
							$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'' . $question_yesnoID . '\' ';
						}
					}

					break;

				case '20':
					$theBaseID = $QtnListArray[$theXQuestionID]['baseID'];
					$theBaseQtnArray = $QtnListArray[$theBaseID];
					$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
					$optionOrderNum = count($theCheckBoxListArray);

					if ($theBaseQtnArray['isHaveOther'] == 1) {
						$optionOrderNum++;
					}

					$l = 1;

					for (; $l <= $optionOrderNum; $l++) {
						$theXArray[$k][] = $l;
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' = ' . $l . ' ';
					}

					break;

				case '24':
					foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
						$theXArray[$k][] = qnospecialchar($theQuestionArray['optionName']);
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  ' . $question_radioID . ' ';
					}

					break;

				case '25':
					$theXArray[$k][] = qnospecialchar($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
					$theXArray[$k][] = qnospecialchar($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\'';
					break;

				case '26':
					foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
						$theXArray[$k][] = qnospecialchar($theAnswerArray['optionAnswer']);
						$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . '_' . $theLabelID . ' = ' . $question_range_answerID . ' ';
					}

					break;

				case '28':
					$theBaseID = $QtnListArray[$theXQuestionID]['baseID'];
					$theBaseQtnArray = $QtnListArray[$theBaseID];
					$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

					if ($theOptionID == 0) {
						$theXArray[$k][] = qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_0) ';
						$theXArray[$k][] = qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_no_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_0) =0 AND b.option_' . $theXQuestionID . '_0 != \'\' ';
					}
					else {
						$theXArray[$k][] = qnospecialchar($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') ';
						$theXArray[$k][] = qnospecialchar($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_no_checked'];
						$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') =0 AND b.option_' . $theXQuestionID . '_' . $theOptionID . ' != \'\' ';
					}

					break;

				case '31':
					foreach ($CascadeArray[$theXQuestionID] as $nodeID => $theQuestionArray) {
						if ($theOptionID == $theQuestionArray['level']) {
							$theXArray[$k][] = qcrossqtnname($theQuestionArray['nodeName']);
							$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' =  \'' . $nodeID . '\' ';
							$theXFather[$k][] = $theQuestionArray['nodeFatherID'];
							$theXSelf[$k][] = $nodeID;
						}
					}

					break;
				}

				$k++;
			}

			$theXName = $theXCond = array();

			if (count($theXArray) == 2) {
				foreach ($theXArray[0] as $k => $thisFristName) {
					foreach ($theXArray[1] as $m => $thisSecName) {
						if ((count($theXFather) == 2) && (count($theXSelf) == 2)) {
							if ($theXFather[1][$m] == $theXSelf[0][$k]) {
								$theXName[] = $thisFristName . ' / ' . $thisSecName;
								$theXCond[] = $theXFields[0][$k] . ' AND ' . $theXFields[1][$m];
							}
							else {
								continue;
							}
						}
						else {
							$theXName[] = $thisFristName . ' / ' . $thisSecName;
							$theXCond[] = $theXFields[0][$k] . ' AND ' . $theXFields[1][$m];
						}
					}
				}
			}
			else {
				foreach ($theXArray[0] as $k => $thisFristName) {
					$theXName[] = $thisFristName;
					$theXCond[] = $theXFields[0][$k];
				}
			}

			if (count($theXName) == 0) {
				$DataHTML .= '<br/><font color=red><b>自变量的组合为空，可能是选择的两个级联变量不是父子关系，尚不能进行交叉分析</b></font><br/>';
				continue;
			}

			if ($theDefineType == 2) {
				$DataCrossHTML = '';
				$questionID = $theDeQuestionID;
				$theSurveyID = $_POST['surveyID'];
				$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.dcross.inc.php';
				unset($theXArray);
				unset($theXFields);
				unset($theXName);
				unset($theXCond);
				unset($theXFather);
				unset($theXSelf);
				unset($theGetQuestionID1);
				$DataHTML .= $DataCrossHTML;
			}
			else {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $theDefineReportText . 'File', 'MeanCross2D.html');
				$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'COLS', 'cols' . $theDefineReportText);
				$EnableQCoreClass->replace('cols' . $theDefineReportText, '');
				$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'COL0', 'col0' . $theDefineReportText);
				$EnableQCoreClass->replace('col0' . $theDefineReportText, '');

				foreach ($theXName as $k => $thisXName) {
					$EnableQCoreClass->replace('colName', $thisXName);
					$EnableQCoreClass->parse('cols' . $theDefineReportText, 'COLS', true);
					$EnableQCoreClass->parse('col0' . $theDefineReportText, 'COL0', true);
				}

				$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'ROWS', 'rows' . $theDefineReportText);
				$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $theDefineReportText);
				$EnableQCoreClass->replace('rows' . $theDefineReportText, '');
				$EnableQCoreClass->replace('cell' . $theDefineReportText, '');
				$theGetQuestionID1 = explode(',', $theDeQuestionID);

				foreach ($theGetQuestionID1 as $questionID) {
					$theSurveyID = $_POST['surveyID'];

					if (!empty($QtnListArray[$questionID])) {
						$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.mean.inc.php';
					}
				}

				unset($theXArray);
				unset($theXFields);
				unset($theXName);
				unset($theXCond);
				unset($theXFather);
				unset($theXSelf);
				unset($theGetQuestionID1);
				$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $theDefineReportText, 'ShowOption' . $theDefineReportText . 'File');
				$DataHTML .= $DataCrossHTML;
			}

			break;
		}
	}

	exit($DataHTML);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . REPORTDEFINE_TABLE . ' WHERE defineID = \'' . $_GET['defineID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['report_define_dele']);
	_showsucceed($lang['report_define_dele'], $thisURL);
}

if ($_POST['DeleteDefineSubmit']) {
	if (is_array($_POST['defineID']) && !empty($_POST['defineID'])) {
		$defineIDLists = join(',', $_POST['defineID']);
		$SQL = ' DELETE FROM  ' . REPORTDEFINE_TABLE . ' WHERE defineID IN (' . $defineIDLists . ') AND surveyID=\'' . $_POST['surveyID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
		$DB->query($SQL);
	}

	writetolog($lang['report_define_dele']);
	_showsucceed($lang['report_define_dele'], $thisURL);
}

if ($_POST['Action'] == 'DefineReportAddSubmit') {
	switch ($_POST['reportType']) {
	case 1:
		$SQL = ' SELECT questionID FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND questionID = \'' . $_POST['questionID'] . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) AND defineType=1 LIMIT 1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (!$HaveRow) {
			$SQL = ' INSERT INTO ' . REPORTDEFINE_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',questionID = \'' . $_POST['questionID'] . '\',defineType=1,administratorsID =\'' . $_SESSION['administratorsID'] . '\',defineShare=\'' . $_POST['defineShare'] . '\' ';
			$DB->query($SQL);
		}

		break;

	case 2:
		$theXQuestionIDArray = explode('*', $_POST['questionID1'][0]);
		$theXQuestionID = $theXQuestionIDArray[0];
		$theOptionID = $theXQuestionIDArray[1];
		$theLabelID = $theXQuestionIDArray[2];
		$theXQuestionIDArray2 = explode('*', $_POST['questionID1'][1]);
		$theXQuestionID2 = $theXQuestionIDArray2[0];
		$theOptionID2 = $theXQuestionIDArray2[1];
		$theLabelID2 = $theXQuestionIDArray2[2];
		$SQL = ' SELECT questionID FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND questionID = \'' . $_POST['questionID2'] . '\' AND condOnID =\'' . $theXQuestionID . '\' AND optionID =\'' . $theLabelID . '\' AND qtnID =\'' . $theOptionID . '\' AND condOnID2 =\'' . $theXQuestionID2 . '\' AND optionID2 =\'' . $theLabelID2 . '\' AND qtnID2 =\'' . $theOptionID2 . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) AND defineType=2 LIMIT 1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (!$HaveRow) {
			$SQL = ' INSERT INTO ' . REPORTDEFINE_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',questionID = \'' . $_POST['questionID2'] . '\',condOnID =\'' . $theXQuestionID . '\',optionID =\'' . $theLabelID . '\',qtnID =\'' . $theOptionID . '\',condOnID2 =\'' . $theXQuestionID2 . '\',optionID2 =\'' . $theLabelID2 . '\',qtnID2 =\'' . $theOptionID2 . '\',defineType=2,administratorsID =\'' . $_SESSION['administratorsID'] . '\',defineShare=\'' . $_POST['defineShare'] . '\' ';
			$DB->query($SQL);
		}

		break;

	case 3:
		$SQL = ' SELECT questionID FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND questionID = \'' . $_POST['questionID3'] . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) AND defineType=3 LIMIT 1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (!$HaveRow) {
			$SQL = ' INSERT INTO ' . REPORTDEFINE_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',questionID = \'' . $_POST['questionID3'] . '\',defineType=3,administratorsID =\'' . $_SESSION['administratorsID'] . '\',defineShare=\'' . $_POST['defineShare'] . '\' ';
			$DB->query($SQL);
		}

		break;

	case 4:
		$theXQuestionIDArray = explode('*', $_POST['questionID5'][0]);
		$theXQuestionID = $theXQuestionIDArray[0];
		$theOptionID = $theXQuestionIDArray[1];
		$theLabelID = $theXQuestionIDArray[2];
		$theXQuestionIDArray2 = explode('*', $_POST['questionID5'][1]);
		$theXQuestionID2 = $theXQuestionIDArray2[0];
		$theOptionID2 = $theXQuestionIDArray2[1];
		$theLabelID2 = $theXQuestionIDArray2[2];
		$SQL = ' SELECT questionID FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND questionID = \'' . implode(',', $_POST['questionID4']) . '\' AND condOnID =\'' . $theXQuestionID . '\' AND optionID =\'' . $theLabelID . '\' AND qtnID =\'' . $theOptionID . '\' AND condOnID2 =\'' . $theXQuestionID2 . '\' AND optionID2 =\'' . $theLabelID2 . '\' AND qtnID2 =\'' . $theOptionID2 . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) AND defineType=4 LIMIT 1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (!$HaveRow) {
			$SQL = ' INSERT INTO ' . REPORTDEFINE_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',questionID = \'' . implode(',', $_POST['questionID4']) . '\',condOnID =\'' . $theXQuestionID . '\',optionID =\'' . $theLabelID . '\',qtnID =\'' . $theOptionID . '\',condOnID2 =\'' . $theXQuestionID2 . '\',optionID2 =\'' . $theLabelID2 . '\',qtnID2 =\'' . $theOptionID2 . '\',defineType=4,administratorsID =\'' . $_SESSION['administratorsID'] . '\',defineShare=\'' . $_POST['defineShare'] . '\' ';
			$DB->query($SQL);
		}

		break;
	}

	writetolog($lang['report_define_add']);
	_showmessage($lang['report_define_add'], true);
}

$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$theSID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if ($_GET['Action'] == 'NewDefine') {
	$EnableQCoreClass->setTemplateFile('ReportDefineAddFile', 'DefineReportAdd.html');

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '5':
		$EnableQCoreClass->replace('defineDisabled', '');
		break;

	default:
		$EnableQCoreClass->replace('defineDisabled', 'disabled');
		break;
	}

	$questionList = $questionList1 = $questionList2 = $questionList3 = $questionList4 = $questionList5 = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (in_array($theQtnArray['questionType'], array('1', '2', '3', '4', '6', '7', '10', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '28'))) {
			if ($theQtnArray['questionType'] == '4') {
				if ($theQtnArray['isCheckType'] == '4') {
					$questionList3 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}
			else if ($theQtnArray['questionType'] == '23') {
				$isHaveNumber = false;

				foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
					if ($theQuestionArray['isCheckType'] == 4) {
						$isHaveNumber = true;
						break;
					}
				}

				if ($isHaveNumber == true) {
					$questionList3 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}
			else {
				$questionList3 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		switch ($theQtnArray['questionType']) {
		case '1':
		case '2':
		case '24':
			$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList5 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			break;

		case '13':
			$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList5 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			break;

		case '3':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
				$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if ($theQtnArray['isNeg'] == '1') {
				$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
				$questionList1 .= '<option value=' . $questionID . '*99999>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*99999>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '17':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			if ($QtnListArray[$questionID]['isSelect'] == 1) {
				$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
			else {
				$theBaseID = $QtnListArray[$questionID]['baseID'];
				$theBaseQtnArray = $QtnListArray[$theBaseID];
				$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

				foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}

				if ($theBaseQtnArray['isHaveOther'] == '1') {
					$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}

				if ($theQtnArray['isCheckType'] == '1') {
					$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
					$questionList1 .= '<option value=' . $questionID . '*99999>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*99999>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			break;

		case '18':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			if ($QtnListArray[$questionID]['isSelect'] == 1) {
				foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_yesnoID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_yesnoID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}
			else {
				$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '25':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '6':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '7':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			break;

		case '10':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
				$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '19':
		case '20':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if ($theBaseQtnArray['isHaveOther'] == '1') {
				$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList5 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '15':
			if ($theQtnArray['isSelect'] == 0) {
				$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

				foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			break;

		case '21':
			if ($theQtnArray['isSelect'] == 0) {
				$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				$theBaseID = $QtnListArray[$questionID]['baseID'];
				$theBaseQtnArray = $QtnListArray[$theBaseID];
				$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

				foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}

				if ($theBaseQtnArray['isHaveOther'] == '1') {
					$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			break;

		case '26':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_labelID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_labelID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			break;

		case '28':
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
					$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*' . $question_checkboxID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			if ($theBaseQtnArray['isHaveOther'] == '1') {
				foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
					$questionList1 .= '<option value=' . $questionID . '*0*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
					$questionList5 .= '<option value=' . $questionID . '*0*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
				}
			}

			break;

		case '30':
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			break;

		case '31':
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
			$i = 1;

			for (; $i <= $QtnListArray[$questionID]['maxSize']; $i++) {
				$tmp = $i - 1;
				$questionList1 .= '<option value=' . $questionID . '*' . $i . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;
		}

		switch ($theQtnArray['questionType']) {
		case '1':
		case '2':
		case '6':
		case '10':
		case '15':
		case '16':
		case '19':
		case '20':
		case '21':
		case '22':
		case '24':
		case '26':
			$questionList4 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			break;

		case '17':
			if ($QtnListArray[$questionID]['isSelect'] == 1) {
				$questionList4 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '18':
			if ($QtnListArray[$questionID]['isSelect'] != 1) {
				$questionList4 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '4':
			if ($QtnListArray[$questionID]['isCheckType'] == '4') {
				$questionList4 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '23':
			$haveValueCheck = false;

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				if ($theQuestionArray['isCheckType'] == '4') {
					$haveValueCheck = true;
					break;
				}
			}

			if ($haveValueCheck == true) {
				$questionList4 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;

		case '27':
		case '29':
			$haveValueCheck = false;

			foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
				if ($theLabelArray['isCheckType'] == '4') {
					$haveValueCheck = true;
					break;
				}
			}

			if ($haveValueCheck == true) {
				$questionList4 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;
		}
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->replace('questionList1', $questionList1);
	$EnableQCoreClass->replace('questionList2', $questionList2);
	$EnableQCoreClass->replace('questionList3', $questionList3);
	$EnableQCoreClass->replace('questionList4', $questionList4);
	$EnableQCoreClass->replace('questionList5', $questionList5);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$ReportDefinePage = $EnableQCoreClass->parse('ReportDefinePage', 'ReportDefineAddFile');
	echo $ReportDefinePage;
	exit();
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$EnableQCoreClass->setTemplateFile('ReportDefineFile', 'DefineReport.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('defineURL', $thisURL);
$EnableQCoreClass->replace('newDefineURL', $thisURL . '&Action=NewDefine');
$EnableQCoreClass->set_CycBlock('ReportDefineFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$SQL = ' SELECT * FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) )  ORDER BY defineID ASC ';
$Result = $DB->query($SQL);
$theRecNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $theRecNum);

if ($theRecNum == 0) {
	$EnableQCoreClass->replace('isHaveDefineReport', 'disabled');
	$EnableQCoreClass->replace('isHaveDefineReportText', '<tr><td colspan=4 style="padding-left:10px"><span class=red>当前调查问卷或者您未有自由组装分析报告的定义</span></td></tr>');
}
else {
	$EnableQCoreClass->replace('isHaveDefineReport', '');
	$EnableQCoreClass->replace('isHaveDefineReportText', '');
}

$orderNum = 1;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('defineID', $Row['defineID']);

	if ($Row['defineShare'] == 0) {
		$EnableQCoreClass->replace('reportName', $lang['reporttype_' . $Row['defineType']] . $lang['report_private']);
	}
	else {
		$EnableQCoreClass->replace('reportName', $lang['reporttype_' . $Row['defineType']]);
	}

	if ($Row['defineShare'] == 1) {
		if ($Row['administratorsID'] == 0) {
			$EnableQCoreClass->replace('haveDelete', '');
			$EnableQCoreClass->replace('deleteDisabled', '');
			$EnableQCoreClass->replace('deleteURL', $thisURL . '&Action=Delete&defineID=' . $Row['defineID']);
		}
		else {
			if (($Row['administratorsID'] == $_SESSION['administratorsID']) || ($_SESSION['adminRoleType'] == '1')) {
				$EnableQCoreClass->replace('haveDelete', '');
				$EnableQCoreClass->replace('deleteDisabled', '');
				$EnableQCoreClass->replace('deleteURL', $thisURL . '&Action=Delete&defineID=' . $Row['defineID']);
			}
			else {
				$EnableQCoreClass->replace('haveDelete', 'none');
				$EnableQCoreClass->replace('deleteDisabled', '');
				$EnableQCoreClass->replace('deleteURL', 'javascript:void(0);');
			}
		}
	}
	else {
		if (($Row['administratorsID'] == $_SESSION['administratorsID']) || ($_SESSION['adminRoleType'] == '1')) {
			$EnableQCoreClass->replace('haveDelete', '');
			$EnableQCoreClass->replace('deleteDisabled', '');
			$EnableQCoreClass->replace('deleteURL', $thisURL . '&Action=Delete&defineID=' . $Row['defineID']);
		}
		else {
			$EnableQCoreClass->replace('haveDelete', 'none');
			$EnableQCoreClass->replace('deleteDisabled', '');
			$EnableQCoreClass->replace('deleteURL', 'javascript:void(0);');
		}
	}

	if (($Row['defineType'] == 1) || ($Row['defineType'] == 3)) {
		$defineList = qnospecialchar($QtnListArray[$Row['questionID']]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$Row['questionID']]['questionType']] . ')';
		$EnableQCoreClass->replace('defineList', $defineList);
	}
	else {
		$defineList = '';
		$theSpaceChr = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$theRowQuestionIDList = explode(',', $Row['questionID']);
		$tmp = 0;

		foreach ($theRowQuestionIDList as $questionID) {
			if ($tmp == 0) {
				$defineList .= $lang['cross_row_var'] . qnospecialchar($QtnListArray[$questionID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$questionID]['questionType']] . ')<br/>';
			}
			else if (!empty($QtnListArray[$questionID])) {
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$questionID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$questionID]['questionType']] . ')<br/>';
			}

			$tmp++;
		}

		$condOnID = $Row['condOnID'];

		switch ($QtnListArray[$condOnID]['questionType']) {
		case '1':
		case '2':
		case '13':
		case '24':
			$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			break;

		case '3':
			if ($Row['qtnID'] != '0') {
				if ($Row['qtnID'] == '99999') {
					$negText = ($QtnListArray[$condOnID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$condOnID]['allowType']));
					$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($CheckBoxListArray[$condOnID][$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
			}
			else {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$condOnID]['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}

			break;

		case '25':
			$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($CheckBoxListArray[$condOnID][$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			break;

		case '17':
			if ($QtnListArray[$condOnID]['isSelect'] == 1) {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}
			else {
				$theBaseID = $QtnListArray[$condOnID]['baseID'];
				$theBaseQtnArray = $QtnListArray[$theBaseID];
				$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

				if ($Row['qtnID'] != '0') {
					if ($Row['qtnID'] == '99999') {
						$negText = ($QtnListArray[$condOnID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$condOnID]['allowType']));
						$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
					else {
						$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
				}
				else {
					$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
			}

			break;

		case '18':
			if ($QtnListArray[$condOnID]['isSelect'] == 1) {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($YesNoListArray[$condOnID][$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}
			else {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}

			break;

		case '6':
			$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($OptionListArray[$condOnID][$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			break;

		case '7':
			$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($OptionListArray[$condOnID][$Row['qtnID']]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$condOnID][$Row['optionID']]['optionAnswer']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			break;

		case '10':
			if ($Row['qtnID'] != '0') {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($RankListArray[$condOnID][$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}
			else {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$condOnID]['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}

			break;

		case '19':
		case '20':
			$theBaseID = $QtnListArray[$condOnID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			if ($Row['qtnID'] != '0') {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}
			else {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}

			break;

		case '15':
			if ($QtnListArray[$condOnID]['isSelect'] == 0) {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($RankListArray[$condOnID][$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}

			break;

		case '21':
			if ($QtnListArray[$condOnID]['isSelect'] == 0) {
				$theBaseID = $QtnListArray[$condOnID]['baseID'];
				$theBaseQtnArray = $QtnListArray[$theBaseID];
				$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

				if ($Row['qtnID'] != '0') {
					$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
			}

			break;

		case '26':
			$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($OptionListArray[$condOnID][$Row['qtnID']]['optionName']) . ' - ' . qnospecialchar($LabelListArray[$condOnID][$Row['optionID']]['optionLabel']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			break;

		case '28':
			$theBaseID = $QtnListArray[$condOnID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			if ($Row['qtnID'] != '0') {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID']]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$condOnID][$Row['optionID']]['optionAnswer']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}
			else {
				$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($AnswerListArray[$condOnID][$Row['optionID']]['optionAnswer']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			}

			break;

		case '31':
			$theUnitText = explode('#', $QtnListArray[$condOnID]['unitText']);
			$defineList .= $lang['cross_col_var'] . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theUnitText[$Row['qtnID'] - 1]) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
			break;
		}

		if ($Row['condOnID2'] != '0') {
			$condOnID = $Row['condOnID2'];

			switch ($QtnListArray[$condOnID]['questionType']) {
			case '1':
			case '2':
			case '13':
			case '24':
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				break;

			case '3':
				if ($Row['qtnID2'] != '0') {
					if ($Row['qtnID2'] == '99999') {
						$negText = ($QtnListArray[$condOnID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$condOnID]['allowType']));
						$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
					else {
						$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($CheckBoxListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
				}
				else {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$condOnID]['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}

				break;

			case '25':
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($CheckBoxListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				break;

			case '17':
				if ($QtnListArray[$condOnID]['isSelect'] == 1) {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$theBaseID = $QtnListArray[$condOnID]['baseID'];
					$theBaseQtnArray = $QtnListArray[$theBaseID];
					$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

					if ($Row['qtnID2'] != '0') {
						if ($Row['qtnID2'] == '99999') {
							$negText = ($QtnListArray[$condOnID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$condOnID]['allowType']));
							$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
						}
						else {
							$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
						}
					}
					else {
						$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
				}

				break;

			case '18':
				if ($QtnListArray[$condOnID]['isSelect'] == 1) {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($YesNoListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}

				break;

			case '6':
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($OptionListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				break;

			case '7':
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($OptionListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$condOnID][$Row['optionID2']]['optionAnswer']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				break;

			case '10':
				if ($Row['qtnID2'] != '0') {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($RankListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$condOnID]['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}

				break;

			case '19':
			case '20':
				$theBaseID = $QtnListArray[$condOnID]['baseID'];
				$theBaseQtnArray = $QtnListArray[$theBaseID];
				$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

				if ($Row['qtnID2'] != '0') {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}

				break;

			case '15':
				if ($QtnListArray[$condOnID]['isSelect'] == 0) {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($RankListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}

				break;

			case '21':
				if ($QtnListArray[$condOnID]['isSelect'] == 0) {
					$theBaseID = $QtnListArray[$condOnID]['baseID'];
					$theBaseQtnArray = $QtnListArray[$theBaseID];
					$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

					if ($Row['qtnID2'] != '0') {
						$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID2']]['optionName']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
					else {
						$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
					}
				}

				break;

			case '26':
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($OptionListArray[$condOnID][$Row['qtnID2']]['optionName']) . ' - ' . qnospecialchar($LabelListArray[$condOnID][$Row['optionID2']]['optionLabel']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				break;

			case '28':
				$theBaseID = $QtnListArray[$condOnID]['baseID'];
				$theBaseQtnArray = $QtnListArray[$theBaseID];
				$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

				if ($Row['qtnID2'] != '0') {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$Row['qtnID2']]['optionName']) . ' - ' . qnospecialchar($AnswerListArray[$condOnID][$Row['optionID2']]['optionAnswer']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}
				else {
					$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($AnswerListArray[$condOnID][$Row['optionID2']]['optionAnswer']) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				}

				break;

			case '31':
				$theUnitText = explode('#', $QtnListArray[$condOnID]['unitText']);
				$defineList .= $theSpaceChr . qnospecialchar($QtnListArray[$condOnID]['questionName']) . ' - ' . qnospecialchar($theUnitText[$Row['qtnID2'] - 1]) . ' (' . $lang['question_type_' . $QtnListArray[$condOnID]['questionType']] . ')<br/>';
				break;
			}
		}

		$EnableQCoreClass->replace('defineList', $defineList);
	}

	$EnableQCoreClass->replace('orderNum', $orderNum);
	$orderNum++;
	$EnableQCoreClass->parse('list', 'LIST', true);
}

$ReportDefinePage = $EnableQCoreClass->parse('ReportDefinePage', 'ReportDefineFile');
echo $ReportDefinePage;

?>

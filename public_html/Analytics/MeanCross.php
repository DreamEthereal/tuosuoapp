<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'DataCrossSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$theSID = $Row['surveyID'];
	if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
	$dataSource = getdatasourcesql($_POST['dataSource'], $_POST['surveyID']);
	$dataSourceId = $_POST['dataSource'];

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	$theXArray = array();
	$theXFields = array();
	$k = 0;
	$theGetQuestionID1 = explode(',', $_POST['questionID1']);

	foreach ($theGetQuestionID1 as $theXQuestionIDText) {
		$theXQuestionIDArray = explode('*', $theXQuestionIDText);
		$theXQuestionID = $theXQuestionIDArray[0];
		$theOptionID = $theXQuestionIDArray[1];
		$theLabelID = $theXQuestionIDArray[2];

		switch ($QtnListArray[$theXQuestionID]['questionType']) {
		case '1':
			foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
				$theXArray[$k][] = qcrossqtnname($theQuestionArray['optionName']);
				$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  ' . $question_yesnoID . ' ';
			}

			break;

		case '2':
			foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
				$theXArray[$k][] = qcrossqtnname($theQuestionArray['optionName']);
				$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  ' . $question_radioID . ' ';
			}

			if (($QtnListArray[$theXQuestionID]['isSelect'] != '1') && ($QtnListArray[$theXQuestionID]['isHaveOther'] == '1')) {
				$theXArray[$k][] = qcrossqtnname($QtnListArray[$theXQuestionID]['otherText']);
				$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = 0 AND b.TextOtherValue_' . $theXQuestionID . ' != \'\'  ';
			}

			break;

		case '3':
			if ($theOptionID != 0) {
				if ($theOptionID == '99999') {
					$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qcrossqtnname($QtnListArray[$theXQuestionID]['allowType']));
					$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
					$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_no_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
				}
				else {
					$theXArray[$k][] = qcrossqtnname($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
					$theXArray[$k][] = qcrossqtnname($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
				}
			}
			else {
				$theXArray[$k][] = qcrossqtnname($QtnListArray[$theXQuestionID]['otherText']) . ' - ' . $lang['checkbox_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') AND b.TextOtherValue_' . $theXQuestionID . ' != \'\' ';
				$theXArray[$k][] = qcrossqtnname($QtnListArray[$theXQuestionID]['otherText']) . ' - ' . $lang['checkbox_no_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') =0 AND b.TextOtherValue_' . $theXQuestionID . ' = \'\' AND b.option_' . $theXQuestionID . ' != \'\' ';
			}

			break;

		case '6':
		case '19':
			foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
				$theXArray[$k][] = qcrossqtnname($theAnswerArray['optionAnswer']);
				$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . ' = ' . $question_range_answerID . ' ';
			}

			break;

		case '7':
			$theXArray[$k][] = qcrossqtnname($OptionListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . qcrossqtnname($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_checked'];
			$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') ';
			$theXArray[$k][] = qcrossqtnname($OptionListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . qcrossqtnname($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_no_checked'];
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
					$theXArray[$k][] = qcrossqtnname($theQuestionArray['optionName']);
					$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'' . $question_checkboxID . '\' ';
				}

				if ($theBaseQtnArray['isHaveOther'] == '1') {
					$theXArray[$k][] = qcrossqtnname($theBaseQtnArray['otherText']);
					$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'0\' ';
				}

				if ($QtnListArray[$theXQuestionID]['isCheckType'] == '1') {
					$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qcrossqtnname($QtnListArray[$theXQuestionID]['allowType']));
					$theXArray[$k][] = $negText;
					$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' = \'99999\' ';
				}
			}
			else if ($theOptionID != 0) {
				if ($theOptionID == '99999') {
					$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qcrossqtnname($QtnListArray[$theXQuestionID]['allowType']));
					$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
					$theXArray[$k][] = $negText . ' - ' . $lang['checkbox_no_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
				}
				else {
					$theXArray[$k][] = qcrossqtnname($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
					$theXArray[$k][] = qcrossqtnname($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
					$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
				}
			}
			else {
				$theXArray[$k][] = qcrossqtnname($theBaseQtnArray['otherText']) . ' - ' . $lang['checkbox_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') ';
				$theXArray[$k][] = qcrossqtnname($theBaseQtnArray['otherText']) . ' - ' . $lang['checkbox_no_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(0,b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
			}

			break;

		case '18':
			if ($QtnListArray[$theXQuestionID]['isSelect'] == 1) {
				$theXArray[$k][] = qcrossqtnname($YesNoListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
				$theXArray[$k][] = qcrossqtnname($YesNoListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\' ';
			}
			else {
				foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
					$theXArray[$k][] = qcrossqtnname($theQuestionArray['optionName']);
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
				$theXArray[$k][] = qcrossqtnname($theQuestionArray['optionName']);
				$theXFields[$k][] = ' b.option_' . $theXQuestionID . ' =  ' . $question_radioID . ' ';
			}

			break;

		case '25':
			$theXArray[$k][] = qcrossqtnname($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_checked'];
			$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') ';
			$theXArray[$k][] = qcrossqtnname($CheckBoxListArray[$theXQuestionID][$theOptionID]['optionName']) . ' - ' . $lang['checkbox_no_checked'];
			$theXFields[$k][] = ' FIND_IN_SET(' . $theOptionID . ',b.option_' . $theXQuestionID . ') =0 AND b.option_' . $theXQuestionID . ' != \'\'';
			break;

		case '26':
			foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
				$theXArray[$k][] = qcrossqtnname($theAnswerArray['optionAnswer']);
				$theXFields[$k][] = ' b.option_' . $theXQuestionID . '_' . $theOptionID . '_' . $theLabelID . ' = ' . $question_range_answerID . ' ';
			}

			break;

		case '28':
			$theBaseID = $QtnListArray[$theXQuestionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			if ($theOptionID == 0) {
				$theXArray[$k][] = qcrossqtnname($theBaseQtnArray['otherText']) . ' - ' . qcrossqtnname($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_0) ';
				$theXArray[$k][] = qcrossqtnname($theBaseQtnArray['otherText']) . ' - ' . qcrossqtnname($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_no_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_0) =0 AND b.option_' . $theXQuestionID . '_0 != \'\' ';
			}
			else {
				$theXArray[$k][] = qcrossqtnname($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . qcrossqtnname($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') ';
				$theXArray[$k][] = qcrossqtnname($theCheckBoxListArray[$theOptionID]['optionName']) . ' - ' . qcrossqtnname($AnswerListArray[$theXQuestionID][$theLabelID]['optionAnswer']) . ' - ' . $lang['checkbox_no_checked'];
				$theXFields[$k][] = ' FIND_IN_SET(' . $theLabelID . ',b.option_' . $theXQuestionID . '_' . $theOptionID . ') =0 AND b.option_' . $theXQuestionID . '_' . $theOptionID . ' != \'\' ';
			}

			break;
		}

		$k++;
	}

	$theXName = $theXCond = array();

	if (count($theXArray) == 2) {
		foreach ($theXArray[0] as $k => $thisFristName) {
			foreach ($theXArray[1] as $m => $thisSecName) {
				$theXName[] = $thisFristName . ' / ' . $thisSecName;
				$theXCond[] = $theXFields[0][$k] . ' AND ' . $theXFields[1][$m];
			}
		}
	}
	else {
		foreach ($theXArray[0] as $k => $thisFristName) {
			$theXName[] = $thisFristName;
			$theXCond[] = $theXFields[0][$k];
		}
	}

	$theDefineReportText = $_POST['surveyID'];
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
	$theGetQuestionID1 = explode(',', $_POST['questionID2']);

	foreach ($theGetQuestionID1 as $questionID) {
		$theSurveyID = $_POST['surveyID'];
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.mean.inc.php';
	}

	unset($theXArray);
	unset($theXFields);
	unset($theXName);
	unset($theXCond);
	$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $theDefineReportText, 'ShowOption' . $theDefineReportText . 'File');
	exit($DataCrossHTML);
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisURL = 'MeanCross.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->setTemplateFile('DataCrossFile', 'MeanCross.html');
$EnableQCoreClass->replace('crossType', 2);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$theSID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
$questionList1 = $questionList2 = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	switch ($theQtnArray['questionType']) {
	case '1':
	case '2':
	case '24':
		$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '13':
		$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '3':
		foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
			$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if ($theQtnArray['isNeg'] == '1') {
			$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
			$questionList1 .= '<option value=' . $questionID . '*99999>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '17':
		if ($QtnListArray[$questionID]['isSelect'] == 1) {
			$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}
		else {
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if ($theBaseQtnArray['isHaveOther'] == '1') {
				$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if ($theQtnArray['isCheckType'] == '1') {
				$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
				$questionList1 .= '<option value=' . $questionID . '*99999>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . $negText . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '18':
		if ($QtnListArray[$questionID]['isSelect'] == 1) {
			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_yesnoID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}
		else {
			$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '25':
		foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '6':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '7':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '10':
		foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '19':
	case '20':
		$theBaseID = $QtnListArray[$questionID]['baseID'];
		$theBaseQtnArray = $QtnListArray[$theBaseID];
		$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

		foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if ($theBaseQtnArray['isHaveOther'] == '1') {
			$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '15':
		if ($theQtnArray['isSelect'] == 0) {
			foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '21':
		if ($theQtnArray['isSelect'] == 0) {
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			if ($theBaseQtnArray['isHaveOther'] == '1') {
				$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '26':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_labelID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '28':
		$theBaseID = $QtnListArray[$questionID]['baseID'];
		$theBaseQtnArray = $QtnListArray[$theBaseID];
		$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

		foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		if ($theBaseQtnArray['isHaveOther'] == '1') {
			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$questionList1 .= '<option value=' . $questionID . '*0*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
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
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '17':
		if ($QtnListArray[$questionID]['isSelect'] == 1) {
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '18':
		if ($QtnListArray[$questionID]['isSelect'] != 1) {
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '4':
		if ($QtnListArray[$questionID]['isCheckType'] == '4') {
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
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
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
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
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;
	}
}

$EnableQCoreClass->replace('questionList1', $questionList1);
$EnableQCoreClass->replace('questionList2', $questionList2);
$DataCross = $EnableQCoreClass->parse('DataCross', 'DataCrossFile');
echo $DataCross;
exit();

?>

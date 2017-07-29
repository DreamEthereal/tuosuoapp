<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'ChiSquareCrossSubmit') {
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
	$theXFather = array();
	$theXSelf = array();
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
					$negText = ($QtnListArray[$theXQuestionID]['allowType'] == '' ? $lang['neg_text'] : qcrossqtnname($QtnListArray[$theXQuestionID]['allowType']));
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
		exit('<font color=red><b>自变量的组合为空，可能是选择的两个级联变量不是父子关系，尚不能进行交叉分析</b></font>');
	}

	$DataCrossHTML = '';
	$questionID = $_POST['questionID2'];
	$theSurveyID = $_POST['surveyID'];
	$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.xcross.inc.php';
	unset($theXArray);
	unset($theXFields);
	unset($theXName);
	unset($theXCond);
	unset($theXFather);
	unset($theXSelf);
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
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$thisURL = 'ChiSquareCross.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->setTemplateFile('ChiSquareCrossFile', 'ChiSquareCross.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('crossURL', $thisURL);
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
	case '13':
	case '24':
		$questionList1 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '3':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

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
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

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
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

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
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

		foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '6':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '7':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_answerID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theAnswerArray['optionAnswer']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '10':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

		foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
			$questionList1 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$questionList1 .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '19':
	case '20':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
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
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

			foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '21':
		if ($theQtnArray['isSelect'] == 0) {
			$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
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
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';

		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
				$questionList1 .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_labelID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '28':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
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

	case '31':
		$questionList2 .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
		$i = 1;

		for (; $i <= $QtnListArray[$questionID]['maxSize']; $i++) {
			$tmp = $i - 1;
			$questionList1 .= '<option value=' . $questionID . '*' . $i . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;
	}
}

$EnableQCoreClass->replace('questionList1', $questionList1);
$EnableQCoreClass->replace('questionList2', $questionList2);
$ChiSquareCross = $EnableQCoreClass->parse('ChiSquareCross', 'ChiSquareCrossFile');
echo $ChiSquareCross;
exit();

?>

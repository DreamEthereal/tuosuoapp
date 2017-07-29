<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$thisURL = 'ClusterAnalysis.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$_GET['surveyID'] = (int) $_GET['surveyID'];
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

if ($_POST['Action'] == 'VariableMathSubmit') {
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

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	$theXQuestionIDArray = explode(',', $_POST['questionID']);
	$theXFields = $theMissingXFields = $theXQuestionIDListArray = $theXQtnTypeList = array();
	$temp = 0;

	foreach ($theXQuestionIDArray as $theXQuestionIDList) {
		$theXQuestionIDItem = explode('*', $theXQuestionIDList);
		$theXQuestionID = $theXQuestionIDItem[0];
		$theXQuestionIDListArray[$temp] = $theXQuestionID;
		$theXOptionID = $theXQuestionIDItem[1];
		$theXLabelID = $theXQuestionIDItem[2];
		$theXQtnType = $QtnListArray[$theXQuestionID]['questionType'];
		$theXQtnTypeList[$temp] = $theXQtnType;

		switch ($theXQtnType) {
		case '1':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
			$isUnkown = array();

			foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_yesnoID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '2':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			if (($QtnListArray[$theXQuestionID]['isSelect'] != '1') && ($QtnListArray[$theXQuestionID]['isHaveOther'] == '1')) {
				if ($QtnListArray[$theXQuestionID]['isUnkown'] == 1) {
					$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
				}
				else {
					$theMissingXFieldsTemp = ' ( option_' . $theXQuestionID . ' != 0  OR (option_' . $theXQuestionID . ' = 0 AND TextOtherValue_' . $theXQuestionID . ' != \'\')) ';
				}
			}
			else {
				$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
			}

			$isUnkown = array();

			foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_radioID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '24':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
			$isUnkown = array();

			foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_radioID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '4':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFields[$temp] = ' option_' . $theXQuestionID . ' != \'\' ';
			break;

		case '18':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != \'\' ';
			$isUnkown = array();

			foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_yesnoID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '23':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'\' ';
			break;

		case '6':
		case '19':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
			$isUnkown = array();

			foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
				if ($theAnswerArray['isUnkown'] == 1) {
					$isUnkown[] = $question_range_answerID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . '_' . $theXOptionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '10':
		case '20':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
			break;

		case '16':
		case '22':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFields[$temp] = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'0.00\') ';
			break;

		case '15':
		case '21':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';

			switch ($QtnListArray[$theXQuestionID]['isSelect']) {
			case '0':
				$theMissingXFields[$temp] = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != 99) ';
				break;

			case '1':
				$theMissingXFields[$temp] = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'0.00\') ';
				break;

			case '2':
				$theMissingXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
				break;
			}

			break;

		case '26':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' != 0 ';
			$isUnkown = array();

			foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
				if ($theAnswerArray['isUnkown'] == 1) {
					$isUnkown[] = $question_range_answerID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;
		}

		$temp++;
	}

	$theXValue = array();
	$theXFieldsList = implode(',', $theXFields);
	$theMissingXFieldsList = implode(' AND ', $theMissingXFields);
	$SQL = ' SELECT responseID,administratorsName,' . $theXFieldsList . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theMissingXFieldsList . ' and ' . $dataSource;
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$label = 0;
		$o = 0;
		$p = $Row['responseID'];
		$theXValue[$o][$p] = trim($Row['administratorsName']) == '' ? $Row['responseID'] : trim($Row['administratorsName']) . '(' . $Row['responseID'] . ')';
		$o++;

		foreach ($theXQuestionIDListArray as $theXQuestionID) {
			$theXFieldItem = trim($theXFields[$label]);

			switch ($theXQtnTypeList[$label]) {
			case '2':
				if ($Row[$theXFieldItem] == 0) {
					$theXValue[$o][$p] = $QtnListArray[$theXQuestionID]['otherCode'];
				}
				else {
					$theXValue[$o][$p] = $RadioListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				}

				break;

			case '24':
				$theXValue[$o][$p] = $RadioListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;

			case '18':
			case '1':
				$theXValue[$o][$p] = $YesNoListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;

			case '6':
			case '19':
				$theXValue[$o][$p] = $AnswerListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;

			case '4':
			case '10':
			case '16':
			case '20':
			case '22':
			case '15':
			case '21':
			case '23':
				$theXValue[$o][$p] = $Row[$theXFieldItem];
				break;

			case '26':
				$theXValue[$o][$p] = $AnswerListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;
			}

			$label++;
			$o++;
		}
	}

	unset($theXFields);
	unset($theMissingXFields);
	unset($theXQuestionIDListArray);
	include_once ROOT_PATH . 'Math/MathCluster.php';
	$obj = new MathCluster();
	$obj->dataLoad($theXValue);
	$cateNum = (int) $_POST['cateNum'];
	$meanResult = $obj->kMean($cateNum);
	$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'ClusterKMeanResult.html');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');

	foreach ($meanResult as $panelName => $clusterId) {
		$EnableQCoreClass->replace('panelName', $panelName);
		$EnableQCoreClass->replace('clusterId', $clusterId + 1);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	$VariableResultHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
	unset($theXValue);
	exit($VariableResultHTML);
}

$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$theSID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$EnableQCoreClass->setTemplateFile('VariableMathFile', 'ClusterAnalysis.html');
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	switch ($theQtnArray['questionType']) {
	case '1':
	case '2':
	case '24':
		$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '4':
		if ($QtnListArray[$questionID]['isCheckType'] == '4') {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '23':
		foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
			if ($theQuestionArray['isCheckType'] == '4') {
				$questionList .= '<option value=' . $questionID . '*' . $question_yesnoID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '6':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '10':
	case '15':
	case '16':
		foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '18':
		if ($QtnListArray[$questionID]['isSelect'] != 1) {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '19':
	case '20':
	case '21':
	case '22':
		$theBaseID = $QtnListArray[$questionID]['baseID'];
		$theBaseQtnArray = $QtnListArray[$theBaseID];
		$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

		foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if ($theBaseQtnArray['isHaveOther'] == '1') {
			$questionList .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '26':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
				$questionList .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_labelID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$VariableMathHTML = $EnableQCoreClass->parse('VariableMath', 'VariableMathFile');
echo $VariableMathHTML;
exit();

?>

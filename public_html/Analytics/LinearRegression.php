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
$thisURL = 'LinearRegression.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('linearRegressionURL', $thisURL);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

if ($_POST['Action'] == 'LinearRegressionSubmit') {
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

	$theXQuestionIDArray = explode('*', $_POST['questionID1']);
	$theXQuestionID = $theXQuestionIDArray[0];
	$theXOptionID = $theXQuestionIDArray[1];
	$theXLabelID = $theXQuestionIDArray[2];
	$theXQtnType = $QtnListArray[$theXQuestionID]['questionType'];

	switch ($theXQtnType) {
	case '2':
		$theXFields = 'option_' . $theXQuestionID . ' as theXFields ';
		if (($QtnListArray[$theXQuestionID]['isSelect'] != '1') && ($QtnListArray[$theXQuestionID]['isHaveOther'] == '1')) {
			if ($QtnListArray[$theXQuestionID]['isUnkown'] == 1) {
				$theMissingXFields = ' option_' . $theXQuestionID . ' != 0 ';
			}
			else {
				$theMissingXFields = ' ( option_' . $theXQuestionID . ' != 0  OR (option_' . $theXQuestionID . ' = 0 AND TextOtherValue_' . $theXQuestionID . ' != \'\')) ';
			}
		}
		else {
			$theMissingXFields = ' option_' . $theXQuestionID . ' != 0 ';
		}

		$isUnkown = array();

		foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_radioID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingXFields .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '24':
		$theXFields = 'option_' . $theXQuestionID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . ' != 0 ';
		$isUnkown = array();

		foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_radioID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingXFields .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '4':
		$theXFields = 'option_' . $theXQuestionID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . ' != \'\' ';
		break;

	case '18':
		$theXFields = 'option_' . $theXQuestionID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . ' != \'\' ';
		$isUnkown = array();

		foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_yesnoID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingXFields .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '23':
		$theXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'\' ';
		break;

	case '6':
	case '19':
		$theXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
		$isUnkown = array();

		foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
			if ($theAnswerArray['isUnkown'] == 1) {
				$isUnkown[] = $question_range_answerID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingXFields .= ' and option_' . $theXQuestionID . '_' . $theXOptionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '10':
	case '20':
		$theXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
		break;

	case '16':
	case '22':
		$theXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' as theXFields ';
		$theMissingXFields = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'0.00\' )';
		break;

	case '15':
	case '21':
		$theXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' as theXFields ';

		switch ($QtnListArray[$theXQuestionID]['isSelect']) {
		case '0':
			$theMissingXFields = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != 99) ';
			break;

		case '1':
			$theMissingXFields = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'0.00\') ';
			break;

		case '2':
			$theMissingXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
			break;
		}

		break;

	case '26':
		$theXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' as theXFields ';
		$theMissingXFields = ' option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' != 0 ';
		$isUnkown = array();

		foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
			if ($theAnswerArray['isUnkown'] == 1) {
				$isUnkown[] = $question_range_answerID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingXFields .= ' and option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;
	}

	$theYQuestionIDArray = explode('*', $_POST['questionID2']);
	$theYQuestionID = $theYQuestionIDArray[0];
	$theYOptionID = $theYQuestionIDArray[1];
	$theYLabelID = $theYQuestionIDArray[2];
	$theYQtnType = $QtnListArray[$theYQuestionID]['questionType'];

	switch ($theYQtnType) {
	case '2':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		if (($QtnListArray[$theYQuestionID]['isSelect'] != '1') && ($QtnListArray[$theYQuestionID]['isHaveOther'] == '1')) {
			if ($QtnListArray[$theYQuestionID]['isUnkown'] == 1) {
				$theMissingYFields = ' option_' . $theYQuestionID . ' != 0 ';
			}
			else {
				$theMissingYFields = ' ( option_' . $theYQuestionID . ' != 0  OR (option_' . $theYQuestionID . ' = 0 AND TextOtherValue_' . $theYQuestionID . ' != \'\')) ';
			}
		}
		else {
			$theMissingYFields = ' option_' . $theYQuestionID . ' != 0 ';
		}

		$isUnkown = array();

		foreach ($RadioListArray[$theYQuestionID] as $question_radioID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_radioID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '24':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . ' != 0 ';
		$isUnkown = array();

		foreach ($RadioListArray[$theYQuestionID] as $question_radioID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_radioID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '4':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . ' != \'\' ';
		break;

	case '18':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . ' != \'\' ';
		$isUnkown = array();

		foreach ($YesNoListArray[$theYQuestionID] as $question_yesnoID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_yesnoID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '23':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != \'\' ';
		break;

	case '6':
	case '19':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 ';
		$isUnkown = array();

		foreach ($AnswerListArray[$theYQuestionID] as $question_range_answerID => $theAnswerArray) {
			if ($theAnswerArray['isUnkown'] == 1) {
				$isUnkown[] = $question_range_answerID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . '_' . $theYOptionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '10':
	case '20':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 ';
		break;

	case '16':
	case '22':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' (option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 AND option_' . $theYQuestionID . '_' . $theYOptionID . ' != \'0.00\') ';
		break;

	case '15':
	case '21':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';

		switch ($QtnListArray[$theYQuestionID]['isSelect']) {
		case '0':
			$theMissingYFields = ' ( option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 AND option_' . $theYQuestionID . '_' . $theYOptionID . ' != 99) ';
			break;

		case '1':
			$theMissingYFields = ' ( option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 AND option_' . $theYQuestionID . '_' . $theYOptionID . ' != \'0.00\') ';
			break;

		case '2':
			$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 ';
			break;
		}

		break;

	case '26':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . '_' . $theYLabelID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . '_' . $theYLabelID . ' != 0 ';
		$isUnkown = array();

		foreach ($AnswerListArray[$theYQuestionID] as $question_range_answerID => $theAnswerArray) {
			if ($theAnswerArray['isUnkown'] == 1) {
				$isUnkown[] = $question_range_answerID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . '_' . $theYOptionID . '_' . $theYLabelID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;
	}

	$theXValue = array();
	$theYValue = array();
	$SQL = ' SELECT ' . $theXFields . ',' . $theYFields . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theMissingXFields . ' AND ' . $theMissingYFields . ' and ' . $dataSource;
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		switch ($theXQtnType) {
		case '2':
			if ($Row['theXFields'] == 0) {
				$theXValue[] = $QtnListArray[$theXQuestionID]['otherCode'];
			}
			else {
				$theXValue[] = $RadioListArray[$theXQuestionID][$Row['theXFields']]['itemCode'];
			}

			break;

		case '24':
			$theXValue[] = $RadioListArray[$theXQuestionID][$Row['theXFields']]['itemCode'];
			break;

		case '18':
			$theXValue[] = $YesNoListArray[$theXQuestionID][$Row['theXFields']]['itemCode'];
			break;

		case '6':
		case '19':
			$theXValue[] = $AnswerListArray[$theXQuestionID][$Row['theXFields']]['itemCode'];
			break;

		case '4':
		case '10':
		case '16':
		case '20':
		case '22':
		case '15':
		case '21':
		case '23':
			$theXValue[] = $Row['theXFields'];
			break;

		case '26':
			$theXValue[] = $AnswerListArray[$theXQuestionID][$Row['theXFields']]['itemCode'];
			break;
		}

		switch ($theYQtnType) {
		case '2':
			if ($Row['theYFields'] == 0) {
				$theYValue[] = $QtnListArray[$theYQuestionID]['otherCode'];
			}
			else {
				$theYValue[] = $RadioListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			}

			break;

		case '24':
			$theYValue[] = $RadioListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;

		case '18':
			$theYValue[] = $YesNoListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;

		case '6':
		case '19':
			$theYValue[] = $AnswerListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;

		case '4':
		case '10':
		case '16':
		case '20':
		case '22':
		case '15':
		case '21':
		case '23':
			$theYValue[] = $Row['theYFields'];
			break;

		case '26':
			$theYValue[] = $AnswerListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;
		}
	}

	include_once ROOT_PATH . 'PDL/LinearRegressionHTML.php';
	$slr = new LinearRegressionHTML($theXValue, $theYValue, $_POST['confidence']);
	$slr->showAnalysisOfVariance();
	$slr->showParameterEstimates('X', 'Y');
	$slr->showFormula('X', 'Y');
	$slr->showRValues('X', 'Y');
	$slr->showTableSummary('X', 'Y');
	unset($theXValue);
	unset($theYValue);
	exit();
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
$EnableQCoreClass->setTemplateFile('LinearRegressionFile', 'LinearRegression.html');
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	switch ($theQtnArray['questionType']) {
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

$EnableQCoreClass->replace('questionList1', $questionList);
$EnableQCoreClass->replace('questionList2', $questionList);
$LinearRegressionHTML = $EnableQCoreClass->parse('LinearRegression', 'LinearRegressionFile');
echo $LinearRegressionHTML;
exit();

?>

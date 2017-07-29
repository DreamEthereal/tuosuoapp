<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'EGGSubmit') {
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

	$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'GGExtendedAnalytics.html');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$EnableQCoreClass->set_CycBlock('LIST', 'ANSWER', 'answer');
	$EnableQCoreClass->replace('answer', '');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'ANSWER0', 'answer0');
	$EnableQCoreClass->replace('answer0', '');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'ANSWER1', 'answer1');
	$EnableQCoreClass->replace('answer1', '');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'PRICE', 'price');
	$EnableQCoreClass->replace('price', '');
	$questionID = $_POST['questionID'];
	$trueBrand = $_POST['trueBrand'];
	$thisOptionResponseNum = 0;
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$thePriceArray = array();
	$tmp = 0;
	$_SESSION['titlename'] = $_SESSION['pricevalue'] = $_SESSION['thedatavalue'] = array();

	foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
		$EnableQCoreClass->replace('thePrice', qnospecialchar($theQuestionArray['optionName']));
		$_SESSION['pricevalue'][] = $theQuestionArray['optionName'];
		$thePriceArray[$tmp] = qnospecialchar($theQuestionArray['optionName']);
		$tmp++;
		$EnableQCoreClass->parse('price', 'PRICE', true);
		$OptionCountSQL = ' SELECT COUNT(*) AS rep_answerNum FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE b.option_' . $questionID . '_' . $question_range_optionID . ' !=0 and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$thisOptionResponseNum = $OptionCountRow['rep_answerNum'];
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.option_' . $questionID . '_' . $question_range_optionID . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_range_optionID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[$question_range_optionID][] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$question_range_optionID][$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}
	}

	$theIncome = $theElasticity = array();

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('brand', qnospecialchar($theAnswerArray['optionAnswer']));
		$_SESSION['titlename'][$question_range_answerID] = $theAnswerArray['optionAnswer'];
		$tmp = 0;

		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			if (in_array($question_range_answerID, $allResponseOptionID[$question_range_optionID])) {
				$thePriceRate = countpercent($allOptionResponseNum[$question_range_optionID][$question_range_answerID], $thisOptionResponseNum);
				$EnableQCoreClass->replace('thePriceRate', $thePriceRate);
			}
			else {
				$thePriceRate = 0;
				$EnableQCoreClass->replace('thePriceRate', $thePriceRate);
			}

			$_SESSION['thedatavalue'][$question_range_answerID][] = $thePriceRate;
			$EnableQCoreClass->parse('answer', 'ANSWER', true);

			if ($trueBrand == $question_range_answerID) {
				$theElasticity[$tmp] = $thePriceRate;
				$tmp++;
				$theIncome[$question_range_optionID] = $theQuestionArray['optionName'] * $thePriceRate;
			}
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
		$EnableQCoreClass->unreplace('answer');
	}

	$EnableQCoreClass->replace('optionName', qnospecialchar($AnswerListArray[$questionID][$trueBrand]['optionAnswer']));

	foreach ($theIncome as $question_range_optionID => $theIncomeValue) {
		$EnableQCoreClass->replace('theIncomeValue', @round($theIncomeValue / 100, 2));
		$EnableQCoreClass->parse('answer1', 'ANSWER1', true);
	}

	$k = 0;

	foreach ($theElasticity as $thePriceRate) {
		if ($k == count($theElasticity) - 1) {
			$EnableQCoreClass->replace('theElasticity', ' - ');
		}
		else {
			$m = $k + 1;

			if ($thePriceRate == 0) {
				$thisElasticity = 0;
			}
			else {
				$thisElasticityMolecular = (($theElasticity[$m] / 100) - ($thePriceRate / 100)) / $thePriceRate / 100;
				$thisElasticityDenominator = ($thePriceArray[$m] - $thePriceArray[$k]) / $thePriceArray[$k];
				$thisElasticity = @round($thisElasticityMolecular / $thisElasticityDenominator, 3);
			}

			$EnableQCoreClass->replace('theElasticity', $thisElasticity);
		}

		$k++;
		$EnableQCoreClass->parse('answer0', 'ANSWER0', true);
	}

	unset($theElasticity);
	unset($theIncome);
	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	unset($thePriceArray);
	$EnableQCoreClass->replace('chartWidth', $_POST['chartWidth']);
	$EnableQCoreClass->replace('chartHeight', $_POST['chartHeight']);
	$EnableQCoreClass->replace('legendY', $_POST['chartHeight'] - 20);
	$EnableQCoreClass->replace('xLineNum', count($AnswerListArray[$questionID]));
	$EnableQCoreClass->replace('surveyID', $_POST['surveyID']);
	$EGGHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
	exit($EGGHTML);
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisURL = 'EGG.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->setTemplateFile('EGGFile', 'GGExtended.html');
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
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	switch ($theQtnArray['questionType']) {
	case '6':
		$isNumber = true;

		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			if (!is_numeric($theQuestionArray['optionName'])) {
				$isNumber = false;
				break;
			}
		}

		if ($isNumber == true) {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$EGG = $EnableQCoreClass->parse('EGG', 'EGGFile');
echo $EGG;
exit();

?>

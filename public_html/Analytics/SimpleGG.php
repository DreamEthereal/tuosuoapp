<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'SGGSubmit') {
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

	$thePriceRate = array();
	$theVarTotalNum = $_POST['dataRows'];
	$_SESSION['pricevalue'] = array();
	$tmp = 1;

	for (; $tmp <= $theVarTotalNum; $tmp++) {
		$_SESSION['pricevalue'][$tmp] = $_POST['priceValue' . $tmp];
		$theQuestionArray = explode('*', $_POST['questionID' . $tmp]);
		$questionID = $theQuestionArray[0];
		$optionID = $theQuestionArray[1];
		$surveyID = $_POST['surveyID'];
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.sgg.count.php';
	}

	$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'GGSimpleAnalytics.html');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$k = 0;

	foreach ($thePriceRate as $tmp => $thePriceRateValue) {
		$m = $tmp + 1;
		$EnableQCoreClass->replace('priceValue', $_POST['priceValue' . $tmp]);
		$EnableQCoreClass->replace('thePriceRate', $thePriceRateValue);
		$EnableQCoreClass->replace('theIncomeValue', @round($thePriceRateValue * $_POST['priceValue' . $tmp], 2));

		if ($k == count($thePriceRate) - 1) {
			$EnableQCoreClass->replace('theElasticity', ' - ');
		}
		else {
			$thisElasticityMolecular = (($thePriceRate[$m] / 100) - ($thePriceRateValue / 100)) / $thePriceRateValue / 100;
			$thisElasticityDenominator = ($_POST['priceValue' . $m] - $_POST['priceValue' . $tmp]) / $_POST['priceValue' . $tmp];
			$thisElasticity = @round($thisElasticityMolecular / $thisElasticityDenominator, 3);
			$EnableQCoreClass->replace('theElasticity', $thisElasticity);
		}

		$k++;
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	$EnableQCoreClass->replace('chartWidth', $_POST['chartWidth']);
	$EnableQCoreClass->replace('chartHeight', $_POST['chartHeight']);
	$EnableQCoreClass->replace('legendY', $_POST['chartHeight'] - 20);
	$EnableQCoreClass->replace('xLineNum', count($_SESSION['pricevalue']));
	$EnableQCoreClass->replace('surveyID', $_POST['surveyID']);
	$_SESSION['thedatavalue'] = $thePriceRate;
	unset($thePriceRate);
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
$EnableQCoreClass->setTemplateFile('EGGFile', 'GGSimple.html');
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
$EnableQCoreClass->replace('optionOrderID', '1');
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	switch ($theQtnArray['questionType']) {
	case '1':
	case '2':
	case '24':
		$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '6':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$EGG = $EnableQCoreClass->parse('EGG', 'EGGFile');
echo $EGG;
exit();

?>

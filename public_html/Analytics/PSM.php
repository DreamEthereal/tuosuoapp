<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'PSMSubmit') {
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

	$theQuestionIDArray = explode('*', $_POST['questionID1']);

	if (count($theQuestionIDArray) == 2) {
		$theCheap = 'option_' . $theQuestionIDArray[0] . '_' . $theQuestionIDArray[1];
	}
	else {
		$theCheap = 'option_' . $theQuestionIDArray[0];
	}

	$SQL = ' SELECT ' . $theCheap . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theCheap . ' != \'\' and ' . $theCheap . ' != \'0\' and ' . $dataSource . ' ORDER BY ' . $theCheap . ' ASC ';
	$Result = $DB->query($SQL);
	$theCheapNumArray = array();
	$cheapPriceArray = array();

	while ($Row = $DB->queryArray($Result)) {
		$theCheapValue = $Row[$theCheap];

		if (array_key_exists($theCheapValue, $theCheapNumArray)) {
			$theCheapNumArray[$theCheapValue] = $theCheapNumArray[$theCheapValue] + 1;
		}
		else {
			$cheapPriceArray[] = $theCheapValue;
			$theCheapNumArray[$theCheapValue] = 1;
		}
	}

	$totalCheapRepNum = array_sum($theCheapNumArray);
	$theQuestionIDArray = explode('*', $_POST['questionID2']);

	if (count($theQuestionIDArray) == 2) {
		$theExpensive = 'option_' . $theQuestionIDArray[0] . '_' . $theQuestionIDArray[1];
	}
	else {
		$theExpensive = 'option_' . $theQuestionIDArray[0];
	}

	$SQL = ' SELECT ' . $theExpensive . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theExpensive . ' != \'\' and ' . $theExpensive . ' != \'0\' and ' . $dataSource . ' ORDER BY ' . $theExpensive . ' ASC ';
	$Result = $DB->query($SQL);
	$theExpensiveNumArray = array();
	$expensivePriceArray = array();

	while ($Row = $DB->queryArray($Result)) {
		$theExpensiveValue = $Row[$theExpensive];

		if (array_key_exists($theExpensiveValue, $theExpensiveNumArray)) {
			$theExpensiveNumArray[$theExpensiveValue] = $theExpensiveNumArray[$theExpensiveValue] + 1;
		}
		else {
			$expensivePriceArray[] = $theExpensiveValue;
			$theExpensiveNumArray[$theExpensiveValue] = 1;
		}
	}

	$totalExpensiveRepNum = array_sum($theExpensiveNumArray);
	$theQuestionIDArray = explode('*', $_POST['questionID3']);

	if (count($theQuestionIDArray) == 2) {
		$theTooExpensive = 'option_' . $theQuestionIDArray[0] . '_' . $theQuestionIDArray[1];
	}
	else {
		$theTooExpensive = 'option_' . $theQuestionIDArray[0];
	}

	$SQL = ' SELECT ' . $theTooExpensive . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theTooExpensive . ' != \'\' and ' . $theTooExpensive . ' != \'0\' and ' . $dataSource . ' ORDER BY ' . $theTooExpensive . ' ASC ';
	$Result = $DB->query($SQL);
	$theTooExpensiveNumArray = array();
	$tooExpensivePriceArray = array();

	while ($Row = $DB->queryArray($Result)) {
		$theTooExpensiveValue = $Row[$theTooExpensive];

		if (array_key_exists($theTooExpensiveValue, $theTooExpensiveNumArray)) {
			$theTooExpensiveNumArray[$theTooExpensiveValue] = $theTooExpensiveNumArray[$theTooExpensiveValue] + 1;
		}
		else {
			$tooExpensivePriceArray[] = $theTooExpensiveValue;
			$theTooExpensiveNumArray[$theTooExpensiveValue] = 1;
		}
	}

	$totalTooExpensiveRepNum = array_sum($theTooExpensiveNumArray);
	$theQuestionIDArray = explode('*', $_POST['questionID4']);

	if (count($theQuestionIDArray) == 2) {
		$theTooCheap = 'option_' . $theQuestionIDArray[0] . '_' . $theQuestionIDArray[1];
	}
	else {
		$theTooCheap = 'option_' . $theQuestionIDArray[0];
	}

	$SQL = ' SELECT ' . $theTooCheap . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theTooCheap . ' != \'\' and ' . $theTooCheap . ' != \'0\' and ' . $dataSource . ' ORDER BY ' . $theTooCheap . ' ASC ';
	$Result = $DB->query($SQL);
	$theTooCheapNumArray = array();
	$tooCheapPriceArray = array();

	while ($Row = $DB->queryArray($Result)) {
		$theTooCheapValue = $Row[$theTooCheap];

		if (array_key_exists($theTooCheapValue, $theTooCheapNumArray)) {
			$theTooCheapNumArray[$theTooCheapValue] = $theTooCheapNumArray[$theTooCheapValue] + 1;
		}
		else {
			$tooCheapPriceArray[] = $theTooCheapValue;
			$theTooCheapNumArray[$theTooCheapValue] = 1;
		}
	}

	$totalTooCheapRepNum = array_sum($theTooCheapNumArray);
	$thePriceArray = array_unique(array_merge($cheapPriceArray, $expensivePriceArray, $tooExpensivePriceArray, $tooCheapPriceArray));
	$theExpensivePriceArray = $thePriceArray;
	$theCheapPriceArray = $thePriceArray;
	$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'PSMAnalytics.html');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$tmp = 0;
	$heapCheapNumArray = $heapExpensiveNumArray = $heapTooExpensiveNumArray = $heapTooCheapNumArray = array();
	$theAcceptable = $theRetention = $theUnacceptable = array();
	$theCheapArray = $theExpensiveArray = $theTooCheapArray = $theTooExpensiveArray = array();
	$theExistPrice = array();
	sort($theExpensivePriceArray);

	foreach ($theExpensivePriceArray as $thisPrice) {
		$theExistPrice[$tmp] = $thisPrice;

		if ($tmp == 0) {
			$heapExpensiveNumArray[$tmp] = array_key_exists($thisPrice, $theExpensiveNumArray) ? $theExpensiveNumArray[$thisPrice] : 0;
			$theExpensiveArray[$thisPrice] = @round(($theExpensiveNumArray[$thisPrice] * 100) / $totalExpensiveRepNum, 2);
			$heapTooExpensiveNumArray[$tmp] = array_key_exists($thisPrice, $theTooExpensiveNumArray) ? $theTooExpensiveNumArray[$thisPrice] : 0;
			$theTooExpensiveArray[$thisPrice] = @round(($theTooExpensiveNumArray[$thisPrice] * 100) / $totalTooExpensiveRepNum, 2);
		}
		else {
			if (array_key_exists($thisPrice, $theExpensiveNumArray)) {
				$heapExpensiveNumArray[$tmp] = $heapExpensiveNumArray[$tmp - 1] + $theExpensiveNumArray[$thisPrice];
				$theExpensiveArray[$thisPrice] = @round(($heapExpensiveNumArray[$tmp] * 100) / $totalExpensiveRepNum, 2);
			}
			else {
				$heapExpensiveNumArray[$tmp] = $heapExpensiveNumArray[$tmp - 1];
				$theExpensiveArray[$thisPrice] = $theExpensiveArray[$theExistPrice[$tmp - 1]];
			}

			if (array_key_exists($thisPrice, $theTooExpensiveNumArray)) {
				$heapTooExpensiveNumArray[$tmp] = $heapTooExpensiveNumArray[$tmp - 1] + $theTooExpensiveNumArray[$thisPrice];
				$theTooExpensiveArray[$thisPrice] = @round(($heapTooExpensiveNumArray[$tmp] * 100) / $totalTooExpensiveRepNum, 2);
			}
			else {
				$heapTooExpensiveNumArray[$tmp] = $heapTooExpensiveNumArray[$tmp - 1];
				$theTooExpensiveArray[$thisPrice] = $theTooExpensiveArray[$theExistPrice[$tmp - 1]];
			}
		}

		$tmp++;
	}

	$theExistPrice = array();
	rsort($theCheapPriceArray);
	$tmp = 0;

	foreach ($theCheapPriceArray as $thisPrice) {
		$theExistPrice[$tmp] = $thisPrice;

		if ($tmp == 0) {
			$heapCheapNumArray[$tmp] = array_key_exists($thisPrice, $theCheapNumArray) ? $theCheapNumArray[$thisPrice] : 0;
			$theCheapArray[$thisPrice] = @round(($theCheapNumArray[$thisPrice] * 100) / $totalCheapRepNum, 2);
			$heapTooCheapNumArray[$tmp] = $theTooCheapNumArray[$thisPrice];
			$theTooCheapArray[$thisPrice] = @round(($theTooCheapNumArray[$thisPrice] * 100) / $totalTooCheapRepNum, 2);
		}
		else {
			if (array_key_exists($thisPrice, $theCheapNumArray)) {
				$heapCheapNumArray[$tmp] = $heapCheapNumArray[$tmp - 1] + $theCheapNumArray[$thisPrice];
				$theCheapArray[$thisPrice] = @round(($heapCheapNumArray[$tmp] * 100) / $totalCheapRepNum, 2);
			}
			else {
				$heapCheapNumArray[$tmp] = $heapCheapNumArray[$tmp - 1];
				$theCheapArray[$thisPrice] = $theCheapArray[$theExistPrice[$tmp - 1]];
			}

			if (array_key_exists($thisPrice, $theTooCheapNumArray)) {
				$heapTooCheapNumArray[$tmp] = $heapTooCheapNumArray[$tmp - 1] + $theTooCheapNumArray[$thisPrice];
				$theTooCheapArray[$thisPrice] = @round(($heapTooCheapNumArray[$tmp] * 100) / $totalTooCheapRepNum, 2);
			}
			else {
				$heapTooCheapNumArray[$tmp] = $heapTooCheapNumArray[$tmp - 1];
				$theTooCheapArray[$thisPrice] = $theTooCheapArray[$theExistPrice[$tmp - 1]];
			}
		}

		$tmp++;
	}

	sort($thePriceArray);

	foreach ($thePriceArray as $thisPrice) {
		$EnableQCoreClass->replace('thisPrice', $thisPrice);
		$EnableQCoreClass->replace('theCheapRate', $theCheapArray[$thisPrice]);
		$EnableQCoreClass->replace('theExpensiveRate', $theExpensiveArray[$thisPrice]);
		$EnableQCoreClass->replace('theTooExpensiveRate', $theTooExpensiveArray[$thisPrice]);
		$EnableQCoreClass->replace('theTooCheapRate', $theTooCheapArray[$thisPrice]);
		$theUnacceptable[$thisPrice] = $theTooCheapArray[$thisPrice] + $theTooExpensiveArray[$thisPrice];
		$theAcceptable[$thisPrice] = 100 - $theCheapArray[$thisPrice] - $theExpensiveArray[$thisPrice];
		$theRetention[$thisPrice] = 100 - $theAcceptable[$thisPrice] - $theUnacceptable[$thisPrice];
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	unset($cheapPriceArray);
	unset($expensivePriceArray);
	unset($tooExpensivePriceArray);
	unset($tooCheapPriceArray);
	unset($theCheapNumArray);
	unset($theTooCheapNumArray);
	unset($theExpensiveNumArray);
	unset($theTooExpensiveNumArray);
	unset($heapCheapNumArray);
	unset($heapExpensiveNumArray);
	unset($heapTooExpensiveNumArray);
	unset($heapTooCheapNumArray);
	unset($theExistPrice);
	$_SESSION['seriesvalue'] = $thePriceArray;
	rsort($theCheapArray);
	$_SESSION['theCheap'] = $theCheapArray;
	$_SESSION['theExpensive'] = $theExpensiveArray;
	rsort($theTooCheapArray);
	$_SESSION['theTooCheap'] = $theTooCheapArray;
	$_SESSION['theTooExpensive'] = $theTooExpensiveArray;
	$_SESSION['seriesvalue0'] = $thePriceArray;
	$_SESSION['theAcceptable'] = $theAcceptable;
	$_SESSION['theUnacceptable'] = $theUnacceptable;
	$_SESSION['theRetention'] = $theRetention;
	$EnableQCoreClass->replace('chartWidth', $_POST['chartWidth']);
	$EnableQCoreClass->replace('chartHeight', $_POST['chartHeight']);
	$EnableQCoreClass->replace('legendY', $_POST['chartHeight'] - 20);
	$EnableQCoreClass->replace('xLineNum', 40);
	$EnableQCoreClass->replace('surveyID', $_POST['surveyID']);
	$PSMHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
	unset($thePriceArray);
	unset($theCheapArray);
	unset($theExpensiveArray);
	unset($theTooCheapArray);
	unset($theTooCheapArray);
	unset($theAcceptable);
	unset($theRetention);
	unset($theUnacceptable);
	exit($PSMHTML);
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisURL = 'PSM.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->setTemplateFile('PSMFile', 'PSM.html');
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
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$PSM = $EnableQCoreClass->parse('PSM', 'PSMFile');
echo $PSM;
exit();

?>

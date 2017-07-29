<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'ChiSquareSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$theSID = $Row['surveyID'];
	if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
	$questionID = $_POST['questionID'];
	$theSurveyID = $_POST['surveyID'];
	$dataSource = getdatasourcesql($_POST['dataSource'], $_POST['surveyID']);
	$dataSourceId = $_POST['dataSource'];

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	define('PHP_MATH', ROOT_PATH . 'PDL/');
	require_once PHP_MATH . 'ChiSquareHTML.php';
	$Alpha = 0.05;

	switch ($QtnListArray[$questionID]['questionType']) {
	case '1':
	case '2':
	case '13':
	case '24':
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.chi.inc.php';
		$Chi = new ChiSquareHTML($ObsFreq, $Alpha);
		$Chi->showTableSummary($TitleName, $Headings);
		$Chi->showChiSquareStats();
		break;

	case '17':
	case '18':
		$theRadioType = false;
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.chi.inc.php';

		if ($theRadioType == true) {
			$Chi = new ChiSquareHTML($ObsFreq, $Alpha);
			$Chi->showTableSummary($TitleName, $Headings);
			$Chi->showChiSquareStats();
		}
		else {
			foreach ($theTitleName as $label => $TitleName) {
				$Chi = new ChiSquareHTML($ObsFreq[$label], $Alpha);
				$Chi->showTableSummary($TitleName, $Headings[$label]);
				$Chi->showChiSquareStats();
			}
		}

		break;

	case '3':
	case '25':
	case '6':
	case '10':
	case '15':
	case '19':
	case '20':
	case '21':
	case '31':
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.chi.inc.php';

		foreach ($theTitleName as $label => $TitleName) {
			$Chi = new ChiSquareHTML($ObsFreq[$label], $Alpha);
			$Chi->showTableSummary($TitleName, $Headings[$label]);
			$Chi->showChiSquareStats();
		}

		break;

	case '7':
	case '26':
	case '28':
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.chi.inc.php';

		foreach ($theTitleName as $label1 => $TitleNameArray) {
			foreach ($TitleNameArray as $label2 => $TitleName) {
				$Chi = new ChiSquareHTML($ObsFreq[$label1][$label2], $Alpha);
				$Chi->showTableSummary($TitleName, $Headings[$label1][$label2]);
				$Chi->showChiSquareStats();
			}
		}

		break;
	}

	exit();
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$EnableQCoreClass->setTemplateFile('ChiSquareFile', 'ChiSquare.html');
$thisURL = 'ChiSquare.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('chiSquareURL', $thisURL);
$theSurveyID = ($_GET['surveyID'] == '' ? 0 : (int) $_GET['surveyID']);
$EnableQCoreClass->replace('surveyID', $theSurveyID);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) ORDER BY surveyID DESC ';
	break;

case '2':
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND administratorsID= \'' . $_SESSION['administratorsID'] . '\' ORDER BY surveyID DESC ';
	break;

case '3':
case '7':
	$AuthSQL = ' SELECT surveyID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
	$AuthResult = $DB->query($AuthSQL);
	$SurveyArray = array();

	while ($AuthRow = $DB->queryArray($AuthResult)) {
		$SurveyArray[] = $AuthRow['surveyID'];
	}

	if (!empty($SurveyArray)) {
		$SurveyList = implode(',', $SurveyArray);
		$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND surveyID IN (' . $SurveyList . ') ORDER BY surveyID DESC ';
	}
	else {
		$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
	}

	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND administratorsID IN (' . $UserIDList . ')';
	break;

default:
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ORDER BY surveyID DESC ';
	break;
}

$Result = $DB->query($SQL);
$surveyIDList = '';

while ($SuRow = $DB->queryArray($Result)) {
	if ($SuRow['surveyID'] == $_GET['surveyID']) {
		$surveyIDList .= '<option value="' . $SuRow['surveyID'] . '" selected>' . $SuRow['surveyTitle'] . '</option>';
	}
	else {
		$surveyIDList .= '<option value="' . $SuRow['surveyID'] . '">' . $SuRow['surveyTitle'] . '</option>';
	}
}

$EnableQCoreClass->replace('surveyIDList', $surveyIDList);
$EnableQCoreClass->replace('m_questionID', '');
$EnableQCoreClass->replace('m_questionName', $lang['pls_select']);
$ChiSquare = $EnableQCoreClass->parse('ChiSquare', 'ChiSquareFile');
echo $ChiSquare;
exit();

?>

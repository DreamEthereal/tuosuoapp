<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$theSID = $theSurveyID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
if (isset($_GET['dataSourceId']) && ($_GET['dataSourceId'] != '')) {
	$dataSource = getdatasourcesql($_GET['dataSourceId'], $_GET['surveyID']);
	$dataSourceId = $_GET['dataSourceId'];
}
else {
	$dataSource = getdatasourcesql(0, $_GET['surveyID']);
	$dataSourceId = 0;
}

$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart');
$EnableQCoreClass->setTemplateFile('AmPieSettingFile', 'AmPieSetting.xml');

if ($_GET['chartType'] == 2) {
	$EnableQCoreClass->replace('xvalue', '190');
	$EnableQCoreClass->replace('inner_radiu', '');
	$EnableQCoreClass->replace('height', '20');
	$EnableQCoreClass->replace('angle', '40');
	$EnableQCoreClass->replace('brightness_step', '');
	$EnableQCoreClass->replace('radius', '');
	$EnableQCoreClass->replace('gradient', 'linear');
	$EnableQCoreClass->replace('gradient_ratio', '-50,0,0,-50');
}
else {
	$EnableQCoreClass->replace('xvalue', '200');
	$EnableQCoreClass->replace('inner_radiu', '30');
	$EnableQCoreClass->replace('height', '');
	$EnableQCoreClass->replace('angle', '');
	$EnableQCoreClass->replace('brightness_step', '10');
	$EnableQCoreClass->replace('radius', '90');
	$EnableQCoreClass->replace('gradient', 'radial');
	$EnableQCoreClass->replace('gradient_ratio', '0,0,-50,0,0,0,-50');
}

$questionID = $_GET['questionID'];
$questionName = qshowchartqtnname($QtnListArray[$questionID]['questionName']);

switch ($QtnListArray[$questionID]['questionType']) {
case '6':
	$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($OptionListArray[$questionID][$_GET['optionID']]['optionName']);
	break;

case '15':
	$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($RankListArray[$questionID][$_GET['optionID']]['optionName']);
	break;

case '10':
	if ($_GET['optionID'] != 0) {
		$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($RankListArray[$questionID][$_GET['optionID']]['optionName']);
	}
	else {
		$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($QtnListArray[$questionID]['otherText']);
	}

	break;

case '19':
case '20':
case '21':
	$theBaseID = $QtnListArray[$questionID]['baseID'];
	$theBaseQtnArray = $QtnListArray[$theBaseID];
	$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

	if ($_GET['optionID'] != 0) {
		$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($theCheckBoxListArray[$_GET['optionID']]['optionName']);
	}
	else {
		$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($theBaseQtnArray['otherText']);
	}

	break;

case '26':
	$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($OptionListArray[$questionID][$_GET['optionID']]['optionName']) . ' - ' . qshowchartqtnname($LabelListArray[$questionID][$_GET['labelID']]['optionLabel']);
	break;

case '31':
	$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
	$thePieTitleName = $questionName . ' - ' . qshowchartqtnname($theUnitText[$_GET['optionID'] - 1]);
	break;
}

$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', $thePieTitleName));
$questionID = $_GET['questionID'];
$optionID = $_GET['optionID'];
$labelID = $_GET['labelID'];
$Headings = $ObsFreq = array();

switch ($QtnListArray[$questionID]['questionType']) {
case '6':
case '10':
case '15':
case '19':
case '20':
case '26':
case '21':
case '31':
	$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.pie.inc.php';
	break;
}

$totalValue = array_sum($ObsFreq);
$EnableQCoreClass->replace('totalValue', $totalValue);
$AmPieSetting = $EnableQCoreClass->parse('AmPieSetting', 'AmPieSettingFile');
echo $AmPieSetting;
exit();

?>

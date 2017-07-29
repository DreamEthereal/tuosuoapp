<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $_GET['questionID'] . '\' LIMIT 1 ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart');
$EnableQCoreClass->setTemplateFile('AmPieSettingFile', 'AmPieSetting.xml');

if ($_GET['chartType'] == 2) {
	$EnableQCoreClass->replace('xvalue', '190');
	$EnableQCoreClass->replace('inner_radiu', '');
	$EnableQCoreClass->replace('height', '20');
	$EnableQCoreClass->replace('angle', '20');
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

$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', qshowchartqtnname($Row['questionName'])));
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

$questionID = $_GET['questionID'];
$Headings = $ObsFreq = array();

switch ($QtnListArray[$questionID]['questionType']) {
case '1':
case '2':
case '13':
case '17':
case '18':
case '24':
	$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.chart.inc.php';
	break;
}

$totalValue = array_sum($ObsFreq);
$EnableQCoreClass->replace('totalValue', $totalValue);
$AmPieSetting = $EnableQCoreClass->parse('AmPieSetting', 'AmPieSettingFile');
echo $AmPieSetting;
exit();

?>

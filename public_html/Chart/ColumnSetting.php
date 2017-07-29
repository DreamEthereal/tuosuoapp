<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$_GET['questionID'] = (int) $_GET['questionID'];
$SQL = ' SELECT questionName,questionType,unitText FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $_GET['questionID'] . '\' LIMIT 1 ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart');
$EnableQCoreClass->setTemplateFile('AmColumnSettingFile', 'AmColumnSetting.xml');

if ($_GET['chartType'] == 3) {
	$EnableQCoreClass->replace('lineType', 'bar');
	$EnableQCoreClass->replace('lineTypeLeft', 100);
	$EnableQCoreClass->replace('lineTypeRight', 50);
	$EnableQCoreClass->replace('lineTypeBottom', 40);
}
else {
	$EnableQCoreClass->replace('lineType', 'column');
	$EnableQCoreClass->replace('lineTypeLeft', 70);
	$EnableQCoreClass->replace('lineTypeRight', 50);
	$EnableQCoreClass->replace('lineTypeBottom', '');
}

if ($Row['questionType'] == '31') {
	$theUnitText = explode('#', $Row['unitText']);
	$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', qshowchartqtnname($Row['questionName']) . ' - ' . qshowchartqtnname($theUnitText[$_GET['optionID'] - 1])));
}
else {
	$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', qshowchartqtnname($Row['questionName'])));
}

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

$questionID = (int) $_GET['questionID'];
$optionID = (int) $_GET['optionID'];
$Headings = $ObsFreq = array();
$totalValue = 0;

switch ($QtnListArray[$questionID]['questionType']) {
case '1':
case '2':
case '3':
case '13':
case '17':
case '18':
case '24':
case '25':
case '31':
	$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.chart.inc.php';
	break;
}

$EnableQCoreClass->replace('totalValue', $totalValue);
$AmColumnSetting = $EnableQCoreClass->parse('AmColumnSetting', 'AmColumnSettingFile');
echo $AmColumnSetting;
exit();

?>

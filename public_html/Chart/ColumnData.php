<?php
//dezend by http://www.yunlu99.com/
function percent($optionResponseNum, $totalResponseNum)
{
	if ($totalResponseNum != 0) {
		$_obf_Tg4KRli4JlEMa7GuzA__ = @round((100 / $totalResponseNum) * $optionResponseNum, 2);
	}
	else {
		$_obf_Tg4KRli4JlEMa7GuzA__ = 0;
	}

	return $_obf_Tg4KRli4JlEMa7GuzA__;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
@set_time_limit(0);
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

$ColorJoin = array('DA3600', '0F84D4', 'F9A308', '62D038', 'FE670F', '2C9232', '7F0B80', 'DFDE29', '9F9F9F', 'EDEDED', 'BAE700', 'FFFFCC', 'FFCC33', 'FFA144', 'CCFF99', 'CCCC99', 'FFCCCC', 'FF9999', 'FF7300', '66FF99', '669999', '86B47A', 'CC99CC', 'CC9966', '33CCFF', '079ED7', 'EEEEEE', 'FF0F00', 'FF6600', 'FF9E01', 'FCD202', 'F8FF01', 'B0DE09', '04D215', '0D8ECF', '0D52D1', '2A0CD0');
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart/');
$EnableQCoreClass->setTemplateFile('AmColumnDataFile', 'AmColumnData.xml');
$EnableQCoreClass->set_CycBlock('AmColumnDataFile', 'LABEL', 'label');
$EnableQCoreClass->replace('label', '');
$EnableQCoreClass->set_CycBlock('AmColumnDataFile', 'DATA', 'data');
$EnableQCoreClass->replace('data', '');
$temp = 0;

foreach ($Headings as $labelName) {
	$EnableQCoreClass->replace('xid', $temp);
	$EnableQCoreClass->replace('labelName', iconv('gbk', 'UTF-8', $labelName));
	$EnableQCoreClass->parse('label', 'LABEL', true);
	$EnableQCoreClass->replace('gid', $temp);
	$EnableQCoreClass->replace('value', percent($ObsFreq[$temp], $totalValue));

	if ($ColorJoin[$temp] != '') {
		$EnableQCoreClass->replace('color', $ColorJoin[$temp]);
	}
	else {
		$key = array_rand($ColorJoin);
		$EnableQCoreClass->replace('color', $ColorJoin[$key]);
	}

	$EnableQCoreClass->parse('data', 'DATA', true);
	$temp++;
}

$AmColumnData = $EnableQCoreClass->parse('AmColumnData', 'AmColumnDataFile');
unset($Headings);
unset($ObsFreq);
echo $AmColumnData;
exit();

?>

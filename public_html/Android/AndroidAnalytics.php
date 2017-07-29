<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
_checkroletype('1|5|6');
$EnableQCoreClass->setTemplateFile('AndroidAnalyticsFile', 'AndroidAnalytics.html');

switch ($_SESSION['adminRoleType']) {
case '1':
case '6':
	$EnableQCoreClass->replace('isExport', '');
	break;

case '5':
	$EnableQCoreClass->replace('isExport', 'none');
	break;
}

$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . ANDROID_INFO_TABLE . ' WHERE deviceId != \'\' ';
$Row = $DB->queryFirstRow($SQL);
$totalResponseNum = $Row['totalResponseNum'];

$EnableQCoreClass->replace('totalResponseNum', $Row['totalResponseNum']);
$EnableQCoreClass->set_CycBlock('AndroidAnalyticsFile', 'LIST0', 'list0');
$EnableQCoreClass->replace('list0', '');
$SQL = ' SELECT surveyID,count(*) AS list0Num FROM ' . ANDROID_INFO_TABLE . ' WHERE surveyID !=0 AND deviceId != \'\' GROUP BY surveyID ORDER BY list0Num DESC LIMIT 20 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$HSQL = ' SELECT surveyTitle,surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $Row['surveyID'] . '\' ';
	$HRow = $DB->queryFirstRow($HSQL);
	$EnableQCoreClass->replace('surveyTitle', $HRow['surveyTitle'] . '(' . $HRow['surveyName'] . ')');
	$EnableQCoreClass->replace('list0Num', $Row['list0Num']);
	$list0Rate = countpercent($Row['list0Num'], $totalResponseNum);
	$EnableQCoreClass->replace('list0Rate', $list0Rate);
	$EnableQCoreClass->parse('list0', 'LIST0', true);
}

$EnableQCoreClass->set_CycBlock('AndroidAnalyticsFile', 'LIST1', 'list1');
$EnableQCoreClass->replace('list1', '');
$SQL = ' SELECT brand,count(*) AS list1Num FROM ' . ANDROID_INFO_TABLE . ' WHERE brand !=\'\' AND deviceId != \'\' GROUP BY brand ORDER BY list1Num DESC LIMIT 5 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('brand', $Row['brand']);
	$EnableQCoreClass->replace('list1Num', $Row['list1Num']);
	$list1Rate = countpercent($Row['list1Num'], $totalResponseNum);
	$EnableQCoreClass->replace('list1Rate', $list1Rate);
	$EnableQCoreClass->parse('list1', 'LIST1', true);
}

$EnableQCoreClass->set_CycBlock('AndroidAnalyticsFile', 'LIST2', 'list2');
$EnableQCoreClass->replace('list2', '');
$SQL = ' SELECT model,count(*) AS list2Num FROM ' . ANDROID_INFO_TABLE . ' WHERE model !=\'\' AND deviceId != \'\' GROUP BY model ORDER BY list2Num DESC LIMIT 10 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('model', $Row['model']);
	$EnableQCoreClass->replace('list2Num', $Row['list2Num']);
	$list2Rate = countpercent($Row['list2Num'], $totalResponseNum);
	$EnableQCoreClass->replace('list2Rate', $list2Rate);
	$EnableQCoreClass->parse('list2', 'LIST2', true);
}

$EnableQCoreClass->set_CycBlock('AndroidAnalyticsFile', 'LIST3', 'list3');
$EnableQCoreClass->replace('list3', '');
$SQL = ' SELECT simOperatorName,count(*) AS list3Num FROM ' . ANDROID_INFO_TABLE . ' WHERE simOperatorName !=\'\' AND deviceId != \'\' GROUP BY simOperatorName ORDER BY list3Num DESC LIMIT 5 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('simOperatorName', $Row['simOperatorName']);
	$EnableQCoreClass->replace('list3Num', $Row['list3Num']);
	$list3Rate = countpercent($Row['list3Num'], $totalResponseNum);
	$EnableQCoreClass->replace('list3Rate', $list3Rate);
	$EnableQCoreClass->parse('list3', 'LIST3', true);
}

$EnableQCoreClass->parse('AndroidAnalytics', 'AndroidAnalyticsFile');
$EnableQCoreClass->output('AndroidAnalytics');

?>

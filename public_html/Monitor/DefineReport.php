<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.monitor.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyID,status,administratorsID,surveyName,isCache,projectType FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'DefineReport.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
@set_time_limit(0);

if ($License['isMonitor'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uMonitorDefineReport.html');

if ($Sur_G_Row['projectType'] == 1) {
	$EnableQCoreClass->replace('actionURL', 'TaskResult.php');
	$EnableQCoreClass->replace('actionName', '任务报告');
}
else {
	$EnableQCoreClass->replace('actionURL', 'QuotaResult.php');
	$EnableQCoreClass->replace('actionName', '配额进度');
}

$dataSourceList = '';
if (!isset($_SESSION['dataSource' . $_GET['surveyID']]) || ($_SESSION['dataSource' . $_GET['surveyID']] == 0)) {
	$dataSourceList .= '<option value=\'0\' selected>完成和导入数据</option>';
}
else {
	$dataSourceList .= '<option value=\'0\'>完成和导入数据</option>';
}

$SQL = ' SELECT * FROM ' . QUERY_LIST_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) ORDER BY queryID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if ($Row['defineShare'] == 0) {
		$dataSourceName = qnohtmltag($Row['queryName'], 1) . $lang['report_private'];
	}
	else {
		$dataSourceName = qnohtmltag($Row['queryName'], 1);
	}

	if (isset($_SESSION['dataSource' . $_GET['surveyID']]) && ($_SESSION['dataSource' . $_GET['surveyID']] == $Row['queryID'])) {
		$dataSourceList .= '<option value=\'' . $Row['queryID'] . '\' selected>' . $dataSourceName . '</option>';
	}
	else {
		$dataSourceList .= '<option value=\'' . $Row['queryID'] . '\'>' . $dataSourceName . '</option>';
	}
}

$EnableQCoreClass->replace('dataSourceList', $dataSourceList);
$theDefineIDValue = '';
$SQL = ' SELECT * FROM ' . REPORTDEFINE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND defineType IN (1,2) AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) )  ORDER BY defineID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theDefineIDValue .= $Row['defineID'] . ',';
}

$EnableQCoreClass->replace('theDefineIDValue', substr($theDefineIDValue, 0, -1));
$QuestionList = $EnableQCoreClass->parse('QuestionList', 'SurveyListFile');
echo $QuestionList;
exit();

?>

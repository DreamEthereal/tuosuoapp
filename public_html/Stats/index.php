<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
require_once ROOT_PATH . 'Stats/Date.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,administratorsID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->setTemplateFile('IndexFile', 'StatsIndex.html');
$SQL = ' SELECT * FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$StartDate = $Row['StartDate'];
$StartDate_unix = explode('-', $StartDate);
$StartDate_unix = mktime(0, 0, 0, $StartDate_unix[1], $StartDate_unix[2], $StartDate_unix[0]);
$date = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

if ($StartDate != '0') {
	$days = datediff('d', $StartDate_unix, $date) + 1;
}
else {
	$day = 0;
}

if (1 <= $days) {
	$AveDayNum = intval($Row['TotalNum'] / $days);
}

$DayNum = $Row['DayNum'];
$intending_today = intval(($DayNum * 24 * 60) / ((date('G') * 60) + date('i')));
$EnableQCoreClass->replace('average_days', $AveDayNum);
$EnableQCoreClass->replace('days_num', $days);
$EnableQCoreClass->replace('begin_time', $StartDate);
$EnableQCoreClass->replace('visit_num', $Row['TotalNum']);
$EnableQCoreClass->replace('today_num', $Row['DayNum']);
$EnableQCoreClass->replace('intending_today', $intending_today);
$EnableQCoreClass->replace('tiptop_month', $Row['MonthMaxNum']);
$EnableQCoreClass->replace('max_month', $Row['MonthMaxDate']);
$EnableQCoreClass->replace('tiptop_day', $Row['DayMaxNum']);
$EnableQCoreClass->replace('max_day', $Row['DayMaxDate']);
$EnableQCoreClass->replace('tiptop_hour', $Row['HourMaxNum']);
$EnableQCoreClass->replace('max_hour', $Row['HourMaxTime']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('URL', 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']));
$EnableQCoreClass->pparse('Index', 'IndexFile');

?>

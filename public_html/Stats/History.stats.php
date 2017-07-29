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

$EnableQCoreClass->setTemplateFile('HistoryFile', 'StatsHistory.html');
$SQL = ' SELECT TotalNum FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 0,1 ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('TotalNum', $Row['TotalNum']);
$i = 2007;

for (; $i <= 2030; $i++) {
	if ($i == date('Y')) {
		$years .= '<option value=' . $i . ' selected>' . $i . '</option>' . "\n" . '';
	}
	else {
		$years .= '<option value=' . $i . '>' . $i . '</option>' . "\n" . '';
	}
}

$i = 1;

for (; $i <= 12; $i++) {
	if ($i == date('n')) {
		$months .= '<option value=' . $i . ' selected>' . $i . '</option>' . "\n" . '';
	}
	else {
		$months .= '<option value=' . $i . '>' . $i . '</option>' . "\n" . '';
	}
}

$year29 = date('Y') % 4;

switch (date('n')) {
case 2:
	if ($year29 == 0) {
		$monthdays = 29;
	}
	else {
		$monthdays = 28;
	}

	break;

case 4:
	$monthdays = 30;
	break;

case 6:
	$monthdays = 30;
	break;

case 9:
	$monthdays = 30;
	break;

case 11:
	$monthdays = 30;
	break;

default:
	$monthdays = 31;
}

$i = 1;

for (; $i <= $monthdays; $i++) {
	if ($i == date('j')) {
		$days .= '<option value=' . $i . ' selected>' . $i . '</option>' . "\n" . '';
	}
	else {
		$days .= '<option value=' . $i . '>' . $i . '</option>' . "\n" . '';
	}
}

$EnableQCoreClass->replace('yearsList', $years);
$EnableQCoreClass->replace('monthsList', $months);
$EnableQCoreClass->replace('daysList', $days);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('URL', 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']));
$EnableQCoreClass->pparse('History', 'HistoryFile');

?>

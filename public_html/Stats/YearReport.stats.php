<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,administratorsID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->setTemplateFile('StatYearFile', 'StatsYearReport.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('URL', 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']));
if (!isset($_POST['qyear']) || ($_POST['qyear'] == '')) {
	$EnableQCoreClass->replace('today', date('Y'));
}
else {
	$EnableQCoreClass->replace('today', $_POST['qyear']);
}

$EnableQCoreClass->pparse('StatYear', 'StatYearFile');

?>

<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';

if ($_GET['qname'] == '') {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}
else {
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1 ';
	$S_Row = $DB->queryFirstRow($SQL);

	if (!$S_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

$_GET['type'] = (int) $_GET['type'];

if ($_GET['type'] == 1) {
	if ($S_Row['status'] != '1') {
		_shownotes($lang['status_error'], $lang['no_edit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}
}
else if ($S_Row['status'] != '0') {
	_shownotes($lang['status_error'], $lang['no_design_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

_checkpassport('1|2|5', $S_Row['surveyID']);

if ($_GET['type'] == 1) {
	$EnableQCoreClass->setTemplateFile('ControlSurveyFile', 'SurveyModi.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ControlSurveyFile', 'SurveyControl.html');
}

$EnableQCoreClass->replace('siteTitle', $S_Row['surveyTitle'] . ' - ' . $Config['siteName']);
$EnableQCoreClass->replace('surveyName', $S_Row['surveyName']);
$EnableQCoreClass->replace('qlang', $S_Row['lang']);
$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
$EnableQCoreClass->replace('surveyURLTitle', urlencode($S_Row['surveyTitle']));
$EnableQCoreClass->replace('preFirstQtnId', $_GET['preFirstQtnId'] != '' ? (int) $_GET['preFirstQtnId'] : '');
$StyleArray = array('Standard', 'Coffee', 'PurplePink', 'BrownBlue', 'RedPink', 'RedGray', 'PurpleYellow', 'BlueBrown', 'BlueGray', 'BlueGrayII', 'BlueGreen', 'GreenPink', 'Spring', 'Summer', 'UserDefine');

foreach ($StyleArray as $style) {
	if ($S_Row['theme'] == $style) {
		$EnableQCoreClass->replace('theme_' . $style, '¡ñ');
	}
	else {
		$EnableQCoreClass->replace('theme_' . $style, '');
	}
}

$nickName = ($_SESSION['administratorsNickName'] == '' ? $_SESSION['administratorsName'] : $_SESSION['administratorsNickName']);
$EnableQCoreClass->replace('nickUserName', $nickName);
$EnableQCoreClass->parse('ControlSurvey', 'ControlSurveyFile');
$EnableQCoreClass->output('ControlSurvey');

?>

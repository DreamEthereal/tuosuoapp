<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$thisProg = 'ShowOptWeight.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$SQL = ' SELECT surveyTitle,status,surveyID,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($_POST['Action'] == 'SettingCoeffSubmit') {
	require_once 'ShowOptWeight.inc.php';
	optweightdatasubmit();
	$theSID = $_GET['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
	writetolog($lang['setting_option_coeff'] . ':' . $Sur_G_Row['surveyTitle']);
	_showsucceed($lang['setting_option_coeff'] . ':' . $Sur_G_Row['surveyTitle'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('SettingConFile', 'SurveyOptWeight.html');
$EnableQCoreClass->set_CycBlock('SettingConFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');

if ($Sur_G_Row['status'] != '0') {
	$EnableQCoreClass->setTemplateFile('SurveyActionFile', 'DeployActionList.html');
	$EnableQCoreClass->replace('navActionList', $EnableQCoreClass->parse('SurveyActionPage', 'SurveyActionFile'));
	if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
}
else {
	$EnableQCoreClass->setTemplateFile('SurveyActionFile', 'DesignActionList.html');
	$tmp = 1;

	for (; $tmp <= 4; $tmp++) {
		$EnableQCoreClass->replace('cur_' . $tmp, '');
	}

	$EnableQCoreClass->replace('cur_3', ' class="cur"');
	$EnableQCoreClass->replace('navActionList', $EnableQCoreClass->parse('SurveyActionPage', 'SurveyActionFile'));
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
}

$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyID', $Sur_G_Row['surveyID']);
if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
	$EnableQCoreClass->replace('isViewUserAction', 'disabled');
}
else {
	$EnableQCoreClass->replace('isViewUserAction', '');
}

require 'ShowOptWeight.inc.php';
$SettingCon = $EnableQCoreClass->parse('SettingCon', 'SettingConFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -24);
$SettingCon = str_replace($All_Path, '', $SettingCon);
$SettingCon = str_replace('PerUserData', '../PerUserData', $SettingCon);
echo $SettingCon;
exit();

?>

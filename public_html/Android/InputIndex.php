<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.entry.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(4);
$thisProg = 'InputIndex.php';

if ($License['isOnline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uAndroidInputList.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$EnableQCoreClass->replace('siteTitle', $Config['siteName']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);
$pushURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22) . 'Android/Push.php?type=1';
$EnableQCoreClass->replace('pushURL', $pushURL);
$SQL = ' SELECT a.surveyID,a.surveyName,a.surveyTitle,a.beginTime,a.surveyInfo,a.surveySubTitle,a.lang,a.projectType FROM ' . SURVEY_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.surveyID = b.surveyID AND a.status =1 AND a.beginTime <= \'' . date('Y-m-d') . '\' AND b.administratorsID =\'' . $_SESSION['administratorsID'] . '\' ORDER BY a.surveyID DESC ';
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

while ($Row = $DB->queryArray($Result)) {
	$actionTitle = '';

	if ($Row['projectType'] == 1) {
		$surveyURL = 'ShowInputerTask.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$actionTitle .= '<a href=\'' . $surveyURL . '\'>任务列表</a>';
		$modiURL = 'ShowInputData.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$actionTitle .= '&nbsp;&nbsp;<a href=\'' . $modiURL . '\'>数据列表</a>';
	}
	else {
		$surveyURL = 'InputSurveyAnswer.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$actionTitle .= '<a href=\'' . $surveyURL . '\'>录入数据</a>';
		$modiURL = 'ShowInputData.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$actionTitle .= '&nbsp;&nbsp;<a href=\'' . $modiURL . '\'>数据列表</a>';
	}

	$EnableQCoreClass->replace('actionTitle', $actionTitle);
	$EnableQCoreClass->replace('surveyURL', $surveyURL);
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$HSQL = ' SELECT COUNT(*) as replyNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' WHERE area =\'' . $_SESSION['administratorsName'] . '\' ';
	$HRow = $DB->queryFirstRow($HSQL);
	$EnableQCoreClass->replace('replyNum', $HRow['replyNum']);
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->parse('list', 'LIST', true);
}

$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;
exit();

?>

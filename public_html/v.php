<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

@set_time_limit(0);
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.cons.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'License/License.xml';

if ($_GET['surveyID'] == '') {
	if ($_GET['qname'] == '') {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
	else {
		$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,surveyName,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' ';
		$S_Row = $DB->queryFirstRow($SQL);

		if (!$S_Row) {
			_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
		}
	}
}
else {
	$_GET['surveyID'] = (int) $_GET['surveyID'];
	$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,surveyName,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);
}

if ($S_Row['status'] == '0') {
	_shownotes($lang['status_error'], $lang['design_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

if ($S_Row['isViewResult'] != '1') {
	_shownotes($lang['auth_error'], $lang['no_view_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

$EnableQCoreClass->replace('theme', $S_Row['theme']);
$_GET['surveyID'] = $S_Row['surveyID'];
$_GET['surveyTitle'] = $S_Row['surveyTitle'];
$thisProg = 'v.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . $S_Row['surveyTitle'];
if (($S_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php';
$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
$surveyTplFile = 'uResult.html';

if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
	$surveyTplFile = 'uResultSystem.html';
}

$EnableQCoreClass->setTemplateFile('QuestionListFile', $surveyTplFile);
$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$isViewResult = 1;
$dataSource = getdatasourcesql(0, $S_Row['surveyID']);
$dataSourceId = 0;
$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($SQL);
$totalResponseNum = $CountRow['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (!in_array($theQtnArray['questionType'], array(4, 5, 11, 12, 14, 23, 27, 29, 30))) {
		$surveyID = $S_Row['surveyID'];
		$ModuleName = $Module[$theQtnArray['questionType']];

		if ($theQtnArray['questionType'] != 9) {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
		}
		else {
			require ROOT_PATH . 'PlugIn/Info/Info.php';
		}

		$EnableQCoreClass->parse('question', 'QUESTION', true);
	}
}

$EnableQCoreClass->replace('isComb', 0);
$EnableQCoreClass->replace('pageID', 1);
$EnableQCoreClass->replace('dataSourceId', $dataSourceId);

if ($License['isModiLogo'] != 1) {
	$EnableQCoreClass->replace('footer', '<tr><td align=right style="font-size: 12px;font-family: Calibri;color:#333;padding-bottom:10px;padding-right:20px;">Powered by <a href="http://www.enableq.com" target=_blank><font color=blue><b>EnableQ</b>&#8482;</font></a></td></tr>');
}
else {
	$EnableQCoreClass->replace('footer', '');
}

$CommonResult = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
$ResultPage = _gettplfile($S_Row['panelID'], $S_Row['surveyTitle'], $CommonResult);
$ResultPage = str_replace('ShowUserDefine.php', 'Analytics/ShowUserDefine.php', $ResultPage);
echo $ResultPage;
exit();

?>

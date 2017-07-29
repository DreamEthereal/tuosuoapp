<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'License/License.xml';

if ($_GET['qname'] == '') {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}

$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' LIMIT 0,1 ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash_code']) != md5(trim($SerialRow['license']))) {
	_shownotes($lang['system_error'], 'EnableQ Security Violation', 'EnableQ Security Violation');
}

$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyName,surveyInfo,isViewResult,theme,panelID FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1 ';
$S_Row = $DB->queryFirstRow($SQL);
$_GET['surveyID'] = $S_Row['surveyID'];
$_GET['surveyTitle'] = $S_Row['surveyTitle'];

if ($_GET['isPrint'] == 1) {
	$EnableQCoreClass->setTemplateFile('QuestionListFile', 'uResult.html');
	$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$isViewResult = 1;
	$EnableQCoreClass->replace('theme', 'Print');
}
else {
	$surveyTplFile = 'uResult.html';

	if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
		$surveyTplFile = 'uResultSystem.html';
	}

	$EnableQCoreClass->setTemplateFile('QuestionListFile', $surveyTplFile);
	$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$isViewResult = 1;
	$EnableQCoreClass->replace('theme', $S_Row['theme']);
}

$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
if (($S_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php';
if (isset($_GET['dataSourceId']) && ($_GET['dataSourceId'] != '')) {
	$dataSource = getdatasourcesql($_GET['dataSourceId'], $S_Row['surveyID'], $_GET['roleType']);
	$dataSourceId = $_GET['dataSourceId'];
}
else {
	$dataSource = getdatasourcesql(0, $S_Row['surveyID'], $_GET['roleType']);
	$dataSourceId = 0;
}

$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($SQL);
$totalResponseNum = $CountRow['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if ($theQtnArray['questionType'] != '12') {
		if ($_GET['printType'] == 'all') {
			$surveyID = $S_Row['surveyID'];
			$ModuleName = $Module[$theQtnArray['questionType']];

			if ($theQtnArray['questionType'] != 9) {
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
			}
			else {
				require ROOT_PATH . 'PlugIn/Info/Admin/Info.page.inc.php';
			}

			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
		else if (trim($_GET['printType']) != '') {
			$theExportQtnList = explode(',', trim($_GET['printType']));

			if (in_array($questionID, $theExportQtnList)) {
				$surveyID = $S_Row['surveyID'];
				$ModuleName = $Module[$theQtnArray['questionType']];

				if ($theQtnArray['questionType'] != 9) {
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
				}
				else {
					require ROOT_PATH . 'PlugIn/Info/Admin/Info.page.inc.php';
				}

				$EnableQCoreClass->parse('question', 'QUESTION', true);
			}
		}
	}
}

$EnableQCoreClass->replace('isComb', 0);
$EnableQCoreClass->replace('dataSourceId', $dataSourceId);

if ($License['isModiLogo'] != 1) {
	$EnableQCoreClass->replace('footer', '<table><tr><td align=right style="font-size: 12px;font-family: Calibri;color:#333;padding-bottom:10px;padding-right:20px;">Powered by <a href="http://www.enableq.com" target=_blank><font color=blue><b>EnableQ</b>&#8482;</font></a></td></tr></table>');
}
else {
	$EnableQCoreClass->replace('footer', '');
}

$Result = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');

if ($_GET['isPrint'] == 1) {
	$ResultPage = $Result;
}
else {
	$ResultPage = _gettplfile($S_Row['panelID'], $S_Row['surveyTitle'], $Result);
}

$ResultPage = str_replace('ShowUserDefine.php', 'Analytics/ShowUserDefine.php', $ResultPage);
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
$ResultPage = str_replace($All_Path, '', $ResultPage);
$ResultPage = str_replace('CSS/', $All_Path . 'CSS/', $ResultPage);
$ResultPage = str_replace('JS/', $All_Path . 'JS/', $ResultPage);
$ResultPage = str_replace('../Images/', $All_Path . 'Images/', $ResultPage);
$ResultPage = str_replace('PerUserData/', $All_Path . 'PerUserData/', $ResultPage);
$ResultPage = str_replace('System/', $All_Path . 'System/', $ResultPage);
$ResultPage = str_replace('Analytics/', $All_Path . 'Analytics/', $ResultPage);

if ($_GET['isPrint'] == 1) {
	$ResultPage = preg_replace('\'<tr[^>]*?style="display:none"[^>]*?>\\s*?<td[^>]*?>\\s*?<table[^>]*?.*?\\/table>.*?\\/tr>\'si', '', $ResultPage);
	$ResultPage = preg_replace('\'<tr[^>]*?style="display:none"[^>]*?>.*?\\/tr>\'si', '', $ResultPage);
	$SizeArray = array();

	if (preg_match_all('\'<img[^>]*?height=10[^>]*?src="[^>]*?Images/bar.gif"[^>]*?width=".?[^>]*?"[^>]*?>\'si', $ResultPage, $Matches, PREG_SET_ORDER)) {
		foreach ($Matches as $MatchValue) {
			if (preg_match_all('\'width=".*%.*"\'si', $MatchValue[0], $SizeMatchs, PREG_SET_ORDER)) {
				$SizeNum = explode('=', $SizeMatchs[0][0]);
				$SizeArray[] = str_replace('%', '', str_replace('"', '', $SizeNum[1]));
			}
		}
	}

	foreach ($SizeArray as $textSize) {
		$preg = '/bar.gif"[^>]*?width="' . $textSize . '%"/si';
		$result_replace = 'bar.gif" width="' . round($textSize * 2) . '"';
		$ResultPage = preg_replace($preg, $result_replace, $ResultPage);
	}
}

echo $ResultPage;
exit();

?>

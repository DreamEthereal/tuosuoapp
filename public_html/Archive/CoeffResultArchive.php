<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_GET['qname'] == '') {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}

$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' LIMIT 0,1 ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash_code']) != md5(trim($SerialRow['license']))) {
	_shownotes($lang['system_error'], 'EnableQ Security Violation', 'EnableQ Security Violation');
}

$SQL = ' SELECT status,surveyID,surveyTitle,surveySubTitle,surveyInfo,isViewResult,theme FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1 ';
$Sur_Row = $DB->queryFirstRow($SQL);
$_GET['surveyID'] = $Sur_Row['surveyID'];
$_GET['surveyTitle'] = $Sur_Row['surveyTitle'];
$EnableQCoreClass->setTemplateFile('QuestionListFile', 'uCoeff.html');
$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$isViewResult = 1;
$EnableQCoreClass->replace('theme', 'Print');
$EnableQCoreClass->replace('surveyID', $Sur_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sur_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $Sur_Row['surveySubTitle']);
$EnableQCoreClass->replace('surveyInfo', $Sur_Row['surveyInfo']);
if (($Sur_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_Row['surveyID'] . '/' . md5('Qtn' . $Sur_Row['surveyID']) . '.php')) {
	$theSID = $Sur_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_Row['surveyID'] . '/' . md5('Qtn' . $Sur_Row['surveyID']) . '.php';
if (isset($_GET['dataSourceId']) && ($_GET['dataSourceId'] != '')) {
	$dataSource = getdatasourcesql($_GET['dataSourceId'], $Sur_Row['surveyID'], $_GET['roleType']);
	$dataSourceId = $_GET['dataSourceId'];
}
else {
	$dataSource = getdatasourcesql(0, $Sur_Row['surveyID'], $_GET['roleType']);
	$dataSourceId = 0;
}

$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($SQL);
$totalResponseNum = $CountRow['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (($theQtnArray['questionType'] != '12') && ($theQtnArray['questionType'] != '30')) {
		if ($_GET['printType'] == 'all') {
			$surveyID = $_GET['surveyID'];
			$PlugInName = $Module[$theQtnArray['questionType']];

			switch ($theQtnArray['questionType']) {
			case '4':
			case '5':
			case '10':
			case '11':
			case '13':
			case '14':
			case '16':
			case '20':
			case '22':
			case '23':
			case '27':
			case '29':
			case '31':
				require ROOT_PATH . 'PlugIn/' . $PlugInName . '/Admin/' . $PlugInName . '.count.inc.php';
				break;

			case '1':
			case '2':
			case '3':
			case '6':
			case '7':
			case '15':
			case '17':
			case '18':
			case '19':
			case '21':
			case '24':
			case '25':
			case '26':
			case '28':
				require ROOT_PATH . 'PlugIn/' . $PlugInName . '/Admin/' . $PlugInName . '.coeff.inc.php';
				break;

			case '9':
				require ROOT_PATH . 'PlugIn/Info/Admin/Info.page.inc.php';
				break;
			}

			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
		else if (trim($_GET['printType']) != '') {
			$theExportQtnList = explode(',', trim($_GET['printType']));

			if (in_array($questionID, $theExportQtnList)) {
				$surveyID = $_GET['surveyID'];
				$PlugInName = $Module[$theQtnArray['questionType']];

				switch ($theQtnArray['questionType']) {
				case '4':
				case '5':
				case '10':
				case '11':
				case '13':
				case '14':
				case '16':
				case '20':
				case '22':
				case '23':
				case '27':
				case '29':
					require ROOT_PATH . 'PlugIn/' . $PlugInName . '/Admin/' . $PlugInName . '.count.inc.php';
					break;

				case '1':
				case '2':
				case '3':
				case '6':
				case '7':
				case '15':
				case '17':
				case '18':
				case '19':
				case '21':
				case '24':
				case '25':
				case '26':
				case '28':
					require ROOT_PATH . 'PlugIn/' . $PlugInName . '/Admin/' . $PlugInName . '.coeff.inc.php';
					break;

				case '9':
					require ROOT_PATH . 'PlugIn/Info/Admin/Info.page.inc.php';
					break;
				}

				$EnableQCoreClass->parse('question', 'QUESTION', true);
			}
		}
	}
}

$EnableQCoreClass->replace('isComb', 2);
$ResultPage = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
$ResultPage = str_replace('ShowUserDefine.php', 'System/ShowUserDefine.php', $ResultPage);
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -30);
$ResultPage = str_replace($All_Path, '', $ResultPage);
$ResultPage = str_replace('CSS/', $All_Path . 'CSS/', $ResultPage);
$ResultPage = str_replace('JS/', $All_Path . 'JS/', $ResultPage);
$ResultPage = str_replace('../Images/', $All_Path . 'Images/', $ResultPage);
$ResultPage = str_replace('PerUserData/', $All_Path . 'PerUserData/', $ResultPage);
$ResultPage = str_replace('System/', $All_Path . 'System/', $ResultPage);
$ResultPage = str_replace('Analytics/', $All_Path . 'Analytics/', $ResultPage);
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

echo $ResultPage;
exit();

?>

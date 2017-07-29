<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.cond.inc.php';

if ($_GET['qname'] == '') {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}

$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' LIMIT 0,1 ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash_code']) != md5(trim($SerialRow['license']))) {
	_shownotes($lang['system_error'], 'EnableQ Security Violation', 'EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1 ';
$S_Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->setTemplateFile('ShowSurveyFile', 'uSurveyPrint.html');
$EnableQCoreClass->set_CycBlock('ShowSurveyFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
if (($S_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php';

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (($theQtnArray['questionType'] != '12') && ($theQtnArray['questionType'] != '11') && ($theQtnArray['questionType'] != '30')) {
		$EnableQCoreClass->replace('questionID', $questionID);
		$surveyID = $S_Row['surveyID'];
		$ModuleName = $Module[$theQtnArray['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';
		$EnableQCoreClass->parse('question', 'QUESTION', true);
	}
}

$SurveyPage = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -30);
$SurveyPage = str_replace($All_Path, '', $SurveyPage);
$SurveyPage = str_replace('CSS/', $All_Path . 'CSS/', $SurveyPage);
$SurveyPage = str_replace('JS/', $All_Path . 'JS/', $SurveyPage);
$SurveyPage = str_replace('Images/', $All_Path . 'Images/', $SurveyPage);
$SurveyPage = str_replace('PerUserData/', $All_Path . 'PerUserData/', $SurveyPage);
$SurveyPage = str_replace('v.php', $All_Path . 'v.php', $SurveyPage);
$SurveyPage = str_replace('action=""', 'action="' . $All_Path . 'q.php?qname=' . trim($_GET['qname']) . '"', $SurveyPage);
$SurveyPage = preg_replace('\'<tr[^>]*?style="display:none"[^>]*?>\\s*?<td[^>]*?>\\s*?<table[^>]*?.*?\\/table>.*?\\/tr>\'si', '', $SurveyPage);
$SurveyPage = preg_replace('\'<tr[^>]*?style="display:none"[^>]*?>.*?\\/tr>\'si', '', $SurveyPage);
$SurveyPage = preg_replace('\'<td[^>]*?style="display:none"[^>]*?>\\s*?<table[^>]*?.*?\\/table>.*?\\/td>\'si', '', $SurveyPage);
$SurveyPage = preg_replace('\'<tr[^>]*?rel="eqprint"[^>]*?>\\s*?<td[^>]*?>\\s*?<table[^>]*?.*?\\/table>.*?\\/tr>\'si', '', $SurveyPage);
$SizeArray = array();

if (preg_match_all('\'<input[^>]*?type=.?text.?[^>]*?>\'si', $SurveyPage, $Matches, PREG_SET_ORDER)) {
	foreach ($Matches as $MatchValue) {
		if (preg_match_all('\'size=.?\\d*.?\'si', $MatchValue[0], $SizeMatchs, PREG_SET_ORDER)) {
			$SizeNum = explode('=', $SizeMatchs[0][0]);
			$SizeArray[] = str_replace('\'', '', str_replace('"', '', $SizeNum[1]));
		}
	}
}

foreach ($SizeArray as $textSize) {
	if ($textSize == 1) {
		$wordString = '<U>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</U>';
	}
	else {
		if (40 <= $textSize) {
			$spaceWidth = 40;
		}
		else {
			$spaceWidth = $textSize;
		}

		$wordString = '<U>';
		$i = 0;

		for (; $i <= $spaceWidth; $i++) {
			$wordString .= '&nbsp;&nbsp;';
		}

		$wordString .= '</U>';
	}

	$preg = '/<input[^>]*?type=.?text.?[^>]*?size=.?' . $textSize . '.?[^>]*?>/si';
	$SurveyPage = preg_replace($preg, $wordString, $SurveyPage);
}

$textSize20 = '<U>';
$i = 1;

for (; $i <= 20; $i++) {
	$textSize20 .= '&nbsp;&nbsp;';
}

$textSize20 .= '</U>';
$SurveyPage = preg_replace('\'<input[^>]*?type=.?text.?[^>]*?>\'si', $textSize20, $SurveyPage);
$SurveyPage = preg_replace('\'<input[^>]*?type=.?radio.?[^>]*?>\'si', '&nbsp;<span lang=EN-US   style=\'font-family:Wingdings;mso-bidi-font-family:Wingdings\'>m </span>', $SurveyPage);
$SurveyPage = preg_replace('\'<input[^>]*?type=.?checkbox.?[^>]*?>\'si', '&nbsp;<span lang=EN-US style=\'font-family:Wingdings;mso-fareast-font-family:Wingdings;mso-bidi-font-family:Wingdings;mso-fareast-language:ZH-CN\'>q </span>', $SurveyPage);
$ColArray = array();

if (preg_match_all('\'<textarea[^>]*?[^>]*?>\'si', $SurveyPage, $Matches, PREG_SET_ORDER)) {
	foreach ($Matches as $MatchValue) {
		if (preg_match_all('\'cols=.?\\d*.?\'si', $MatchValue[0], $ColMatchs, PREG_SET_ORDER)) {
			$ColNum = explode('=', $ColMatchs[0][0]);
			$ColArray[] = str_replace('\'', '', str_replace('"', '', $ColNum[1]));
		}
	}
}

foreach ($ColArray as $ColSize) {
	$wordString = '';

	if (70 <= $ColSize) {
		$spaceWidth = 70;
	}
	else {
		$spaceWidth = $ColSize;
	}

	$j = 1;

	for (; $j <= 3; $j++) {
		$wordString .= '<U>';
		$i = 0;

		for (; $i <= $spaceWidth; $i++) {
			$wordString .= '&nbsp;&nbsp;';
		}

		$wordString .= '</U><br>';
	}

	$preg = '/<textarea[^>]*?cols=.?' . $ColSize . '.?[^>]*?>/si';
	$SurveyPage = preg_replace($preg, $wordString, $SurveyPage);
}

echo $SurveyPage;
exit();

?>

<?php
//dezend by http://www.yunlu99.com/
function cnsubstr($str, $start = 0, $end = 0, $flag = 0)
{
	$_obf_u_c_ = chr(127);
	$_obf_8w__ = array('/[-ş]([-ş]|[@-ş])/', '/[-w]/');
	$_obf_OQ__ = array('', '');

	if (2 < func_num_args()) {
		$end = func_get_arg(2);
	}
	else {
		$end = strlen($str);
	}

	if ($start < 0) {
		$start += $end;
	}

	if (0 < $start) {
		$p = substr($str, 0, $start);

		if ($_obf_u_c_ < $p[strlen($p) - 1]) {
			$p = preg_replace($_obf_8w__, $_obf_OQ__, $p);
			$start += strlen($p);
		}
	}

	$p = substr($str, $start, $end * 2);
	$end = strlen($p);

	if ($_obf_u_c_ < $p[$end - 1]) {
		$p = preg_replace($_obf_8w__, $_obf_OQ__, $p);
		$end += strlen($p);
	}

	$_obf_4VWt = substr($str, $start, $end);
	if (($end < strlen($str)) && ($flag == 1)) {
		$_obf_4VWt .= '...';
	}

	return $_obf_4VWt;
}

define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$thisProg = 's.php?uname=' . $_GET['uname'] . '&pub=' . $_GET['pub'] . '&lnum=' . $_GET['lnum'] . '&tnum=' . $_GET['tnum'];
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);
$hash_Code = md5(trim($SerialRow['license']));

if (trim($_GET['hash']) != md5(trim($SerialRow['license']) . 'EnableQ')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uSurveySList.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');

if ($_GET['lnum'] == '') {
	$pageNum = 20;
}
else {
	$pageNum = (int) $_GET['lnum'];
	$pageNum = ($pageNum < 0 ? 20 : $pageNum);
}

$SQL = ' SELECT a.surveyName,a.surveyTitle,a.surveySubTitle,a.surveyInfo,a.lang FROM ' . SURVEY_TABLE . ' a,' . ADMINISTRATORS_TABLE . ' b WHERE a.status =1 AND a.administratorsID = b.administratorsID  ';

if ($_GET['uname'] != '') {
	$SQL .= ' AND b.administratorsName = \'' . $_GET['uname'] . '\' ';
}

if ($_GET['pub'] != '') {
	$SQL .= ' AND a.isPublic = \'' . $_GET['pub'] . '\' ';
}

$SQL .= ' ORDER BY a.beginTime DESC,a.surveyID DESC LIMIT 0,' . $pageNum . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if ($_GET['tnum'] == '') {
		$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	}
	else {
		$_GET['tnum'] = (int) $_GET['tnum'];
		$EnableQCoreClass->replace('surveyTitle', cnsubstr($Row['surveyTitle'], 0, $_GET['tnum'], 1));
	}

	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -6);

	if ($_GET['task'] == 'n4') {
		$_SESSION['hash_Code'] = $hash_Code;
		$EnableQCoreClass->replace('surveyURL', $All_Path . '/x.php?task=n4&qname=' . $Row['surveyName'] . '&username=' . urlencode(trim($_SESSION['membersName'])) . '&hash=' . $hash_Code);
	}
	else {
		$EnableQCoreClass->replace('surveyURL', $All_Path . '/q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang']);
	}

	$EnableQCoreClass->replace('fullPath', $All_Path);
	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
$SurveyList = str_replace('"', '\'', $SurveyList);
$SurveyList = str_replace("\n", '', $SurveyList);
$SurveyList = str_replace("\r", '', $SurveyList);
$SurveyList = 'document.write("' . $SurveyList . '");';
echo $SurveyList;
exit();

?>

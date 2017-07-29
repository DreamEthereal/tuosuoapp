<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$thisProg = 'AjaxGetUserSurvey.php?uname=' . $_GET['uname'] . '&pub=' . $_GET['pub'] . '&type=' . $_GET['type'] . '&hash=' . $_GET['hash'] . ' ';
header('Content-Type:text/html; charset=gbk');
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash']) != md5(trim($SerialRow['license']))) {
	exit('false=' . $lang['get_hash_error']);
}

$SQL = ' SELECT a.surveyName,a.surveyTitle,a.surveyID FROM ' . SURVEY_TABLE . ' a,' . ADMINISTRATORS_TABLE . ' b WHERE a.status =1 AND a.administratorsID = b.administratorsID  ';

if ($_GET['uname'] != '') {
	$SQL .= ' AND b.administratorsName = \'' . $_GET['uname'] . '\' ';
}

if ($_GET['pub'] != '') {
	$SQL .= ' AND a.isPublic = \'' . $_GET['pub'] . '\' ';
}

$SQL .= ' ORDER BY a.beginTime DESC ';
$Result = $DB->query($SQL);
$surveyIDList = '';
$surveyNameList = '';

while ($Row = $DB->queryArray($Result)) {
	$surveyIDList .= $Row['surveyID'] . '|' . $Row['surveyTitle'] . '#';
	$surveyNameList .= $Row['surveyName'] . '|' . $Row['surveyTitle'] . '#';
}

$surveyIDList = substr($surveyIDList, 0, -1);
$surveyNameList = substr($surveyNameList, 0, -1);

if ($_GET['type'] == 2) {
	exit('true=' . $surveyIDList);
}
else {
	exit('true=' . $surveyNameList);
}

?>

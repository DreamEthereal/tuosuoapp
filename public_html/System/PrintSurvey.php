<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.cond.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] != '1') {
	_showerror($lang['system_error'], $lang['no_exe_survey']);
}

if ($_GET['DO'] == 'PrintWord') {
	include_once ROOT_PATH . 'Includes/HTML2DOC.class.php';
	$htmltodoc = new HTML_TO_DOC();
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
	$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
	$SerialRow = $DB->queryFirstRow($SQL);
	$PrintURL = $All_Path . 'Archive/PrintSurveyArchive.php?qname=' . trim($S_Row['surveyName']) . '&isPrint=1&qlang=' . $_GET['qlang'] . '&hash_code=' . md5(trim($SerialRow['license']));
	$htmltodoc->createDocFromURL($PrintURL, 'Survey_Print_Page_' . trim($S_Row['surveyName']), $download = true);
	exit();
}

?>

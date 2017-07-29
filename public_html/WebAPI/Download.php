<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
_checkroletype('1|2|3|4|5|6|7');
if ((trim($_GET['option']) == '') || ($_GET['qid'] == '') || ($_GET['responseID'] == '') || ($_GET['flag'] == '')) {
	_showerror($lang['error_system'], $lang['no_download_file']);
}
else {
	$SQL = ' SELECT ' . trim($_GET['option']) . ' FROM ' . $table_prefix . 'response_' . $_GET['qid'] . ' WHERE responseID = \'' . $_GET['responseID'] . '\' LIMIT 1 ';
	$R_Row = $DB->queryFirstRow($SQL);
}

if (trim($_GET['option']) == 'recordFile') {
	$dSQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['qid'] . '\' ';
	$dRow = $DB->queryFirstRow($dSQL);

	if ($dRow['custDataPath'] == '') {
		$FilePhyPath = ROOT_PATH . $Config['dataDirectory'] . '/response_' . $_GET['qid'] . '/';
	}
	else {
		$FilePhyPath = ROOT_PATH . $Config['dataDirectory'] . '/user/' . $dRow['custDataPath'] . '/';
	}
}
else {
	$dSQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['qid'] . '\' ';
	$dRow = $DB->queryFirstRow($dSQL);

	if ($dRow['custDataPath'] == '') {
		$FilePhyPath = ROOT_PATH . $Config['dataDirectory'] . '/response_' . $_GET['qid'] . '/' . date('Y-m', $_GET['flag']) . '/' . date('d', $_GET['flag']) . '/';
	}
	else {
		$FilePhyPath = ROOT_PATH . $Config['dataDirectory'] . '/user/' . $dRow['custDataPath'] . '/';
	}
}

if (!file_exists($FilePhyPath . $R_Row[trim($_GET['option'])]) || ($R_Row[trim($_GET['option'])] == '')) {
	_showerror($lang['error_system'], $lang['no_download_file']);
}
else {
	_downloadfile($FilePhyPath, $R_Row[trim($_GET['option'])]);
}

?>

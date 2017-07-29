<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$oldName = trim($_GET['oldName']);
$custDataPath = trim($_GET['custDataPath']);

if ($custDataPath == '') {
	echo 'null';
}
else if ($_GET['oldName'] != '') {
	$SQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE custDataPath=\'' . $custDataPath . '\' AND custDataPath !=\'' . $oldName . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}
else {
	$SQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE custDataPath=\'' . $custDataPath . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}

?>

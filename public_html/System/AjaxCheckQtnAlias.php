<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$oldName = trim($_GET['oldName']);
$surveyID = (int) trim($_GET['surveyID']);
$alias = trim($_GET['alias']);

if ($alias == '') {
	echo 'null';
	exit();
}

if ($oldName != '') {
	$SQL = ' SELECT alias FROM ' . QUESTION_TABLE . ' WHERE alias=\'' . $alias . '\' AND alias !=\'' . $oldName . '\' AND surveyID =\'' . $surveyID . '\'  LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}
else {
	$SQL = ' SELECT alias FROM ' . QUESTION_TABLE . ' WHERE alias=\'' . $alias . '\' AND surveyID =\'' . $surveyID . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}

?>

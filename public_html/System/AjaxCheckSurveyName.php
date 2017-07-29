<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$oldName = trim($_GET['oldName']);
$surveyName = trim($_GET['surveyName']);

if ($surveyName == '') {
	echo 'null';
}
else if ($_GET['oldName'] != '') {
	$SQL = ' SELECT surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . $surveyName . '\' AND surveyName !=\'' . $oldName . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}
else {
	$SQL = ' SELECT surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . $surveyName . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}

?>

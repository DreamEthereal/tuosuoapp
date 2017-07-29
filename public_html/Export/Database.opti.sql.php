<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($Config['is_mysql_proxy'] == 1) {
	$databaseName = $db_rw_server['db_name'];
}
else {
	$databaseName = $DB_name;
}

$SQL = ' SHOW TABLES FROM ' . $databaseName . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if (trim($Row['Tables_in_' . $databaseName]) != '') {
		$SQL = ' OPTIMIZE TABLE ' . $Row['Tables_in_' . $databaseName] . ' ';
		$DB->query($SQL);
	}
}

?>

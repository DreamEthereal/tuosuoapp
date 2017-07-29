<?php
//dezend by http://www.yunlu99.com/
function enableq_run_query($sql, $dblangtype)
{
	global $DB;
	global $lang;
	$ret = array();
	$num = 0;
	$sql = str_replace("\r\n", "\n", $sql);

	foreach (explode(';' . "\n" . '', trim($sql)) as $query) {
		$queries = explode("\n", trim($query));

		foreach ($queries as $query) {
			$ret[$num] .= (($query[0] == '#') || (($query[0] . $query[1]) == '--') ? '' : $query);
		}

		$num++;
	}

	unset($sql);
	ob_end_clean();
	$style = '<style>' . "\n" . '';
	$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
	$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
	$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
	$style .= '</style>' . "\n" . '';
	echo $style;
	flush();
	$scroll = '<SCRIPT type=text/javascript>window.scrollTo(0,document.body.scrollHeight);</SCRIPT>';
	$prefix = '';
	$i = 0;

	for (; $i < 300; $i++) {
		$prefix .= ' ' . "\n" . '';
	}

	$tablenum = 0;

	foreach ($ret as $query) {
		$query = trim($query);

		if ($query) {
			if (substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace('/CREATE TABLE `([a-z0-9_]+)` .*/is', '\\1', $query);
				$str = '<div class="tipsinfo">' . $lang['restore_table_notes'] . $name . ' ... <b class=green>Succ</b></div>';
				ob_end_clean();
				echo $prefix . $str . $scroll;
				flush();
				$DB->query($query);
				$tablenum++;
			}
			else {
				$DB->query($query);
			}
		}
	}

	ob_end_clean();
	echo '<div class="tipsinfo"><b>' . $lang['restore_table_notes'] . $tablenum . '</b></div>' . "\n" . '';
	flush();
	return true;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

_checkroletype(1);
$thisProg = 'DataBackup.php';
$DataFile_DIR_Name = $Config['absolutenessPath'] . 'PerUserData/data/';
createdir($DataFile_DIR_Name);

if ($_GET['Action'] == 'Download') {
	@set_time_limit(0);

	if (!file_exists($DataFile_DIR_Name . $_GET['FileName'])) {
		_showerror($lang['error_system'], $lang['no_download_file']);
	}
	else {
		writetolog($lang['down_data_file'] . ':' . $_GET['FileName']);
		_downloadfile($DataFile_DIR_Name, $_GET['FileName']);
	}
}

if ($_GET['Action'] == 'CreateNew') {
	@set_time_limit(0);
	include_once ROOT_PATH . 'Includes/MysqlBackup.class.php';
	$mysqlbackup = new MySQL_Backup();

	if ($Config['is_mysql_proxy'] == 1) {
		$databaseHost = $db_rw_server['db_host'];
		$databaseUser = $db_rw_server['db_user'];
		$databasePwd = $db_rw_server['db_pw'];
		$databaseName = $db_rw_server['db_name'];
	}
	else {
		$databaseHost = $DB_host;
		$databaseUser = $DB_user;
		$databasePwd = $DB_password;
		$databaseName = $DB_name;
	}

	$mysqlbackup->server = $databaseHost;
	$mysqlbackup->username = $databaseUser;
	$mysqlbackup->password = $databasePwd;
	$mysqlbackup->database = $databaseName;
	$mysqlbackup->enableqver = str_replace('EnableQ V', '', $Config['version']);
	$mysqlbackup->backup_dir = $DataFile_DIR_Name;

	if ($mysqlbackup->Execute(2)) {
		writetolog($lang['new_db_backup']);
		_showsucceed($lang['new_db_backup'], $thisProg);
	}
	else {
		_showerror($lang['MySQL_error'], $lang['error_db_backup']);
	}
}

if ($_GET['Action'] == 'Restore') {
	@set_time_limit(0);

	if (!file_exists($DataFile_DIR_Name . $_GET['FileName'])) {
		_showerror($lang['error_system'], $lang['no_restore_file'] . $_GET['FileName']);
	}

	$theDataVersion = explode('_', $_GET['FileName']);

	if (str_replace('EnableQ V', '', $Config['version']) != str_replace('.sql', '', $theDataVersion[7])) {
		_showerror($lang['error_system'], $lang['error_data_version']);
	}

	$sqlfile = $DataFile_DIR_Name . $_GET['FileName'];
	$fp = fopen($sqlfile, 'rb');
	$sql = fread($fp, filesize($sqlfile));
	fclose($fp);

	if (enableq_run_query($sql)) {
		require ROOT_PATH . 'Export/Database.opti.sql.php';
		writetolog($lang['restore_db_backup']);
		exit();
	}
}

if ($_GET['Action'] == 'DeleteDataFile') {
	@unlink($DataFile_DIR_Name . $_GET['FileName']);
	writetolog($lang['dele_data_file'] . ':' . $_GET['FileName']);
	_showsucceed($lang['dele_data_file'] . ':' . $_GET['FileName'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('DataListFile', 'DataBackup.html');
$EnableQCoreClass->set_CycBlock('DataListFile', 'BLOCK', 'block');
$EnableQCoreClass->replace('block', '');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -21) . 'PerUserData/data/';
$EnableQCoreClass->replace('fileDirName', $All_Path);
$recNum = 0;

if (is_dir($DataFile_DIR_Name)) {
	if ($DataDir = opendir($DataFile_DIR_Name)) {
		while (($DataFile = readdir($DataDir)) !== false) {
			if (($DataFile == '.') || ($DataFile == '..')) {
				continue;
			}

			if (filesize($DataFile_DIR_Name . $DataFile) == 0) {
				continue;
			}
			else {
				$tmpExt = explode('.', $DataFile);
				$tmpNum = count($tmpExt) - 1;

				if (strtolower($tmpExt[$tmpNum]) != 'sql') {
					continue;
				}
				else {
					$EnableQCoreClass->replace('fileName', $DataFile);
					$EnableQCoreClass->replace('fileSize', number_format(filesize($DataFile_DIR_Name . $DataFile) / 1024, 2));
				}

				$recNum++;
			}

			$EnableQCoreClass->replace('time', date('Y-m-d H:i:s', filemtime($DataFile_DIR_Name . $DataFile)));
			$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=DeleteDataFile&FileName=' . $DataFile);
			$EnableQCoreClass->replace('downloadURL', $thisProg . '?Action=Download&FileName=' . $DataFile);
			$EnableQCoreClass->replace('restoreURL', $thisProg . '?Action=Restore&FileName=' . $DataFile);
			$EnableQCoreClass->parse('block', 'BLOCK', true);
		}

		closedir($DataDir);
	}
}

$EnableQCoreClass->replace('recNum', $recNum);
$EnableQCoreClass->replace('newURL', $thisProg . '?Action=CreateNew');
$EnableQCoreClass->parse('DataList', 'DataListFile');
$EnableQCoreClass->output('DataList');

?>

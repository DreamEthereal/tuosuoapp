<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
_checkroletype('1|5|6');
$DataFile_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

if ($_GET['Action'] == 'Download') {
	@set_time_limit(0);

	if (!file_exists($DataFile_DIR_Name . $_GET['fileName'])) {
		_showerror($lang['error_system'], $lang['no_download_file']);
	}
	else {
		writetolog($lang['down_data_file'] . ':' . $_GET['fileName']);
		_downloadfile($DataFile_DIR_Name, $_GET['fileName']);
	}
}

if ($_GET['Action'] == 'Delete') {
	@unlink($DataFile_DIR_Name . $_GET['fileName']);
	$SQL = ' SELECT actionMess FROM ' . ANDROID_LOG_TABLE . ' WHERE logId= \'' . $_GET['logId'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$theNewMess = explode('<br/>', $Row['actionMess']);
	$SQL = ' UPDATE ' . ANDROID_LOG_TABLE . ' SET actionMess = \'' . $theNewMess[0] . '\' WHERE logId= \'' . $_GET['logId'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['dele_data_file'] . ':' . $_GET['fileName']);
	_showsucceed($lang['dele_data_file'] . ':' . $_GET['fileName'], 'OfflineActionLog.php?type=3');
}

?>

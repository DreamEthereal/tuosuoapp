<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
$SQL = ' DELETE FROM ' . MAILLIST_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$ImageFile_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/' . $theDeleUserId . '/';

if (is_dir($ImageFile_DIR_Name)) {
	deletedir($ImageFile_DIR_Name);
}

$SQL = '  SELECT tplName FROM ' . TPL_TABLE . ' WHERE administratorsID= \'' . $theDeleUserId . '\' ORDER BY panelID ';
$Result = $DB->query($SQL);

while ($FileRow = $DB->queryArray($Result)) {
	$PhyFileFullName = $Config['absolutenessPath'] . 'Templates/CN/' . trim($FileRow['tplName']);

	if (file_exists($PhyFileFullName)) {
		@unlink($PhyFileFullName);
	}
}

$SQL = ' DELETE FROM ' . TPL_TABLE . ' WHERE administratorsID= \'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . OPTION_TABLE . ' WHERE administratorsID= \'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . QUERY_LIST_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . QUERY_COND_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . REPORTDEFINE_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$survey_ID = $Row['surveyID'];
	require 'Survey.dele.php';
}

$SQL = ' DELETE FROM ' . ANDROID_PUSH_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE userId =\'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE administratorsID= \'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . DATA_TRACE_TABLE . ' WHERE administratorsID= \'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . DATA_TASK_TABLE . ' WHERE administratorsID= \'' . $theDeleUserId . '\' ';
$DB->query($SQL);
$SQL = ' DELETE FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID=\'' . $theDeleUserId . '\' ';
$DB->query($SQL);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit(' Security Violation');
}

$theDataAuthArray = explode('$$$', getdataauth($_GET['surveyID'], $_GET['responseID'], $R_Row, $Sur_G_Row));
$haveDeleDataAuth = $theDataAuthArray[2];

if ($haveDeleDataAuth != 1) {
	_showerror($lang['auth_error'], $lang['passport_is_permit'] . ':' . $lang['no_auth_del_data']);
}

if ($_GET['fields'] == 'recordFile') {
	if ($Sur_G_Row['custDataPath'] == '') {
		$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
		$fileName = $filePath . trim($_GET['fileName']);
		$vFilePhyPath = 'response_' . $_GET['surveyID'] . '/' . trim($_GET['fileName']);
	}
	else {
		$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
		$fileName = $filePath . trim($_GET['fileName']);
		$vFilePhyPath = 'user/' . $Sur_G_Row['custDataPath'] . '/' . trim($_GET['fileName']);
	}
}
else if ($Sur_G_Row['custDataPath'] == '') {
	$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/' . date('Y-m', $_GET['fileTime']) . '/' . date('d', $_GET['fileTime']) . '/';
	$fileName = $filePath . trim($_GET['fileName']);
	$vFilePhyPath = 'response_' . $_GET['surveyID'] . '/' . date('Y-m', $_GET['fileTime']) . '/' . date('d', $_GET['fileTime']) . '/' . trim($_GET['fileName']);
}
else {
	$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
	$fileName = $filePath . trim($_GET['fileName']);
	$vFilePhyPath = 'user/' . $Sur_G_Row['custDataPath'] . '/' . trim($_GET['fileName']);
}

if (file_exists($fileName)) {
	require_remote_service(1, base64_encode($vFilePhyPath));
	@unlink($fileName);
}

$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET ' . trim($_GET['fields']) . ' = \'\' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
$DB->query($SQL);

if ($_GET['fields'] != 'recordFile') {
	$theQtnArray = explode('_', trim($_GET['fields']));
	$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',responseID=\'' . $_GET['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theQtnArray[1] . '\',varName=\'' . trim($_GET['fields']) . '\',oriValue=\'' . addslashes(trim($_GET['fileName'])) . '\',updateValue=\'\',isAppData =0,traceTime=\'' . time() . '\' ';
	$DB->query($uSQL);
}

writetolog($lang['action_dele_file_info'] . ',ÐòºÅ:' . $_GET['responseID'] . ':' . $_GET['surveyTitle']);
if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '4')) {
	if ($isMobile == 1) {
		$rtnURL = ROOT_PATH . 'Android/ShowInputData.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&Does=View&responseID=' . $_GET['responseID'];
	}
	else {
		$rtnURL = ROOT_PATH . 'System/ShowInputData.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&Does=View&responseID=' . $_GET['responseID'];
	}
}
else {
	$rtnURL = ROOT_PATH . 'Analytics/DataList.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&Does=View&responseID=' . $_GET['responseID'];
}

_showsucceed($lang['action_dele_file_info'], $rtnURL);

?>

<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|3|4|5|6|7');
$ConfigRow['topicNum'] = 30;
$thisProg = 'AdministratorsLogs.php';

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsLogID=\'' . $_GET['administratorsLogID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_logs']);
	_showsucceed($lang['delete_logs'], $thisProg);
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf(' DELETE FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE (administratorsLogID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
	$DB->query($SQL);
	writetolog($lang['delete_logs']);
	_showsucceed($lang['delete_logs'], $thisProg);
}

if ($_POST['clearLogs']) {
	if ($_SESSION['adminRoleType'] == '1') {
		$SQL = ' TRUNCATE TABLE ' . ADMINISTRATORSLOG_TABLE;
	}
	else {
		$SQL = ' DELETE FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID=\'' . $_SESSION['administratorsID'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['clear_logs']);
	_showsucceed($lang['clear_logs'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('AdministratorsLogsFile', 'AdministratorsLogsList.html');
$EnableQCoreClass->set_CycBlock('AdministratorsLogsFile', 'LOGS', 'logs');
$EnableQCoreClass->replace('logs', '');
$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('t_createDate', '');
$EnableQCoreClass->replace('t_createDateEnd', '');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . ADMINISTRATORSLOG_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID ';
	$EnableQCoreClass->replace('users_list', '操作人：<input class="inp" type="text" name="t_administratorsID" id="t_administratorsID">');
	break;

default:
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . ADMINISTRATORSLOG_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND a.administratorsID=\'' . $_SESSION['administratorsID'] . '\'';
	$EnableQCoreClass->replace('users_list', '操作人：<select name="t_administratorsID" id="t_administratorsID" class="sel"><option value=' . $_SESSION['administratorsName'] . ' selected>' . $_SESSION['administratorsName'] . '</option></select>');
	break;
}

$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	$SQL .= ' AND a.operationTitle LIKE BINARY \'%' . $_POST['t_name'] . '%\' ';
	$page_others = '&t_name=' . urlencode($_POST['t_name']);
	$EnableQCoreClass->replace('t_name', $_POST['t_name']);

	if (trim($_POST['t_createDate']) != '') {
		$actionTime = explode('-', $_POST['t_createDate']);
		$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
		$SQL .= ' AND a.createDate >= \'' . $actionUnixTime . '\' ';
		$page_others .= '&t_createDate=' . $_POST['t_createDate'];
		$EnableQCoreClass->replace('t_createDate', $_POST['t_createDate']);
	}

	if (trim($_POST['t_createDateEnd']) != '') {
		$actionTime = explode('-', $_POST['t_createDateEnd']);
		$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
		$SQL .= ' AND a.createDate <= \'' . $actionUnixTime . '\' ';
		$page_others .= '&t_createDateEnd=' . $_POST['t_createDateEnd'];
		$EnableQCoreClass->replace('t_createDateEnd', $_POST['t_createDateEnd']);
	}

	if ($_POST['t_administratorsID'] != '0') {
		$SQL .= ' AND b.administratorsName like \'%' . trim($_POST['t_administratorsID']) . '%\' ';
		$page_others .= '&t_administratorsID=' . trim($_POST['t_administratorsID']);

		switch ($_SESSION['adminRoleType']) {
		case '1':
			$EnableQCoreClass->replace('users_list', '操作人：<input class="inp" type="text" name="t_administratorsID" id="t_administratorsID" value="' . trim($_POST['t_administratorsID']) . '">');
			break;

		default:
			break;
		}
	}
}

if (isset($_GET['t_name']) && !$_POST['Action']) {
	$SQL .= ' AND a.operationTitle LIKE BINARY \'%' . $_GET['t_name'] . '%\' ';
	$page_others = '&t_name=' . urlencode($_GET['t_name']);
	$EnableQCoreClass->replace('t_name', $_GET['t_name']);
}

if (isset($_GET['t_createDate']) && !$_POST['Action'] && ($_GET['t_createDate'] != '')) {
	$actionTime = explode('-', $_GET['t_createDate']);
	$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
	$SQL .= ' AND a.createDate >= \'' . $actionUnixTime . '\' ';
	$page_others .= '&t_createDate=' . $_GET['t_createDate'];
	$EnableQCoreClass->replace('t_createDate', $_GET['t_createDate']);
}

if (isset($_GET['t_createDateEnd']) && !$_POST['Action'] && ($_GET['t_createDateEnd'] != '')) {
	$actionTime = explode('-', $_GET['t_createDateEnd']);
	$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
	$SQL .= ' AND a.createDate <= \'' . $actionUnixTime . '\' ';
	$page_others .= '&t_createDateEnd=' . $_GET['t_createDateEnd'];
	$EnableQCoreClass->replace('t_createDateEnd', $_GET['t_createDateEnd']);
}

if (isset($_GET['t_administratorsID']) && ($_GET['t_administratorsID'] != '0') && !$_POST['Action']) {
	$SQL .= ' AND b.administratorsName like \'%' . trim($_GET['t_administratorsID']) . '%\' ';
	$page_others .= '&t_administratorsID=' . trim($_GET['t_administratorsID']);

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$EnableQCoreClass->replace('users_list', '操作人：<input class="inp" type="text" name="t_administratorsID" id="t_administratorsID" value="' . trim($_GET['t_administratorsID']) . '">');
		break;

	default:
		break;
	}
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY a.administratorsLogID DESC  ';
$_SESSION['logSQL'] = base64_encode($SQL);
$SQL .= ' LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('ID', $Row['administratorsLogID']);
	$EnableQCoreClass->replace('operationTitle', $Row['operationTitle']);
	$EnableQCoreClass->replace('ip', $Row['operationIP']);
	$EnableQCoreClass->replace('userName', $Row['administratorsName']);
	$EnableQCoreClass->replace('time', date('Y-m-d H:i:s', $Row['createDate']));

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$deleteURL = $thisProg . '?Action=Delete&administratorsLogID=' . $Row['administratorsLogID'];
		$actionURL = '<a href="' . $deleteURL . '" onclick="return window.confirm(\'您真的想删除该记录吗？本操作不可恢复！\')"><img src="../Images/del.gif" alt=删除该条日志 border=0></a>';
		$EnableQCoreClass->replace('actionURL', $actionURL);
		break;

	default:
		$EnableQCoreClass->replace('actionURL', '被禁止');
		break;
	}

	$EnableQCoreClass->parse('logs', 'LOGS', true);
}

$EnableQCoreClass->replace('exportURL', '../Export/Export.log.inc.php');

switch ($_SESSION['adminRoleType']) {
case '1':
	$EnableQCoreClass->replace('adminRoleAction', '');
	break;

default:
	$EnableQCoreClass->replace('adminRoleAction', 'disabled');
	break;
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('AdministratorsLogs', 'AdministratorsLogsFile');
$EnableQCoreClass->output('AdministratorsLogs');

?>

<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
$ConfigRow['topicNum'] = 30;
$_GET['type'] = (int) $_GET['type'];
$thisProg = 'OfflineActionLog.php?type=' . $_GET['type'];
_checkroletype('1|5|6');

if ($_SESSION['adminRoleType'] == '5') {
	if ($_SESSION['adminRoleGroupID'] == 0) {
		$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' ) ';
		$theSonGroup = array();
		$theSonGroup[] = 0;
		$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$theSonGroup[] = $sRow['userGroupID'];
		}

		$MSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =4 AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 ';
		$MResult = $DB->query($MSQL);
		$sameGroupUser = array();
		$sameGroupUserName = array();

		while ($MRow = $DB->queryArray($MResult)) {
			$sameGroupUser[] = $MRow['administratorsID'];
			$sameGroupUserName[$MRow['administratorsID']] = $MRow['administratorsName'];
		}
	}
	else {
		$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
		$MSQL = ' SELECT a.administratorsID,a.administratorsName FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =4 AND a.userGroupID = b.userGroupID AND a.groupType=1 AND ' . $theSonSQL . ' ';
		$MResult = $DB->query($MSQL);
		$sameGroupUser = array();
		$sameGroupUserName = array();

		while ($MRow = $DB->queryArray($MResult)) {
			$sameGroupUser[] = $MRow['administratorsID'];
			$sameGroupUserName[$MRow['administratorsID']] = $MRow['administratorsName'];
		}
	}
}
else {
	$MSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =\'4\' AND groupType=1 ';
	$MResult = $DB->query($MSQL);
	$sameGroupUser = array();
	$sameGroupUserName = array();

	while ($MRow = $DB->queryArray($MResult)) {
		$sameGroupUser[] = $MRow['administratorsID'];
		$sameGroupUserName[$MRow['administratorsID']] = $MRow['administratorsName'];
	}
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE logId=\'' . $_GET['logId'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_logs']);
	_showsucceed($lang['delete_logs'], $thisProg);
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf(' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE (logId IN (\'%s\'))', @join('\',\'', $_POST['ID']));
	$DB->query($SQL);
	writetolog($lang['delete_logs']);
	_showsucceed($lang['delete_logs'], $thisProg);
}

if ($_POST['clearLogs']) {
	if ($_SESSION['adminRoleType'] != '5') {
		$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE actionType=\'' . $_POST['offlineActionType'] . '\' ';
	}
	else if (count($sameGroupUser) != 0) {
		$SQL = ' DELETE FROM ' . ANDROID_LOG_TABLE . ' WHERE actionType=\'' . $_POST['offlineActionType'] . '\' AND userId IN (' . implode(',', $sameGroupUser) . ') ';
	}

	$DB->query($SQL);
	writetolog($lang['clear_logs']);
	_showsucceed($lang['clear_logs'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('AndroidOfflineLogFile', 'AndroidOfflineLog.html');
$EnableQCoreClass->set_CycBlock('AndroidOfflineLogFile', 'LOGS', 'logs');
$EnableQCoreClass->replace('logs', '');
$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('t_actionTime', '');
$EnableQCoreClass->replace('t_actionTimeEnd', '');
$i = 1;

for (; $i <= 6; $i++) {
	$EnableQCoreClass->replace('actiontype' . $i, '');
}

switch ($_GET['type']) {
case 1:
default:
	$EnableQCoreClass->replace('actiontype1', 'class="cur"');
	$EnableQCoreClass->replace('actionType', '下载问卷');
	$_GET['type'] = 1;
	break;

case 6:
	$EnableQCoreClass->replace('actiontype6', 'class="cur"');
	$EnableQCoreClass->replace('actionType', '任务下载');
	break;

case 2:
	$EnableQCoreClass->replace('actiontype2', 'class="cur"');
	$EnableQCoreClass->replace('actionType', '数据上行');
	break;

case 3:
	$EnableQCoreClass->replace('actiontype3', 'class="cur"');
	$EnableQCoreClass->replace('actionType', '异常处理');
	break;

case 4:
	$EnableQCoreClass->replace('actiontype4', 'class="cur"');
	$EnableQCoreClass->replace('actionType', '问卷删除');
	break;

case 5:
	$EnableQCoreClass->replace('actiontype5', 'class="cur"');
	$EnableQCoreClass->replace('actionType', '程序更新');
	break;
}

$EnableQCoreClass->replace('offlineActionType', $_GET['type']);
$users_list = '';

foreach ($sameGroupUserName as $thisUserId => $thisUserName) {
	$users_list .= '<option value=' . $thisUserId . '>' . $thisUserName . '</option>';
}

$EnableQCoreClass->replace('users_list', $users_list);

if ($_SESSION['adminRoleType'] == '5') {
	if (count($sameGroupUser) == 0) {
		$SQL = ' SELECT * FROM ' . ANDROID_LOG_TABLE . ' WHERE userId = 0 ';
	}
	else {
		$SQL = ' SELECT * FROM ' . ANDROID_LOG_TABLE . ' WHERE userId IN (' . implode(',', $sameGroupUser) . ') AND actionType =\'' . $_GET['type'] . '\'';
	}
}
else {
	$SQL = ' SELECT * FROM ' . ANDROID_LOG_TABLE . ' WHERE actionType =\'' . $_GET['type'] . '\' ';
}

$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	$SQL .= ' AND actionMess LIKE BINARY \'%' . $_POST['t_name'] . '%\' ';
	$page_others = '&t_name=' . urlencode($_POST['t_name']);
	$EnableQCoreClass->replace('t_name', $_POST['t_name']);

	if (trim($_POST['t_actionTime']) != '') {
		$actionTime = explode('-', $_POST['t_actionTime']);
		$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
		$SQL .= ' AND actionTime >= \'' . $actionUnixTime . '\' ';
		$page_others .= '&t_actionTime=' . $_POST['t_actionTime'];
		$EnableQCoreClass->replace('t_actionTime', $_POST['t_actionTime']);
	}

	if (trim($_POST['t_actionTimeEnd']) != '') {
		$actionTime = explode('-', $_POST['t_actionTimeEnd']);
		$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
		$SQL .= ' AND actionTime <= \'' . $actionUnixTime . '\' ';
		$page_others .= '&t_actionTimeEnd=' . $_POST['t_actionTimeEnd'];
		$EnableQCoreClass->replace('t_actionTimeEnd', $_POST['t_actionTimeEnd']);
	}

	if ($_POST['t_administratorsID'] != '0') {
		$SQL .= ' AND userId = \'' . $_POST['t_administratorsID'] . '\' ';
		$page_others .= '&t_administratorsID=' . $_POST['t_administratorsID'];
		$users_list = '';

		foreach ($sameGroupUserName as $thisUserId => $thisUserName) {
			if ($thisUserId == $_POST['t_administratorsID']) {
				$users_list .= '<option value=' . $thisUserId . ' selected>' . $thisUserName . '</option>';
			}
			else {
				$users_list .= '<option value=' . $thisUserId . '>' . $thisUserName . '</option>';
			}
		}

		$EnableQCoreClass->replace('users_list', $users_list);
	}
}

if (isset($_GET['t_name']) && !$_POST['Action']) {
	$SQL .= ' AND actionMess LIKE BINARY \'%' . $_GET['t_name'] . '%\' ';
	$page_others = '&t_name=' . urlencode($_GET['t_name']);
	$EnableQCoreClass->replace('t_name', $_GET['t_name']);
}

if (isset($_GET['t_actionTime']) && !$_POST['Action'] && ($_GET['t_actionTime'] != '')) {
	$actionTime = explode('-', $_GET['t_actionTime']);
	$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
	$SQL .= ' AND actionTime >= \'' . $actionUnixTime . '\' ';
	$page_others .= '&t_actionTime=' . $_GET['t_actionTime'];
	$EnableQCoreClass->replace('t_actionTime', $_GET['t_actionTime']);
}

if (isset($_GET['t_actionTimeEnd']) && !$_POST['Action'] && ($_GET['t_actionTimeEnd'] != '')) {
	$actionTime = explode('-', $_GET['t_actionTimeEnd']);
	$actionUnixTime = mktime(0, 0, 0, $actionTime[1], $actionTime[2], $actionTime[0]);
	$SQL .= ' AND actionTime <= \'' . $actionUnixTime . '\' ';
	$page_others .= '&t_actionTimeEnd=' . $_GET['t_actionTimeEnd'];
	$EnableQCoreClass->replace('t_actionTimeEnd', $_GET['t_actionTimeEnd']);
}

if (isset($_GET['t_administratorsID']) && ($_GET['t_administratorsID'] != '0') && !$_POST['Action']) {
	$SQL .= ' AND userId = \'' . $_GET['t_administratorsID'] . '\' ';
	$page_others .= '&t_administratorsID=' . $_GET['t_administratorsID'];
	$users_list = '';

	foreach ($sameGroupUserName as $thisUserId => $thisUserName) {
		if ($thisUserId == $_GET['t_administratorsID']) {
			$users_list .= '<option value=' . $thisUserId . ' selected>' . $thisUserName . '</option>';
		}
		else {
			$users_list .= '<option value=' . $thisUserId . '>' . $thisUserName . '</option>';
		}
	}

	$EnableQCoreClass->replace('users_list', $users_list);
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

$SQL .= ' ORDER BY actionTime DESC ';
$_SESSION['offLogSQL'] = base64_encode($SQL);
$SQL .= ' LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('ID', $Row['logId']);
	$EnableQCoreClass->replace('actionMess', str_replace('AbnormalFile.php?Action', 'AbnormalFile.php?logId=' . $Row['logId'] . '&Action', $Row['actionMess']));
	$EnableQCoreClass->replace('userName', $Row['nickName']);
	$EnableQCoreClass->replace('time', date('Y-m-d H:i:s', $Row['actionTime']));

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$deleteURL = $thisProg . '&Action=Delete&logId=' . $Row['logId'];
		$actionURL = '<a href="' . $deleteURL . '" onclick="return window.confirm(\'您真的想删除该记录吗？本操作不可恢复！\')"><img src="../Images/del.gif" alt=删除该条日志 border=0></a>';
		$EnableQCoreClass->replace('actionURL', $actionURL);
		break;

	default:
		$EnableQCoreClass->replace('actionURL', '被禁止');
		break;
	}

	$EnableQCoreClass->parse('logs', 'LOGS', true);
}

$EnableQCoreClass->replace('exportURL', '../Export/Export.offlog.inc.php');

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
$EnableQCoreClass->parse('AndroidOfflineLog', 'AndroidOfflineLogFile');
$EnableQCoreClass->output('AndroidOfflineLog');


?>

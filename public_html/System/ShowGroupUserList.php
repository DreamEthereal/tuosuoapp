<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
$_GET['groupType'] = (int) $_GET['groupType'];
$_GET['groupId'] = (int) $_GET['groupId'];
$thisProg = 'ShowGroupUserList.php?groupType=' . $_GET['groupType'] . '&groupId=' . $_GET['groupId'];
$thisURLString = 'groupType=' . $_GET['groupType'] . '&groupId=' . $_GET['groupId'];
$ConfigRow['topicNum'] = 9;
_checkroletype('1|5|6');
if (!isset($_GET['groupType']) || !in_array($_GET['groupType'], array(1, 2)) || !isset($_GET['groupId'])) {
	_showerror('参数错误', '参数错误：无法识别的用户结构树参数');
}

if ($_SESSION['userListBackURL'] != '') {
	$userListBackURL = $_SESSION['userListBackURL'];
	$EnableQCoreClass->replace('userListURL', $_SESSION['userListBackURL']);
}
else {
	$userListBackURL = $thisProg;
	$EnableQCoreClass->replace('userListURL', $thisProg);
}

$EnableQCoreClass->replace('thisURL', $thisProg);
$EnableQCoreClass->replace('thisNodeURL', 'ShowGroupNodeList.php?' . $thisURLString);
$EnableQCoreClass->replace('addURL', $thisProg . '&Action=Add');
$EnableQCoreClass->replace('importURL', $thisProg . '&Action=Import');
$EnableQCoreClass->replace('importNodesURL', $thisProg . '&Action=ImportNodes');
$EnableQCoreClass->replace('groupType', $_GET['groupType']);

if ($_GET['groupId'] != 0) {
	$SQL = ' SELECT userGroupName,absPath,groupType,fatherId FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['groupId'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$nodeName = $Row['userGroupName'];
	$nodeAllName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $Row['groupType']);

	if ($_SESSION['adminRoleType'] == '5') {
		$EnableQCoreClass->replace('isTopNode', 'none');
	}
	else {
		$EnableQCoreClass->replace('isTopNode', '');
	}

	$EnableQCoreClass->replace('topURL', 'ShowGroupUserList.php?groupType=' . $_GET['groupType'] . '&groupId=' . $Row['fatherId']);
}
else {
	switch ($_GET['groupType']) {
	case 1:
		$nodeName = $lang['corp_root_node'];
		break;

	case 2:
		$nodeName = $lang['cus_root_node'];
		break;
	}

	$nodeAllName = $nodeName;
	$EnableQCoreClass->replace('isTopNode', 'none');
	$EnableQCoreClass->replace('topURL', '');
}

$EnableQCoreClass->replace('nodeName', $nodeName);
$EnableQCoreClass->replace('nodeAllName', $nodeAllName);
include_once ROOT_PATH . 'System/ShowGroupUserList.inc.php';
$EnableQCoreClass->setTemplateFile('UserListFile', 'GroupUsersList.html');
$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');
$EnableQCoreClass->replace('user', '');
$EnableQCoreClass->replace('isAdminRole', $_SESSION['adminRoleType']);

if ($_GET['groupId'] != 0) {
	$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_GET['groupId'] . '-%\' OR b.userGroupID = \'' . $_GET['groupId'] . '\') ';

	switch ($_SESSION['adminRoleType']) {
	case 1:
		$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin !=0 AND a.userGroupID = b.userGroupID AND a.groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ';
		$EnableQCoreClass->replace('isAdmin5', '');
		break;

	case 6:
		$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND a.userGroupID = b.userGroupID AND a.groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ';
		$EnableQCoreClass->replace('isAdmin5', '');
		break;

	case 5:
		$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND a.userGroupID = b.userGroupID AND a.groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ';
		$EnableQCoreClass->replace('isAdmin5', 'none');
		break;
	}
}
else {
	$theSonSQL = ' concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['groupId'] . '-%\' ';
	$theSonGroup = array();
	$theSonGroup[] = 0;
	$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType=\'' . $_GET['groupType'] . '\' ';
	$sResult = $DB->query($sSQL);

	while ($sRow = $DB->queryArray($sResult)) {
		$theSonGroup[] = $sRow['userGroupID'];
	}

	switch ($_SESSION['adminRoleType']) {
	case 1:
		$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' a WHERE a.isAdmin !=0 AND a.userGroupID IN (' . implode(',', $theSonGroup) . ') AND a.groupType=\'' . $_GET['groupType'] . '\' ';
		$EnableQCoreClass->replace('isAdmin5', '');
		break;

	case 6:
		$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' a WHERE a.isAdmin NOT IN (0,1,6) AND a.userGroupID IN (' . implode(',', $theSonGroup) . ') AND a.groupType=\'' . $_GET['groupType'] . '\' ';
		$EnableQCoreClass->replace('isAdmin5', '');
		break;

	case 5:
		$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' a WHERE a.isAdmin NOT IN (0,1,6) AND a.userGroupID IN (' . implode(',', $theSonGroup) . ') AND a.groupType=\'' . $_GET['groupType'] . '\' ';
		$EnableQCoreClass->replace('isAdmin5', 'none');
		break;
	}
}

$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	if ($_POST['isAdmin'] != 0) {
		$SQL .= ' AND a.isAdmin = \'' . $_POST['isAdmin'] . '\' ';
		$page_others = '&isAdmin=' . $_POST['isAdmin'];
		$Option = 'option_' . $_POST['isAdmin'];
		$EnableQCoreClass->replace($Option, 'selected');
	}

	$name = trim($_POST['name']);
	$SQL .= ' AND a.administratorsName LIKE \'%' . $name . '%\' ';
	$page_others .= '&name=' . urlencode($name);
	$EnableQCoreClass->replace('name', $name);
}
else {
	$EnableQCoreClass->replace('name', '');
}

if (isset($_GET['isAdmin']) && !$_POST['Action'] && ($_GET['isAdmin'] != 0)) {
	$SQL .= ' AND a.isAdmin = \'' . $_GET['isAdmin'] . '\' ';
	$page_others .= '&isAdmin=' . $_GET['isAdmin'];
	$Option = 'option_' . $_GET['isAdmin'];
	$EnableQCoreClass->replace($Option, 'selected');
}

if (isset($_GET['name']) && !$_POST['Action']) {
	$name = trim($_GET['name']);
	$SQL .= ' AND a.administratorsName LIKE \'%' . $name . '%\' ';
	$page_others .= '&name=' . $name;
	$EnableQCoreClass->replace('name', $name);
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

$SQL .= ' ORDER BY a.administratorsID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);
$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$userListBackURL = $thisProg . '&pageID=' . $pageID . $page_others;
$_SESSION['userListBackURL'] = $userListBackURL;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('administratorsID', $Row['administratorsID']);
	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('roleName', $lang['isAdmin_' . $Row['isAdmin']]);
	$EnableQCoreClass->replace('groupType', $Row['groupType']);
	$EnableQCoreClass->replace('groupId', $Row['userGroupID']);

	if ($_GET['groupId'] != 0) {
		if ($Row['userGroupID'] != 0) {
			$userAllGroupName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $Row['groupType']);
		}
		else {
			$userAllGroupName = getusergroupname($Row['userGroupID'], $_GET['groupType']);
		}
	}
	else if ($Row['userGroupID'] != 0) {
		$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $Row['userGroupID'] . '\' ';
		$gRow = $DB->queryFirstRow($gSQL);
		$userAllGroupName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
	}
	else {
		$userAllGroupName = getusergroupname($Row['userGroupID'], $Row['groupType']);
	}

	$EnableQCoreClass->replace('userAllGroupName', $userAllGroupName);
	$EnableQCoreClass->replace('userGroupName', cnsubstr($userAllGroupName, 0, 18, 1));

	if ($Row['isActive'] == 1) {
		$EnableQCoreClass->replace('userName', $Row['administratorsName']);
		if (($_SESSION['administratorsID'] == $Row['administratorsID']) || ($Row['isInit'] == '1')) {
			$EnableQCoreClass->replace('isStop', '');
			$StopAlert = 'style="display:none"';
			$EnableQCoreClass->replace('StopAlert', $StopAlert);
			$EnableQCoreClass->replace('closeURL', '');
		}
		else {
			$EnableQCoreClass->replace('isStop', $lang['stop']);
			$StopAlert = 'onclick="return window.confirm(\'' . $lang['user_stop_confim'] . '\')"';
			$EnableQCoreClass->replace('StopAlert', $StopAlert);
			$EnableQCoreClass->replace('closeURL', $thisProg . '&Action=Close&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
		}
	}
	else {
		$EnableQCoreClass->replace('userName', $Row['administratorsName'] . '&nbsp;<img src=../Images/hide.gif>' . $isConfim);
		$EnableQCoreClass->replace('isStop', $lang['active']);
		$EnableQCoreClass->replace('StopAlert', '');
		$EnableQCoreClass->replace('closeURL', $thisProg . '&Action=Active&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
	}

	if (($_SESSION['administratorsID'] == $Row['administratorsID']) || ($Row['isInit'] == '1')) {
		$EnableQCoreClass->replace('isSelf', 'none');
		$EnableQCoreClass->replace('deleteAlertURL', '');
		$EnableQCoreClass->replace('isAdmin1', 'disabled');
	}
	else {
		$EnableQCoreClass->replace('isSelf', '');
		$EnableQCoreClass->replace('isAdmin1', '');
		$EnableQCoreClass->replace('deleteAlertURL', $thisProg . '&Action=DeleteInfo&administratorsID=' . $Row['administratorsID']);
	}

	$EnableQCoreClass->replace('editURL', $thisProg . '&Action=Edit&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
	$EnableQCoreClass->parse('user', 'USER', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('UserList', 'UserListFile');
$EnableQCoreClass->output('UserList');

?>

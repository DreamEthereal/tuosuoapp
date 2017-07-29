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
$thisProg = 'ShowGroupNodeList.php?groupType=' . $_GET['groupType'] . '&groupId=' . $_GET['groupId'];
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

$EnableQCoreClass->replace('thisNodeURL', $thisProg);
$EnableQCoreClass->replace('thisURL', 'ShowGroupUserList.php?' . $thisURLString);
$EnableQCoreClass->replace('addURL', $thisProg . '&Action=Add');
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

	$EnableQCoreClass->replace('topURL', 'ShowGroupNodeList.php?groupType=' . $_GET['groupType'] . '&groupId=' . $Row['fatherId']);
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

if ($_POST['formAction'] == 'TranUsersSubmit') {
	if (is_array($_POST['userGroupID'])) {
		if ($_POST['tran_userGroupID'] == '0') {
			$absPath = '0';
		}
		else {
			$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_POST['tran_userGroupID'] . '\' ';
			$fRow = $DB->queryFirstRow($SQL);
			$absPath = $fRow['absPath'] . '-' . $_POST['tran_userGroupID'];
		}

		$tranUsersNum = 0;

		foreach ($_POST['userGroupID'] as $thisNodeId) {
			$hSQL = ' SELECT absPath,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID =\'' . $thisNodeId . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);
			$theOldAbsPath = $hRow['absPath'];
			$cSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName = \'' . trim($hRow['userGroupName']) . '\' AND fatherId=\'' . $_POST['tran_userGroupID'] . '\' LIMIT 0,1 ';
			$cRow = $DB->queryFirstRow($cSQL);

			if ($cRow) {
				continue;
			}

			$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET fatherId=\'' . $_POST['tran_userGroupID'] . '\',absPath=\'' . $absPath . '\' WHERE userGroupID =\'' . $thisNodeId . '\' ';
			$DB->query($SQL);
			$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET absPath=\'' . $absPath . '-' . $thisNodeId . '\' WHERE fatherId =\'' . $thisNodeId . '\' ';
			$DB->query($SQL);
			$theSonSQL = ' concat(\'-\',absPath,\'-\') LIKE \'%-' . $thisNodeId . '-%\' ';
			$SQL = ' SELECT userGroupID,absPath FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND fatherId !=\'' . $thisNodeId . '\' ORDER BY absPath ASC ';
			$Result = $DB->query($SQL);

			while ($Row = $DB->queryArray($Result)) {
				$theNewPath = str_replace($theOldAbsPath . '-', $absPath . '-', $Row['absPath']);
				$uSQL = ' UPDATE ' . USERGROUP_TABLE . ' SET absPath = \'' . $theNewPath . '\' WHERE userGroupID = \'' . $Row['userGroupID'] . '\' ';
				$DB->query($uSQL);
			}

			$tranUsersNum++;
		}

		if ($tranUsersNum != 0) {
			$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf =0 WHERE userGroupID = \'' . $_POST['tran_userGroupID'] . '\' ';
			$DB->query($SQL);
		}

		writetolog($lang['tran_group_list']);
	}

	_showtreemessage($lang['tran_group_list'], $userListBackURL);
}

if ($_POST['formAction'] == 'DeleUsersSubmit') {
	if (is_array($_POST['userGroupID'])) {
		foreach ($_POST['userGroupID'] as $thisNodeId) {
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $thisNodeId . '-%\' OR b.userGroupID = \'' . $thisNodeId . '\') ';
			$theSonGroup = array();
			$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' b WHERE ' . $theSonSQL . ' ';
			$Result = $DB->query($SQL);

			while ($Row = $DB->queryArray($Result)) {
				$theSonGroup[] = $Row['userGroupID'];
			}

			if (count($theSonGroup) != 0) {
				$dSQL = ' SELECT administratorsID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin !=0 AND userGroupID IN (' . implode(',', $theSonGroup) . ') ';
				$dResult = $DB->query($dSQL);
				$haveCreateTempGroup = false;

				while ($dRow = $DB->queryArray($dResult)) {
					$theDeleUserId = $dRow['administratorsID'];

					switch ($dRow['groupType']) {
					case '1':
						if ($haveCreateTempGroup == false) {
							$nSQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . date('ymdHis') . rand(10, 99) . '删除节点\',fatherId=0,groupType=1,createDate=\'' . time() . '\',absPath=\'0\',isLeaf=1 ';
							$DB->query($nSQL);
							$lastGroupID = $DB->_GetInsertID();
							$haveCreateTempGroup = true;
						}

						$uSQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET userGroupID =\'' . $lastGroupID . '\' WHERE administratorsID = \'' . $theDeleUserId . '\' ';
						$DB->query($uSQL);
						break;

					case '2':
						require 'User.dele.php';
						break;
					}
				}

				$dSQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE taskID IN (' . implode(',', $theSonGroup) . ') ';
				$DB->query($dSQL);
				$dSQL = ' DELETE FROM ' . USERGROUP_TABLE . ' WHERE userGroupID IN (' . implode(',', $theSonGroup) . ') ';
				$DB->query($dSQL);
			}

			$SQL = ' SELECT fatherId,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $thisNodeId . '\' ';
			$Row = $DB->queryFirstRow($SQL);
			$hSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['fatherId'] . '\' AND groupType =\'' . $Row['groupType'] . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 1 WHERE userGroupID = \'' . $Row['fatherId'] . '\' AND groupType =\'' . $Row['groupType'] . '\' ';
				$DB->query($SQL);
			}
		}

		require ROOT_PATH . 'Export/Database.opti.sql.php';
		writetolog($lang['dele_user_group']);
	}

	_showtreemessage($lang['dele_user_group'], $userListBackURL);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['userGroupID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		_showerror('系统错误', '系统错误：您选择删除的节点可能不存在或者已经被删除！');
	}

	$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_GET['userGroupID'] . '-%\' OR b.userGroupID = \'' . $_GET['userGroupID'] . '\') ';
	$theSonGroup = array();
	$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' b WHERE ' . $theSonSQL . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$theSonGroup[] = $Row['userGroupID'];
	}

	if (count($theSonGroup) != 0) {
		$dSQL = ' SELECT administratorsID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin !=0 AND userGroupID IN (' . implode(',', $theSonGroup) . ') ';
		$dResult = $DB->query($dSQL);
		$haveCreateTempGroup = false;

		while ($dRow = $DB->queryArray($dResult)) {
			$theDeleUserId = $dRow['administratorsID'];

			switch ($dRow['groupType']) {
			case '1':
				if ($haveCreateTempGroup == false) {
					$nSQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . date('ymdHis') . rand(10, 99) . '删除节点\',fatherId=0,groupType=1,createDate=\'' . time() . '\',absPath=\'0\',isLeaf=1 ';
					$DB->query($nSQL);
					$lastGroupID = $DB->_GetInsertID();
					$haveCreateTempGroup = true;
				}

				$uSQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET userGroupID =\'' . $lastGroupID . '\' WHERE administratorsID = \'' . $theDeleUserId . '\' ';
				$DB->query($uSQL);
				break;

			case '2':
				require 'User.dele.php';
				break;
			}
		}

		$dSQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE taskID IN (' . implode(',', $theSonGroup) . ') ';
		$DB->query($dSQL);
		$dSQL = ' DELETE FROM ' . USERGROUP_TABLE . ' WHERE userGroupID IN (' . implode(',', $theSonGroup) . ') ';
		$DB->query($dSQL);
	}

	$SQL = ' SELECT fatherId,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['userGroupID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$hSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['fatherId'] . '\' AND groupType =\'' . $Row['groupType'] . '\' ';
	$hRow = $DB->queryFirstRow($hSQL);

	if (!$hRow) {
		$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 1 WHERE userGroupID = \'' . $Row['fatherId'] . '\' AND groupType =\'' . $Row['groupType'] . '\' ';
		$DB->query($SQL);
	}

	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['dele_user_group'] . ':' . $_GET['userGroupName']);
	_showtreemessage($lang['dele_user_group'], $userListBackURL);
}

if ($_POST['Action'] == 'UserAddSubmit') {
	$SQL = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupName=\'' . trim($_POST['userGroupName']) . '\' AND fatherId=\'' . $_POST['groupId'] . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['noename_is_exist']);
	}

	if ($_POST['groupId'] == '0') {
		$absPath = '0';
	}
	else {
		$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_POST['groupId'] . '\' ';
		$fRow = $DB->queryFirstRow($SQL);
		$absPath = $fRow['absPath'] . '-' . $_POST['groupId'];
	}

	$SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . trim($_POST['userGroupName']) . '\',fatherId=\'' . $_POST['groupId'] . '\',groupType=\'' . $_POST['groupType'] . '\',createDate=\'' . time() . '\',userGroupDesc=\'' . trim($_POST['userGroupDesc']) . '\',userGroupLabel=\'' . trim($_POST['userGroupLabel']) . '\',absPath =\'' . $absPath . '\',isLeaf=1 ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 0 WHERE userGroupID = \'' . $_POST['groupId'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['add_user_group'] . ':' . $_POST['userGroupName']);
	_showmessage($lang['add_user_group'] . ':' . $_POST['userGroupName'], true);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('UsersAddFile', 'GroupNodesEdit.html');
	$EnableQCoreClass->replace('userGroupName', '');
	$EnableQCoreClass->replace('userGroupDesc', '');
	$EnableQCoreClass->replace('userGroupID', '');
	$EnableQCoreClass->replace('userGroupLabel', '');
	$EnableQCoreClass->replace('groupId', $_GET['groupId']);
	$EnableQCoreClass->replace('groupType', $_GET['groupType']);
	$EnableQCoreClass->replace('Action', 'UserAddSubmit');
	$EnableQCoreClass->parse('UsersAdd', 'UsersAddFile');
	$EnableQCoreClass->output('UsersAdd');
}

if ($_POST['Action'] == 'UserEditSubmit') {
	$SQL = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupName=\'' . trim($_POST['userGroupName']) . '\' AND userGroupID !=\'' . $_POST['userGroupID'] . '\' AND fatherId=\'' . $_POST['groupId'] . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['noename_is_exist']);
	}

	$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET userGroupName=\'' . trim($_POST['userGroupName']) . '\',userGroupDesc=\'' . trim($_POST['userGroupDesc']) . '\',userGroupLabel=\'' . trim($_POST['userGroupLabel']) . '\' WHERE userGroupID =\'' . $_POST['userGroupID'] . '\'';
	$DB->query($SQL);
	writetolog($lang['edit_user_group'] . ':' . $_POST['userGroupName']);
	_showmessage($lang['edit_user_group'] . ':' . $_POST['userGroupName'], true);
}

if ($_GET['Action'] == 'Edit') {
	$EnableQCoreClass->setTemplateFile('UsersAddFile', 'GroupNodesEdit.html');
	$SQL = ' SELECT * FROM ' . USERGROUP_TABLE . ' WHERE userGroupID=\'' . $_GET['userGroupID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		_showerror('系统错误', '系统错误：您选择编辑的节点可能不存在或者已经被删除！');
	}

	$EnableQCoreClass->replace('userGroupName', $Row['userGroupName']);
	$EnableQCoreClass->replace('userGroupDesc', $Row['userGroupDesc']);
	$EnableQCoreClass->replace('userGroupLabel', $Row['userGroupLabel']);
	$EnableQCoreClass->replace('userGroupID', $Row['userGroupID']);
	$EnableQCoreClass->replace('groupId', $Row['fatherId']);
	$EnableQCoreClass->replace('groupType', $_GET['groupType']);
	$EnableQCoreClass->replace('Action', 'UserEditSubmit');
	$EnableQCoreClass->parse('UsersAdd', 'UsersAddFile');
	$EnableQCoreClass->output('UsersAdd');
}

$theSonSQL = ' concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['groupId'] . '-%\' ';
$SQL = ' SELECT * FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ';

switch ($_GET['groupType']) {
case 1:
	$EnableQCoreClass->setTemplateFile('UserListFile', 'GroupNodesList.html');
	break;

case 2:
	$EnableQCoreClass->setTemplateFile('UserListFile', 'GroupCustNodesList.html');
	$lSQL = ' SELECT DISTINCT userGroupLabel FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ORDER BY absPath ASC ';
	$lResult = $DB->query($lSQL);
	$userGroupLabel_list = '';

	while ($lRow = $DB->queryArray($lResult)) {
		$t_userGroupLabel = ($lRow['userGroupLabel'] == '' ? '未归类' : $lRow['userGroupLabel']);
		$userGroupLabel_list .= '<option value="' . $lRow['userGroupLabel'] . '">' . $t_userGroupLabel . '</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('userGroupLabel_list', $userGroupLabel_list);
	break;
}

$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');
$EnableQCoreClass->replace('user', '');
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	$name = trim($_POST['name']);
	$SQL .= ' AND userGroupName LIKE \'%' . $name . '%\' ';
	$page_others = '&name=' . urlencode($name);
	$EnableQCoreClass->replace('name', $name);

	if ($_POST['t_userGroupLabel'] != 'all') {
		$SQL .= ' AND userGroupLabel = \'' . $_POST['t_userGroupLabel'] . '\' ';
		$lSQL = ' SELECT DISTINCT userGroupLabel FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ORDER BY absPath ASC ';
		$lResult = $DB->query($lSQL);
		$userGroupLabel_list = '';

		while ($lRow = $DB->queryArray($lResult)) {
			$t_userGroupLabel = ($lRow['userGroupLabel'] == '' ? '未归类' : $lRow['userGroupLabel']);

			if ($lRow['userGroupLabel'] == $_POST['t_userGroupLabel']) {
				$userGroupLabel_list .= '<option value="' . $lRow['userGroupLabel'] . '" selected>' . $t_userGroupLabel . '</option>' . "\n" . '';
			}
			else {
				$userGroupLabel_list .= '<option value="' . $lRow['userGroupLabel'] . '">' . $t_userGroupLabel . '</option>' . "\n" . '';
			}
		}

		$EnableQCoreClass->replace('userGroupLabel_list', $userGroupLabel_list);
		$page_others .= '&t_userGroupLabel=' . $_POST['t_userGroupLabel'];
	}
}
else {
	$EnableQCoreClass->replace('name', '');
}

if (isset($_GET['name']) && !$_POST['Action']) {
	$name = trim($_GET['name']);
	$SQL .= ' AND userGroupName LIKE \'%' . $name . '%\' ';
	$page_others .= '&name=' . $name;
	$EnableQCoreClass->replace('name', $name);
}

if (isset($_GET['t_userGroupLabel']) && ($_GET['t_userGroupLabel'] != 'all') && !$_POST['Action']) {
	$SQL .= ' AND userGroupLabel = \'' . $_GET['t_userGroupLabel'] . '\' ';
	$lSQL = ' SELECT DISTINCT userGroupLabel FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND ' . $theSonSQL . ' ORDER BY absPath ASC ';
	$lResult = $DB->query($lSQL);
	$userGroupLabel_list = '';

	while ($lRow = $DB->queryArray($lResult)) {
		$t_userGroupLabel = ($lRow['userGroupLabel'] == '' ? '未归类' : $lRow['userGroupLabel']);

		if ($lRow['userGroupLabel'] == $_GET['t_userGroupLabel']) {
			$userGroupLabel_list .= '<option value="' . $lRow['userGroupLabel'] . '" selected>' . $t_userGroupLabel . '</option>' . "\n" . '';
		}
		else {
			$userGroupLabel_list .= '<option value="' . $lRow['userGroupLabel'] . '">' . $t_userGroupLabel . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('userGroupLabel_list', $userGroupLabel_list);
	$page_others .= '&t_userGroupLabel=' . $_GET['t_userGroupLabel'];
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

$SQL .= ' ORDER BY absPath ASC,userGroupID ASC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);
$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$userListBackURL = $thisProg . '&pageID=' . $pageID . $page_others;
$_SESSION['userListBackURL'] = $userListBackURL;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('userGroupID', $Row['userGroupID']);
	$EnableQCoreClass->replace('userGroupLabel', $Row['userGroupLabel']);

	if ($Row['userGroupID'] != 0) {
		$userAllGroupName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $Row['groupType']);
	}
	else {
		$userAllGroupName = getusergroupname($Row['userGroupID'], $Row['groupType']);
	}

	$EnableQCoreClass->replace('userAllGroupName', $userAllGroupName);
	$EnableQCoreClass->replace('userGroupName', cnsubstr($userAllGroupName, 0, 28, 1));
	$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' ) ';
	$hSQL = ' SELECT COUNT(*) FROM ' . USERGROUP_TABLE . ' b WHERE ' . $theSonSQL . ' ';
	$hRow = $DB->queryFirstRow($hSQL);
	$EnableQCoreClass->replace('userGroupNodeNum', $hRow[0]);
	$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';
	$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin !=0 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);
	$EnableQCoreClass->replace('userGroupUserNum', $hRow[0]);
	$EnableQCoreClass->replace('viewURL', 'ShowGroupNodeList.php?groupType=' . $_GET['groupType'] . '&groupId=' . $Row['userGroupID']);
	$EnableQCoreClass->replace('editURL', $thisProg . '&Action=Edit&userGroupID=' . $Row['userGroupID'] . '&userGroupName=' . urlencode($Row['userGroupName']));
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&Action=Delete&userGroupID=' . $Row['userGroupID'] . '&userGroupName=' . urlencode($Row['userGroupName']));
	$EnableQCoreClass->parse('user', 'USER', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('UserList', 'UserListFile');
$EnableQCoreClass->output('UserList');

?>

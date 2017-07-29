<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|5|6');
header('Content-Type:text/html; charset=gbk');
$_GET['groupId'] = (int) $_GET['groupId'];
$_GET['groupType'] = (int) $_GET['groupType'];
$_GET['isAdmin'] = (int) $_GET['isAdmin'];
$_GET['isInit'] = (int) $_GET['isInit'];
$thisProg = 'AjaxUserGroup.php?groupType=' . $_GET['groupType'] . '&groupId=' . $_GET['groupId'] . '&t_name=' . $_GET['t_name'] . '&isAdmin=' . $_GET['isAdmin'] . '&isInit=' . $_GET['isInit'];

switch ($_SESSION['adminRoleType']) {
case 1:
case 6:
	$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' ORDER BY absPath ASC,userGroupID ASC  ';
	break;

case 5:
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
	$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' AND ' . $theSonSQL . ' ORDER BY absPath ASC,userGroupID ASC  ';
	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);

if (50 <= $recordCount) {
	exit('false######匹配的节点>=50,请重新输入关键字以缩小检索规模');
}

$usersList = '<select name="userGroupID" id="userGroupID" style="width:375px;*width:385px">\\n';

switch ($_GET['groupType']) {
case 1:
	$nodeName = $lang['corp_root_node'];
	break;

case 2:
	$nodeName = $lang['cus_root_node'];
	break;
}

switch ($_SESSION['adminRoleType']) {
case 1:
case 6:
	$usersList .= '<option value=\'0\'>' . $nodeName . '</option>' . "\n" . '';
	break;

case 5:
	if ($_SESSION['adminRoleGroupID'] == 0) {
		$usersList .= '<option value=\'0\'>' . $nodeName . '</option>' . "\n" . '';
	}
	else {
		$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\' ';
		$gRow = $DB->queryFirstRow($gSQL);
		$nodesName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
		$usersList .= '<option value=\'' . $_SESSION['adminRoleGroupID'] . '\'>' . $nodesName . '</option>' . "\n" . '';
	}

	break;
}

while ($Row = $DB->queryArray($Result)) {
	$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $_GET['groupType']);

	switch ($_SESSION['adminRoleType']) {
	case 1:
	case 6:
		if ($_GET['groupId'] == $Row['userGroupID']) {
			$usersList .= '<option value=\'' . $Row['userGroupID'] . '\' selected>' . $nodesName . '</option>\\n';
		}
		else {
			$usersList .= '<option value=\'' . $Row['userGroupID'] . '\'>' . $nodesName . '</option>\\n';
		}

		break;

	case 5:
		if ($Row['userGroupID'] != $_SESSION['adminRoleGroupID']) {
			if ($_GET['groupId'] == $Row['userGroupID']) {
				$usersList .= '<option value=\'' . $Row['userGroupID'] . '\' selected>' . $nodesName . '</option>\\n';
			}
			else {
				$usersList .= '<option value=\'' . $Row['userGroupID'] . '\'>' . $nodesName . '</option>\\n';
			}
		}

		break;
	}
}

$usersList .= '</select>';
$roleArray = array(1 => $lang['isAdmin_1'], 6 => $lang['isAdmin_6'], 5 => $lang['isAdmin_5'], 2 => $lang['isAdmin_2'], 4 => $lang['isAdmin_4'], 7 => $lang['isAdmin_7'], 3 => $lang['isAdmin_3']);
if (isset($_GET['isInit']) && ($_GET['isInit'] == 1)) {
	$admin_role_list = '<SELECT name="isAdmin" id="isAdmin" disabled><OPTION value="">请选择...</OPTION>';
}
else {
	$admin_role_list = '<SELECT name="isAdmin" id="isAdmin"><OPTION value="">请选择...</OPTION>';
}

switch ($_SESSION['adminRoleType']) {
case 1:
	if ($_GET['groupType'] == 2) {
		$admin_role_list .= '<option value=\'3\' selected>' . $lang['isAdmin_3'] . '</option>';
	}
	else {
		foreach ($roleArray as $adminRoleId => $adminRoleName) {
			if ($adminRoleId == $_GET['isAdmin']) {
				$admin_role_list .= '<option value=\'' . $adminRoleId . '\' selected>' . $adminRoleName . '</option>';
			}
			else {
				$admin_role_list .= '<option value=\'' . $adminRoleId . '\'>' . $adminRoleName . '</option>';
			}
		}
	}

	break;

case 6:
	if ($_GET['groupType'] == 2) {
		$admin_role_list .= '<option value=\'3\' selected>' . $lang['isAdmin_3'] . '</option>';
	}
	else {
		foreach ($roleArray as $adminRoleId => $adminRoleName) {
			if (($adminRoleId != 1) && ($adminRoleId != 6)) {
				if ($adminRoleId == $_GET['isAdmin']) {
					$admin_role_list .= '<option value=\'' . $adminRoleId . '\' selected>' . $adminRoleName . '</option>';
				}
				else {
					$admin_role_list .= '<option value=\'' . $adminRoleId . '\'>' . $adminRoleName . '</option>';
				}
			}
		}
	}

	break;

case 5:
	if ($_GET['groupType'] == 2) {
		$admin_role_list .= '<option value=\'3\' selected>' . $lang['isAdmin_3'] . '</option>';
	}
	else {
		foreach ($roleArray as $adminRoleId => $adminRoleName) {
			if (($adminRoleId != 1) && ($adminRoleId != 6)) {
				if ($adminRoleId == $_GET['isAdmin']) {
					$admin_role_list .= '<option value=\'' . $adminRoleId . '\' selected>' . $adminRoleName . '</option>';
				}
				else {
					$admin_role_list .= '<option value=\'' . $adminRoleId . '\'>' . $adminRoleName . '</option>';
				}
			}
		}
	}

	break;
}

$admin_role_list .= '</select>';
echo 'true######' . $admin_role_list . '######' . $usersList;

?>

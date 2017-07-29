<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
$thisProg = 'ShowUserList.php?';
$ConfigRow['topicNum'] = 10;
_checkroletype('1|5|6');
if ($_SESSION['userListBackURL'] != '') {
	$userListBackURL = $_SESSION['userListBackURL'];
	$EnableQCoreClass->replace('userListURL', $_SESSION['userListBackURL']);
}
else {
	$userListBackURL = $thisProg;
	$EnableQCoreClass->replace('userListURL', $thisProg);
}





$EnableQCoreClass->replace('thisURL', $thisProg);
$EnableQCoreClass->replace('addURL', $thisProg . '&groupType=1&groupId=0&Action=Add');
$EnableQCoreClass->replace('importURL', $thisProg . '&Action=Import');
$EnableQCoreClass->replace('importNodesURL', $thisProg . '&Action=ImportNodes');
$EnableQCoreClass->replace('rootNodeName', '');






include_once ROOT_PATH . 'System/ShowGroupUserList.inc.php';
$EnableQCoreClass->setTemplateFile('UserListFile', 'AdministratorsList.html');


$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');



$EnableQCoreClass->replace('user', '');
$EnableQCoreClass->replace('isAdminRole', $_SESSION['adminRoleType']);

switch ($_SESSION['adminRoleType']) {
case 1:
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin !=0 ';
	$EnableQCoreClass->replace('isAdmin5', '');
	break;

case 6:
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin NOT IN (0,1,6) ';
	$EnableQCoreClass->replace('isAdmin5', '');
	break;

case 5:
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\') ';
	$theSonGroup = array();
	$theSonGroup[] = $_SESSION['adminRoleGroupID'];
	$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
	$sResult = $DB->query($sSQL);

	while ($sRow = $DB->queryArray($sResult)) {
		$theSonGroup[] = $sRow['userGroupID'];
	}

	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . '  WHERE userGroupID IN (' . implode(',', $theSonGroup) . ') AND isAdmin NOT IN (0,1,6) ';
	$EnableQCoreClass->replace('isAdmin5', 'none');
	break;
}

$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);




if ($_POST['Action'] == 'querySubmit') {
	if ($_POST['isAdmin'] != 0) {
		$SQL .= ' AND isAdmin = \'' . $_POST['isAdmin'] . '\' ';
		$page_others = '&isAdmin=' . $_POST['isAdmin'];
		$Option = 'option_' . $_POST['isAdmin'];
		$EnableQCoreClass->replace($Option, 'selected');
	}

	$name = trim($_POST['name']);
	$SQL .= ' AND administratorsName LIKE \'%' . $name . '%\' ';
	$page_others .= '&name=' . urlencode($name);
	$EnableQCoreClass->replace('name', $name);
}
else {
	$EnableQCoreClass->replace('name', '');
}

if (isset($_GET['isAdmin']) && !$_POST['Action'] && ($_GET['isAdmin'] != 0)) {
	$SQL .= ' AND isAdmin = \'' . $_GET['isAdmin'] . '\' ';
	$page_others .= '&isAdmin=' . $_GET['isAdmin'];
	$Option = 'option_' . $_GET['isAdmin'];
	$EnableQCoreClass->replace($Option, 'selected');
}

if (isset($_GET['name']) && !$_POST['Action']) {
	$name = trim($_GET['name']);
	$SQL .= ' AND administratorsName LIKE \'%' . $name . '%\' ';
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

$SQL .= ' ORDER BY administratorsID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);
$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$userListBackURL = $thisProg . '&pageID=' . $pageID . $page_others;
$_SESSION['userListBackURL'] = $userListBackURL;



// echo 2;
// var_dump($EnableQCoreClass->varvals);
// // var_dump($EnableQCoreClass->varkeys);
// echo "<hr>";





while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('administratorsID', $Row['administratorsID']);
	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('roleName', $lang['isAdmin_' . $Row['isAdmin']]);
	$EnableQCoreClass->replace('isActive', 123);

	switch ($Row['groupType']) {
	case 1:
		$typeName = $lang['corp_root_node'];
		break;

	case 2:
		$typeName = $lang['cus_root_node'];
		break;
	}

	$EnableQCoreClass->replace('typeName', $typeName);
	$EnableQCoreClass->replace('groupType', $Row['groupType']);
	$EnableQCoreClass->replace('groupId', $Row['userGroupID']);

	if ($Row['userGroupID'] != 0) {
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

	$EnableQCoreClass->replace('editURL', $thisProg . '&Action=Edit&groupType=' . $Row['groupType'] . '&groupId=' . $Row['userGroupID'] . '&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));

	$EnableQCoreClass->parse('user', 'USER', true);
	// echo  "aa";
	// echo "<pre>";
	// unset($EnableQCoreClass->varvals['UserListFile']);
	// var_dump($EnableQCoreClass->varvals);

}



// echo "<pre>";
// echo "abnnndkahldhglksdah";
// var_dump($EnableQCoreClass->varvals);
// // var_dump($EnableQCoreClass->varkeys);
// echo "<hr>";

// die;



include_once ROOT_PATH . 'Includes/Pages.class.php';

$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);


// echo 444;
// echo "<pre>";

// var_dump($EnableQCoreClass->varvals);
// // var_dump($EnableQCoreClass->varkeys);
// echo "<hr style='color:red'>";

//输出  用户信息

// echo "<hr>11";
// var_dump($EnableQCoreClass->varvals['UserList']);
// // var_dump($EnableQCoreClass->varkeys);


// echo "<hr style='color:red'>";
$EnableQCoreClass->parse('UserList', 'UserListFile');
// echo "<hr>";
// var_dump($EnableQCoreClass->varvals['UserList']);
// // var_dump($EnableQCoreClass->varkeys);


// echo "<hr style='color:red'>";die;

$EnableQCoreClass->output('UserList');die;
?>

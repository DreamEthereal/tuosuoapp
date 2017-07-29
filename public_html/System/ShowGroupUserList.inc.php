<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';

if ($_POST['Action'] == 'ImportSubmit') {
	$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if (!is_dir($File_DIR_Name)) {
		mkdir($File_DIR_Name, 511);
	}

	$tmpExt = explode('.', $_FILES['csvFile']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$newFileName = 'CSV_' . date('YmdHis', time()) . rand(1, 999) . '.csv';
	$newFullName = $File_DIR_Name . $newFileName;
	if (is_uploaded_file($_FILES['csvFile']['tmp_name']) && ($extension == 'csv')) {
		copy($_FILES['csvFile']['tmp_name'], $newFullName);
	}
	else {
		_showerror($lang['error_system'], $lang['csv_file_type_error']);
	}

	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newFullName, 'r');
	$recNum = 0;
	$csvLineNum = 0;

	while ($csvData = _fgetcsv($File)) {
		if ($csvLineNum != 0) {
			$csvData = qaddslashes($csvData, 1);
			$administrators_Name = trim($csvData[0]);
			$nick_Name = trim($csvData[1]);
			$passWord = trim($csvData[2]);
			$userGroupName = trim($csvData[3]);
			$administratorsRole = trim($csvData[4]);
			$userGroupDesc = trim($csvData[5]);
			$userGroupLabel = trim($csvData[6]);
			$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . $administrators_Name . '\' AND isAdmin != 0 LIMIT 0,1 ';
			$Row = $DB->queryFirstRow($SQL);
			if (($administrators_Name == '') || $Row) {
				continue;
			}

			if ($passWord == '') {
				$passWord = md5('12345678');
			}
			else {
				$passWord = md5($passWord);
			}

			switch ($_SESSION['adminRoleType']) {
			case '1':
				switch ($administratorsRole) {
				case 1:
					$isAdmin = 1;
					break;

				case 2:
					$isAdmin = 5;
					break;

				case 3:
					$isAdmin = 2;
					break;

				case 4:
				default:
					$isAdmin = 3;
					break;

				case 5:
					$isAdmin = 4;
					break;

				case 6:
					$isAdmin = 6;
					break;

				case 7:
					$isAdmin = 7;
					break;
				}

				break;

			case '6':
				switch ($administratorsRole) {
				case 2:
					$isAdmin = 5;
					break;

				case 3:
					$isAdmin = 2;
					break;

				case 4:
				default:
					$isAdmin = 3;
					break;

				case 5:
					$isAdmin = 4;
					break;

				case 7:
					$isAdmin = 7;
					break;
				}

				break;
			}

			if ($userGroupName != '') {
				$theGroupArray = explode(chr(92) . chr(92), $userGroupName);

				switch ($theGroupArray[0]) {
				case $lang['corp_root_node']:
				default:
					$groupType = 1;
					break;

				case $lang['cus_root_node']:
					$groupType = 2;
					break;
				}

				$theFatherArray = array();
				$theFatherArray[1] = 0;
				$thePathLength = count($theGroupArray);
				$tmp = 1;

				for (; $tmp < $thePathLength; $tmp++) {
					$theGroupName = $theGroupArray[$tmp];
					$theSonOrderNo = $tmp + 1;

					if (trim($theGroupName) == '') {
						$userGroupID = $theFatherArray[$tmp];
						break;
					}
					else if ($tmp == $thePathLength - 1) {
						$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName=\'' . $theGroupName . '\' AND groupType=\'' . $groupType . '\'  AND fatherId=\'' . $theFatherArray[$tmp] . '\' LIMIT 0,1 ';
						$GroupRow = $DB->queryFirstRow($SQL);

						if (!$GroupRow) {
							if ($theFatherArray[$tmp] == '0') {
								$absPath = '0';
							}
							else {
								$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
								$fRow = $DB->queryFirstRow($SQL);
								$absPath = $fRow['absPath'] . '-' . $theFatherArray[$tmp];
							}

							$SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . $theGroupName . '\',createDate=\'' . time() . '\',fatherId=\'' . $theFatherArray[$tmp] . '\',groupType=\'' . $groupType . '\',absPath =\'' . $absPath . '\',isLeaf=1 ';

							if ($groupType == 2) {
								$SQL .= ' ,userGroupDesc =\'' . $userGroupDesc . '\',userGroupLabel =\'' . $userGroupLabel . '\' ';
							}

							$DB->query($SQL);
							$userGroupID = $DB->_GetInsertID();
							$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 0 WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
							$DB->query($SQL);
						}
						else {
							$userGroupID = $GroupRow['userGroupID'];
						}
					}
					else {
						$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName=\'' . $theGroupName . '\' AND groupType=\'' . $groupType . '\' AND fatherId=\'' . $theFatherArray[$tmp] . '\' LIMIT 0,1 ';
						$GroupRow = $DB->queryFirstRow($SQL);

						if (!$GroupRow) {
							if ($theFatherArray[$tmp] == '0') {
								$absPath = '0';
							}
							else {
								$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
								$fRow = $DB->queryFirstRow($SQL);
								$absPath = $fRow['absPath'] . '-' . $theFatherArray[$tmp];
							}

							$SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . $theGroupName . '\',createDate=\'' . time() . '\',fatherId=\'' . $theFatherArray[$tmp] . '\',groupType=\'' . $groupType . '\',absPath =\'' . $absPath . '\',isLeaf=1 ';

							if ($groupType == 2) {
								$SQL .= ' ,userGroupDesc =\'' . $userGroupDesc . '\',userGroupLabel =\'' . $userGroupLabel . '\' ';
							}

							$DB->query($SQL);
							$theFatherArray[$theSonOrderNo] = $DB->_GetInsertID();
							$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 0 WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
							$DB->query($SQL);
						}
						else {
							$theFatherArray[$theSonOrderNo] = $GroupRow['userGroupID'];
						}
					}
				}
			}
			else {
				$groupType = 1;
				$userGroupID = 0;
			}

			$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . $administrators_Name . '\',nickName=\'' . $nick_Name . '\',passWord=\'' . $passWord . '\',ipAddress=\'' . _getip() . '\',createDate=\'' . time() . '\',isAdmin=\'' . $isAdmin . '\',byUserID=\'' . $_SESSION['administratorsID'] . '\',userGroupID=\'' . $userGroupID . '\',groupType=\'' . $groupType . '\' ';
			$DB->query($SQL);
			$recNum++;
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	writetolog($lang['new_import'] . $recNum . $lang['import_num']);
	_showmessage($lang['new_import'] . $recNum . $lang['import_num'], true);
}

if ($_POST['Action'] == 'ImportNodesSubmit') {
	$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if (!is_dir($File_DIR_Name)) {
		mkdir($File_DIR_Name, 511);
	}

	$tmpExt = explode('.', $_FILES['csvFile']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$newFileName = 'CSV_' . date('YmdHis', time()) . rand(1, 999) . '.csv';
	$newFullName = $File_DIR_Name . $newFileName;
	if (is_uploaded_file($_FILES['csvFile']['tmp_name']) && ($extension == 'csv')) {
		copy($_FILES['csvFile']['tmp_name'], $newFullName);
	}
	else {
		_showerror($lang['error_system'], $lang['csv_file_type_error']);
	}

	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newFullName, 'r');
	$recNum = 0;
	$csvLineNum = 0;

	while ($csvData = _fgetcsv($File)) {
		if ($csvLineNum != 0) {
			$csvData = qaddslashes($csvData, 1);
			$userGroupName = trim($csvData[0]);
			$userGroupDesc = trim($csvData[1]);
			$userGroupLabel = trim($csvData[2]);

			if ($userGroupName != '') {
				$theGroupArray = explode(chr(92) . chr(92), $userGroupName);

				switch ($theGroupArray[0]) {
				case $lang['corp_root_node']:
				default:
					$groupType = 1;
					break;

				case $lang['cus_root_node']:
					$groupType = 2;
					break;
				}

				$theFatherArray = array();
				$theFatherArray[1] = 0;
				$thePathLength = count($theGroupArray);
				$tmp = 1;

				for (; $tmp < $thePathLength; $tmp++) {
					$theGroupName = $theGroupArray[$tmp];
					$theSonOrderNo = $tmp + 1;

					if (trim($theGroupName) == '') {
						break;
					}
					else if ($tmp == $thePathLength - 1) {
						$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName=\'' . $theGroupName . '\' AND groupType=\'' . $groupType . '\'  AND fatherId=\'' . $theFatherArray[$tmp] . '\' LIMIT 0,1 ';
						$GroupRow = $DB->queryFirstRow($SQL);

						if (!$GroupRow) {
							if ($theFatherArray[$tmp] == '0') {
								$absPath = '0';
							}
							else {
								$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
								$fRow = $DB->queryFirstRow($SQL);
								$absPath = $fRow['absPath'] . '-' . $theFatherArray[$tmp];
							}

							$SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . $theGroupName . '\',createDate=\'' . time() . '\',fatherId=\'' . $theFatherArray[$tmp] . '\',groupType=\'' . $groupType . '\',absPath =\'' . $absPath . '\',isLeaf=1 ';

							if ($groupType == 2) {
								$SQL .= ' ,userGroupDesc =\'' . $userGroupDesc . '\',userGroupLabel =\'' . $userGroupLabel . '\' ';
							}

							$DB->query($SQL);
							$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 0 WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
					else {
						$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName=\'' . $theGroupName . '\' AND groupType=\'' . $groupType . '\' AND fatherId=\'' . $theFatherArray[$tmp] . '\' LIMIT 0,1 ';
						$GroupRow = $DB->queryFirstRow($SQL);

						if (!$GroupRow) {
							if ($theFatherArray[$tmp] == '0') {
								$absPath = '0';
							}
							else {
								$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
								$fRow = $DB->queryFirstRow($SQL);
								$absPath = $fRow['absPath'] . '-' . $theFatherArray[$tmp];
							}

							$SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET userGroupName=\'' . $theGroupName . '\',createDate=\'' . time() . '\',fatherId=\'' . $theFatherArray[$tmp] . '\',groupType=\'' . $groupType . '\',absPath =\'' . $absPath . '\',isLeaf=1 ';

							if ($groupType == 2) {
								$SQL .= ' ,userGroupDesc =\'' . $userGroupDesc . '\',userGroupLabel =\'' . $userGroupLabel . '\' ';
							}

							$DB->query($SQL);
							$theFatherArray[$theSonOrderNo] = $DB->_GetInsertID();
							$SQL = ' UPDATE ' . USERGROUP_TABLE . ' SET isLeaf = 0 WHERE userGroupID = \'' . $theFatherArray[$tmp] . '\' ';
							$DB->query($SQL);
						}
						else {
							$theFatherArray[$theSonOrderNo] = $GroupRow['userGroupID'];
						}
					}
				}
			}
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	writetolog($lang['new_import'] . $recNum . $lang['import_num']);
	_showmessage($lang['new_import'] . $recNum . $lang['import_num'], true);
}

if ($_GET['Action'] == 'Import') {
	_checkroletype('1|6');
	$EnableQCoreClass->setTemplateFile('UsersImportFile', 'AdministratorsImport.html');
	$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
	$EnableQCoreClass->output('UsersImport');
}

if ($_GET['Action'] == 'ImportNodes') {
	_checkroletype('1|6');
	$EnableQCoreClass->setTemplateFile('UsersImportFile', 'AdminNodesImport.html');
	$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
	$EnableQCoreClass->output('UsersImport');
}

if ($_POST['formAction'] == 'TranUsersSubmit') {
	if (is_array($_POST['administratorsID'])) {
		$administratorsIDLists = join(',', $_POST['administratorsID']);
		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET userGroupID=\'' . $_POST['tran_userGroupID'] . '\' WHERE (administratorsID IN (' . $administratorsIDLists . '))';
		$DB->query($SQL);
		writetolog($lang['tran_user_list']);
	}

	_showtreemessage($lang['tran_user_list'], $userListBackURL);
}

if ($_POST['formAction'] == 'DeleUsersSubmit') {
	if (is_array($_POST['administratorsID'])) {
		foreach ($_POST['administratorsID'] as $theDeleUserId) {
			require 'User.dele.php';
		}

		require ROOT_PATH . 'Export/Database.opti.sql.php';
		writetolog($lang['system_user_delete']);
	}

	_showtreemessage($lang['system_user_delete'], $userListBackURL);
}

if ($_GET['Action'] == 'Delete') {
	$theDeleUserId = $_GET['administratorsID'];
	require 'User.dele.php';
	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['system_user_delete'] . ':' . $_GET['administratorsName']);
	_showmessage($lang['system_user_delete'], true);
}

if ($_GET['Action'] == 'Close') {
	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive=0 WHERE administratorsID=\'' . $_GET['administratorsID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['close_user'] . ':' . $_GET['administratorsName']);
	_showsucceed($lang['close_user'], $userListBackURL);
}

if ($_GET['Action'] == 'Active') {
	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive=1 WHERE administratorsID=\'' . $_GET['administratorsID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['open_user'] . ':' . $_GET['administratorsName']);
	_showsucceed($lang['open_user'], $userListBackURL);
}

if ($_POST['Action'] == 'UserAddSubmit') {
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_POST['administratorsName']) . '\' AND isAdmin != 0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['username_is_exist']);
	}

	$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . trim($_POST['administratorsName']) . '\',nickName=\'' . $_POST['nickName'] . '\',passWord=\'' . md5(trim($_POST['passWord'])) . '\',ipAddress=\'' . _getip() . '\',createDate=\'' . time() . '\',byUserID=\'' . $_SESSION['administratorsID'] . '\',userGroupID=\'' . $_POST['userGroupID'] . '\',groupType=\'' . $_POST['groupType'] . '\' ';

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$SQL .= ',isAdmin=\'' . $_POST['isAdmin'] . '\' ';
		break;

	case '5':
		switch ($_POST['isAdmin']) {
		case '1':
		case '6':
			$n_isAdmin = '3';
			break;

		default:
			$n_isAdmin = $_POST['isAdmin'];
			break;
		}

		$SQL .= ',isAdmin=\'' . $n_isAdmin . '\' ';
		break;

	case '6':
		switch ($_POST['isAdmin']) {
		case '1':
		case '6':
			$n_isAdmin = '3';
			break;

		default:
			$n_isAdmin = $_POST['isAdmin'];
			break;
		}

		$SQL .= ',isAdmin=\'' . $n_isAdmin . '\' ';
		break;
	}

	$DB->query($SQL);
	writetolog($lang['add_system_user'] . ':' . $_POST['administratorsName']);
	_showmessage($lang['add_system_user'] . ':' . $_POST['administratorsName'], true);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('UsersAddFile', 'AdministratorsAdd.html');
	$EnableQCoreClass->replace('administratorsID', '');
	$EnableQCoreClass->replace('administratorsName', '');
	$EnableQCoreClass->replace('nickName', '');
	$EnableQCoreClass->replace('password_notes', '');
	$EnableQCoreClass->replace('users_group_list', '');
	$EnableQCoreClass->replace('groupType_' . $_GET['groupType'], 'checked');
	$EnableQCoreClass->replace('groupType', $_GET['groupType']);
	$EnableQCoreClass->replace('groupId', $_GET['groupId']);
	$EnableQCoreClass->replace('Init', '');

	if ($_SESSION['adminRoleType'] == 5) {
		$EnableQCoreClass->replace('groupType_2', 'disabled');
	}

	$EnableQCoreClass->replace('userAdminType', $_SESSION['adminRoleType']);
	$EnableQCoreClass->replace('userGroupType', $_GET['groupType']);
	$EnableQCoreClass->replace('Action', 'UserAddSubmit');
	$EnableQCoreClass->parse('UsersAdd', 'UsersAddFile');
	$EnableQCoreClass->output('UsersAdd');
}

if ($_POST['Action'] == 'UserEditSubmit') {
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_POST['administratorsName']) . '\' AND administratorsName != \'' . trim($_POST['ori_administratorsName']) . '\' AND isAdmin != 0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['username_is_exist']);
	}

	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . trim($_POST['administratorsName']) . '\',nickName=\'' . $_POST['nickName'] . '\',userGroupID=\'' . $_POST['userGroupID'] . '\',groupType=\'' . $_POST['groupType'] . '\' ';

	if ($_POST['Init'] != '1') {
		switch ($_SESSION['adminRoleType']) {
		case '1':
			$SQL .= ',isAdmin=\'' . $_POST['isAdmin'] . '\' ';
			break;

		case '5':
			switch ($_POST['isAdmin']) {
			case '1':
			case '6':
				$n_isAdmin = '3';
				break;

			default:
				$n_isAdmin = $_POST['isAdmin'];
				break;
			}

			$SQL .= ',isAdmin=\'' . $n_isAdmin . '\' ';
			break;

		case '6':
			switch ($_POST['isAdmin']) {
			case '1':
				$n_isAdmin = '3';
				break;

			default:
				$n_isAdmin = $_POST['isAdmin'];
				break;
			}

			$SQL .= ',isAdmin=\'' . $n_isAdmin . '\' ';
			break;
		}
	}

	if ($_POST['passWord'] != '') {
		$SQL .= ' ,passWord=\'' . md5(trim($_POST['passWord'])) . '\' ';
	}

	$SQL .= ' WHERE administratorsID=\'' . $_POST['administratorsID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['isAdmin'] != $_POST['ori_isAdmin']) {
		switch ($_POST['ori_isAdmin']) {
		case 3:
		case 7:
			$SQL = ' DELETE FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID=\'' . $_POST['administratorsID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID=\'' . $_POST['administratorsID'] . '\' ';
			$DB->query($SQL);
			break;

		case 4:
			$SQL = ' DELETE FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID=\'' . $_POST['administratorsID'] . '\' ';
			$DB->query($SQL);
			break;
		}
	}

	writetolog($lang['edit_system_user'] . ':' . $_POST['administratorsName']);
	_showmessage($lang['edit_system_user'] . ':' . $_POST['administratorsName'], true);
}

if ($_GET['Action'] == 'Edit') {
	$EnableQCoreClass->setTemplateFile('UsersEditFile', 'AdministratorsEdit.html');
	$SQL = ' SELECT administratorsName,nickName,isInit,isAdmin,userGroupID,byUserID,groupType,createDate,lastVisitTime,ipAddress,loginNum FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $_GET['administratorsID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		_showerror('系统错误', '系统错误：您选择编辑的用户可能不存在或者已经被删除！');
	}

	$EnableQCoreClass->replace('administratorsID', $_GET['administratorsID']);
	$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);
	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('password_notes', $lang['password_notes']);
	$EnableQCoreClass->replace('isAdmin', $Row['isAdmin']);
	$EnableQCoreClass->replace('option_' . $Row['isAdmin'], 'selected');
	$EnableQCoreClass->replace('byUserID', getbyusername($Row['byUserID']));
	$EnableQCoreClass->replace('groupType_' . $Row['groupType'], 'checked');
	$EnableQCoreClass->replace('groupType', $Row['groupType']);
	$EnableQCoreClass->replace('groupId', $Row['userGroupID']);
	$EnableQCoreClass->replace('createDate', date('Y-m-d H:i:s', $Row['createDate']));
	$EnableQCoreClass->replace('lastVisited', $Row['lastVisitTime'] == 0 ? '从未登录' : date('Y-m-d H:i:s', $Row['lastVisitTime']));
	$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
	$EnableQCoreClass->replace('loginNum', $Row['loginNum']);
	$EnableQCoreClass->replace('userAdminType', $_SESSION['adminRoleType']);
	$EnableQCoreClass->replace('isUserId', $_SESSION['administratorsID']);
	$EnableQCoreClass->replace('userGroupType', $Row['groupType']);

	if ($Row['isInit'] == '1') {
		$EnableQCoreClass->replace('Init', '1');
	}
	else {
		$EnableQCoreClass->replace('Init', '0');
	}

	if ($_SESSION['adminRoleType'] == 5) {
		$EnableQCoreClass->replace('groupType_2', 'disabled');
	}

	switch ($Row['groupType']) {
	case 1:
		$nodeName = $lang['corp_root_node'];
		break;

	case 2:
		$nodeName = $lang['cus_root_node'];
		break;
	}

	if ($Row['userGroupID'] == 0) {
		$EnableQCoreClass->replace('rootNodeId', 0);
		$EnableQCoreClass->replace('rootNodeName', $nodeName);
	}
	else {
		$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $Row['userGroupID'] . '\' ';
		$gRow = $DB->queryFirstRow($gSQL);
		$EnableQCoreClass->replace('rootNodeId', $Row['userGroupID']);
		$EnableQCoreClass->replace('rootNodeName', _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']));
	}

	$EnableQCoreClass->replace('Action', 'UserEditSubmit');
	$UsersEdit = $EnableQCoreClass->parse('UsersEdit', 'UsersEditFile');
	echo $UsersEdit;
	exit();
}

if ($_GET['Action'] == 'DeleteInfo') {
	$EnableQCoreClass->setTemplateFile('UsersEditFile', 'AdministratorsDelete.html');
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $_GET['administratorsID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		_showerror('系统错误', '系统错误：您选择删除的用户可能不存在或者已经被删除！');
	}

	$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);
	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('byUserID', getbyusername($Row['byUserID']));
	$EnableQCoreClass->replace('createDate', date('Y-m-d H:i:s', $Row['createDate']));
	$EnableQCoreClass->replace('lastVisited', $Row['lastVisitTime'] == 0 ? '从未登录' : date('Y-m-d H:i:s', $Row['lastVisitTime']));
	$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
	$EnableQCoreClass->replace('loginNum', $Row['loginNum']);
	$EnableQCoreClass->replace('groupTypeName', $Row['groupType'] == 1 ? '单位' : '客户');
	$EnableQCoreClass->replace('roleName', $lang['isAdmin_' . $Row['isAdmin']]);

	if ($Row['userGroupID'] != 0) {
		$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $Row['userGroupID'] . '\' ';
		$gRow = $DB->queryFirstRow($gSQL);
		$userGroupName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
	}
	else {
		$userGroupName = getusergroupname($Row['userGroupID'], $Row['groupType']);
	}

	$EnableQCoreClass->replace('userGroupName', $userGroupName);
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&Action=Delete&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
	$deleteInfo = '';

	switch ($Row['isAdmin']) {
	case 1:
	case 2:
	case 5:
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . SURVEY_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户设计的调查问卷以及对应数据：(<font color=red>' . $cRow['recNum'] . '</font>)张问卷<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . DATA_TASK_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷数据审核流程信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . DATA_TRACE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷数据审核痕迹信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . TPL_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷展示框架模版：(<font color=red>' . $cRow['recNum'] . '</font>)个模版<br/>';
		$ImageFile_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/' . $Row['administratorsID'] . '/';

		if (is_dir($ImageFile_DIR_Name)) {
			$deleteInfo .= '将删除该用户拥有的附属文件存储目录：(<font color=red>1</font>)个目录<br/>';
		}

		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . OPTION_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的问题常用选项：(<font color=red>' . $cRow['recNum'] . '</font>)个选项<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . QUERY_LIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的数据源定义：(<font color=red>' . $cRow['recNum'] . '</font>)个数据源<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . QUERY_COND_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的定义数据源条件：(<font color=red>' . $cRow['recNum'] . '</font>)个条件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . REPORTDEFINE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的问卷自定义报告条件：(<font color=red>' . $cRow['recNum'] . '</font>)个条件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的系统操作日志：(<font color=red>' . $cRow['recNum'] . '</font>)条日志<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . MAILLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的发送邮件信息：(<font color=red>' . $cRow['recNum'] . '</font>)条邮件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ANDROID_PUSH_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的安卓端推送信息：(<font color=red>' . $cRow['recNum'] . '</font>)条消息<br/>';
		break;

	case 4:
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷回复权限信息：(<font color=red>' . $cRow['recNum'] . '</font>)条权限<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . TASK_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷回复任务信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . DATA_TRACE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷数据审核痕迹信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的系统操作日志：(<font color=red>' . $cRow['recNum'] . '</font>)条日志<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ANDROID_LOG_TABLE . ' WHERE userId = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的离线访员端操作日志：(<font color=red>' . $cRow['recNum'] . '</font>)条日志<br/>';
		break;

	case 3:
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷(查看|审核)权限信息：(<font color=red>' . $cRow['recNum'] . '</font>)条权限<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷申诉(核准)权限信息：(<font color=red>' . $cRow['recNum'] . '</font>)条权限<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . DATA_TRACE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷数据审核痕迹信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . QUERY_LIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的数据源定义：(<font color=red>' . $cRow['recNum'] . '</font>)个数据源<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . QUERY_COND_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的定义数据源条件：(<font color=red>' . $cRow['recNum'] . '</font>)个条件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . REPORTDEFINE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的问卷自定义报告条件：(<font color=red>' . $cRow['recNum'] . '</font>)个条件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . MAILLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的发送邮件信息：(<font color=red>' . $cRow['recNum'] . '</font>)条邮件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的系统操作日志：(<font color=red>' . $cRow['recNum'] . '</font>)条日志<br/>';
		break;

	case 7:
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷(查看|审核)权限信息：(<font color=red>' . $cRow['recNum'] . '</font>)条权限<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷申诉(核准)权限信息：(<font color=red>' . $cRow['recNum'] . '</font>)条权限<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . DATA_TASK_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷数据审核流程信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . DATA_TRACE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的问卷数据审核痕迹信息：(<font color=red>' . $cRow['recNum'] . '</font>)条信息<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . QUERY_LIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的数据源定义：(<font color=red>' . $cRow['recNum'] . '</font>)个数据源<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . QUERY_COND_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的定义数据源条件：(<font color=red>' . $cRow['recNum'] . '</font>)个条件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . REPORTDEFINE_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的问卷自定义报告条件：(<font color=red>' . $cRow['recNum'] . '</font>)个条件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . MAILLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的发送邮件信息：(<font color=red>' . $cRow['recNum'] . '</font>)条邮件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的系统操作日志：(<font color=red>' . $cRow['recNum'] . '</font>)条日志<br/>';
		break;

	case 6:
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ADMINISTRATORSLOG_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的系统操作日志：(<font color=red>' . $cRow['recNum'] . '</font>)条日志<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . MAILLIST_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户拥有的发送邮件信息：(<font color=red>' . $cRow['recNum'] . '</font>)条邮件<br/>';
		$cSQL = ' SELECT COUNT(*) as recNum FROM ' . ANDROID_PUSH_TABLE . ' WHERE administratorsID = \'' . $Row['administratorsID'] . '\' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$deleteInfo .= '将删除该用户创建的安卓端推送信息：(<font color=red>' . $cRow['recNum'] . '</font>)条消息<br/>';
		break;
	}

	$EnableQCoreClass->replace('deleteInfo', $deleteInfo);
	$EnableQCoreClass->replace('Action', 'UserEditSubmit');
	$UsersEdit = $EnableQCoreClass->parse('UsersEdit', 'UsersEditFile');
	echo $UsersEdit;
	exit();
}

?>

<?php
//dezend by http://www.yunlu99.com/
function deletedir($destination)
{
	if (file_exists($destination)) {
		if (is_file($destination)) {
			unlink($destination);
		}
		else {
			$_obf_iUdm8Jn7 = opendir($destination);

			while ($_obf_6hS1Rw__ = readdir($_obf_iUdm8Jn7)) {
				if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
					continue;
				}

				if (is_dir($destination . '/' . $_obf_6hS1Rw__)) {
					deletedir($destination . '/' . $_obf_6hS1Rw__);
				}
				else {
					unlink($destination . '/' . $_obf_6hS1Rw__);
				}
			}

			closedir($_obf_iUdm8Jn7);
			rmdir($destination . '/' . $_obf_6hS1Rw__);
		}
	}
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$thisProg = 'AjaxUserAPI.php?task=\'' . $_GET['task'] . '\'&username=\'' . $_GET['username'] . '\'&newname=\'' . $_GET['newname'] . '\'&password=\'' . $_GET['password'] . '\'&hash=\'' . $_GET['hash'] . '\' ';
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);
if ((trim($_GET['hash']) != md5(trim($SerialRow['license']))) || ($_GET['task'] == '')) {
	exit('false|EnableQ Security Violation');
}

if (trim($_GET['username']) == '') {
	exit('false|Empty UserName');
}

if ($_GET['task'] == 'DeleteUser') {
	$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['username']) . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		exit('false|Incorrect User Name');
	}
	else {
		$theDeleUserId = $Row['administratorsID'];
		require ROOT_PATH . 'System/User.dele.php';
		require ROOT_PATH . 'Export/Database.opti.sql.php';
		exit('true');
	}
}

if ($_GET['task'] == 'CloseUser') {
	$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['username']) . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		exit('false|Incorrect User Name');
	}
	else {
		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive=0 WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
		$DB->query($SQL);
		exit('true');
	}
}

if ($_GET['task'] == 'ActiveUser') {
	$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['username']) . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if (!$Row) {
		exit('false|Incorrect User Name');
	}
	else {
		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive=1 WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
		$DB->query($SQL);
		exit('true');
	}
}

if ($_GET['task'] == 'AddUser') {
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['username']) . '\' AND isAdmin != 0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		exit('false|User Name is exist');
	}
	else {
		if (!isset($_GET['grouptype']) || (trim($_GET['grouptype']) == '')) {
			$groupType = 1;
		}
		else {
			$groupType = 2;
		}

		$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . trim($_GET['username']) . '\',nickName=\'' . $_GET['username'] . '\',passWord=\'' . md5(trim($_GET['password'])) . '\',ipAddress=\'' . _getip() . '\',createDate=\'' . time() . '\' ';

		if ($groupType == 1) {
			switch ($_GET['adminrole']) {
			case '2':
				$SQL .= ' ,isAdmin=\'2\' ';
				break;

			case '4':
				$SQL .= ' ,isAdmin=\'4\' ';
				break;

			case '3':
			default:
				$SQL .= ' ,isAdmin=\'3\' ';
				break;

			case '5':
				$SQL .= ' ,isAdmin=\'5\' ';
				break;

			case '7':
				$SQL .= ' ,isAdmin=\'7\' ';
				break;
			}
		}
		else {
			$SQL .= ' ,isAdmin=\'3\' ';
		}

		if (trim($_GET['groupname']) != '') {
			$G_SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName =\'' . trim($_GET['groupname']) . '\' AND groupType = \'' . $groupType . '\' LIMIT 0,1 ';
			$G_Row = $DB->queryFirstRow($G_SQL);

			if ($G_Row) {
				$SQL .= ' ,userGroupID=\'' . $G_Row['userGroupID'] . '\' ';
			}
			else {
				$G_SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET  userGroupName =\'' . trim($_GET['groupname']) . '\',fatherId=0,groupType=\'' . $groupType . '\',absPath=\'0\',isLeaf=1 ';
				$DB->query($G_SQL);
				$userGroupID = $DB->_GetInsertID();
				$SQL .= ' ,userGroupID=\'' . $userGroupID . '\' ';
			}
		}

		$SQL .= ' ,groupType=\'' . $groupType . '\' ';
		$DB->query($SQL);
		exit('true');
	}
}

if ($_GET['task'] == 'EditUser') {
	$SQL = ' SELECT administratorsID,isAdmin FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['username']) . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);
	if (!$Row || (trim($_GET['newname']) == '')) {
		exit('false|Incorrect User Name');
	}
	else {
		$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['newname']) . '\' AND administratorsName != \'' . trim($_GET['username']) . '\' AND isAdmin != 0 LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			exit('false|User Name is exist');
		}

		if (!isset($_GET['grouptype']) || (trim($_GET['grouptype']) == '')) {
			$groupType = 1;
		}
		else {
			$groupType = 2;
		}

		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . trim($_GET['newname']) . '\',nickName=\'' . $_GET['newname'] . '\' ';

		if ($_GET['password'] != '') {
			$SQL .= ' ,passWord=\'' . md5(trim($_GET['password'])) . '\' ';
		}

		if ($groupType == 1) {
			switch ($_GET['adminrole']) {
			case '2':
				$SQL .= ' ,isAdmin=\'2\' ';
				break;

			case '4':
				$SQL .= ' ,isAdmin=\'4\' ';
				break;

			case '3':
			default:
				$SQL .= ' ,isAdmin=\'3\' ';
				break;

			case '5':
				$SQL .= ' ,isAdmin=\'5\' ';
				break;

			case '7':
				$SQL .= ' ,isAdmin=\'7\' ';
				break;
			}
		}
		else {
			$SQL .= ' ,isAdmin=\'3\' ';
		}

		if (trim($_GET['groupname']) != '') {
			$G_SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName =\'' . trim($_GET['groupname']) . '\' AND groupType = \'' . $groupType . '\' LIMIT 0,1 ';
			$G_Row = $DB->queryFirstRow($G_SQL);

			if ($G_Row) {
				$SQL .= ' ,userGroupID=\'' . $G_Row['userGroupID'] . '\' ';
			}
			else {
				$G_SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET  userGroupName =\'' . trim($_GET['groupname']) . '\',fatherId=0,groupType=\'' . $groupType . '\',absPath=\'0\',isLeaf=1 ';
				$DB->query($G_SQL);
				$userGroupID = $DB->_GetInsertID();
				$SQL .= ' ,userGroupID=\'' . $userGroupID . '\' ';
			}
		}

		$SQL .= ' ,groupType=\'' . $groupType . '\' ';
		$SQL .= ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
		$DB->query($SQL);

		if ($_GET['adminrole'] != $Row['isAdmin']) {
			switch ($Row['isAdmin']) {
			case 3:
			case 7:
				$SQL = ' DELETE FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
				$DB->query($SQL);
				break;

			case 4:
				$SQL = ' DELETE FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
				$DB->query($SQL);
				break;
			}
		}

		exit('true');
	}
}

if ($_GET['task'] == 'UserLogin') {
	$administratorsName = trim($_GET['username']);
	$passWord = md5(trim($_GET['password']));
	$SQL = ' SELECT administratorsName,nickName,administratorsID,passWord,isActive,isAdmin,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . $administratorsName . '\' AND isAdmin !=0 LIMIT 0,1 ';
	$Login_Row = $DB->queryFirstRow($SQL);

	if (!$Login_Row) {
		exit('false|Incorrect User Name');
	}
	else {
		if ($Login_Row['isActive'] != '1') {
			exit('false|No-Active User');
		}

		if ($passWord != $Login_Row['passWord']) {
			exit('false|Incorrect User Password');
		}
		else {
			$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET loginNum=loginNum+1,lastVisitTime=\'' . time() . '\' WHERE administratorsID=\'' . $Login_Row['administratorsID'] . '\' ';
			$DB->query($SQL);
			$_SESSION['administratorsID'] = $Login_Row['administratorsID'];
			$_SESSION['administratorsName'] = $Login_Row['administratorsName'];
			$_SESSION['administratorsNickName'] = $Login_Row['nickName'];
			$_SESSION['adminRoleType'] = $Login_Row['isAdmin'];
			$_SESSION['adminRoleGroupID'] = $Login_Row['userGroupID'];
			$_SESSION['adminRoleGroupType'] = $Login_Row['groupType'];

			switch ($_SESSION['adminRoleType']) {
			case '5':
				if ($_SESSION['adminRoleGroupID'] == 0) {
					$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' ) ';
					$theSonGroup = array();
					$theSonGroup[] = 0;
					$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
					$sResult = $DB->query($sSQL);

					while ($sRow = $DB->queryArray($sResult)) {
						$theSonGroup[] = $sRow['userGroupID'];
					}

					$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin IN (2,5) AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 ';
					$thisSameGroupMembers = array();
					$Result = $DB->query($SQL);

					while ($UserRow = $DB->queryArray($Result)) {
						$thisSameGroupMembers[] = $UserRow['administratorsID'];
					}

					$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
				}
				else {
					$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
					$SQL = ' SELECT a.administratorsID FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin IN (2,5) AND a.userGroupID = b.userGroupID AND a.groupType=1 AND ' . $theSonSQL . ' ';
					$thisSameGroupMembers = array();
					$Result = $DB->query($SQL);

					while ($UserRow = $DB->queryArray($Result)) {
						$thisSameGroupMembers[] = $UserRow['administratorsID'];
					}

					$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
				}

				break;

			case '3':
			case '7':
				switch ($_SESSION['adminRoleGroupType']) {
				case 1:
					if ($_SESSION['adminRoleGroupID'] == 0) {
						$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' ) ';
						$theSonGroup = array();
						$theSonGroup[] = 0;
						$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
						$sResult = $DB->query($sSQL);

						while ($sRow = $DB->queryArray($sResult)) {
							$theSonGroup[] = $sRow['userGroupID'];
						}

						$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin NOT IN (0,3,6,7) AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 ';
						$thisSameGroupMembers = array();
						$Result = $DB->query($SQL);

						while ($UserRow = $DB->queryArray($Result)) {
							$thisSameGroupMembers[] = $UserRow['administratorsName'];
						}

						$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
					}
					else {
						$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
						$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,3,6,7) AND a.userGroupID = b.userGroupID AND a.groupType=1 AND ' . $theSonSQL . ' ';
						$thisSameGroupMembers = array();
						$Result = $DB->query($SQL);

						while ($UserRow = $DB->queryArray($Result)) {
							$thisSameGroupMembers[] = $UserRow['administratorsName'];
						}

						$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
					}

					break;

				case 2:
					$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
					$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND ' . $theSonSQL . ' ';
					$thisSameGroupMembers = array();
					$Result = $DB->query($SQL);

					while ($UserRow = $DB->queryArray($Result)) {
						$thisSameGroupMembers[] = $UserRow['userGroupID'];
					}

					$_SESSION['adminSameGroupUsers'] = $thisSameGroupMembers;
					break;
				}

				break;
			}

			exit('true');
		}
	}
}

?>

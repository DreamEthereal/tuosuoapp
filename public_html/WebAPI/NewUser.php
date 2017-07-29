<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$LicenseRow = $DB->queryFirstRow($SQL);
if ((trim($_SESSION['hash_Code']) != md5(trim($LicenseRow['license']))) || ($_GET['task'] == '')) {
	exit('false|安全通信出现故障，请联系系统管理员');
}

if (trim($_GET['username']) == '') {
	exit('false|用户名为空');
}

if ($_GET['task'] == 'AddUser') {
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . trim($_GET['username']) . '\' AND isAdmin != 0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		exit('false|User Name is exist');
	}
	else {
		$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . trim($_GET['username']) . '\',nickName=\'' . $_GET['username'] . '\',passWord=\'' . md5(trim($_GET['password'])) . '\',createDate=\'' . time() . '\' ';

		switch ($_GET['adminrole']) {
		case '2':
		default:
			$SQL .= ' ,isAdmin=\'2\' ';
			break;

		case '4':
			$SQL .= ' ,isAdmin=\'4\' ';
			break;

		case '3':
			$SQL .= ' ,isAdmin=\'3\' ';
			break;
		}

		if (trim($_GET['groupname']) != '') {
			$G_SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupName =\'' . trim($_GET['groupname']) . '\' LIMIT 0,1 ';
			$Row = $DB->queryFirstRow($G_SQL);

			if ($Row) {
				$SQL .= ' ,userGroupID=\'' . $Row['userGroupID'] . '\' ';
			}
			else {
				$G_SQL = ' INSERT INTO ' . USERGROUP_TABLE . ' SET  userGroupName =\'' . trim($_GET['groupname']) . '\' ';
				$DB->query($G_SQL);
				$userGroupID = $DB->_GetInsertID();
				$SQL .= ' ,userGroupID=\'' . $userGroupID . '\' ';
			}
		}

		$DB->query($SQL);
		exit('true');
	}
}

?>

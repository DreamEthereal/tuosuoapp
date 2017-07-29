<?php
//dezend by http://www.yunlu99.com/
function _checkmemberdataauth($theMemberID)
{
	global $DB;

	switch ($_SESSION['adminRoleType']) {
	case 1:
	case 6:
		return true;
		break;

	case 2:
		$_obf_xCnI = ' SELECT byUserID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $theMemberID . '\' LIMIT 1 ';
		$_obf_HCmdFUF23Q__ = $DB->queryFirstRow($_obf_xCnI);

		if ($_obf_HCmdFUF23Q__['byUserID'] == $_SESSION['administratorsID']) {
			return true;
		}
		else {
			return false;
		}

		break;

	case 5:
		$_obf_xCnI = ' SELECT byUserID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $theMemberID . '\' LIMIT 1 ';
		$_obf_HCmdFUF23Q__ = $DB->queryFirstRow($_obf_xCnI);

		if (in_array($_obf_HCmdFUF23Q__['byUserID'], $_SESSION['adminSameGroupUsers'])) {
			return true;
		}
		else {
			return false;
		}

		break;
	}
}

function _getmemberqueryfields($the_administratorsoptionID = 0)
{
	global $DB;
	$_obf_xCnI = ' SELECT administratorsoptionID,optionFieldName FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_ZXuOB9OOxkxhJw__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ['administratorsoptionID'] == $the_administratorsoptionID) {
			$_obf_ZXuOB9OOxkxhJw__ .= '<option value="' . $_obf_9WwQ['administratorsoptionID'] . '" selected>' . $_obf_9WwQ['optionFieldName'] . '</option>' . "\n" . '';
		}
		else {
			$_obf_ZXuOB9OOxkxhJw__ .= '<option value="' . $_obf_9WwQ['administratorsoptionID'] . '">' . $_obf_9WwQ['optionFieldName'] . '</option>' . "\n" . '';
		}
	}

	return $_obf_ZXuOB9OOxkxhJw__;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
require_once ROOT_PATH . 'Functions/Functions.fields.inc.php';
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$thisProg = 'ShowMembersList.php';
_checkroletype('1|2|5|6');
$ConfigRow['topicNum'] = 30;

if ($_SESSION['MemberListBackURL'] != '') {
	$MemberListBackURL = $_SESSION['MemberListBackURL'];
	$EnableQCoreClass->replace('membersListURL', $_SESSION['MemberListBackURL']);
}
else {
	$MemberListBackURL = $thisProg;
	$EnableQCoreClass->replace('membersListURL', $thisProg);
}

if ($_POST['formAction'] == 'TranMembersSubmit') {
	if (is_array($_POST['administratorsID'])) {
		$administratorsIDLists = join(',', $_POST['administratorsID']);
		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsGroupID=\'' . $_POST['tran_administratorsGroupID'] . '\' WHERE (administratorsID IN (' . $administratorsIDLists . '))';
		$DB->query($SQL);
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
		$DB->query($SQL);
		writetolog($lang['tran_members_list']);
	}

	_showsucceed($lang['tran_members_list'], $MemberListBackURL);
}

if ($_GET['Action'] == 'Close') {
	if (!_checkmemberdataauth($_GET['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive=0 WHERE administratorsID=\'' . $_GET['administratorsID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['close_member'] . ':' . $_GET['administratorsName']);
	_showsucceed($lang['close_member'], $MemberListBackURL);
}

if ($_GET['Action'] == 'Active') {
	if (!_checkmemberdataauth($_GET['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET isActive=1 WHERE administratorsID=\'' . $_GET['administratorsID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['open_member'] . ':' . $_GET['administratorsName']);
	_showsucceed($lang['open_member'], $MemberListBackURL);
}

if ($_POST['formAction'] == 'DeleMembersSubmit') {
	if (is_array($_POST['administratorsID'])) {
		foreach ($_POST['administratorsID'] as $SelectUsersID) {
			$SQL = ' DELETE FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID=\'' . $SelectUsersID . '\' ';
			$DB->query($SQL);
			deleteoptionvalue('administrators', $SelectUsersID);
		}

		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
		$DB->query($SQL);
	}

	writetolog($lang['member_user_delete']);
	_showsucceed($lang['member_user_delete'], $MemberListBackURL);
}

if ($_GET['Action'] == 'Delete') {
	if (!_checkmemberdataauth($_GET['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	$SQL = ' DELETE FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID=\'' . $_GET['administratorsID'] . '\' ';
	$DB->query($SQL);
	deleteoptionvalue('administrators', $_GET['administratorsID']);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['member_user_delete'] . ':' . $_GET['administratorsName']);
	_showsucceed($lang['member_user_delete'], $MemberListBackURL);
}

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
		$csvData = qaddslashes($csvData, 1);

		if ($csvLineNum == 0) {
			$optionTextID = array();
			$col = 6;

			for (; $col < count($csvData); $col++) {
				if (trim($csvData[$col]) != '') {
					$SQL = ' SELECT administratorsoptionID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE optionFieldName=\'' . trim($csvData[$col]) . '\' LIMIT 0,1 ';
					$HaveRow = $DB->queryFirstRow($SQL);

					if ($HaveRow) {
						$optionTextID[$col] = $HaveRow['administratorsoptionID'];
					}
					else {
						$SQL = ' INSERT INTO ' . ADMINISTRATORSOPTION_TABLE . ' SET optionFieldName=\'' . $csvData[$col] . '\',length=50,rows=0,types=\'text\',isPublic=1,isCheckNull=1,isCheckType=0 ';
						$DB->query($SQL);
						$new_optionID = $DB->_GetInsertID();
						$optionTextID[$col] = $new_optionID;
						updateorderid('administratorsoption');
					}
				}
			}
		}
		else {
			$administrators_Name = trim($csvData[0]);
			$nick_Name = trim($csvData[1]);
			$passWord = trim($csvData[2]);
			$hintPass = trim($csvData[3]);
			$answerPass = trim($csvData[4]);
			$administratorsGroupName = trim($csvData[5]);

			if ($administrators_Name == '') {
				continue;
			}

			if (!checkemail($administrators_Name)) {
				continue;
			}

			if ($passWord == '') {
				$passWord = md5('12345678');
			}
			else {
				$passWord = md5($passWord);
			}

			if ($hintPass == '') {
				$hintPass = '1';
			}

			if ($answerPass == '') {
				$answerPass = '12345678';
			}

			$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) = \'' . strtolower($administrators_Name) . '\' AND isAdmin=0 AND isInit=0 LIMIT 0,1 ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				if ($_POST['isUpdateTag'] == 1) {
					$SQL = ' SELECT administratorsGroupID FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupName=\'' . $administratorsGroupName . '\' LIMIT 0,1 ';
					$GroupRow = $DB->queryFirstRow($SQL);

					if (!$GroupRow) {
						$SQL = ' INSERT INTO ' . ADMINISTRATORSGROUP_TABLE . ' SET administratorsGroupName=\'' . $administratorsGroupName . '\',createDate=\'' . time() . '\' ';
						$DB->query($SQL);
						$new_administratorsGroupID = $DB->_GetInsertID();
					}
					else {
						$new_administratorsGroupID = $GroupRow['administratorsGroupID'];
					}

					$new_administratorsID = $Row['administratorsID'];
					$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET  administratorsGroupID=\'' . $new_administratorsGroupID . '\',administratorsName=\'' . strtolower($administrators_Name) . '\',nickName=\'' . strtolower($nick_Name) . '\',passWord=\'' . $passWord . '\',hintPass=\'' . $hintPass . '\',answerPass=\'' . $answerPass . '\',isAdmin=0,isInit=0,isActive=1,byUserID=\'' . $_SESSION['administratorsID'] . '\' WHERE administratorsID =\'' . $new_administratorsID . '\' ';
					$DB->query($SQL);
					$j = 6;

					for (; $j < count($csvData); $j++) {
						$hSQL = ' SELECT administratorsoptionvalueID FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE administratorsID=\'' . $new_administratorsID . '\' AND administratorsoptionID=\'' . $optionTextID[$j] . '\' ';
						$hRow = $DB->queryFirstRow($hSQL);

						if (!$hRow) {
							$SQL = ' INSERT INTO ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' SET administratorsID=\'' . $new_administratorsID . '\',administratorsoptionID=\'' . $optionTextID[$j] . '\',value=\'' . $csvData[$j] . '\' ';
							$DB->query($SQL);
						}
						else {
							$SQL = ' UPDATE ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' SET value=\'' . $csvData[$j] . '\' WHERE administratorsID=\'' . $new_administratorsID . '\' AND administratorsoptionID=\'' . $optionTextID[$j] . '\' ';
							$DB->query($SQL);
						}
					}

					$recNum++;
				}
				else {
					continue;
				}
			}
			else {
				$SQL = ' SELECT administratorsGroupID FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupName=\'' . $administratorsGroupName . '\' LIMIT 0,1 ';
				$GroupRow = $DB->queryFirstRow($SQL);

				if (!$GroupRow) {
					$SQL = ' INSERT INTO ' . ADMINISTRATORSGROUP_TABLE . ' SET administratorsGroupName=\'' . $administratorsGroupName . '\',createDate=\'' . time() . '\' ';
					$DB->query($SQL);
					$new_administratorsGroupID = $DB->_GetInsertID();
				}
				else {
					$new_administratorsGroupID = $GroupRow['administratorsGroupID'];
				}

				$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET  administratorsGroupID=\'' . $new_administratorsGroupID . '\',administratorsName=\'' . strtolower($administrators_Name) . '\',nickName=\'' . strtolower($nick_Name) . '\',passWord=\'' . $passWord . '\',hintPass=\'' . $hintPass . '\',answerPass=\'' . $answerPass . '\',ipAddress=\'' . _getip() . '\',isAdmin=0,isInit=0,isActive=1,byUserID=\'' . $_SESSION['administratorsID'] . '\',createDate=\'' . time() . '\' ';
				$DB->query($SQL);
				$new_administratorsID = $DB->_GetInsertID();
				$j = 6;

				for (; $j < count($csvData); $j++) {
					$SQL = ' INSERT INTO ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' SET administratorsID=\'' . $new_administratorsID . '\',administratorsoptionID=\'' . $optionTextID[$j] . '\',value=\'' . $csvData[$j] . '\' ';
					$DB->query($SQL);
				}

				$recNum++;
			}
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['new_import'] . $recNum . $lang['import_num']);
	_showmessage($lang['new_import'] . $recNum . $lang['import_num'], true);
}

if ($_GET['Action'] == 'Import') {
	$EnableQCoreClass->setTemplateFile('UsersImportFile', 'MembersImport.html');
	$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
	$EnableQCoreClass->output('UsersImport');
}

if ($_POST['Action'] == 'UserAddSubmit') {
	$theUserName = strtolower(trim($_POST['administratorsName']));
	$theNickName = strtolower(trim($_POST['nickName']));
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName)=\'' . $theUserName . '\' AND isAdmin= 0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['administratorsname_is_exist']);
	}

	_checkoptioninput('administrators', 'phpCheck', 1);
	$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . $theUserName . '\',nickName=\'' . $theNickName . '\',passWord=\'' . md5(trim($_POST['passWord'])) . '\',ipAddress=\'' . _getip() . '\',createDate=\'' . time() . '\',isAdmin=0,hintPass=\'' . $_POST['hintPass'] . '\',answerPass=\'' . $_POST['answerPass'] . '\',administratorsGroupID=\'' . $_POST['administratorsGroupID'] . '\',isActive=\'' . $_POST['isActive'] . '\',byUserID=\'' . $_SESSION['administratorsID'] . '\' ';
	$DB->query($SQL);
	$lastInsertID = $DB->_GetInsertID();
	insertoptionvalue('administrators', $lastInsertID, 1);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['add_member_user'] . ':' . $_POST['administratorsName']);
	_showmessage($lang['add_member_user'] . ':' . $_POST['administratorsName'], true);
}
//新增样本信息的操作
if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('UsersAddFile', 'MembersEdit.html');
	$EnableQCoreClass->replace('administratorsName', '');
	$EnableQCoreClass->replace('nickName', '');
	$EnableQCoreClass->replace('password_notes', '');
	$EnableQCoreClass->replace('answerPass', '');
	$EnableQCoreClass->replace('lastVisitTime', '');
	$EnableQCoreClass->replace('loginNum', '');
	$EnableQCoreClass->replace('createDate', '');
	$EnableQCoreClass->replace('isAdd', 'none');
	$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($ConfigRow['defaultGroupID']));
	displayaddoption('administrators', 1);
	_checkoptioninput('administrators', '', 1);
	$EnableQCoreClass->replace('Action', 'UserAddSubmit');
	$EnableQCoreClass->parse('UsersAdd', 'UsersAddFile');
	$EnableQCoreClass->output('UsersAdd');
}

if ($_POST['Action'] == 'UserEditSubmit') {
	$theUserName = strtolower(trim($_POST['administratorsName']));
	$theNickName = strtolower(trim($_POST['nickName']));
	$theOriUserName = strtolower(trim($_POST['ori_administratorsName']));
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) =\'' . $theUserName . '\' AND isAdmin= 0 AND LCASE(administratorsName) != \'' . $theOriUserName . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['administratorsname_is_exist']);
	}

	_checkoptioninput('administrators', 'phpCheck', 1);
	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . $theUserName . '\',nickName=\'' . $theNickName . '\',hintPass=\'' . $_POST['hintPass'] . '\',answerPass=\'' . $_POST['answerPass'] . '\',administratorsGroupID=\'' . $_POST['administratorsGroupID'] . '\',isActive=\'' . $_POST['isActive'] . '\' ';

	if ($_POST['passWord'] != '') {
		$SQL .= ' ,passWord=\'' . md5(trim($_POST['passWord'])) . '\' ';
	}

	$SQL .= ' WHERE administratorsID=\'' . $_POST['administratorsID'] . '\' ';
	$DB->query($SQL);
	editoptionvalue('administrators', $_POST['administratorsID'], 1);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['edit_member_user'] . ':' . $_POST['administratorsName']);
	_showmessage($lang['edit_member_user'] . ':' . $_POST['administratorsName'], true);
}

if ($_GET['Action'] == 'Edit') {
	if (!_checkmemberdataauth($_GET['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	$EnableQCoreClass->setTemplateFile('UsersEditFile', 'MembersEdit.html');
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $_GET['administratorsID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('administratorsID', $Row['administratorsID']);
	$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);

	if ($Row['isActive'] == 0) {
		$EnableQCoreClass->replace('isActive', 'selected');
	}

	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('password_notes', $lang['password_notes']);
	$hintPass = 'option' . $Row['hintPass'];
	$EnableQCoreClass->replace($hintPass, 'selected');
	$EnableQCoreClass->replace('answerPass', $Row['answerPass']);
	$EnableQCoreClass->replace('lastVisitTime', date('Y-m-d H:m', $Row['lastVisitTime']));
	$EnableQCoreClass->replace('loginNum', $Row['loginNum']);
	$EnableQCoreClass->replace('createDate', date('Y-m-d', $Row['createDate']));
	$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($Row['administratorsGroupID']));
	$EnableQCoreClass->replace('isAdd', '');
	$EnableQCoreClass->replace('Action', 'UserEditSubmit');
	displayeditoption('administrators', $_GET['administratorsID'], 1);
	_checkoptioninput('administrators', '', 1);
	$UsersEdit = $EnableQCoreClass->parse('UsersEdit', 'UsersEditFile');
	$UsersEdit = preg_replace('/<!-- CHECK PASSWORD -->(.*)<!-- END PASSWORD -->/s', '', $UsersEdit);
	echo $UsersEdit;
	exit();
}

if ($_GET['Action'] == 'View') {
	$EnableQCoreClass->setTemplateFile('UsersViewFile', 'MembersView.html');
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $_GET['administratorsID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);
	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('lastVisitTime', date('Y-m-d H:m', $Row['lastVisitTime']));
	$EnableQCoreClass->replace('loginNum', $Row['loginNum']);
	$EnableQCoreClass->replace('createDate', date('Y-m-d', $Row['createDate']));
	displayviewoption('administrators', $_GET['administratorsID'], 1);
	$EnableQCoreClass->set_CycBlock('UsersViewFile', 'SURVEY', 'survey');
	$EnableQCoreClass->replace('survey', '');
	$SQL = ' SELECT surveyID,surveyTitle,surveyName FROM ' . SURVEY_TABLE . ' WHERE status IN (1,2) ORDER BY surveyID ASC ';
	$Result = $DB->query($SQL);
	$overFlagNum0 = $overFlagNum1 = $overFlagNum2 = $overFlagNum3 = 0;

	while ($SRow = $DB->queryArray($Result)) {
		$HaveSQL = ' SELECT joinTime,overFlag FROM ' . $table_prefix . 'response_' . $SRow['surveyID'] . ' WHERE LCASE(administratorsName) = \'' . strtolower(trim($Row['administratorsName'])) . '\' LIMIT 1 ';
		$HaveRow = $DB->queryFirstRow($HaveSQL);

		if ($HaveRow) {
			$EnableQCoreClass->replace('surveyName', $SRow['surveyTitle']);
			$EnableQCoreClass->replace('replyTime', date('Y-m-d H:i:s', $HaveRow['joinTime']));

			switch ($HaveRow['overFlag']) {
			case '0':
				$EnableQCoreClass->replace('overFlag', '#ffe000');
				$EnableQCoreClass->replace('surveyFlag', $lang['result_no_all']);
				$overFlagNum0++;
				break;

			case '1':
				$EnableQCoreClass->replace('overFlag', '#ffffff');
				$EnableQCoreClass->replace('surveyFlag', $lang['result_have_all']);
				$overFlagNum1++;
				break;

			case '2':
				$EnableQCoreClass->replace('overFlag', '#cc0000');
				$EnableQCoreClass->replace('surveyFlag', $lang['result_to_quota']);
				$overFlagNum2++;
				break;

			case '3':
				$EnableQCoreClass->replace('overFlag', '#339933');
				$EnableQCoreClass->replace('surveyFlag', $lang['result_in_export']);
				$overFlagNum3++;
				break;
			}

			$EnableQCoreClass->parse('survey', 'SURVEY', true);
		}
	}

	$EnableQCoreClass->replace('overFlagNum0', $overFlagNum0);
	$EnableQCoreClass->replace('overFlagNum1', $overFlagNum1);
	$EnableQCoreClass->replace('overFlagNum2', $overFlagNum2);
	$EnableQCoreClass->replace('overFlagNum3', $overFlagNum3);
	$EnableQCoreClass->replace('surveyTotal', $overFlagNum0 + $overFlagNum1 + $overFlagNum2 + $overFlagNum3);
	$UsersView = $EnableQCoreClass->parse('UsersView', 'UsersViewFile');
	echo $UsersView;
	exit();
}

switch ($_SESSION['adminRoleType']) {
case '1':
	$EnableQCoreClass->setTemplateFile('UserListFile', 'MembersList.html');
	$EnableQCoreClass->replace('isAdmin6', '');
	break;

case '6':
	$EnableQCoreClass->setTemplateFile('UserListFile', 'MembersList.html');
	$EnableQCoreClass->replace('isAdmin6', 'none');
	break;

case '2':
case '5':
	$EnableQCoreClass->setTemplateFile('UserListFile', 'SuperMembersList.html');
	break;
}

$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');
$EnableQCoreClass->replace('user', '');
$EnableQCoreClass->replace('members_group_list', _getmembergroupslist('all'));
$EnableQCoreClass->replace('fieldsList', _getmemberqueryfields());
$EnableQCoreClass->replace('name', '');

switch ($_SESSION['adminRoleType']) {
case '1':
case '6':
	$SQL = $ExportSQL = $RecSQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 ';
	break;

case '2':
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 ';
	$ExportSQL = $RecSQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 AND byUserID = \'' . $_SESSION['administratorsID'] . '\' ';
	break;

case '5':
	$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 ';
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$ExportSQL = $RecSQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 AND byUserID IN (' . $UserIDList . ') ';
	break;
}

$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	if (trim($_POST['name']) != '') {
		$name = trim($_POST['name']);

		switch ($_POST['searchType']) {
		case 't1':
			$SQL .= ' AND administratorsName LIKE BINARY \'%' . $name . '%\' ';
			$ExportSQL .= ' AND administratorsName LIKE BINARY \'%' . $name . '%\' ';
			$EnableQCoreClass->replace('isNickName', '');
			$page_others = '&searchType=t1&name=' . urlencode($name);
			break;

		case 't2':
			$SQL .= ' AND nickName LIKE BINARY \'%' . $name . '%\' ';
			$ExportSQL .= ' AND nickName LIKE BINARY \'%' . $name . '%\' ';
			$EnableQCoreClass->replace('isNickName', 'selected');
			$page_others = '&searchType=t2&name=' . urlencode($name);
			break;

		default:
			$iSQL = ' SELECT a.administratorsID FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' o,' . ADMINISTRATORS_TABLE . ' a WHERE o.administratorsoptionID =\'' . $_POST['searchType'] . '\' AND o.administratorsID = a.administratorsID  AND o.value LIKE BINARY \'%' . $name . '%\' AND a.isAdmin =0 ';
			$iResult = $DB->query($iSQL);
			$iUserArray = array();

			while ($iRow = $DB->queryArray($iResult)) {
				$iUserArray[] = $iRow['administratorsID'];
			}

			if (count($iUserArray) == 0) {
				$SQL .= ' AND administratorsID = 0 ';
				$ExportSQL .= ' AND administratorsID = 0 ';
			}
			else {
				$SQL .= ' AND administratorsID IN (' . implode(',', $iUserArray) . ') ';
				$ExportSQL .= ' AND administratorsID IN (' . implode(',', $iUserArray) . ') ';
			}

			$EnableQCoreClass->replace('isNickName', '');
			$page_others = '&searchType=' . $_POST['searchType'] . '&name=' . urlencode($name);
			$EnableQCoreClass->replace('fieldsList', _getmemberqueryfields($_POST['searchType']));
			break;
		}

		$EnableQCoreClass->replace('name', $name);
	}

	if ($_POST['administratorsGroupID'] != 'all') {
		$SQL .= ' AND administratorsGroupID = \'' . $_POST['administratorsGroupID'] . '\' ';
		$ExportSQL .= ' AND administratorsGroupID = \'' . $_POST['administratorsGroupID'] . '\' ';
		$page_others .= '&administratorsGroupID=' . $_POST['administratorsGroupID'];
		$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($_POST['administratorsGroupID']));
	}
}

if (isset($_GET['name']) && !$_POST['Action'] && ($_GET['searchType'] != '') && ($_GET['name'] != '')) {
	$name = trim($_GET['name']);

	switch ($_POST['searchType']) {
	case 't1':
		$SQL .= ' AND administratorsName LIKE BINARY \'%' . $name . '%\' ';
		$ExportSQL .= ' AND administratorsName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isNickName', '');
		$page_others = '&searchType=t1&name=' . urlencode($name);
		break;

	case 't2':
		$SQL .= ' AND nickName LIKE BINARY \'%' . $name . '%\' ';
		$ExportSQL .= ' AND nickName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isNickName', 'selected');
		$page_others = '&searchType=t2&name=' . urlencode($name);
		break;

	default:
		$iSQL = ' SELECT a.administratorsID FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' o,' . ADMINISTRATORS_TABLE . ' a WHERE o.administratorsoptionID =\'' . $_GET['searchType'] . '\' AND o.administratorsID = a.administratorsID  AND o.value LIKE BINARY \'%' . $name . '%\' AND a.isAdmin =0 ';
		$iResult = $DB->query($iSQL);
		$iUserArray = array();

		while ($iRow = $DB->queryArray($iResult)) {
			$iUserArray[] = $iRow['administratorsID'];
		}

		if (count($iUserArray) == 0) {
			$SQL .= ' AND administratorsID = 0 ';
			$ExportSQL .= ' AND administratorsID = 0 ';
		}
		else {
			$SQL .= ' AND administratorsID IN (' . implode(',', $iUserArray) . ') ';
			$ExportSQL .= ' AND administratorsID IN (' . implode(',', $iUserArray) . ') ';
		}

		$EnableQCoreClass->replace('isNickName', '');
		$page_others = '&searchType=' . $_GET['searchType'] . '&name=' . urlencode($name);
		$EnableQCoreClass->replace('fieldsList', _getmemberqueryfields($_GET['searchType']));
		break;
	}

	$EnableQCoreClass->replace('name', $name);
}

if (isset($_GET['administratorsGroupID']) && ($_GET['administratorsGroupID'] != 'all') && !$_POST['Action']) {
	$SQL .= ' AND administratorsGroupID = \'' . $_GET['administratorsGroupID'] . '\' ';
	$ExportSQL .= ' AND administratorsGroupID = \'' . $_GET['administratorsGroupID'] . '\' ';
	$page_others .= '&administratorsGroupID=' . $_GET['administratorsGroupID'];
	$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($_GET['administratorsGroupID']));
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

$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$MemberBackURL = $thisProg . '?pageID=' . $pageID . $page_others;
$_SESSION['MemberListBackURL'] = $MemberBackURL;
$ExportSQL .= ' ORDER BY administratorsID DESC ';
$_SESSION['mSQL'] = base64_encode($ExportSQL);
$haveRecRow = $DB->queryFirstRow($RecSQL . ' LIMIT 1 ');

if ($haveRecRow) {
	$EnableQCoreClass->replace('haveRecNum', 1);
}
else {
	$EnableQCoreClass->replace('haveRecNum', 0);
}

$SQL .= ' ORDER BY administratorsID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('administratorsID', $Row['administratorsID']);
	$EnableQCoreClass->replace('nickName', $Row['nickName']);
	$EnableQCoreClass->replace('loginNum', $Row['loginNum']);
	$EnableQCoreClass->replace('lastVisitTime', date('y-m-d H:m', $Row['lastVisitTime']));
	$EnableQCoreClass->replace('time', date('y-m-d', $Row['createDate']));
	$SQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $Row['administratorsGroupID'] . '\' ';
	$GroupRow = $DB->queryFirstRow($SQL);

	if ($GroupRow['administratorsGroupName'] != '') {
		$EnableQCoreClass->replace('administratorsGroupName', $GroupRow['administratorsGroupName']);
	}
	else {
		$EnableQCoreClass->replace('administratorsGroupName', $lang['no_group']);
	}

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '6':
		$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=Delete&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
		$EnableQCoreClass->replace('editURL', $thisProg . '?Action=Edit&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));

		if ($Row['isActive'] == 1) {
			$EnableQCoreClass->replace('userName', $Row['administratorsName']);
			$EnableQCoreClass->replace('isStop', $lang['stop']);
			$StopAlert = 'onclick="return window.confirm(\'' . $lang['stop_confim'] . '\')"';
			$EnableQCoreClass->replace('StopAlert', $StopAlert);
			$EnableQCoreClass->replace('closeURL', $thisProg . '?Action=Close&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
		}
		else {
			$EnableQCoreClass->replace('userName', $Row['administratorsName'] . '&nbsp;<img src=../Images/hide.gif>' . $isConfim);
			$EnableQCoreClass->replace('isStop', $lang['active']);
			$EnableQCoreClass->replace('StopAlert', '');
			$EnableQCoreClass->replace('closeURL', $thisProg . '?Action=Active&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
		}

		break;

	case '2':
		if ($Row['byUserID'] == $_SESSION['administratorsID']) {
			$theDeleURL = $thisProg . '?Action=Delete&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
			$theEditURL = $thisProg . '?Action=Edit&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
			$deleteURL = '&nbsp;<a href="' . $theDeleURL . '" onclick="return window.confirm(\'' . $lang['delete_member_confim'] . '\')">' . $lang['delete_action_info'] . '</a>';
			$EnableQCoreClass->replace('deleteURL', $deleteURL);
			$editURL = '&nbsp;<a href="javascript:void(0);" onclick="javascript:showPopWin(\'' . $theEditURL . '\', 700, 450, refreshParent, null,\'' . $lang['edit_member_info'] . '\')">' . $lang['edit_action_info'] . '</a>';
			$EnableQCoreClass->replace('editURL', $editURL);
			$EnableQCoreClass->replace('isAuthTran', '');

			if ($Row['isActive'] == 1) {
				$EnableQCoreClass->replace('userName', $Row['administratorsName']);
				$theCloseURL = $thisProg . '?Action=Close&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
				$closeURL = '&nbsp;<a href="' . $theCloseURL . '" onclick="return window.confirm(\'' . $lang['stop_confim'] . '\')">' . $lang['stop'] . '</a>';
				$EnableQCoreClass->replace('closeURL', $closeURL);
			}
			else {
				$EnableQCoreClass->replace('userName', $Row['administratorsName'] . '&nbsp;<img src=../Images/hide.gif>' . $isConfim);
				$theCloseURL = $thisProg . '?Action=Active&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
				$closeURL = '&nbsp;<a href="' . $theCloseURL . '">' . $lang['active'] . '</a>';
				$EnableQCoreClass->replace('closeURL', $closeURL);
			}
		}
		else {
			if ($Row['isActive'] == 1) {
				$EnableQCoreClass->replace('userName', $Row['administratorsName']);
			}
			else {
				$EnableQCoreClass->replace('userName', $Row['administratorsName'] . '&nbsp;<img src=../Images/hide.gif>' . $isConfim);
			}

			$EnableQCoreClass->replace('deleteURL', '');
			$EnableQCoreClass->replace('editURL', '');
			$EnableQCoreClass->replace('closeURL', '');
			$EnableQCoreClass->replace('isAuthTran', 'disabled');
		}

		break;

	case '5':
		$UsersArray = array_unique($_SESSION['adminSameGroupUsers']);

		if (in_array($Row['byUserID'], $UsersArray)) {
			$theDeleURL = $thisProg . '?Action=Delete&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
			$theEditURL = $thisProg . '?Action=Edit&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
			$deleteURL = '&nbsp;<a href="' . $theDeleURL . '" onclick="return window.confirm(\'' . $lang['delete_member_confim'] . '\')">' . $lang['delete_action_info'] . '</a>';
			$EnableQCoreClass->replace('deleteURL', $deleteURL);
			$editURL = '&nbsp;<a href="javascript:void(0);" onclick="javascript:showPopWin(\'' . $theEditURL . '\', 700, 450, refreshParent, null,\'' . $lang['edit_member_info'] . '\')">' . $lang['edit_action_info'] . '</a>';
			$EnableQCoreClass->replace('editURL', $editURL);
			$EnableQCoreClass->replace('isAuthTran', '');

			if ($Row['isActive'] == 1) {
				$EnableQCoreClass->replace('userName', $Row['administratorsName']);
				$theCloseURL = $thisProg . '?Action=Close&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
				$closeURL = '&nbsp;<a href="' . $theCloseURL . '" onclick="return window.confirm(\'' . $lang['stop_confim'] . '\')">' . $lang['stop'] . '</a>';
				$EnableQCoreClass->replace('closeURL', $closeURL);
			}
			else {
				$EnableQCoreClass->replace('userName', $Row['administratorsName'] . '&nbsp;<img src=../Images/hide.gif>' . $isConfim);
				$theCloseURL = $thisProg . '?Action=Active&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']);
				$closeURL = '&nbsp;<a href="' . $theCloseURL . '">' . $lang['active'] . '</a>';
				$EnableQCoreClass->replace('closeURL', $closeURL);
			}
		}
		else {
			if ($Row['isActive'] == 1) {
				$EnableQCoreClass->replace('userName', $Row['administratorsName']);
			}
			else {
				$EnableQCoreClass->replace('userName', $Row['administratorsName'] . '&nbsp;<img src=../Images/hide.gif>' . $isConfim);
			}

			$EnableQCoreClass->replace('deleteURL', '');
			$EnableQCoreClass->replace('editURL', '');
			$EnableQCoreClass->replace('closeURL', '');
			$EnableQCoreClass->replace('isAuthTran', 'disabled');
		}

		break;
	}

	$EnableQCoreClass->replace('viewURL', $thisProg . '?Action=View&administratorsID=' . $Row['administratorsID'] . '&administratorsName=' . urlencode($Row['administratorsName']));
	$EnableQCoreClass->parse('user', 'USER', true);
}

$EnableQCoreClass->replace('exportURL', '../Export/Export.members.inc.php');
$EnableQCoreClass->replace('exportReplyURL', '../Export/Export.reply.inc.php');
include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('UserList', 'UserListFile');
$EnableQCoreClass->output('UserList');

?>

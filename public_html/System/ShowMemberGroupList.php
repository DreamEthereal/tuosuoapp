<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$thisProg = 'ShowMemberGroupList.php';
_checkroletype('1|6');

if ($_POST['Action'] == 'MemberInGroupSubmit') {
	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsGroupID=0 WHERE administratorsGroupID=\'' . $_POST['groupID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['administratorsNameList'] != '') {
		$i = 0;

		for (; $i < count($_POST['administratorsNameList']); $i++) {
			if ($i == count($_POST['administratorsNameList']) - 1) {
				$administratorsIDList .= $_POST['administratorsNameList'][$i];
			}
			else {
				$administratorsIDList .= $_POST['administratorsNameList'][$i] . ',';
			}
		}

		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsGroupID=\'' . $_POST['groupID'] . '\' WHERE administratorsID in (' . $administratorsIDList . ') ';
		$DB->query($SQL);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['member_in_a_group'] . ':' . $_POST['groupName']);
	_showmessage($lang['member_in_a_group'] . ':' . $_POST['groupName'], true);
}

if ($_GET['Action'] == 'MemberInGroup') {
	$EnableQCoreClass->setTemplateFile('MemberInGroupsPageFile', 'MembersInGroup.html');
	$EnableQCoreClass->replace('groupName', $_GET['administratorsGroupName']);
	$EnableQCoreClass->replace('groupID', $_GET['administratorsGroupID']);
	$SQL = ' SELECT administratorsID,administratorsName,nickName,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin=0 ORDER BY administratorsID DESC ';
	$Result = $DB->query($SQL);
	$administratorsNameList = $administratorsNameList0 = '';

	while ($Row = $DB->queryArray($Result)) {
		if ($Row['administratorsGroupID'] == $_GET['administratorsGroupID']) {
			$administratorsNameList .= '<option value="' . $Row['administratorsID'] . '">' . '(' . $Row['nickName'] . ')' . $Row['administratorsName'] . '</option>' . "\n" . '';
		}
		else {
			$administratorsNameList0 .= '<option value="' . $Row['administratorsID'] . '">' . '(' . $Row['nickName'] . ')' . $Row['administratorsName'] . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('administratorsNameList', $administratorsNameList);
	$EnableQCoreClass->replace('administratorsNameList0', $administratorsNameList0);
	$EnableQCoreClass->parse('MemberInGroupsPage', 'MemberInGroupsPageFile');
	$EnableQCoreClass->output('MemberInGroupsPage');
}

if ($_POST['UpdateSubmit']) {
	if (is_array($_POST['ID'])) {
		$SQL = sprintf(' DELETE FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE (administratorsGroupID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
		$DB->query($SQL);
		$SQL = sprintf(' UPDATE ' . ADMINISTRATORS_TABLE . ' SET administratorsGroupID = 0 WHERE (administratorsGroupID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
		$DB->query($SQL);
		$SQL = sprintf(' DELETE FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE (administratorsGroupID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
		$DB->query($SQL);
		writetolog($lang['dele_mermber_group']);
	}

	if ($_POST['administratorsGroupName_new'] != '') {
		$SQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupName=\'' . trim($_POST['administratorsGroupName_new']) . '\' LIMIT 0,1 ';
		$Row = $DB->queryFirstRow($SQL);

		if ($Row) {
			_showerror($lang['error_system'], $lang['membergroupname_is_exist']);
		}

		$SQL = ' INSERT INTO ' . ADMINISTRATORSGROUP_TABLE . ' SET administratorsGroupName=\'' . $_POST['administratorsGroupName_new'] . '\',createDate=\'' . time() . '\' ';
		$DB->query($SQL);
		writetolog($lang['add_member_group'] . ':' . $_POST['administratorsGroupName_new']);
	}

	if (is_array($_POST['administratorsGroupName'])) {
		foreach ($_POST['administratorsGroupName'] as $ID => $administratorsGroupName) {
			$SQL = ' UPDATE ' . ADMINISTRATORSGROUP_TABLE . ' SET administratorsGroupName=\'' . $administratorsGroupName . '\' WHERE administratorsGroupID =\'' . $ID . '\' ';
			$DB->query($SQL);
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	_showsucceed($lang['edit_member_group'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('MemberGroupsListPageFile', 'MembersGroupList.html');
$EnableQCoreClass->set_CycBlock('MemberGroupsListPageFile', 'GROUPS', 'groups');
$EnableQCoreClass->replace('groups', '');
$SQL = ' SELECT * FROM ' . ADMINISTRATORSGROUP_TABLE . '  ORDER BY administratorsGroupID  ASC ';
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('ID', $Row['administratorsGroupID']);
	$EnableQCoreClass->replace('administratorsGroupName', $Row['administratorsGroupName']);
	$SQL = ' SELECT COUNT(*) AS membersNum FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsGroupID=\'' . $Row['administratorsGroupID'] . '\' ';
	$NumRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('membersNum', $NumRow['membersNum']);
	$EnableQCoreClass->replace('memberURL', $thisProg . '?Action=MemberInGroup&administratorsGroupID=' . $Row['administratorsGroupID'] . '&administratorsGroupName=' . urlencode($Row['administratorsGroupName']));
	$EnableQCoreClass->parse('groups', 'GROUPS', true);
}

$EnableQCoreClass->parse('MemberGroupsListPage', 'MemberGroupsListPageFile');
$EnableQCoreClass->output('MemberGroupsListPage');

?>

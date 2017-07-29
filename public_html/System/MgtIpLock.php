<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$thisProg = 'MgtIpLock.php';
$ConfigRow['topicNum'] = 30;
_checkroletype(1);

if ($_POST['Action'] == 'IPLockAddSubmit') {
	$FromIPAddress = trim($_POST['ipAddress']);
	$SQL = ' INSERT INTO ' . BANNED_TABLE . ' SET ipAddress=\'' . $FromIPAddress . '\' ';
	$DB->query($SQL);
	writetolog($lang['add_ipbanned']);
	echo '<script>parent.hidePopWin(true);</script>';
	exit();
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('IpLockAddFile', 'LockIpAdd.html');
	$EnableQCoreClass->parse('IpLockAdd', 'IpLockAddFile');
	$EnableQCoreClass->output('IpLockAdd');
}

if ($_POST['Action'] == 'QuerySubmit') {
	$EnableQCoreClass->setTemplateFile('IpLockQuerySubmitFile', 'LockIpQuery.html');
	$Num = explode('.', $_POST['StartIp']);
	$Sip = sprintf('%03s', $Num[0]) . '.' . sprintf('%03s', $Num[1]) . '.' . sprintf('%03s', $Num[2]) . '.' . sprintf('%03s', $Num[3]);
	$SQL = ' SELECT Area FROM ' . IPDATABASE_TABLE . ' WHERE StartIp<=\'' . $Sip . '\' AND EndIp>=\'' . $Sip . '\' LIMIT 0,1';

	if ($row = $DB->queryFirstRow($SQL)) {
		$Address = $row['Area'];
	}
	else {
		$Address = $lang['unkown_area'];
	}

	$EnableQCoreClass->replace('StartIp', $_POST['StartIp']);
	$EnableQCoreClass->replace('IpAddress', $Address);
	$EnableQCoreClass->replace('none', '');
	$EnableQCoreClass->parse('IpLockQuerySubmit', 'IpLockQuerySubmitFile');
	$EnableQCoreClass->output('IpLockQuerySubmit');
}

if ($_GET['Action'] == 'Query') {
	$EnableQCoreClass->setTemplateFile('IpLockQueryFile', 'LockIpQuery.html');
	$EnableQCoreClass->replace('StartIp', $_GET['IpAddress']);
	$EnableQCoreClass->replace('IpAddress', '');
	$EnableQCoreClass->replace('none', 'none');
	$EnableQCoreClass->parse('IpLockQuery', 'IpLockQueryFile');
	$EnableQCoreClass->output('IpLockQuery');
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . BANNED_TABLE . ' WHERE bannedID=\'' . $_GET['bannedID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['dele_ipbanned']);
	_showsucceed($lang['dele_ipbanned'], $thisProg);
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf(' DELETE FROM ' . BANNED_TABLE . ' WHERE (bannedID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
	$DB->query($SQL);
	writetolog($lang['dele_ipbanned']);
	_showsucceed($lang['dele_ipbanned'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('IpLockFile', 'LockIpList.html');
$EnableQCoreClass->set_CycBlock('IpLockFile', 'LOGS', 'logs');
$EnableQCoreClass->replace('logs', '');
$SQL = ' SELECT * FROM ' . BANNED_TABLE . ' ';
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

$SQL .= ' ORDER BY bannedID DESC  ';
$SQL .= ' LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('ID', $Row['bannedID']);
	$EnableQCoreClass->replace('ip', $Row['ipAddress']);
	$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=Delete&bannedID=' . $Row['bannedID'] . ' ');
	$EnableQCoreClass->replace('queryURL', $thisProg . '?Action=Query&IpAddress=' . $Row['ipAddress']);
	$EnableQCoreClass->parse('logs', 'LOGS', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('IpLock', 'IpLockFile');
$EnableQCoreClass->output('IpLock');

?>

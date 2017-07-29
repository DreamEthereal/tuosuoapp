<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$thisProg = 'MgtIpWhite.php';
$ConfigRow['topicNum'] = 30;
_checkroletype(1);

if ($_POST['Action'] == 'IPWhiteAddSubmit') {
	$FromIPAddress = trim($_POST['ipAddress']);
	$SQL = ' INSERT INTO ' . WHITE_TABLE . ' SET ipAddress=\'' . $FromIPAddress . '\' ';
	$DB->query($SQL);
	writetolog($lang['add_ipbanned']);
	echo '<script>parent.hidePopWin(true);</script>';
	exit();
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('IpWhiteAddFile', 'WhiteIpAdd.html');
	$EnableQCoreClass->parse('IpWhiteAdd', 'IpWhiteAddFile');
	$EnableQCoreClass->output('IpWhiteAdd');
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . WHITE_TABLE . ' WHERE whiteID=\'' . $_GET['whiteID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['dele_ipbanned']);
	_showsucceed($lang['dele_ipbanned'], $thisProg);
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf(' DELETE FROM ' . WHITE_TABLE . ' WHERE (whiteID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
	$DB->query($SQL);
	writetolog($lang['dele_ipbanned']);
	_showsucceed($lang['dele_ipbanned'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('IpWhiteFile', 'WhiteIpList.html');
$EnableQCoreClass->set_CycBlock('IpWhiteFile', 'LOGS', 'logs');
$EnableQCoreClass->replace('logs', '');
$SQL = ' SELECT * FROM ' . WHITE_TABLE . ' ';
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

$SQL .= ' ORDER BY whiteID DESC  ';
$SQL .= ' LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('ID', $Row['whiteID']);
	$EnableQCoreClass->replace('ip', $Row['ipAddress']);
	$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=Delete&whiteID=' . $Row['whiteID'] . ' ');
	$EnableQCoreClass->parse('logs', 'LOGS', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('IpWhite', 'IpWhiteFile');
$EnableQCoreClass->output('IpWhite');

?>

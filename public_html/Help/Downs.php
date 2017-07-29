<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.free.php';
$thisProg = 'Downs.php';
$ConfigRow['topicNum'] = 20;
$FilesPath = ROOT_PATH . 'Help/';

if ($_GET['Action'] == 'View') {
	$EnableQCoreClass->setTemplateFile('DownsViewFile', 'DownsView.html');
	$SQL = 'SELECT * FROM ' . DOWNS_TABLE . ' WHERE downsID=\'' . $_GET['downsID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['filename']) {
		if (trim($Row['filename']) == 'EnableQUserManual.html') {
			$file_path = '<a href="' . $FilesPath . $Row['filename'] . '" target=_blank>' . $Row['filename'] . '</a>';
		}
		else {
			$downURL = '<a href="../WebAPI/Down.php?path=' . str_replace('+', '%2B', base64_encode('../Help/')) . '&file=' . str_replace('+', '%2B', base64_encode($Row['filename'])) . '">';
			$file_path = $downURL . $Row['filename'] . '</a>';
		}

		$EnableQCoreClass->replace('downFile', $file_path);
	}
	else {
		$EnableQCoreClass->replace('downFile', '');
	}

	$EnableQCoreClass->replace('title', $Row['downsName']);
	$EnableQCoreClass->replace('contents', $Row['downsContent']);
	$EnableQCoreClass->replace('size', $Row['filesize']);
	$EnableQCoreClass->replace('createDate', date('Y-m-d', $Row['createDate']));
	$EnableQCoreClass->replace('updateDate', date('Y-m-d', $Row['updateDate']));
	$EnableQCoreClass->replace('fileSize', $Row['filesize']);
	$EnableQCoreClass->replace('downloadListURL', $thisProg);
	$EnableQCoreClass->parse('DownsView', 'DownsViewFile');
	$EnableQCoreClass->output('DownsView');
}

$EnableQCoreClass->setTemplateFile('DownsListFile', 'DownsList.html');
$EnableQCoreClass->set_CycBlock('DownsListFile', 'DOWNS', 'downs');
$EnableQCoreClass->replace('downs', '');
$SQL = ' SELECT * FROM ' . DOWNS_TABLE;
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$_GET['pageID'] = 0;
}

$_GET['pageID'] = (int) $_GET['pageID'];
$start = $_GET['pageID'] * $ConfigRow['topicNum'];
$SQL .= ' ORDER BY orderByID ASC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('downsName', $Row['downsName']);
	$EnableQCoreClass->replace('fileSize', $Row['filesize']);
	$EnableQCoreClass->replace('time', date('Y-m-d H:i:s', $Row['createDate']));
	$EnableQCoreClass->replace('viewURL', 'Downs.php?Action=View&downsID=' . $Row['downsID']);
	$EnableQCoreClass->parse('downs', 'DOWNS', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('DownsList', 'DownsListFile');
$EnableQCoreClass->output('DownsList');

?>

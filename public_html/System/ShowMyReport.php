<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('3|4');
$thisProg = 'ShowMyReport.php';
$EnableQCoreClass->setTemplateFile('MainPageFile', 'MyReportList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$EnableQCoreClass->replace('nick_Name', $_SESSION['administratorsName']);
$ConfigRow['topicNum'] = 10;
$SQL = ' SELECT * FROM ' . REPORT_TABLE . ' WHERE FIND_IN_SET(' . $_SESSION['administratorsID'] . ',reportRecipient) ';
$Result = $DB->query($SQL);
$totalNum = $recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY reportID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$reportPath = '../PerUserData/report/' . date('Y-m', $Row['reportTime']) . '/' . date('d', $Row['reportTime']) . '/';

	if (!file_exists($reportPath . md5($Row['reportFile']))) {
		if (!file_exists($reportPath . $Row['reportFile'])) {
			$EnableQCoreClass->replace('reportName', $Row['reportName'] . '&nbsp;<img src="../Images/hide.gif" border=0>');
			$EnableQCoreClass->replace('actionName', 'ряи╬ЁЩ');
		}
		else {
			$EnableQCoreClass->replace('reportName', $Row['reportName']);
			$reportFile = '<a href="../WebAPI/Down.php?path=' . str_replace('+', '%2B', base64_encode($reportPath)) . '&file=' . str_replace('+', '%2B', base64_encode($Row['reportFile'])) . '">обть</a>';
			$EnableQCoreClass->replace('actionName', $reportFile);
		}
	}
	else {
		$EnableQCoreClass->replace('reportName', $Row['reportName']);
		$reportFile = '<a href="../WebAPI/DownFile.php?path=' . str_replace('+', '%2B', base64_encode($reportPath)) . '&file=' . urlencode($Row['reportFile']) . '">обть</a>';
		$EnableQCoreClass->replace('actionName', $reportFile);
	}

	$Admin_SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$Admin_Row = $DB->queryFirstRow($Admin_SQL);

	if (!$Admin_Row) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $Admin_Row['administratorsName']);
	}

	$EnableQCoreClass->replace('reportTime', date('Y-m-d', $Row['reportTime']));
	$EnableQCoreClass->parse('list', 'LIST', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('MainPage', 'MainPageFile');
$EnableQCoreClass->output('MainPage', false);

?>

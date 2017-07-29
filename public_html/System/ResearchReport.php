<?php
//dezend by http://www.yunlu99.com/
function _getshowfilename($fileName)
{
	$_obf_jZJFIP3Dx6nXUx4_ = trim($fileName);
	$_obf_A_N_tzzf = explode('.', $_obf_jZJFIP3Dx6nXUx4_);
	$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
	$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
	$_obf_hk_Z__TqtsmTn3k_ = basename($_obf_jZJFIP3Dx6nXUx4_, '.' . $_obf_MxPKhZcSxB7b);
	$_obf_pL_YpAmNxhhxDXo_gfCqQw__ = explode('_', $_obf_hk_Z__TqtsmTn3k_);

	if (count($_obf_pL_YpAmNxhhxDXo_gfCqQw__) == 1) {
		return $fileName;
	}
	else {
		$_obf_nLeBi91MrXfV8AS3fqg_ = '';
		$_obf_juwe = 0;

		for (; $_obf_juwe < (count($_obf_pL_YpAmNxhhxDXo_gfCqQw__) - 1); $_obf_juwe++) {
			$_obf_nLeBi91MrXfV8AS3fqg_ .= $_obf_pL_YpAmNxhhxDXo_gfCqQw__[$_obf_juwe] . '_';
		}

		return substr($_obf_nLeBi91MrXfV8AS3fqg_, 0, -1) . '.' . $_obf_MxPKhZcSxB7b;
	}
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
_checkroletype('1|2|5');
$thisProg = 'ResearchReport.php';

if ($_SESSION['reportListURL'] != '') {
	$EnableQCoreClass->replace('reportListURL', $_SESSION['reportListURL']);
	$reportListURL = $_SESSION['reportListURL'];
}
else {
	$EnableQCoreClass->replace('reportListURL', $thisProg);
	$reportListURL = $thisProg;
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' SELECT reportName,reportFile,reportTime FROM ' . REPORT_TABLE . ' WHERE reportID= \'' . $_GET['reportID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/report/' . date('Y-m', $Row['reportTime']) . '/' . date('d', $Row['reportTime']) . '/';

	if (file_exists($filePhyPath . $Row['reportFile'])) {
		@unlink($filePhyPath . $Row['reportFile']);
	}

	if (file_exists($filePhyPath . md5($Row['reportFile']))) {
		@unlink($filePhyPath . md5($Row['reportFile']));
	}

	$SQL = ' DELETE FROM ' . REPORT_TABLE . ' WHERE reportID= \'' . $_GET['reportID'] . '\' ';
	$DB->query($SQL);
	writetolog('删除上传的调研文件:' . $Row['reportName']);
	_showsucceed('删除上传的调研文件:' . $Row['reportName'], $reportListURL);
}

if ($_GET['Action'] == 'View') {
	$EnableQCoreClass->setTemplateFile('ReportViewPageFile', 'ResearchReportView.html');
	$SQL = ' SELECT * FROM ' . REPORT_TABLE . ' WHERE reportID = \'' . $_GET['reportID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('reportName', $Row['reportName']);
	$theReportRecipient = explode(',', trim($Row['reportRecipient']));
	$theReportRecipientList = '';

	foreach ($theReportRecipient as $thisReportRecipient) {
		$hSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $thisReportRecipient . '\' AND isAdmin IN (3,4) ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$theReportRecipientList .= $hRow['administratorsName'] . ',';
		}
	}

	$EnableQCoreClass->replace('reportRecipient', substr($theReportRecipientList, 0, -1));
	$filePhyPath = '../PerUserData/report/' . date('Y-m', $Row['reportTime']) . '/' . date('d', $Row['reportTime']) . '/';

	if (file_exists($filePhyPath . md5($Row['reportFile']))) {
		$reportFile = '<a href="../WebAPI/DownFile.php?path=' . str_replace('+', '%2B', base64_encode($filePhyPath)) . '&file=' . urlencode($Row['reportFile']) . '">';
		$reportFile .= '<span class=red>' . _getshowfilename($Row['reportFile']) . '</span></a>';
		$EnableQCoreClass->replace('reportFile', $reportFile);
	}
	else if (file_exists($filePhyPath . $Row['reportFile'])) {
		$reportFile = '<a href="../WebAPI/Down.php?path=' . str_replace('+', '%2B', base64_encode($filePhyPath)) . '&file=' . str_replace('+', '%2B', base64_encode($Row['reportFile'])) . '">';
		$reportFile .= '<span class=red>' . $Row['reportFile'] . '</span></a>';
		$EnableQCoreClass->replace('reportFile', $reportFile);
	}
	else {
		$EnableQCoreClass->replace('reportFile', '已删除');
	}

	$Admin_SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$Admin_Row = $DB->queryFirstRow($Admin_SQL);

	if (!$Admin_Row) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $Admin_Row['administratorsName']);
	}

	$EnableQCoreClass->replace('reportTime', date('Y-m-d H:i:s', $Row['reportTime']));
	$EnableQCoreClass->parse('ReportViewPage', 'ReportViewPageFile');
	$EnableQCoreClass->output('ReportViewPage', false);
}

if ($_POST['Action'] == 'ReportEditSubmit') {
	$tmpFilePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
	$reportTime = $_POST['reportTime'];
	$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/report/' . date('Y-m', $reportTime) . '/' . date('d', $reportTime) . '/';
	createdir($filePhyPath);
	$theReportRecipient = explode(',', trim($_POST['reportRecipient']));
	$theReportRecipientList = '';

	foreach ($theReportRecipient as $thisReportRecipient) {
		$hSQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName =\'' . $thisReportRecipient . '\' AND isAdmin IN (3,4) ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$theReportRecipientList .= $hRow['administratorsID'] . ',';
		}
	}

	if ($theReportRecipientList == '') {
		_showerror('系统错误', '系统错误：经检查，合格的文件接受人为空，请重新上传');
	}

	$theUploadFileName = trim($_POST['reportFile']);
	$theUploadMd5FileName = md5($theUploadFileName);

	if (trim($_POST['reportFile']) != trim($_POST['ori_reportFileName'])) {
		if (file_exists($tmpFilePhyPath . $theUploadMd5FileName)) {
			if (copy($tmpFilePhyPath . $theUploadMd5FileName, $filePhyPath . $theUploadMd5FileName)) {
				@unlink($tmpFilePhyPath . $theUploadMd5FileName);

				if (file_exists($filePhyPath . trim($_POST['ori_reportFile']))) {
					@unlink($filePhyPath . trim($_POST['ori_reportFile']));
				}

				if (file_exists($filePhyPath . md5(trim($_POST['ori_reportFile'])))) {
					@unlink($filePhyPath . md5(trim($_POST['ori_reportFile'])));
				}

				$SQL = ' UPDATE ' . REPORT_TABLE . ' SET reportName=\'' . trim($_POST['reportName']) . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',reportRecipient=\'' . substr($theReportRecipientList, 0, -1) . '\',reportFile = \'' . $theUploadFileName . '\' WHERE reportID = \'' . $_POST['reportID'] . '\' ';
				$DB->query($SQL);
			}
			else {
				_showerror('系统错误', '系统错误：复制上传的文件到目标文件夹出现错误，请重新上传');
			}
		}
		else {
			_showerror('系统错误', '系统错误：存储在临时文件夹的文件不存在，请重新上传');
		}
	}
	else {
		$SQL = ' UPDATE ' . REPORT_TABLE . ' SET reportName=\'' . trim($_POST['reportName']) . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',reportRecipient=\'' . substr($theReportRecipientList, 0, -1) . '\' WHERE reportID = \'' . $_POST['reportID'] . '\' ';
		$DB->query($SQL);
	}

	writetolog('修改调研文件上传：' . trim($_POST['reportName']));
	_showmessage('修改调研文件上传：' . trim($_POST['reportName']), true);
}

if ($_GET['Action'] == 'Edit') {
	$EnableQCoreClass->setTemplateFile('ReportEditPageFile', 'ResearchReportEdit.html');
	$SQL = ' SELECT * FROM ' . REPORT_TABLE . ' WHERE reportID = \'' . $_GET['reportID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('reportName', $Row['reportName']);
	$theReportRecipient = explode(',', trim($Row['reportRecipient']));
	$theReportRecipientList = '';

	foreach ($theReportRecipient as $thisReportRecipient) {
		$hSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $thisReportRecipient . '\' AND isAdmin IN (3,4) ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$theReportRecipientList .= $hRow['administratorsName'] . ',';
		}
	}

	$EnableQCoreClass->replace('reportRecipient', substr($theReportRecipientList, 0, -1));
	$EnableQCoreClass->replace('reportFile', $Row['reportFile']);
	$EnableQCoreClass->replace('ori_reportFileName', $Row['reportFile']);
	$EnableQCoreClass->replace('ori_reportFile', md5($Row['reportFile']));
	$EnableQCoreClass->replace('reportID', $Row['reportID']);
	$EnableQCoreClass->replace('reportTime', $Row['reportTime']);
	$EnableQCoreClass->replace('Action', 'ReportEditSubmit');
	$EnableQCoreClass->replace('session_id', session_id());
	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ($POST_MAX_SIZE) {
		$thePostMaxSize = (int) ($multiplier * (int) $POST_MAX_SIZE) / 1048576;
		$EnableQCoreClass->replace('maxSize', $thePostMaxSize);
	}
	else {
		$EnableQCoreClass->replace('maxSize', 2);
	}

	$EnableQCoreClass->replace('allowType', '*.zip;*.rar;*.doc;*.docx;*.ppt;*.pptx;*.xls;*.xlsx');
	$EnableQCoreClass->parse('ReportEditPage', 'ReportEditPageFile');
	$EnableQCoreClass->output('ReportEditPage', false);
}

if ($_POST['Action'] == 'ReportAddSubmit') {
	$tmpFilePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
	$reportTime = time();
	$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/report/' . date('Y-m', $reportTime) . '/' . date('d', $reportTime) . '/';
	createdir($filePhyPath);
	$theReportRecipient = explode(',', trim($_POST['reportRecipient']));
	$theReportRecipientList = '';

	foreach ($theReportRecipient as $thisReportRecipient) {
		$hSQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName =\'' . $thisReportRecipient . '\' AND isAdmin IN (3,4) ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$theReportRecipientList .= $hRow['administratorsID'] . ',';
		}
	}

	if ($theReportRecipientList == '') {
		_showerror('系统错误', '系统错误：经检查，合格的文件接受人为空，请重新上传');
	}

	$theUploadFileName = trim($_POST['reportFile']);
	$theUploadMd5FileName = md5($theUploadFileName);

	if (file_exists($tmpFilePhyPath . $theUploadMd5FileName)) {
		if (copy($tmpFilePhyPath . $theUploadMd5FileName, $filePhyPath . $theUploadMd5FileName)) {
			@unlink($tmpFilePhyPath . $theUploadMd5FileName);
			$SQL = ' INSERT INTO ' . REPORT_TABLE . ' SET reportName=\'' . trim($_POST['reportName']) . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',reportRecipient=\'' . substr($theReportRecipientList, 0, -1) . '\',reportFile = \'' . $theUploadFileName . '\',reportTime=\'' . $reportTime . '\' ';
			$DB->query($SQL);
		}
		else {
			_showerror('系统错误', '系统错误：复制上传的文件到目标文件夹出现错误，请重新上传');
		}
	}
	else {
		_showerror('系统错误', '系统错误：存储在临时文件夹的文件不存在，请重新上传');
	}

	writetolog('上传调研文件：' . trim($_POST['reportName']));
	_showmessage('上传调研文件：' . trim($_POST['reportName']), true);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('ReportAddPageFile', 'ResearchReportEdit.html');
	$EnableQCoreClass->replace('reportName', '');
	$EnableQCoreClass->replace('reportRecipient', '');
	$EnableQCoreClass->replace('reportFile', '');
	$EnableQCoreClass->replace('reportID', '');
	$EnableQCoreClass->replace('reportTime', '');
	$EnableQCoreClass->replace('ori_reportFile', '');
	$EnableQCoreClass->replace('ori_reportFileName', '');
	$EnableQCoreClass->replace('Action', 'ReportAddSubmit');
	$EnableQCoreClass->replace('session_id', session_id());
	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ($POST_MAX_SIZE) {
		$thePostMaxSize = (int) ($multiplier * (int) $POST_MAX_SIZE) / 1048576;
		$EnableQCoreClass->replace('maxSize', $thePostMaxSize);
	}
	else {
		$EnableQCoreClass->replace('maxSize', 2);
	}

	$EnableQCoreClass->replace('allowType', '*.zip;*.rar;*.doc;*.docx;*.ppt;*.pptx;*.xls;*.xlsx');
	$EnableQCoreClass->parse('ReportAddPage', 'ReportAddPageFile');
	$EnableQCoreClass->output('ReportAddPage', false);
}

$EnableQCoreClass->setTemplateFile('MainPageFile', 'ResearchReportList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$ConfigRow['topicNum'] = 30;
$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('t_reportTime', '');
$EnableQCoreClass->replace('t_reportTimeEnd', '');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT * FROM ' . REPORT_TABLE . ' WHERE 1=1 ';
	$EnableQCoreClass->replace('users_list', _getuserslist(0));
	break;

case '2':
	$SQL = ' SELECT * FROM ' . REPORT_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
	$EnableQCoreClass->replace('users_list', '<option value=' . $_SESSION['administratorsID'] . ' selected>' . $_SESSION['administratorsName'] . '</option>');
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT * FROM ' . REPORT_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ')';
	$MemberSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND isAdmin IN (2,5) ';
	$MemberResult = $DB->query($MemberSQL);

	while ($MemberRow = $DB->queryArray($MemberResult)) {
		$users_list .= '<option value=' . $MemberRow['administratorsID'] . '>' . $MemberRow['administratorsName'] . '</option>';
	}

	$EnableQCoreClass->replace('users_list', $users_list);
	break;
}

$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	if (trim($_POST['t_name']) != '') {
		$name = trim($_POST['t_name']);
		$SQL .= ' AND reportName LIKE BINARY \'%' . $name . '%\' ';
		$page_others .= '&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
	}

	if (trim($_POST['t_reportTime']) != '') {
		$reportTime = explode('-', $_POST['t_reportTime']);
		$reportUnixTime = mktime(0, 0, 0, $reportTime[1], $reportTime[2], $reportTime[0]);
		$SQL .= ' AND reportTime >= \'' . $reportUnixTime . '\' ';
		$page_others .= '&t_reportTime=' . $_POST['t_reportTime'];
		$EnableQCoreClass->replace('t_reportTime', $_POST['t_reportTime']);
	}

	if (trim($_POST['t_reportTimeEnd']) != '') {
		$reportTime = explode('-', $_POST['t_reportTimeEnd']);
		$reportUnixTime = mktime(0, 0, 0, $reportTime[1], $reportTime[2], $reportTime[0]);
		$SQL .= ' AND reportTime <= \'' . $reportUnixTime . '\' ';
		$page_others .= '&t_reportTimeEnd=' . $_POST['t_reportTimeEnd'];
		$EnableQCoreClass->replace('t_reportTimeEnd', $_POST['t_reportTimeEnd']);
	}

	if ($_POST['t_administratorsID'] != '0') {
		$SQL .= ' AND administratorsID = \'' . $_POST['t_administratorsID'] . '\' ';
		$page_others .= '&t_administratorsID=' . $_POST['t_administratorsID'];

		switch ($_SESSION['adminRoleType']) {
		case '1':
			$EnableQCoreClass->replace('users_list', _getuserslist($_POST['t_administratorsID']));
			break;

		case '2':
			break;

		case '5':
			$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
			$MemberSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND isAdmin IN (2,5) ';
			$MemberResult = $DB->query($MemberSQL);

			while ($MemberRow = $DB->queryArray($MemberResult)) {
				if ($MemberRow['administratorsID'] == $_POST['t_administratorsID']) {
					$users_list .= '<option value=' . $MemberRow['administratorsID'] . ' selected>' . $MemberRow['administratorsName'] . '</option>';
				}
				else {
					$users_list .= '<option value=' . $MemberRow['administratorsID'] . '>' . $MemberRow['administratorsName'] . '</option>';
				}
			}

			$EnableQCoreClass->replace('users_list', $users_list);
			break;
		}
	}
}

if (isset($_GET['t_name']) && !$_POST['Action'] && ($_GET['t_name'] != '')) {
	$name = trim($_GET['t_name']);
	$SQL .= ' AND reportName LIKE BINARY \'%' . $name . '%\' ';
	$page_others .= '&t_name=' . urlencode($name);
	$EnableQCoreClass->replace('t_name', $name);
}

if (isset($_GET['t_reportTime']) && !$_POST['Action'] && ($_GET['t_reportTime'] != '')) {
	$reportTime = explode('-', $_GET['t_reportTime']);
	$reportUnixTime = mktime(0, 0, 0, $reportTime[1], $reportTime[2], $reportTime[0]);
	$SQL .= ' AND reportTime >= \'' . $reportUnixTime . '\' ';
	$page_others .= '&t_reportTime=' . $_GET['t_reportTime'];
	$EnableQCoreClass->replace('t_reportTime', $_GET['t_reportTime']);
}

if (isset($_GET['t_reportTimeEnd']) && !$_POST['Action'] && ($_GET['t_reportTimeEnd'] != '')) {
	$reportTime = explode('-', $_GET['t_reportTimeEnd']);
	$reportUnixTime = mktime(0, 0, 0, $reportTime[1], $reportTime[2], $reportTime[0]);
	$SQL .= ' AND reportTime <= \'' . $reportUnixTime . '\' ';
	$page_others .= '&t_reportTimeEnd=' . $_GET['t_reportTimeEnd'];
	$EnableQCoreClass->replace('t_reportTimeEnd', $_GET['t_reportTimeEnd']);
}

if (isset($_GET['t_administratorsID']) && ($_GET['t_administratorsID'] != '0') && !$_POST['Action']) {
	$SQL .= ' AND administratorsID = \'' . $_GET['t_administratorsID'] . '\' ';
	$page_others .= '&t_administratorsID=' . $_GET['t_administratorsID'];

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$EnableQCoreClass->replace('users_list', _getuserslist($_GET['t_administratorsID']));
		break;

	case '2':
		break;

	case '5':
		$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
		$MemberSQL = ' SELECT administratorsID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND isAdmin IN (2,5) ';
		$MemberResult = $DB->query($MemberSQL);

		while ($MemberRow = $DB->queryArray($MemberResult)) {
			if ($MemberRow['administratorsID'] == $_GET['t_administratorsID']) {
				$users_list .= '<option value=' . $MemberRow['administratorsID'] . ' selected>' . $MemberRow['administratorsName'] . '</option>';
			}
			else {
				$users_list .= '<option value=' . $MemberRow['administratorsID'] . '>' . $MemberRow['administratorsName'] . '</option>';
			}
		}

		$EnableQCoreClass->replace('users_list', $users_list);
		break;
	}
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
$BackURL = $thisProg . '?pageID=' . $pageID . $page_others;
$_SESSION['reportListURL'] = $BackURL;
$SQL .= ' ORDER BY reportID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$reportPath = '../PerUserData/report/' . date('Y-m', $Row['reportTime']) . '/' . date('d', $Row['reportTime']) . '/';

	if (!file_exists($reportPath . md5($Row['reportFile']))) {
		if (!file_exists($reportPath . $Row['reportFile'])) {
			$EnableQCoreClass->replace('reportName', $Row['reportName'] . '&nbsp;<img src="../Images/hide.gif" border=0>');
		}
		else {
			$EnableQCoreClass->replace('reportName', $Row['reportName']);
		}
	}
	else {
		$EnableQCoreClass->replace('reportName', $Row['reportName']);
	}

	$Admin_SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$Admin_Row = $DB->queryFirstRow($Admin_SQL);

	if (!$Admin_Row) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $Admin_Row['administratorsName']);
	}

	$EnableQCoreClass->replace('reportRecipient', count(explode(',', $Row['reportRecipient'])));
	$EnableQCoreClass->replace('reportTime', date('Y-m-d', $Row['reportTime']));
	$EnableQCoreClass->replace('viewURL', '?Action=View&reportID=' . $Row['reportID']);
	$EnableQCoreClass->replace('editURL', '?Action=Edit&reportID=' . $Row['reportID']);
	$EnableQCoreClass->replace('deleteURL', '?Action=Delete&reportID=' . $Row['reportID']);
	$EnableQCoreClass->parse('list', 'LIST', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('MainPage', 'MainPageFile');
$EnableQCoreClass->output('MainPage', false);

?>

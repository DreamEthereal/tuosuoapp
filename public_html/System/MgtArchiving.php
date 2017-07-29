<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
_checkroletype('1|2|5');
$thisProg = 'MgtArchiving.php';

if ($_SESSION['archivingListURL'] != '') {
	$EnableQCoreClass->replace('archivingListURL', $_SESSION['archivingListURL']);
	$archivingListURL = $_SESSION['archivingListURL'];
}
else {
	$EnableQCoreClass->replace('archivingListURL', $thisProg);
	$archivingListURL = $thisProg;
}

if ($_GET['Action'] == 'SurveyArchive') {
	@set_time_limit(0);

	if ($License['isEvalUsers']) {
		_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
	}

	_checkpassport('1|2|5', $_GET['surveyID']);
	$SQL = ' SELECT surveyID,surveyTitle,surveyName,administratorsID,isPublic,beginTime,endTime,lang,isCache,status,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Sure_Row = $DB->queryFirstRow($SQL);

	if ($Sure_Row['status'] != '2') {
		_showerror('状态错误', '状态错误：问卷当前的状态尚不能执行归档操作，请确定您目前操作的正确？');
	}

	if ($Sure_Row['custDataPath'] == '') {
		$dataPathName = 'response_' . $Sure_Row['surveyID'];

		if (!createdir($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/')) {
			_showerror($lang['new_dir_error'], $lang['cannot_new_a_dir']);
		}
	}
	else {
		$dataPathName = 'user/' . $Sure_Row['custDataPath'];

		if (!createdir($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/')) {
			_showerror($lang['new_dir_error'], $lang['cannot_new_a_dir']);
		}
	}

	$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
	$SerialRow = $DB->queryFirstRow($SQL);
	$surveyPageURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23) . 'Archive/SurveyPageArchive.php?qname=' . $Sure_Row['surveyName'] . '&qlang=' . $Sure_Row['lang'] . '&hash_code=' . md5(trim($SerialRow['license']));
	$pageFileContent = get_url_content($surveyPageURL);
	$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_page_' . $Sure_Row['surveyName'] . '.html', 'w+');
	fwrite($fp, $pageFileContent);
	fclose($fp);
	include_once ROOT_PATH . 'Functions/Functions.export.inc.php';
	include_once ROOT_PATH . 'Functions/Functions.trace.inc.php';
	include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
	if (($Sure_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sure_Row['surveyID'] . '/' . md5('Qtn' . $Sure_Row['surveyID']) . '.php')) {
		$theSID = $Sure_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sure_Row['surveyID'] . '/' . md5('Qtn' . $Sure_Row['surveyID']) . '.php';
	$txtFileContent = export_text($Sure_Row['surveyID']);
	$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_question_' . $Sure_Row['surveyName'] . '.txt', 'w+');
	fwrite($fp, $txtFileContent);
	fclose($fp);
	$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Sure_Row['surveyID'] . ' ';
	$R_Row = $DB->queryFirstRow($R_SQL);

	if ($R_Row['resultNum'] != 0) {
		$surveyCountURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23) . 'Archive/ResultPageArchive.php?qname=' . $Sure_Row['surveyName'] . '&printType=all&hash_code=' . md5(trim($SerialRow['license']));
		$pageFileContent = get_url_content($surveyCountURL);
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_result_' . $Sure_Row['surveyName'] . '.html', 'w+');
		fwrite($fp, $pageFileContent);
		fclose($fp);
		$labelFileContent = export_label($Sure_Row['surveyID']);
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_label_' . $Sure_Row['surveyName'] . '.csv', 'w+');
		fwrite($fp, $labelFileContent);
		fclose($fp);
		$E_SQL = '1= 1';
		$resultFileContent = export($Sure_Row['surveyID'], stripslashes($E_SQL));
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_result_' . $Sure_Row['surveyName'] . '.csv', 'w+');
		fwrite($fp, $resultFileContent);
		fclose($fp);
		$spssFileContent = export_spss($Sure_Row['surveyID'], stripslashes($E_SQL));
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_spss_' . $Sure_Row['surveyName'] . '.csv', 'w+');
		fwrite($fp, $spssFileContent);
		fclose($fp);
		$traceFileContent = exporttrace($Sure_Row['surveyID'], stripslashes($E_SQL), '');
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_trace_' . $Sure_Row['surveyName'] . '.csv', 'w+');
		fwrite($fp, $traceFileContent);
		fclose($fp);
		$authFileContent = exportprocess($Sure_Row['surveyID'], stripslashes($E_SQL), '');
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_auth_' . $Sure_Row['surveyName'] . '.csv', 'w+');
		fwrite($fp, $authFileContent);
		fclose($fp);
		$awardFileContent = export_award($Sure_Row['surveyID']);
		$fp = fopen($Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/' . $dataPathName . '/survey_award_' . $Sure_Row['surveyName'] . '.csv', 'w+');
		fwrite($fp, $awardFileContent);
		fclose($fp);
	}

	include_once ROOT_PATH . 'Includes/Zip.class.php';
	$Ziper = new zip_file('../PerUserData/arc/survey_' . $Sure_Row['surveyName'] . '.zip');
	$Ziper->set_options(array('inmemory' => 0, 'recurse' => 1, 'storepaths' => 0, 'overwrite' => 1, 'type' => 'zip'));
	$Ziper->add_files('../' . $Config['dataDirectory'] . '/' . $dataPathName . '/');
	$ZipSucc = $Ziper->create_archive();

	if ($ZipSucc) {
		$SQL = ' INSERT INTO ' . ARCHIVING_TABLE . ' SET surveyID=\'' . $Sure_Row['surveyID'] . '\',surveyTitle=\'' . $Sure_Row['surveyTitle'] . '\',surveyName=\'' . $Sure_Row['surveyName'] . '\',administratorsID=\'' . $Sure_Row['administratorsID'] . '\',isPublic=\'' . $Sure_Row['isPublic'] . '\', beginTime=\'' . $Sure_Row['beginTime'] . '\',endTime=\'' . $Sure_Row['endTime'] . '\',archivingFile=\'survey_' . $Sure_Row['surveyName'] . '.zip\',archivingTime=\'' . time() . '\',archivingOwner=\'' . $_SESSION['administratorsID'] . '\' ';
		$DB->query($SQL);

		if (file_exists('../PerUserData/arc/survey_' . $Sure_Row['surveyName'] . '.zip')) {
			$survey_ID = $Sure_Row['surveyID'];
			require 'Survey.dele.php';
		}
	}
	else {
		$errorInfo = 'Could not create zip file：<br/>';

		foreach ($Ziper->error as $theErrorInfo) {
			$errorInfo .= $theErrorInfo . '<br/>';
		}

		_showerror('System Error', $errorInfo);
	}

	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['arch_survey'] . ':' . $Sure_Row['surveyTitle']);
	_showsucceed($lang['arch_survey'] . ':' . $Sure_Row['surveyTitle'], $archivingListURL);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' SELECT archivingFile,surveyTitle FROM ' . ARCHIVING_TABLE . ' WHERE archivingID= \'' . $_GET['archivingID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if (file_exists('../PerUserData/arc/' . $Row['archivingFile'])) {
		@unlink('../PerUserData/arc/' . $Row['archivingFile']);
	}

	$SQL = ' DELETE FROM ' . ARCHIVING_TABLE . ' WHERE archivingID= \'' . $_GET['archivingID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_arch_survey'] . ':' . $Row['surveyTitle']);
	_showsucceed($lang['delete_arch_survey'] . ':' . $Row['surveyTitle'], $archivingListURL);
}

if ($_GET['Action'] == 'View') {
	$EnableQCoreClass->setTemplateFile('SurveyViewFile', 'ArchivingView.html');
	$SQL = ' SELECT * FROM ' . ARCHIVING_TABLE . ' WHERE archivingID= \'' . $_GET['archivingID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyName', $Row['surveyName']);
	$EnableQCoreClass->replace('isPublic', $lang['isPublic_' . $Row['isPublic']]);
	$Admin_SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$Admin_Row = $DB->queryFirstRow($Admin_SQL);

	if (!$Admin_Row) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $Admin_Row['administratorsName']);
	}

	$Admin_SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['archivingOwner'] . '\' ';
	$Admin_Row = $DB->queryFirstRow($Admin_SQL);

	if (!$Admin_Row) {
		$EnableQCoreClass->replace('archivingOwner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('archivingOwner', $Admin_Row['administratorsName']);
	}

	$EnableQCoreClass->replace('archivingTime', date('Y-m-d', $Row['archivingTime']));
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$EnableQCoreClass->replace('endTime', $Row['endTime']);

	if (file_exists('../PerUserData/arc/' . $Row['archivingFile'])) {
		$EnableQCoreClass->replace('archivingSize', number_format(filesize('../PerUserData/arc/' . $Row['archivingFile']) / 1024, 2) . 'K');
		$archivingFile = '<a href="../WebAPI/Down.php?path=' . str_replace('+', '%2B', base64_encode('../PerUserData/arc/')) . '&file=' . str_replace('+', '%2B', base64_encode($Row['archivingFile'])) . '">';
		$archivingFile .= '<span class=red>' . $Row['archivingFile'] . '</span></a>';
		$EnableQCoreClass->replace('downFile', $archivingFile);
	}
	else {
		$EnableQCoreClass->replace('archivingSize', '0K');
		$EnableQCoreClass->replace('downFile', '已删除');
	}

	$EnableQCoreClass->parse('SurveyView', 'SurveyViewFile');
	$EnableQCoreClass->output('SurveyView');
}

$EnableQCoreClass->setTemplateFile('MainPageFile', 'ArchivingList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');
$ConfigRow['topicNum'] = 30;
$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('t_archivingTime', '');
$EnableQCoreClass->replace('t_archivingTimeEnd', '');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT * FROM ' . ARCHIVING_TABLE . ' WHERE 1=1 ';
	$EnableQCoreClass->replace('users_list', _getuserslist(0));
	break;

case '2':
	$SQL = ' SELECT * FROM ' . ARCHIVING_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
	$EnableQCoreClass->replace('users_list', '<option value=' . $_SESSION['administratorsID'] . ' selected>' . $_SESSION['administratorsName'] . '</option>');
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT * FROM ' . ARCHIVING_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ')';
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
	switch ($_POST['t_searchType']) {
	case '1':
	default:
		$name = trim($_POST['t_name']);
		$SQL .= ' AND surveyTitle LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', '');
		$page_others = '&t_searchType=1&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;

	case '2':
		$name = trim($_POST['t_name']);
		$SQL .= ' AND surveyName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', 'selected');
		$page_others = '&t_searchType=2&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;
	}

	if (trim($_POST['t_archivingTime']) != '') {
		$archivingTime = explode('-', $_POST['t_archivingTime']);
		$archivingUnixTime = mktime(0, 0, 0, $archivingTime[1], $archivingTime[2], $archivingTime[0]);
		$SQL .= ' AND archivingTime >= \'' . $archivingUnixTime . '\' ';
		$page_others .= '&t_archivingTime=' . $_POST['t_archivingTime'];
		$EnableQCoreClass->replace('t_archivingTime', $_POST['t_archivingTime']);
	}

	if (trim($_POST['t_archivingTimeEnd']) != '') {
		$archivingTime = explode('-', $_POST['t_archivingTimeEnd']);
		$archivingUnixTime = mktime(0, 0, 0, $archivingTime[1], $archivingTime[2], $archivingTime[0]);
		$SQL .= ' AND archivingTime <= \'' . $archivingUnixTime . '\' ';
		$page_others .= '&t_archivingTimeEnd=' . $_POST['t_archivingTimeEnd'];
		$EnableQCoreClass->replace('t_archivingTimeEnd', $_POST['t_archivingTimeEnd']);
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

if (isset($_GET['t_name']) && !$_POST['Action'] && ($_GET['t_searchType'] != '')) {
	switch ($_GET['t_searchType']) {
	case '1':
	default:
		$name = trim($_GET['t_name']);
		$SQL .= ' AND surveyTitle LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', '');
		$page_others = '&t_searchType=1&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;

	case '2':
		$name = trim($_GET['t_name']);
		$SQL .= ' AND surveyName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', 'selected');
		$page_others = '&t_searchType=2&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;
	}
}

if (isset($_GET['t_archivingTime']) && !$_POST['Action'] && ($_GET['t_archivingTime'] != '')) {
	$archivingTime = explode('-', $_GET['t_archivingTime']);
	$archivingUnixTime = mktime(0, 0, 0, $archivingTime[1], $archivingTime[2], $archivingTime[0]);
	$SQL .= ' AND archivingTime >= \'' . $archivingUnixTime . '\' ';
	$page_others .= '&t_archivingTime=' . $_GET['t_archivingTime'];
	$EnableQCoreClass->replace('t_archivingTime', $_GET['t_archivingTime']);
}

if (isset($_GET['t_archivingTimeEnd']) && !$_POST['Action'] && ($_GET['t_archivingTimeEnd'] != '')) {
	$archivingTime = explode('-', $_GET['t_archivingTimeEnd']);
	$archivingUnixTime = mktime(0, 0, 0, $archivingTime[1], $archivingTime[2], $archivingTime[0]);
	$SQL .= ' AND archivingTime <= \'' . $archivingUnixTime . '\' ';
	$page_others .= '&t_archivingTimeEnd=' . $_GET['t_archivingTimeEnd'];
	$EnableQCoreClass->replace('t_archivingTimeEnd', $_GET['t_archivingTimeEnd']);
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
$_SESSION['archivingListURL'] = $BackURL;
$SQL .= ' ORDER BY archivingID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('surveyName', $Row['surveyName']);

	if (!file_exists('../PerUserData/arc/' . $Row['archivingFile'])) {
		$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle'] . '&nbsp;<img src="../Images/hide.gif" border=0>');
	}
	else {
		$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	}

	$EnableQCoreClass->replace('isPublic', $lang['isPublic_' . $Row['isPublic']]);
	$Admin_SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$Admin_Row = $DB->queryFirstRow($Admin_SQL);

	if (!$Admin_Row) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $Admin_Row['administratorsName']);
	}

	$EnableQCoreClass->replace('archivingTime', date('Y-m-d', $Row['archivingTime']));
	$EnableQCoreClass->replace('viewURL', '?Action=View&archivingID=' . $Row['archivingID']);
	$EnableQCoreClass->replace('deleteURL', '?Action=Delete&archivingID=' . $Row['archivingID']);
	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('MainPage', 'MainPageFile');
$EnableQCoreClass->output('MainPage', false);

?>

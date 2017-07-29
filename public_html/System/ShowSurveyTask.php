<?php
//dezend by http://www.yunlu99.com/
function _getinputuserlist($userId = '')
{
	global $DB;
	global $EnableQCoreClass;
	global $theTreeDataRel;
	global $theTreeNodeName;
	$_obf_wBKL040Krb0_ = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.isAdmin=4 ORDER BY a.administratorsID ASC ';

	if ($userId == '0') {
		$_obf_Y83XB8t7ljpK = '<option value="0" selected>ÉÐÎÞÖ´ÐÐÈË</option>';
	}
	else {
		$_obf_Y83XB8t7ljpK = '<option value="0">ÉÐÎÞÖ´ÐÐÈË</option>';
	}

	$_obf_su9srHhWv7MY1Q__ = $DB->query($_obf_wBKL040Krb0_);

	while ($_obf_qqGBgL3FZA__ = $DB->queryArray($_obf_su9srHhWv7MY1Q__)) {
		$_obf_q1T4kyNSkEoe = _getuserallname($_obf_qqGBgL3FZA__['administratorsName'], $_obf_qqGBgL3FZA__['userGroupID'], $_obf_qqGBgL3FZA__['groupType']) . '(' . $_obf_qqGBgL3FZA__['nickName'] . ')';

		if ($_obf_qqGBgL3FZA__['administratorsID'] == $userId) {
			$_obf_Y83XB8t7ljpK .= '<option value=\'' . $_obf_qqGBgL3FZA__['administratorsID'] . '\' selected>' . $_obf_q1T4kyNSkEoe . '</option>';
		}
		else {
			$_obf_Y83XB8t7ljpK .= '<option value=\'' . $_obf_qqGBgL3FZA__['administratorsID'] . '\'>' . $_obf_q1T4kyNSkEoe . '</option>';
		}
	}

	$EnableQCoreClass->replace('userList', $_obf_Y83XB8t7ljpK);
}

function _obf_JwtgY31ifmN3fDBb($string)
{
	return str_replace('\'', '', str_replace('"', '', str_replace('\\', '', str_replace("\r", '', str_replace("\n", '', $string)))));
}

function _getinputusermatchlist($userId = '')
{
	global $DB;
	global $EnableQCoreClass;
	global $theTreeDataRel;
	global $theTreeNodeName;
	$_obf_wBKL040Krb0_ = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.isAdmin=4 ORDER BY a.administratorsID ASC ';
	$_obf_su9srHhWv7MY1Q__ = $DB->query($_obf_wBKL040Krb0_);
	$_obf_Y83XB8t7ljpK = '';

	while ($_obf_qqGBgL3FZA__ = $DB->queryArray($_obf_su9srHhWv7MY1Q__)) {
		$_obf_q1T4kyNSkEoe = _getuserallname($_obf_qqGBgL3FZA__['administratorsName'], $_obf_qqGBgL3FZA__['userGroupID'], $_obf_qqGBgL3FZA__['groupType']) . '(' . $_obf_qqGBgL3FZA__['nickName'] . ')';

		if ($_obf_qqGBgL3FZA__['administratorsID'] == $userId) {
			$_obf_Y83XB8t7ljpK .= '<option value=\'' . $_obf_qqGBgL3FZA__['administratorsID'] . '\' selected>' . $_obf_q1T4kyNSkEoe . '</option>';
		}
		else {
			$_obf_Y83XB8t7ljpK .= '<option value=\'' . $_obf_qqGBgL3FZA__['administratorsID'] . '\'>' . $_obf_q1T4kyNSkEoe . '</option>';
		}
	}

	$EnableQCoreClass->replace('input_user_list', $_obf_Y83XB8t7ljpK);
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5|6', $_GET['surveyID']);
$thisProg = 'ShowSurveyTask.php';
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('thisURL', $thisProg . '?' . $thisURLStr);
$SQL = ' SELECT status,surveyID,surveyTitle,surveyName,beginTime,endTime,projectType,projectOwner FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';

$Sure_Row = $DB->queryFirstRow($SQL);

switch ($Sure_Row['status']) {
case '0':
	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&beginTime=' . urlencode($Sure_Row['beginTime']) . '&endTime=' . urlencode($Sure_Row['endTime']) . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);
	$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('taskURL', $taskURL);
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;

case '1':
	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']) . '&beginTime=' . $Sure_Row['beginTime'] . '&endTime=' . $Sure_Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);
	$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('taskURL', $taskURL);
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;

case '2':
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('planURL', '');
	$EnableQCoreClass->replace('haveTask', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;
}

switch ($_SESSION['adminRoleType']) {
case 6:
	$EnableQCoreClass->replace('isAdmin6', 'none');
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;

default:
	$EnableQCoreClass->replace('isAdmin6', '');
	break;
}

if ($_POST['Action'] == 'ImportTaskSubmit') {
	if ($Sure_Row['projectType'] != '1') {
		_showerror('ÏµÍ³´íÎó', '·ÇÉñÃØ¿Í/°µ·Ã/Ã÷¼ìµÈµ÷ÑÐÏîÄ¿²»ÄÜµ¼ÈëÈÎÎñ·ÖÅäÊý¾Ý');
	}

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
		if ($csvLineNum != 0) {
			$csvData = qaddslashes($csvData, 1);
			$userGroupName = trim($csvData[0]);
			$taskID = trim($csvData[1]);
			$userName = trim($csvData[2]);

			if ($taskID == '') {
				continue;
			}

			if ($userName == '') {
				continue;
			}

			$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . $userName . '\' AND isAdmin=4 ';
			$Row = $DB->queryFirstRow($SQL);

			if (!$Row) {
				continue;
			}

			$theUserId = $Row['administratorsID'];
			$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $Sure_Row['projectOwner'] . '-%\' OR userGroupID = \'' . $Sure_Row['projectOwner'] . '\') ';
			$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE groupType = 2 AND ' . $theSonSQL . ' AND userGroupID = \'' . $taskID . '\' AND isLeaf=1 ';
			$Row = $DB->queryFirstRow($SQL);

			if (!$Row) {
				continue;
			}

			$thisTaskId = $Row['userGroupID'];
			$SQL = ' SELECT administratorsID FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID=\'' . $theUserId . '\' AND surveyID=\'' . $Sure_Row['surveyID'] . '\' LIMIT 0,1 ';
			$hRow = $DB->queryFirstRow($SQL);

			if (!$hRow) {
				if ($_POST['isPubUserTag'] == 1) {
					$SQL = ' INSERT INTO ' . INPUTUSERLIST_TABLE . ' SET administratorsID=\'' . $theUserId . '\', surveyID=\'' . $Sure_Row['surveyID'] . '\' ';
					$DB->query($SQL);
				}
				else {
					continue;
				}
			}

			$hSQL = ' SELECT administratorsID FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $Sure_Row['surveyID'] . '\' AND taskID = \'' . $thisTaskId . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($_POST['isUpdateTag'] == 1) {
				if ($hRow) {
					$SQL = ' UPDATE ' . TASK_TABLE . ' SET administratorsID = \'' . $theUserId . '\' WHERE surveyID=\'' . $Sure_Row['surveyID'] . '\' AND taskID = \'' . $thisTaskId . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . TASK_TABLE . ' SET administratorsID = \'' . $theUserId . '\',taskID=\'' . $thisTaskId . '\',surveyID=\'' . $Sure_Row['surveyID'] . '\' ';
					$DB->query($SQL);
				}

				$recNum++;
			}
			else {
				if ($hRow) {
					continue;
				}

				$SQL = ' INSERT INTO ' . TASK_TABLE . ' SET administratorsID = \'' . $theUserId . '\',taskID=\'' . $thisTaskId . '\',surveyID=\'' . $Sure_Row['surveyID'] . '\' ';
				$DB->query($SQL);
				$recNum++;
			}
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	writetolog($lang['new_import'] . $recNum . $lang['import_num']);
	_showmessage($lang['new_import'] . $recNum . $lang['import_num'], true);
}

if ($_GET['Action'] == 'ImportTask') {
	$EnableQCoreClass->setTemplateFile('UsersImportFile', 'SurveyTaskImport.html');
	$EnableQCoreClass->replace('projectOwner', $Sure_Row['projectOwner']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->parse('UsersImport', 'UsersImportFile');
	$EnableQCoreClass->output('UsersImport');
}

if ($_POST['formAction'] == 'SurveyTaskMatchSubmit') {
	if (is_array($_POST['taskID'])) {
		foreach ($_POST['taskID'] as $thisTaskId) {
			$hSQL = ' SELECT administratorsID FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND taskID = \'' . $thisTaskId . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				$SQL = ' UPDATE ' . TASK_TABLE . ' SET administratorsID = \'' . $_POST['tran_administratorsID'] . '\' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND taskID = \'' . $thisTaskId . '\' ';
			}
			else {
				$SQL = ' INSERT INTO ' . TASK_TABLE . ' SET administratorsID = \'' . $_POST['tran_administratorsID'] . '\',taskID=\'' . $thisTaskId . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
			}

			$DB->query($SQL);
		}

		writetolog($lang['survey_task_match'] . ':' . $_POST['surveyTitle']);
	}

	$taskURL = 'ShowSurveyTask.php?status=' . $Sure_Row['status'] . '&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	_showsucceed($lang['survey_task_match'] . ':' . $_POST['surveyTitle'], $taskURL . '&t_userGroupID=' . $_POST['t_userGroupID'] . '&t_userid=' . $_POST['t_userid']);
}

if ($_POST['formAction'] == 'CancelTaskSubmit') {
	if (is_array($_POST['taskID'])) {
		foreach ($_POST['taskID'] as $thisTaskId) {
			$SQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND taskID = \'' . $thisTaskId . '\'';
			$DB->query($SQL);
		}

		writetolog($lang['survey_task_cancel'] . ':' . $_POST['surveyTitle']);
	}

	$taskURL = 'ShowSurveyTask.php?status=' . $Sure_Row['status'] . '&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	_showsucceed($lang['survey_task_cancel'] . ':' . $_POST['surveyTitle'], $taskURL . '&t_userGroupID=' . $_POST['t_userGroupID'] . '&t_userid=' . $_POST['t_userid']);
}

$EnableQCoreClass->setTemplateFile('SurveyTaskListPageFile', 'SurveyTaskList.html');
$EnableQCoreClass->set_CycBlock('SurveyTaskListPageFile', 'TASK', 'task');
$EnableQCoreClass->replace('task', '');
$numPerPage = 50;
_getinputusermatchlist();
_getinputuserlist();
$EnableQCoreClass->replace('projectOwner', $Sure_Row['projectOwner']);

if ($Sure_Row['projectType'] == 1) {
	$EnableQCoreClass->replace('isProjectType1', '');
}
else {
	$EnableQCoreClass->replace('isProjectType1', 'none');
}

$EnableQCoreClass->replace('t_userGroupID', '\'\'');
$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $Sure_Row['projectOwner'] . '-%\' OR userGroupID = \'' . $Sure_Row['projectOwner'] . '\') ';

if ($Sure_Row['projectType'] == '1') {
	$SQL = ' SELECT * FROM ' . USERGROUP_TABLE . ' WHERE groupType = 2 AND ' . $theSonSQL . ' AND isLeaf=1 ';
}
else {
	$SQL = ' SELECT * FROM ' . USERGROUP_TABLE . ' WHERE userGroupID =0 ';
}



if ($_POST['Action'] == 'querySubmit') {
	if ($_POST['t_userid'] != 'all') {
		if ($_POST['t_userid'] != 0) {
			$iSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE administratorsID =\'' . $_POST['t_userid'] . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
			$iResult = $DB->query($iSQL);
			$iTask = array();

			while ($iRow = $DB->queryArray($iResult)) {
				$iTask[] = $iRow['taskID'];
			}

			if (count($iTask) == 0) {
				$SQL .= ' AND userGroupID = 0 ';
			}
			else {
				$SQL .= ' AND userGroupID IN ( ' . implode(',', $iTask) . ') ';
			}
		}
		else {
			$iSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
			$iResult = $DB->query($iSQL);
			$iTask = array();

			while ($iRow = $DB->queryArray($iResult)) {
				$iTask[] = $iRow['taskID'];
			}

			if (count($iTask) != 0) {
				$SQL .= ' AND userGroupID NOT IN ( ' . implode(',', $iTask) . ') ';
			}
		}

		$page_others = '&t_userid=' . $_POST['t_userid'];
		_getinputuserlist($_POST['t_userid']);
	}

	if (($_POST['t_userGroupID'] != '') && ($Sure_Row['projectType'] == '1')) {
		$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_POST['t_userGroupID'] . '-%\' OR userGroupID = \'' . $_POST['t_userGroupID'] . '\') ';
		$SQL .= ' AND ' . $theSonSQL;
		$page_others .= '&t_userGroupID=' . $_POST['t_userGroupID'];
		$EnableQCoreClass->replace('t_userGroupID', $_POST['t_userGroupID']);
	}
}

if (isset($_GET['t_userid']) && ($_GET['t_userid'] != 'all') && !$_POST['Action']) {
	if ($_GET['t_userid'] != 0) {
		$iSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE administratorsID =\'' . $_GET['t_userid'] . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$iResult = $DB->query($iSQL);
		$iTask = array();

		while ($iRow = $DB->queryArray($iResult)) {
			$iTask[] = $iRow['taskID'];
		}

		if (count($iTask) == 0) {
			$SQL .= ' AND userGroupID = 0 ';
		}
		else {
			$SQL .= ' AND userGroupID IN ( ' . implode(',', $iTask) . ') ';
		}
	}
	else {
		$iSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
		$iResult = $DB->query($iSQL);
		$iTask = array();

		while ($iRow = $DB->queryArray($iResult)) {
			$iTask[] = $iRow['taskID'];
		}

		if (count($iTask) != 0) {
			$SQL .= ' AND userGroupID NOT IN ( ' . implode(',', $iTask) . ') ';
		}
	}

	$page_others = '&t_userid=' . $_GET['t_userid'];
	_getinputuserlist($_GET['t_userid']);
}

if (isset($_GET['t_userGroupID']) && ($_GET['t_userGroupID'] != '') && ($Sure_Row['projectType'] == '1') && !$_POST['Action']) {
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['t_userGroupID'] . '-%\' OR userGroupID = \'' . $_GET['t_userGroupID'] . '\') ';
	$SQL .= ' AND ' . $theSonSQL;
	$page_others .= '&t_userGroupID=' . $_GET['t_userGroupID'];
	$EnableQCoreClass->replace('t_userGroupID', $_GET['t_userGroupID']);
}
$Result = $DB->query($SQL);
$recNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalRecNum', $recNum);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $numPerPage;
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY absPath ASC,userGroupID ASC LIMIT ' . $start . ',' . $numPerPage . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('taskName', $Row['userGroupName']);
	$EnableQCoreClass->replace('taskID', $Row['userGroupID']);

	if ($Row['userGroupDesc'] == '') {
		$EnableQCoreClass->replace('taskDesc', '&nbsp;');
	}
	else {
		$EnableQCoreClass->replace('taskDesc', $Row['userGroupDesc']);
	}

	$hSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.taskID = \'' . $Row['userGroupID'] . '\' AND b.surveyID = \'' . $Sure_Row['surveyID'] . '\' ';
	$hRow = $DB->queryFirstRow($hSQL);

	if (!$hRow) {
		$EnableQCoreClass->replace('bg_color', 'bgcolor=red');
		$EnableQCoreClass->replace('taskMan', $lang['no_task_man']);
	}
	else {
		$EnableQCoreClass->replace('bg_color', '');
		$EnableQCoreClass->replace('taskMan', _getuserallname($hRow['administratorsName'], $hRow['userGroupID'], $hRow['groupType']));
	}

	$EnableQCoreClass->parse('task', 'TASK', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recNum, $numPerPage);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('SurveyTaskListPage', 'SurveyTaskListPageFile');
$EnableQCoreClass->output('SurveyTaskListPage', false);

?>

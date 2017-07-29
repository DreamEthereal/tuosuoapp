<?php
//dezend by http://www.yunlu99.com/
function _getinputuserlist($userId = '')
{
	global $DB;
	global $EnableQCoreClass;
	global $theTreeDataRel;
	global $theTreeNodeName;

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
		$_obf_wBKL040Krb0_ = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.isAdmin=4 ORDER BY a.administratorsID ASC ';
		break;

	case '3':
	case '7':
		if ($_SESSION['adminRoleGroupType'] == 1) {
			$_obf_wBKL040Krb0_ = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.isAdmin=4 AND FIND_IN_SET( a.administratorsName,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ORDER BY a.administratorsID ASC ';
		}
		else {
			$_obf_wBKL040Krb0_ = ' SELECT DISTINCT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.isAdmin=4 AND b.taskID IN (' . implode(',', $_SESSION['adminSameGroupUsers']) . ') ORDER BY a.administratorsID ASC ';
		}

		$_obf_Y83XB8t7ljpK = '';
		break;
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

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,surveyID,surveyTitle,projectType,projectOwner,isViewAuthData FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

switch ($Sur_G_Row['status']) {
case '0':
	_showerror($lang['system_error'], $lang['design_survey_now']);
	break;

case '2':
	$EnableQCoreClass->replace('taskbtn', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	break;

case '1':
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		$EnableQCoreClass->replace('taskbtn', 'none');
		$EnableQCoreClass->replace('taskURL', '');
	}
	else {
		$EnableQCoreClass->replace('taskbtn', '');
		$taskURL = '../System/ShowSurveyTask.php?surveyID=' . $Sur_G_Row['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
	}

	break;
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'TaskResult.php?surveyID=' . $Sur_G_Row['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']);
$EnableQCoreClass->setTemplateFile('SurveyTaskListPageFile', 'SurveyTaskResult.html');
$EnableQCoreClass->set_CycBlock('SurveyTaskListPageFile', 'TASK', 'task');
$EnableQCoreClass->replace('task', '');
$numPerPage = 50;
_getinputuserlist();
$EnableQCoreClass->replace('projectOwner', $Sur_G_Row['projectOwner']);

if ($Sur_G_Row['projectType'] == 1) {
	$EnableQCoreClass->replace('isProjectType1', '');
}
else {
	$EnableQCoreClass->replace('isProjectType1', 'none');
}

$EnableQCoreClass->replace('t_userGroupID', '\'\'');
$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $Sur_G_Row['projectOwner'] . '-%\' OR a.userGroupID = \'' . $Sur_G_Row['projectOwner'] . '\') ';

if ($Sur_G_Row['projectType'] == '1') {
	$SQL = ' SELECT a.* FROM ' . USERGROUP_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.groupType = 2 AND ' . $theSonSQL . ' AND a.isLeaf=1 AND b.taskID = a.userGroupID AND b.surveyID = \'' . $_GET['surveyID'] . '\' ';
	$CountSQL = ' SELECT COUNT(*) FROM ' . USERGROUP_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.groupType = 2 AND ' . $theSonSQL . ' AND a.isLeaf=1 AND b.taskID = a.userGroupID AND b.surveyID = \'' . $_GET['surveyID'] . '\' ';
}
else {
	$SQL = ' SELECT * FROM ' . USERGROUP_TABLE . ' a WHERE a.userGroupID =0 ';
	$CountSQL = ' SELECT COUNT(*) FROM ' . USERGROUP_TABLE . ' a WHERE a.userGroupID =0 ';
}

switch ($_SESSION['adminRoleType']) {
case '3':
case '7':
	if ($_SESSION['adminRoleGroupType'] == 1) {
		$cSQL = ' SELECT a.taskID FROM ' . TASK_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND FIND_IN_SET( b.administratorsName,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') AND a.surveyID = \'' . $_GET['surveyID'] . '\' ';
		$cResult = $DB->query($cSQL);
		$theTaskArray = array();

		while ($cRow = $DB->queryArray($cResult)) {
			$theTaskArray[] = $cRow['taskID'];
		}

		if (count($theTaskArray) == 0) {
			$SQL .= ' AND a.userGroupID =0 ';
			$CountSQL .= ' AND a.userGroupID =0 ';
		}
		else {
			$SQL .= ' AND a.userGroupID IN (' . implode(',', $theTaskArray) . ') ';
			$CountSQL .= ' AND a.userGroupID IN (' . implode(',', $theTaskArray) . ') ';
		}
	}
	else {
		$SQL .= ' AND a.userGroupID IN (' . implode(',', $_SESSION['adminSameGroupUsers']) . ') ';
		$CountSQL .= ' AND a.userGroupID IN (' . implode(',', $_SESSION['adminSameGroupUsers']) . ') ';
	}

	break;
}

if ($_POST['Action'] == 'querySubmit') {
	if (isset($_POST['t_userid']) && ($_POST['t_userid'] != 'all')) {
		if ($_POST['t_userid'] != 0) {
			$iSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE administratorsID =\'' . $_POST['t_userid'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
			$iResult = $DB->query($iSQL);
			$iTask = array();

			while ($iRow = $DB->queryArray($iResult)) {
				$iTask[] = $iRow['taskID'];
			}

			if (count($iTask) == 0) {
				$SQL .= ' AND a.userGroupID = 0 ';
				$CountSQL .= ' AND a.userGroupID = 0 ';
			}
			else {
				$SQL .= ' AND a.userGroupID IN ( ' . implode(',', $iTask) . ') ';
				$CountSQL .= ' AND a.userGroupID IN ( ' . implode(',', $iTask) . ') ';
			}
		}

		$page_others = '&t_userid=' . $_POST['t_userid'];
		_getinputuserlist($_POST['t_userid']);
	}

	if (($_POST['t_userGroupID'] != '') && ($Sur_G_Row['projectType'] == '1')) {
		$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $_POST['t_userGroupID'] . '-%\' OR a.userGroupID = \'' . $_POST['t_userGroupID'] . '\') ';
		$SQL .= ' AND ' . $theSonSQL;
		$CountSQL .= ' AND ' . $theSonSQL;
		$page_others .= '&t_userGroupID=' . $_POST['t_userGroupID'];
		$EnableQCoreClass->replace('t_userGroupID', $_POST['t_userGroupID']);
	}
}

if (isset($_GET['t_userid']) && ($_GET['t_userid'] != 'all') && !$_POST['Action']) {
	if ($_GET['t_userid'] != 0) {
		$iSQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE administratorsID =\'' . $_GET['t_userid'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
		$iResult = $DB->query($iSQL);
		$iTask = array();

		while ($iRow = $DB->queryArray($iResult)) {
			$iTask[] = $iRow['taskID'];
		}

		if (count($iTask) == 0) {
			$SQL .= ' AND a.userGroupID = 0 ';
			$CountSQL .= ' AND a.userGroupID = 0 ';
		}
		else {
			$SQL .= ' AND a.userGroupID IN ( ' . implode(',', $iTask) . ') ';
			$CountSQL .= ' AND a.userGroupID IN ( ' . implode(',', $iTask) . ') ';
		}
	}

	$page_others = '&t_userid=' . $_GET['t_userid'];
	_getinputuserlist($_GET['t_userid']);
}

if (isset($_GET['t_userGroupID']) && ($_GET['t_userGroupID'] != '') && ($Sur_G_Row['projectType'] == '1') && !$_POST['Action']) {
	$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $_GET['t_userGroupID'] . '-%\' OR a.userGroupID = \'' . $_GET['t_userGroupID'] . '\') ';
	$SQL .= ' AND ' . $theSonSQL;
	$CountSQL .= ' AND ' . $theSonSQL;
	$page_others .= '&t_userGroupID=' . $_GET['t_userGroupID'];
	$EnableQCoreClass->replace('t_userGroupID', $_GET['t_userGroupID']);
}

$CountRow = $DB->queryFirstRow($CountSQL);
$recNum = $CountRow[0];
$EnableQCoreClass->replace('totalRecNum', $recNum);
$hSQL = ' SELECT taskID FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE overFlag IN (1,3) ';
$hResult = $DB->query($hSQL);
$theTaskOverArray = array();

while ($hRow = $DB->queryArray($hResult)) {
	$theTaskOverArray[] = $hRow['taskID'];
}

if (count($theTaskOverArray) == 0) {
	$totalRecNum1 = 0;
}
else {
	$cSQL = $CountSQL . ' AND userGroupID IN (' . implode(',', $theTaskOverArray) . ' ) ';
	$cRow = $DB->queryFirstRow($cSQL);
	$totalRecNum1 = $cRow[0];
}

$totalRecNum0 = $recNum - $totalRecNum1;
$EnableQCoreClass->replace('totalRecNum0', $totalRecNum0);
$EnableQCoreClass->replace('totalRecNum1', $totalRecNum1);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $numPerPage;
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY a.absPath ASC,a.userGroupID ASC LIMIT ' . $start . ',' . $numPerPage . ' ';
$Result = $DB->query($SQL);
$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$ViewBackURL = $thisProg . '&pageID=' . $pageID . $page_others;
$_SESSION['ViewBackURL'] = $ViewBackURL;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('taskID', $Row['userGroupID']);
	$EnableQCoreClass->replace('taskDesc', $Row['userGroupDesc'] == '' ? '&nbsp;' : $Row['userGroupDesc']);
	$hSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.taskID = \'' . $Row['userGroupID'] . '\' AND b.surveyID = \'' . $Sur_G_Row['surveyID'] . '\' ';
	$hRow = $DB->queryFirstRow($hSQL);

	if (!$hRow) {
		$EnableQCoreClass->replace('bg_color', 'bgcolor=red');
		$EnableQCoreClass->replace('taskMan', $lang['no_task_man']);
	}
	else {
		$EnableQCoreClass->replace('bg_color', '');
		$EnableQCoreClass->replace('taskMan', $hRow['administratorsName']);
	}

	$hSQL = ' SELECT responseID,joinTime,submitTime,authStat FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID = \'' . $Row['userGroupID'] . '\' AND overFlag IN (1,3) LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow) {
		$EnableQCoreClass->replace('taskTime', date('Y-m-d H:i:s', $hRow['submitTime']));
		$EnableQCoreClass->replace('status_color', '#339933');
		$authStat = $lang['authStat_' . $hRow['authStat']];
		$EnableQCoreClass->replace('status', '<font color=white>…Û∫À:' . $authStat . '</font>');
		$viewURL = '../Analytics/DataList.php?surveyID=' . $Sur_G_Row['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']) . '&Does=View&responseID=' . $hRow['responseID'];

		switch ($_SESSION['adminRoleType']) {
		case '1':
		case '2':
		case '5':
			$EnableQCoreClass->replace('taskName', '<a href="' . $viewURL . '" target=_blank>' . $Row['userGroupName'] . '</a>');
			break;

		case '3':
		case '7':
			if ($Sur_G_Row['isViewAuthData'] == 1) {
				if ($hRow['authStat'] == 1) {
					$EnableQCoreClass->replace('taskName', '<a href="' . $viewURL . '" target=_blank>' . $Row['userGroupName'] . '</a>');
				}
				else {
					$EnableQCoreClass->replace('taskName', $Row['userGroupName']);
				}
			}
			else {
				$EnableQCoreClass->replace('taskName', '<a href="' . $viewURL . '" target=_blank>' . $Row['userGroupName'] . '</a>');
			}

			break;
		}
	}
	else {
		$EnableQCoreClass->replace('taskTime', '&nbsp;');
		$EnableQCoreClass->replace('status_color', '#FFE000');
		$EnableQCoreClass->replace('status', '<font color=black>Œ¥ÕÍ≥…</font>');
		$EnableQCoreClass->replace('taskName', $Row['userGroupName']);
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

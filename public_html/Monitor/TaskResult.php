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
		$_obf_aA9HmsCR8up52TM_ = _getuserallname($_obf_qqGBgL3FZA__['administratorsName'], $_obf_qqGBgL3FZA__['userGroupID'], $_obf_qqGBgL3FZA__['groupType']);
		$_obf_q1T4kyNSkEoe = cnsubstr($_obf_aA9HmsCR8up52TM_, 5) . '(' . $_obf_qqGBgL3FZA__['nickName'] . ')';

		if ($_obf_qqGBgL3FZA__['administratorsID'] == $userId) {
			$_obf_Y83XB8t7ljpK .= '<option value=\'' . $_obf_qqGBgL3FZA__['administratorsID'] . '\' selected>' . $_obf_q1T4kyNSkEoe . '</option>';
		}
		else {
			$_obf_Y83XB8t7ljpK .= '<option value=\'' . $_obf_qqGBgL3FZA__['administratorsID'] . '\'>' . $_obf_q1T4kyNSkEoe . '</option>';
		}
	}

	$EnableQCoreClass->replace('t_user_List', $_obf_Y83XB8t7ljpK);
}

function _getusernode($t_userGroupID = 0)
{
	global $DB;
	global $Sur_G_Row;
	global $EnableQCoreClass;

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
	case '7':
		$_obf_ocyPpAUgokWv_g__ = $Sur_G_Row['projectOwner'];
		$_obf__So1aNSOfXQ_ = 1;
		break;

	case '3':
		switch ($_SESSION['adminRoleGroupType']) {
		case 1:
			$_obf_ocyPpAUgokWv_g__ = $Sur_G_Row['projectOwner'];
			$_obf__So1aNSOfXQ_ = 1;
			break;

		case 2:
			$_obf_ocyPpAUgokWv_g__ = $_SESSION['adminRoleGroupID'];
			$_obf_ILrbVw__ = ' SELECT userGroupID,userGroupName,isLeaf,absPath,groupType FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $_obf_ocyPpAUgokWv_g__ . '\' ';
			$_obf_sThE0g__ = $DB->queryFirstRow($_obf_ILrbVw__);

			if ($_obf_sThE0g__['isLeaf'] == 1) {
				$_obf__So1aNSOfXQ_ = 2;
			}
			else {
				$_obf__So1aNSOfXQ_ = 1;
			}

			break;
		}

		break;
	}

	$_obf_ZmcUn_PNGeagELEHv4f_UGFM = '';

	if ($_obf__So1aNSOfXQ_ == 1) {
		$_obf_xCnI = ' SELECT userGroupID,userGroupName,isLeaf,absPath,groupType FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND fatherId = \'' . $_obf_ocyPpAUgokWv_g__ . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

		while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
			$_obf_23sGkrjAtw__ = true;

			if ($_obf_9WwQ['isLeaf'] == 1) {
				$_obf_OWpxVw__ = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND taskID = \'' . $_obf_9WwQ['userGroupID'] . '\' LIMIT 1 ';
				$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

				if (!$_obf__aTmJQ__) {
					$_obf_23sGkrjAtw__ = false;
				}
			}

			if ($_obf_23sGkrjAtw__ == true) {
				$_obf_8XOV4EeOyOI_ = _getnodeallname($_obf_9WwQ['absPath'], $_obf_9WwQ['userGroupName'], $_obf_9WwQ['groupType']);

				if ($_obf_9WwQ['userGroupID'] == $t_userGroupID) {
					$_obf_ZmcUn_PNGeagELEHv4f_UGFM .= '<option value=\'' . $_obf_9WwQ['userGroupID'] . '\' selected>' . cnsubstr($_obf_8XOV4EeOyOI_, 5) . '</option>' . "\n" . '';
				}
				else {
					$_obf_ZmcUn_PNGeagELEHv4f_UGFM .= '<option value=\'' . $_obf_9WwQ['userGroupID'] . '\'>' . cnsubstr($_obf_8XOV4EeOyOI_, 5) . '</option>' . "\n" . '';
				}
			}
		}
	}
	else {
		$_obf_OWpxVw__ = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND taskID = \'' . $_obf_sThE0g__['userGroupID'] . '\' LIMIT 1 ';
		$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

		if ($_obf__aTmJQ__) {
			$_obf_8XOV4EeOyOI_ = _getnodeallname($_obf_sThE0g__['absPath'], $_obf_sThE0g__['userGroupName'], $_obf_sThE0g__['groupType']);

			if ($_obf_sThE0g__['userGroupID'] == $t_userGroupID) {
				$_obf_ZmcUn_PNGeagELEHv4f_UGFM .= '<option value=\'' . $_obf_sThE0g__['userGroupID'] . '\'>' . cnsubstr($_obf_8XOV4EeOyOI_, 5) . '</option>' . "\n" . '';
			}
			else {
				$_obf_ZmcUn_PNGeagELEHv4f_UGFM .= '<option value=\'' . $_obf_sThE0g__['userGroupID'] . '\'>' . cnsubstr($_obf_8XOV4EeOyOI_, 5) . '</option>' . "\n" . '';
			}
		}
	}

	$EnableQCoreClass->replace('t_userGroupID_List', $_obf_ZmcUn_PNGeagELEHv4f_UGFM);
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.monitor.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,surveyID,surveyTitle,projectType,projectOwner,isViewAuthData FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'TaskResult.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
@set_time_limit(0);

if ($License['isMonitor'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyTaskListPageFile', 'uMonitorTaskResult.html');
$EnableQCoreClass->set_CycBlock('SurveyTaskListPageFile', 'TASK', 'task');
$EnableQCoreClass->replace('task', '');
$numPerPage = 30;
$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $Sur_G_Row['projectOwner'] . '-%\' OR a.userGroupID = \'' . $Sur_G_Row['projectOwner'] . '\') ';

if ($Sur_G_Row['projectType'] == '1') {
	$SQL = ' SELECT a.* FROM ' . USERGROUP_TABLE . ' a,' . TASK_TABLE . ' b WHERE a.groupType = 2 AND ' . $theSonSQL . ' AND a.isLeaf=1 AND b.taskID = a.userGroupID AND b.surveyID = \'' . $_GET['surveyID'] . '\' ';
}
else {
	$SQL = ' SELECT * FROM ' . USERGROUP_TABLE . ' WHERE userGroupID =0 ';
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
		}
		else {
			$SQL .= ' AND a.userGroupID IN (' . implode(',', $theTaskArray) . ') ';
		}
	}
	else {
		$SQL .= ' AND a.userGroupID IN (' . implode(',', $_SESSION['adminSameGroupUsers']) . ') ';
	}

	break;
}

_getinputuserlist();
_getusernode();

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
			}
			else {
				$SQL .= ' AND a.userGroupID IN ( ' . implode(',', $iTask) . ') ';
			}
		}

		$page_others = '&t_userid=' . $_POST['t_userid'];
		_getinputuserlist($_POST['t_userid']);
	}

	if (isset($_POST['t_userGroupID']) && ($_POST['t_userGroupID'] != 'all') && ($Sur_G_Row['projectType'] == '1')) {
		$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $_POST['t_userGroupID'] . '-%\' OR a.userGroupID = \'' . $_POST['t_userGroupID'] . '\') ';
		$SQL .= ' AND ' . $theSonSQL;
		$page_others .= '&t_userGroupID=' . $_POST['t_userGroupID'];
		_getusernode($_POST['t_userGroupID']);
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
		}
		else {
			$SQL .= ' AND a.userGroupID IN ( ' . implode(',', $iTask) . ') ';
		}
	}

	$page_others = '&t_userid=' . $_GET['t_userid'];
	_getinputuserlist($_GET['t_userid']);
}

if (isset($_GET['t_userGroupID']) && ($_GET['t_userGroupID'] != 'all') && ($Sur_G_Row['projectType'] == '1') && !$_POST['Action']) {
	$theSonSQL = '( concat(\'-\',a.absPath,\'-\') LIKE \'%-' . $_GET['t_userGroupID'] . '-%\' OR a.userGroupID = \'' . $_GET['t_userGroupID'] . '\') ';
	$SQL .= ' AND ' . $theSonSQL;
	$page_others .= '&t_userGroupID=' . $_GET['t_userGroupID'];
	_getusernode($_GET['t_userGroupID']);
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);
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
		$EnableQCoreClass->replace('taskName', $Row['userGroupName']);
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
$PAGES = new PageBar($recordCount, $numPerPage);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('SurveyTaskListPage', 'SurveyTaskListPageFile');
$EnableQCoreClass->output('SurveyTaskListPage', false);

?>

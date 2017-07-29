<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|2|5|6|3|7');
header('Content-Type:text/html; charset=gbk');
$_GET['projectOwner'] = (int) $_GET['projectOwner'];
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxUserNodes.php?projectOwner=' . $_GET['projectOwner'] . '&surveyID=' . $_GET['surveyID'] . '&t_name=' . $_GET['t_name'];

switch ($_SESSION['adminRoleType']) {
case '1':
case '2':
case '5':
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['projectOwner'] . '-%\' OR userGroupID = \'' . $_GET['projectOwner'] . '\') ';
	$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND ' . $theSonSQL . ' AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' ORDER BY absPath ASC,userGroupID ASC ';
	break;

case '3':
case '7':
	if ($_SESSION['adminRoleGroupType'] == 1) {
		$cSQL = ' SELECT a.taskID FROM ' . TASK_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.surveyID=\'' . $_GET['surveyID'] . '\' AND a.administratorsID = b.administratorsID AND FIND_IN_SET( b.administratorsName,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ';
		$cResult = $DB->query($cSQL);
		$theTaskArray = array();

		while ($cRow = $DB->queryArray($cResult)) {
			$theTaskArray[] = $cRow['taskID'];
		}

		if (count($theTaskArray) == 0) {
			$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID=0 ORDER BY absPath ASC,userGroupID ASC ';
		}
		else {
			$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['projectOwner'] . '-%\' OR userGroupID = \'' . $_GET['projectOwner'] . '\') ';
			$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND ' . $theSonSQL . ' AND userGroupID IN (' . implode(',', $theTaskArray) . ') AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' ORDER BY absPath ASC,userGroupID ASC ';
		}
	}
	else {
		$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
		$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND ' . $theSonSQL . ' AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' ORDER BY absPath ASC,userGroupID ASC ';
	}

	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);

if (50 <= $recordCount) {
	exit('false######匹配的节点>=50,请重新输入关键字以缩小检索规模');
}

switch ($_GET['type']) {
case 'datamatch':
	$usersList = '<select name="t_userGroupID_' . $_GET['textType'] . '" id="t_userGroupID_' . $_GET['textType'] . '" style="width:535px;*width:545px">\\n';
	break;

case 'datarank':
	$usersList = '<select name="t_userGroupID" id="t_userGroupID" style="width:435px;*width:445px" onchange="javascript:changeGroupNodes();">\\n';
	break;

default:
	$usersList = '<select name="t_userGroupID" id="t_userGroupID" style="width:435px;*width:445px">\\n';
	break;
}

if (($_SESSION['adminRoleType'] == 3) && ($_SESSION['adminRoleGroupType'] == 2)) {
	$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\' ';
	$gRow = $DB->queryFirstRow($gSQL);
	$nodesName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
	$usersList .= '<option value=\'' . $_SESSION['adminRoleGroupID'] . '\'>' . $nodesName . '</option>' . "\n" . '';
}
else {
	$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['projectOwner'] . '\' ';
	$gRow = $DB->queryFirstRow($gSQL);
	$nodesName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
	$usersList .= '<option value=\'' . $_GET['projectOwner'] . '\'>' . $nodesName . '</option>' . "\n" . '';
}

while ($Row = $DB->queryArray($Result)) {
	if (($_SESSION['adminRoleType'] == 3) && ($_SESSION['adminRoleGroupType'] == 2)) {
		if ($Row['userGroupID'] != $_SESSION['adminRoleGroupID']) {
			$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], 2);

			if (trim($_GET['t_name']) == trim($Row['userGroupName'])) {
				$usersList .= '<option value=\'' . $Row['userGroupID'] . '\' selected>' . $nodesName . '</option>\\n';
			}
			else {
				$usersList .= '<option value=\'' . $Row['userGroupID'] . '\'>' . $nodesName . '</option>\\n';
			}
		}
	}
	else if ($Row['userGroupID'] != $_GET['projectOwner']) {
		$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], 2);

		if (trim($_GET['t_name']) == trim($Row['userGroupName'])) {
			$usersList .= '<option value=\'' . $Row['userGroupID'] . '\' selected>' . $nodesName . '</option>\\n';
		}
		else {
			$usersList .= '<option value=\'' . $Row['userGroupID'] . '\'>' . $nodesName . '</option>\\n';
		}
	}
}

$usersList .= '</select>';
echo 'true######' . $usersList;
exit();

?>

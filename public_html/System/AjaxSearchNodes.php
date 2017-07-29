<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|5|6');
header('Content-Type:text/html; charset=gbk');
$_GET['groupType'] = (int) $_GET['groupType'];

switch ($_SESSION['adminRoleType']) {
case 1:
case 6:
	$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' ORDER BY absPath ASC,userGroupID ASC  ';
	break;

case 5:
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
	$SQL = ' SELECT userGroupID,userGroupName,absPath FROM ' . USERGROUP_TABLE . ' WHERE groupType=\'' . $_GET['groupType'] . '\' AND userGroupName LIKE \'%' . trim($_GET['t_name']) . '%\' AND ' . $theSonSQL . ' ORDER BY absPath ASC,userGroupID ASC  ';
	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);

if (50 <= $recordCount) {
	exit('false######匹配的节点>=50,请重新输入关键字以缩小检索规模');
}

$usersList = 'true######<select name="tran_userGroupID" id="tran_userGroupID" style="width:365px;*width:375px">\\n';

switch ($_GET['groupType']) {
case 1:
	$nodeName = $lang['corp_root_node'];
	break;

case 2:
	$nodeName = $lang['cus_root_node'];
	break;
}

switch ($_SESSION['adminRoleType']) {
case 1:
case 6:
	$usersList .= '<option value=\'0\'>' . $nodeName . '</option>' . "\n" . '';
	break;

case 5:
	if ($_SESSION['adminRoleGroupID'] == 0) {
		$usersList .= '<option value=\'0\'>' . $nodeName . '</option>' . "\n" . '';
	}
	else {
		$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\' ';
		$gRow = $DB->queryFirstRow($gSQL);
		$nodesName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
		$usersList .= '<option value=\'' . $_SESSION['adminRoleGroupID'] . '\'>' . $nodesName . '</option>' . "\n" . '';
	}

	break;
}

while ($Row = $DB->queryArray($Result)) {
	$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $_GET['groupType']);

	switch ($_SESSION['adminRoleType']) {
	case 1:
	case 6:
		$usersList .= '<option value=\'' . $Row['userGroupID'] . '\'>' . $nodesName . '</option>\\n';
		break;

	case 5:
		if ($Row['userGroupID'] != $_SESSION['adminRoleGroupID']) {
			$usersList .= '<option value=\'' . $Row['userGroupID'] . '\'>' . $nodesName . '</option>\\n';
		}

		break;
	}
}

$usersList .= '</select>';
exit($usersList);

?>

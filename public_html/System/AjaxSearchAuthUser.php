<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|2|5|6');
$_GET['surveyID'] = (int) $_GET['surveyID'];
$SQL = ' SELECT a.isAdmin,a.userGroupID,a.administratorsName FROM ' . ADMINISTRATORS_TABLE . ' a,' . SURVEY_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' ';
$AuthRow = $DB->queryFirstRow($SQL);

switch ($AuthRow['isAdmin']) {
case '1':
	$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =7 AND groupType=1 AND administratorsName LIKE \'%' . trim($_GET['q_userName']) . '%\' ORDER BY administratorsID ASC ';
	break;

case '2':
case '5':
	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '6':
		$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =7 AND groupType=1 AND administratorsName LIKE \'%' . trim($_GET['q_userName']) . '%\' ORDER BY administratorsID ASC ';
		break;

	default:
		if ($AuthRow['userGroupID'] == 0) {
			$theSonSQL = ' concat(\'-\',absPath,\'-\') LIKE \'%-' . $AuthRow['userGroupID'] . '-%\' ';
			$theSonGroup = array();
			$theSonGroup[] = 0;
			$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
			$sResult = $DB->query($sSQL);

			while ($sRow = $DB->queryArray($sResult)) {
				$theSonGroup[] = $sRow['userGroupID'];
			}

			$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin=7 AND groupType=1 AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND administratorsName LIKE \'%' . trim($_GET['q_userName']) . '%\' ORDER BY administratorsID ASC ';
		}
		else {
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $AuthRow['userGroupID'] . '-%\' OR b.userGroupID = \'' . $AuthRow['userGroupID'] . '\') ';
			$UserSQL = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =7 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID AND a.groupType =1 AND a.administratorsName LIKE \'%' . trim($_GET['q_userName']) . '%\' ORDER BY b.absPath ASC,a.administratorsID ASC ';
		}

		break;
	}

	break;
}

$UserResult = $DB->query($UserSQL);
$usersList = '<select multiple name="viewNameList[]" id="viewNameList" size=23 style="width:348px;*width:358px">\\n';

while ($UserRow = $DB->queryArray($UserResult)) {
	$HaveSQL = ' SELECT administratorsID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID=\'' . $UserRow['administratorsID'] . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' AND isAuth=1 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if (!$HaveRow) {
		$administrators_Name = _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']);
		$usersList .= '<option value=\'' . $UserRow['administratorsID'] . '\'>' . $administrators_Name . '(' . $UserRow['nickName'] . ')</option>\\n';
	}
}

$usersList .= '</select>';
header('Content-Type:text/html; charset=gbk');
exit($usersList);

?>

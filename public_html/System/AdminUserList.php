<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|5|6');
$EnableQCoreClass->setTemplateFile('ControlPageFile', 'AdminUserList.html');

switch ($_SESSION['adminRoleType']) {
case 1:
	$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin NOT IN (0,1) LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow[0] != 0) {
		$EnableQCoreClass->replace('haveNotAdmin1', 0);
	}
	else {
		$EnableQCoreClass->replace('haveNotAdmin1', 1);
	}

	break;

case 6:
	$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin NOT IN (0,1,6) LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow[0] != 0) {
		$EnableQCoreClass->replace('haveNotAdmin1', 0);
	}
	else {
		$EnableQCoreClass->replace('haveNotAdmin1', 1);
	}

	break;

case 5:
	if ($_SESSION['adminRoleGroupID'] == 0) {
		$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' ) ';
		$theSonGroup = array();
		$theSonGroup[] = 0;
		$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$theSonGroup[] = $sRow['userGroupID'];
		}

		$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin NOT IN (0,1,6) AND administratorsID != \'' . $_SESSION['administratorsID'] . '\' AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 LIMIT 1 ';
	}
	else {
		$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
		$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND a.administratorsID != \'' . $_SESSION['administratorsID'] . '\' AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID AND b.groupType =1 LIMIT 1 ';
	}

	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow[0] != 0) {
		$EnableQCoreClass->replace('haveNotAdmin1', 0);
	}
	else {
		$EnableQCoreClass->replace('haveNotAdmin1', 1);
	}

	break;
}

$MainPage = $EnableQCoreClass->parse('ControlPage', 'ControlPageFile');
echo $MainPage;

?>

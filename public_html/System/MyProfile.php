<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
$thisProg = 'MyProfile.php';
_checkroletype('1|2|3|4|5|6|7');
$EnableQCoreClass->setTemplateFile('MyProfileFile', 'MyProfile.html');
$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);
$EnableQCoreClass->replace('nickName', $Row['nickName']);
$EnableQCoreClass->replace('isAdmin', $lang['isAdmin_' . $Row['isAdmin']]);
$EnableQCoreClass->replace('byUserID', getbyusername($Row['byUserID']));

if ($Row['userGroupID'] != 0) {
	$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $Row['userGroupID'] . '\' ';
	$gRow = $DB->queryFirstRow($gSQL);
	$EnableQCoreClass->replace('nodeName', _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']));
}
else {
	$EnableQCoreClass->replace('nodeName', getusergroupname($Row['userGroupID'], $Row['groupType']));
}

$EnableQCoreClass->replace('createDate', date('Y-m-d H:i:s', $Row['createDate']));
$EnableQCoreClass->replace('lastVisited', date('Y-m-d H:i:s', $Row['lastVisitTime']));
$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
$EnableQCoreClass->replace('loginNum', $Row['loginNum']);
$EnableQCoreClass->parse('MyProfilePage', 'MyProfileFile');
$EnableQCoreClass->output('MyProfilePage', false);

?>

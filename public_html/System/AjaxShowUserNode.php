<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|2|5|6|3|7');
header('Content-Type:text/html; charset=gbk');
$_GET['t_userGroupID'] = (int) $_GET['t_userGroupID'];
$thisProg = 'AjaxShowUserNode.php?t_userGroupID=' . $_GET['t_userGroupID'];
$usersList = '<select name="t_userGroupID" id="t_userGroupID" style="width:435px;*width:445px">\\n';
$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['t_userGroupID'] . '\' ';
$gRow = $DB->queryFirstRow($gSQL);
$nodesName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
$usersList .= '<option value=\'' . $_GET['t_userGroupID'] . '\'>' . $nodesName . '</option>' . "\n" . '';
$usersList .= '</select>';
echo $usersList;

?>

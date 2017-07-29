<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5|3|7');
header('Content-Type:text/html; charset=gbk');
$_GET['userGroupID'] = (int) $_GET['userGroupID'];
$thisProg = 'AjaxGroupLabel.php?userGroupID=' . $_GET['userGroupID'];
$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['userGroupID'] . '-%\' OR userGroupID = \'' . $_GET['userGroupID'] . '\') ';
$lSQL = ' SELECT DISTINCT userGroupLabel FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' ORDER BY absPath ASC,userGroupID ASC ';
$lResult = $DB->query($lSQL);
$userGroupLabel_list = '<select name="userGroupLabel" id="userGroupLabel" style="width:435px;*width:445px"><option value=\'\'>«Î—°‘Ò...</option>';

while ($lRow = $DB->queryArray($lResult)) {
	$userGroupLabel = str_replace('"', '', trim($lRow['userGroupLabel']));

	if ($userGroupLabel != '') {
		$userGroupLabel_list .= '<option value=\'' . $userGroupLabel . '\'>' . $userGroupLabel . '</option>';
	}
}

$userGroupLabel_list .= '</select>';
exit($userGroupLabel_list);

?>

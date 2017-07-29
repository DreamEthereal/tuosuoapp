<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$thisProg = 'MembersConfig.php';
_checkroletype('1|6');
$SQL = ' SELECT * FROM ' . ADMINISTRATORSCONFIG_TABLE . ' ';
$Row = $DB->queryFirstRow($SQL);

if ($_POST['Action'] == 'ConfigtureSubmit') {
	if ($Row) {
		$SQL = ' UPDATE ' . ADMINISTRATORSCONFIG_TABLE . ' SET isNotRegister=\'' . $_POST['isNotRegister'] . '\',isUseEmailPass=\'' . $_POST['isUseEmailPass'] . '\',defaultGroupID=\'' . $_POST['administratorsGroupID'] . '\',registerText=\'' . $_POST['registerText'] . '\',isActive=\'' . $_POST['isActive'] . '\',mainAttribute=\'' . implode(',', $_POST['mainAttribute']) . '\' WHERE administratorsConfigID=\'' . $_POST['administratorsConfigID'] . '\' ';
	}
	else {
		$SQL = ' INSERT INTO ' . ADMINISTRATORSCONFIG_TABLE . ' SET isNotRegister=\'' . $_POST['isNotRegister'] . '\',isUseEmailPass=\'' . $_POST['isUseEmailPass'] . '\',defaultGroupID=\'' . $_POST['administratorsGroupID'] . '\',registerText=\'' . $_POST['registerText'] . '\',isActive=\'' . $_POST['isActive'] . '\',mainAttribute=\'' . implode(',', $_POST['mainAttribute']) . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['members_config']);
	_showsucceed($lang['members_config'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('MembersConfigPageFile', 'MembersConfig.html');

if ($Row['isNotRegister'] == 1) {
	$EnableQCoreClass->replace('isNotRegister', 'selected');
}

if ($Row['isUseEmailPass'] == 0) {
	$EnableQCoreClass->replace('isUseEmailPass', 'selected');
}

if ($Row['isActive'] == 0) {
	$EnableQCoreClass->replace('isActive', 'selected');
}

$SQL = ' SELECT administratorsoptionID,optionFieldName FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE types IN (\'select\',\'radio\') ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);
$mainAttribute = '';
$theMainAttribute = explode(',', $Row['mainAttribute']);

while ($OptionRow = $DB->queryArray($Result)) {
	if (in_array($OptionRow['administratorsoptionID'], $theMainAttribute)) {
		$mainAttribute .= '<option value=\'' . $OptionRow['administratorsoptionID'] . '\' selected>' . $OptionRow['optionFieldName'] . '</option>';
	}
	else {
		$mainAttribute .= '<option value=\'' . $OptionRow['administratorsoptionID'] . '\'>' . $OptionRow['optionFieldName'] . '</option>';
	}
}

$EnableQCoreClass->replace('mainAttributeList', $mainAttribute);
$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($Row['defaultGroupID']));
$EnableQCoreClass->replace('administratorsConfigID', $Row['administratorsConfigID']);
$EnableQCoreClass->replace('registerText', $Row['registerText']);
$EnableQCoreClass->parse('MembersConfigPage', 'MembersConfigPageFile');
$EnableQCoreClass->output('MembersConfigPage', false);

?>

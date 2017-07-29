<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5');
$thisProg = 'MgtNormalOption.php';

if ($_POST['Action'] == 'OptionEditSubmit') {
	$optionNameArray = explode("\n", qaddslashes(trim($_POST['optionName'])));
	$optionName = implode('###', $optionNameArray);
	$SQL = ' UPDATE ' . OPTION_TABLE . ' SET optionCate=\'' . qaddslashes(trim($_POST['optionCate'])) . '\',optionName=\'' . $optionName . '\' WHERE optionID=\'' . $_POST['optionID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['edit_normal_option'] . ':' . $_POST['optionCate']);
	_showmessage($lang['edit_normal_option'], true);
}

if ($_GET['Action'] == 'Edit') {
	$SQL = ' SELECT * FROM ' . OPTION_TABLE . ' WHERE optionID=\'' . $_GET['optionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('NormalOptionEditPageFile', 'NormalOptionEdit.html');
	$EnableQCoreClass->replace('optionCate', $Row['optionCate']);
	$optionName = str_replace("\r", '', $Row['optionName']);
	$optionNameArray = explode('###', $optionName);
	$optionName = implode("\r\n", $optionNameArray);
	$EnableQCoreClass->replace('optionName', $optionName);
	$EnableQCoreClass->replace('optionID', $Row['optionID']);
	$EnableQCoreClass->parse('NormalOptionEditPage', 'NormalOptionEditPageFile');
	$EnableQCoreClass->output('NormalOptionEditPage', false);
}

if ($_POST['Action'] == 'OptionAddSubmit') {
	$optionNameArray = explode("\n", qaddslashes(trim($_POST['optionName'])));
	$optionName = implode('###', $optionNameArray);

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$SQL = 'INSERT INTO ' . OPTION_TABLE . ' SET optionCate=\'' . qaddslashes(trim($_POST['optionCate'])) . '\',optionName=\'' . $optionName . '\',administratorsID= \'0\' ';
		break;

	case '2':
	case '5':
		$SQL = 'INSERT INTO ' . OPTION_TABLE . ' SET optionCate=\'' . qaddslashes(trim($_POST['optionCate'])) . '\',optionName=\'' . $optionName . '\',administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
		break;
	}

	$DB->query($SQL);
	writetolog($lang['add_normal_option'] . ':' . $_POST['optionCate']);
	_showsucceed($lang['add_normal_option'] . ':' . $_POST['optionCate'], $thisProg);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . OPTION_TABLE . ' WHERE optionID=\'' . $_GET['optionID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_normal_option'] . ':' . $_GET['optionCate']);
	_showsucceed($lang['delete_normal_option'] . ':' . $_GET['optionCate'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('OptionFile', 'NormalOptionList.html');
$EnableQCoreClass->set_CycBlock('OptionFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT * FROM ' . OPTION_TABLE . ' ORDER BY optionID ';
	break;

case '2':
case '5':
	$SQL = ' SELECT * FROM ' . OPTION_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ORDER BY optionID ';
	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('optionCate', $Row['optionCate']);
	$EnableQCoreClass->replace('optionCateScript', str_replace('"', '', str_replace('\'', '', $Row['optionCate'])));
	$optionNameArray = explode('###', $Row['optionName']);
	$optionName = '';
	$i = 0;

	for (; $i < count($optionNameArray); $i++) {
		$optionName .= $optionNameArray[$i] . '&nbsp;&nbsp;&nbsp;';
	}

	$EnableQCoreClass->replace('optionName', $optionName);
	$EnableQCoreClass->replace('editURL', $thisProg . '?Action=Edit&optionID=' . $Row['optionID'] . '&optionCate=' . urlencode($Row['optionCate']));
	$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=Delete&optionID=' . $Row['optionID'] . '&optionCate=' . urlencode($Row['optionCate']));
	$EnableQCoreClass->parse('option', 'OPTION', true);
}

$EnableQCoreClass->parse('Option', 'OptionFile');
$EnableQCoreClass->output('Option');

?>

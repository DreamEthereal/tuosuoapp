<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
require_once ROOT_PATH . 'Functions/Functions.fields.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$thisProg = 'MembersOptionFields.php?1=1';
_checkroletype('1|6');

if ($_GET['fAction'] == 'Order') {
	$OrderIDNew = $_GET['OrderID'];

	if ($_GET['Compositor'] == 'DESC') {
		$SQL = ' SELECT administratorsoptionID,orderByID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE ';
		$SQL .= ' orderByID>' . $_GET['OrderID'] . ' ORDER BY orderByID ASC LIMIT 0,1 ';
	}
	else {
		$SQL = ' SELECT administratorsoptionID,orderByID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE ';
		$SQL .= ' orderByID<' . $_GET['OrderID'] . ' ORDER BY orderByID DESC LIMIT 0,1 ';
	}

	if ($Row = $DB->queryFirstRow($SQL)) {
		$SQL = ' UPDATE ' . ADMINISTRATORSOPTION_TABLE . ' SET orderByID=\'' . $Row['orderByID'] . '\' WHERE administratorsoptionID=' . $_GET['ID'];
		$DB->query($SQL);
		$SQL = ' UPDATE ' . ADMINISTRATORSOPTION_TABLE . ' SET orderByID=\'' . $OrderIDNew . '\' WHERE administratorsoptionID=\'' . $Row['administratorsoptionID'] . '\' ';
		$DB->query($SQL);
	}
}

if ($_GET['fAction'] == 'Delete') {
	$SQL = ' DELETE FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE administratorsoptionID=\'' . $_GET['fieldsID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE administratorsoptionID=\'' . $_GET['fieldsID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['delete_members_fields']);
	_showsucceed($lang['delete_members_fields'], $thisProg);
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf('DELETE FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE (administratorsoptionID IN (\'%s\'))', join('\',\'', $_POST['fieldsID']));
	$DB->query($SQL);
	$SQL = sprintf('DELETE FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE (administratorsoptionID IN (\'%s\'))', join('\',\'', $_POST['fieldsID']));
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['delete_members_fields_list']);
	_showsucceed($lang['delete_members_fields_list'], $thisProg);
}

if ($_POST['UpdateFieldsSubmit']) {
	if ($_POST['isPublic']) {
		foreach ($_POST['isPublic'] as $optionID => $is_public) {
			$SQL = ' UPDATE ' . ADMINISTRATORSOPTION_TABLE . ' SET isPublic=\'' . $is_public . '\' WHERE administratorsoptionID=\'' . $optionID . '\' ';
			$DB->query($SQL);
		}
	}

	if ($_POST['orderByID']) {
		foreach ($_POST['orderByID'] as $administratorsoptionID => $orderID) {
			$SQL = ' UPDATE ' . ADMINISTRATORSOPTION_TABLE . ' SET orderByID=\'' . $orderID . '\' WHERE administratorsoptionID=\'' . $administratorsoptionID . '\' ';
			$DB->query($SQL);
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
	writetolog($lang['update_members_fields']);
	_showsucceed($lang['update_members_fields'], $thisProg);
}

if ($_POST['Action'] == 'AddFieldsSubmit') {
	addfieldssubmit('administrators', 'Administrators', $thisProg);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
}

if ($_GET['fAction'] == 'Add') {
	displayaddfields('administrators', 'Administrators', $thisProg);
}

if ($_POST['Action'] == 'EditFieldsSubmit') {
	editfieldssubmit('administrators', 'Administrators', $thisProg);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =1 WHERE isPublic=\'0\' ';
	$DB->query($SQL);
}

if ($_GET['fAction'] == 'Edit') {
	displayeditfields('administrators', 'Administrators', $thisProg);
}

listfields('administrators', 'Administrators', $thisProg);

?>

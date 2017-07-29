<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.mgt.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
_checkroletype('1|2|5');
$thisProg = 'MgtTplFile.php';
$EnableQCoreClass->replace('backURL', $thisProg);
$TplPhyPath = $Config['absolutenessPath'] . 'Templates/CN/';

if ($_GET['Action'] == 'Download') {
	$SQL = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID =\'' . $_GET['panelID'] . '\' LIMIT 1 ';
	$Row = $DB->queryFirstRow($SQL);
	if (!file_exists($TplPhyPath . $Row['tplName']) || ($Row['tplName'] == '')) {
		_showerror($lang['error_system'], $lang['no_download_file']);
	}
	else {
		writetolog($lang['download_tpl'] . ':' . $Row['tplName']);
		_downloadfile($TplPhyPath, $Row['tplName']);
	}
}

if ($_POST['Actions'] == 'TplUpLoadSubmit') {
	$SQL = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID =\'' . $_POST['panelID'] . '\' LIMIT 1 ';
	$Row = $DB->queryFirstRow($SQL);
	$tmpExt = explode('.', $_FILES['TplFileName']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	if (is_uploaded_file($_FILES['TplFileName']['tmp_name']) && (($extension == 'html') || ($extension == 'htm')) && ($Row['tplName'] != '')) {
		copy($_FILES['TplFileName']['tmp_name'], $TplPhyPath . $Row['tplName']);
		writetolog($lang['upload_tpl'] . ':' . $Row['tplName']);
		_showmessage($lang['upload_tpl'] . ':' . $Row['tplName'], true);
	}
	else {
		_showerror($lang['error_system'], $lang['html_file_type_error']);
	}
}

if ($_GET['Actions'] == 'Upload') {
	$EnableQCoreClass->setTemplateFile('TplUpLoadFile', 'TplUpLoad.html');
	$SQL = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID =\'' . $_GET['panelID'] . '\' LIMIT 1 ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('TplName', $Row['tplName']);
	$EnableQCoreClass->replace('panelID', $_GET['panelID']);
	$EnableQCoreClass->replace('FileURL', 'PerUserData/' . $_SESSION['administratorsID']);
	$EnableQCoreClass->parse('TplUpLoad', 'TplUpLoadFile');
	$EnableQCoreClass->output('TplUpLoad');
}

if ($_POST['Action'] == 'TplWebEditSubmit') {
	$SQL = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID =\'' . $_POST['panelID'] . '\' LIMIT 1 ';
	$Row = $DB->queryFirstRow($SQL);
	if (file_exists($TplPhyPath . $Row['tplName']) && ($Row['tplName'] != '')) {
		$PhyFileFullName = $TplPhyPath . $Row['tplName'];
		$fp = fopen($PhyFileFullName, 'w');
		fputs($fp, stripslashes(str_replace('&amp;', '&', $_POST['fileContent'])));
		fclose($fp);
		writetolog($lang['webedit_tpl'] . ':' . $Row['tplName']);
	}

	_showmessage($lang['webedit_tpl'] . ':' . $Row['tplName'], false);
}

if ($_GET['Actions'] == 'WebEdit') {
	$SQL = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID =\'' . $_GET['panelID'] . '\' LIMIT 1 ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('TplWebEditFile', 'TplWebEdit.html');
	$EnableQCoreClass->replace('panelID', $_GET['panelID']);
	$EnableQCoreClass->replace('tplFileName', $Row['tplName']);
	$PhyFileFullName = $TplPhyPath . $Row['tplName'];
	if (!file_exists($PhyFileFullName) || ($Row['tplName'] == '')) {
		_showerror($lang['error_system'], $lang['no_download_file']);
	}
	else {
		$fp = fopen($PhyFileFullName, 'r');
		$contents = fread($fp, filesize($PhyFileFullName));
		$EnableQCoreClass->replace('fileContent', str_replace('&', '&amp;', $contents));
	}

	$EnableQCoreClass->parse('TplWebEdit', 'TplWebEditFile');
	$EnableQCoreClass->output('TplWebEdit');
}

if ($_POST['Action'] == 'AddSubmit') {
	$time = time();

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$SQL = 'INSERT INTO ' . TPL_TABLE . ' SET tplTagName=\'' . $_POST['tplTagName'] . '\',administratorsID= \'0\' ';
		break;

	case '2':
	case '5':
		$SQL = 'INSERT INTO ' . TPL_TABLE . ' SET tplTagName=\'' . $_POST['tplTagName'] . '\',administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
		break;
	}

	if (isset($_FILES['tplName'])) {
		$tmpExt = explode('.', $_FILES['tplName']['name']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		if (is_uploaded_file($_FILES['tplName']['tmp_name']) && (($extension == 'html') || ($extension == 'htm'))) {
			$newFileName = 'Panel_' . date('YmdHis', $time) . '.html';
			$newFullName = $TplPhyPath . $newFileName;

			if (!($fp = @fopen($newFullName, 'w+'))) {
				fclose($fp);
			}

			if (copy($_FILES['tplName']['tmp_name'], $newFullName)) {
				$SQL .= ' ,tplName = \'' . $newFileName . '\' ';
			}
		}
		else {
			_showerror($lang['error_system'], $lang['html_file_type_error']);
		}
	}

	$DB->query($SQL);
	writetolog($lang['add_panel'] . ':' . $_POST['tplTagName']);
	_showmessage($lang['add_panel'] . ':' . $_POST['tplTagName'], true);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('PanelAddPageFile', 'TplAdd.html');
	$EnableQCoreClass->replace('FileURL', 'PerUserData/' . $_SESSION['administratorsID']);
	$EnableQCoreClass->parse('PanelAddPage', 'PanelAddPageFile');
	$EnableQCoreClass->output('PanelAddPage');
}

if ($_POST['Actions'] == 'SettingDefaultSubmit') {
	if (!empty($_POST['defaultPanel'])) {
		$SQL = ' UPDATE ' . TPL_TABLE . ' SET isDefault = 1 WHERE panelID =\'' . $_POST['defaultPanel'] . '\' ';
		$DB->query($SQL);
		$SQL = ' UPDATE ' . TPL_TABLE . ' SET isDefault = 0 WHERE panelID !=\'' . $_POST['defaultPanel'] . '\' ';
		$DB->query($SQL);
		writetolog($lang['setting_default_panel']);
	}

	_showsucceed($lang['setting_default_panel'], $thisProg);
}

if ($_GET['Actions'] == 'Delete') {
	$SQL = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID =\'' . $_GET['panelID'] . '\' LIMIT 1 ';
	$Row = $DB->queryFirstRow($SQL);
	$PhyFileFullName = $TplPhyPath . $Row['tplName'];

	if (file_exists($PhyFileFullName)) {
		@unlink($PhyFileFullName);
	}

	$SQL = ' DELETE FROM ' . TPL_TABLE . ' WHERE panelID=\'' . $_GET['panelID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_panel'] . ':' . $Row['tplName']);
	_showsucceed($lang['delete_panel'] . ':' . $Row['tplName'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('TplFile', 'Tpl.html');
$EnableQCoreClass->set_CycBlock('TplFile', 'TPL', 'tpl');
$EnableQCoreClass->replace('tpl', '');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT * FROM ' . TPL_TABLE . ' ORDER BY panelID ';
	$EnableQCoreClass->replace('isSuper', '');
	break;

case '2':
case '5':
	$SQL = ' SELECT * FROM ' . TPL_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ORDER BY panelID ';
	$EnableQCoreClass->replace('isSuper', 'disabled');
	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('panelID', $Row['panelID']);

	if ($Row['isDefault'] == '1') {
		$EnableQCoreClass->replace('isDefault', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isDefault', '');
	}

	if ($Row['isSystem'] == 1) {
		$EnableQCoreClass->replace('isSystem', 'none');
	}
	else {
		$EnableQCoreClass->replace('isSystem', '');
	}

	$EnableQCoreClass->replace('tplTagName', $Row['tplTagName']);
	$theTplName = $Row['tplName'];

	if (!file_exists($TplPhyPath . $Row['tplName'])) {
		$theTplName .= '&nbsp;<img src="../Images/hide.gif" border=0>';
	}

	$EnableQCoreClass->replace('tplFileName', $theTplName);
	$EnableQCoreClass->replace('tplName', $Row['tplName']);
	$EnableQCoreClass->parse('tpl', 'TPL', true);
}

$EnableQCoreClass->parse('Tpl', 'TplFile');
$EnableQCoreClass->output('Tpl');

?>

<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
$thisProg = 'ChangePass.php';
_checkroletype('1|2|3|4|5|6|7');

if ($_POST['Action'] == 'ChangePasswordSubmit') {
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' WHERE passWord=\'' . trim($_POST['theOldPassWord']) . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['0'] == 0) {
		exit('false######您确定输入的原密码是正确的？');
	}

	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET passWord=\'' . trim($_POST['theNewPassWord']) . '\' WHERE administratorsID=\'' . $_SESSION['administratorsID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['change_password']);
	exit('true######' . $lang['change_password']);
	exit();
}

$EnableQCoreClass->setTemplateFile('ChangePasswordFile', 'ChangePass.html');

switch ($_SESSION['adminRoleType']) {
case '1':
case '2':
case '5':
	$EnableQCoreClass->replace('controlURL', 'ShowSurveyList.php');
	break;

case '4':
	$EnableQCoreClass->replace('controlURL', 'ShowInputSurvey.php');
	break;

case '6':
	$EnableQCoreClass->replace('controlURL', 'ShowUserSurvey.php');
	break;

case '7':
	$EnableQCoreClass->replace('controlURL', 'ShowAuthSurvey.php');
	break;

case '3':
	$EnableQCoreClass->replace('controlURL', 'ShowViewSurvey.php');
	break;
}

$EnableQCoreClass->parse('ChangePassword', 'ChangePasswordFile');
$EnableQCoreClass->output('ChangePassword', false);

?>

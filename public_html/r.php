<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
require_once ROOT_PATH . 'Functions/Functions.fields.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';
require_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
$thisProg = 'r.php';
$SQL = ' SELECT defaultGroupID,registerText,isActive,isUseEmailPass,isNotRegister FROM ' . ADMINISTRATORSCONFIG_TABLE . ' ';
$ConfigRow = $DB->queryFirstRow($SQL);

if ($ConfigRow['isNotRegister'] == 1) {
	_showerror($lang['error_system'], $lang['no_get_passport']);
}

if ($_POST['Action'] == 'MemberAddSubmit') {
	if (!checkemail(trim($_POST['administrators_Name']))) {
		_showerror($lang['error_system'], '系统检查错误：您输入的邮件账号不是合法的email地址字符组合');
	}

	$theUserName = strtolower(trim($_POST['administrators_Name']));
	$theNickName = strtolower(trim($_POST['nickName']));
	$SQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName)=\'' . $theUserName . '\' AND isAdmin= 0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['administratorsname_is_exist']);
	}

	_checkoptioninput('administrators', 'phpCheck');
	$SQL = ' INSERT INTO ' . ADMINISTRATORS_TABLE . ' SET administratorsName=\'' . trim($_POST['administrators_Name']) . '\',nickName=\'' . trim($_POST['nickName']) . '\',createDate=\'' . time() . '\',isAdmin=0,hintPass=\'' . $_POST['hintPass'] . '\',answerPass=\'' . $_POST['answerPass'] . '\',administratorsGroupID=\'' . $ConfigRow['defaultGroupID'] . '\',isActive= \'' . $ConfigRow['isActive'] . '\' ';
	$passWord = $_POST['passWord'];
	$SQL .= ' ,passWord=\'' . md5(trim($passWord)) . '\' ';
	$DB->query($SQL);
	$lastInsertID = $DB->_GetInsertID();
	insertoptionvalue('administrators', $lastInsertID);

	if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
		unset($_POST['Action']);
		if (($_POST['qid'] != 0) && ($_POST['qid'] != '')) {
			_showsucceed($lang['register_succeed'], 'a.php?qid=' . $_POST['qid'] . '&qlang=' . strtolower($language));
		}
		else {
			_showsucceed($lang['register_succeed'], 'Android/index.php');
		}
	}
	else {
		if (($_POST['qid'] != 0) && ($_POST['qid'] != '')) {
			_showsucceed($lang['register_succeed'], 'q.php?qid=' . $_POST['qid'] . '&qlang=' . strtolower($language));
		}
		else {
			_showsucceed($lang['register_succeed'], '/');
		}
	}
}

if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
	$EnableQCoreClass->setTemplateFile('UsersAddFile', 'uRegisterAndroid.html');
}
else {
	include_once ROOT_PATH . 'Includes/MobileDetect.php';
	include_once ROOT_PATH . 'License/License.xml';
	$detect = new Mobile_Detect();
	$isMobile = (($License['isMobile'] == 1) && ($detect->isMobile() || $detect->isTablet()) ? true : false);

	if ($isMobile) {
		$EnableQCoreClass->setTemplateFile('UsersAddFile', 'mRegister.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('UsersAddFile', 'uRegister.html');
	}
}

$EnableQCoreClass->replace('registerText', $ConfigRow['registerText']);
$EnableQCoreClass->replace('siteName', $Config['siteName']);
displayaddoption('administrators');
_checkoptioninput('administrators');
$EnableQCoreClass->replace('qlang', strtolower($language));
$EnableQCoreClass->replace('qid', (int) $_GET['qid']);
$commonReplace = $EnableQCoreClass->parse('UsersAdd', 'UsersAddFile');
$CommonPage = $commonReplace;

if (!isset($_GET['step'])) {
	$CommonPage = preg_replace('/<!-- BEGIN STEP1 -->(.*)<!-- STEP1 END -->/s', '', $CommonPage);
}
else if ((int) $_GET['step'] == 1) {
	$CommonPage = preg_replace('/<!-- BEGIN STEP0 -->(.*)<!-- STEP0 END -->/s', '', $CommonPage);
}
else {
	$CommonPage = preg_replace('/<!-- BEGIN STEP0 -->(.*)<!-- STEP0 END -->/s', '', $CommonPage);
}

echo $CommonPage;
exit();

?>

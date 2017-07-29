<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';
$thisProg = 'g.php';

if ($_POST['Action'] == 'GetPasswordSubmit') {
	$theUserName = strtolower(trim($_POST['administratorsName']));
	$SQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) =\'' . $theUserName . '\'  AND isAdmin =0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['0'] == 0) {
		_showerror($lang['error_system'], $lang['no_exist_username'], 1);
	}

	$SQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName)=\'' . $theUserName . '\' AND hintPass=\'' . trim($_POST['hintPass']) . '\' AND answerPass=\'' . trim($_POST['answerPass']) . '\'  AND isAdmin =0 LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['0'] == 0) {
		_showerror($lang['error_system'], $lang['no_fit_hintpass_username'], 1);
	}

	$password = makepassword();
	$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET passWord=\'' . md5($password) . '\' WHERE LCASE(administratorsName)=\'' . $theUserName . '\' AND isAdmin =0 ';
	$DB->query($SQL);
	$mail['chanage_password_content'] = $lang['chanage_password_content'] . $password;
	eval ('$chanage_password_content="' . $mail['chanage_password_content'] . '";');
	$EnableQCoreClass->setTemplateFile('GetPassWordMailFile', 'uMail.html');
	$EnableQCoreClass->replace('mail_subject', $lang['send_pass_title']);
	$EnableQCoreClass->replace('mail_to', $theUserName);
	$EnableQCoreClass->replace('mail_body', $chanage_password_content);
	$EnableQCoreClass->replace('mail_date', date('Y-m-d', time()));
	$EnableQCoreClass->replace('siteName', $Config['siteName']);
	$mail['mail_content'] = $EnableQCoreClass->parse('Mail', 'GetPassWordMailFile');
	_sendmail($theUserName, $lang['send_pass_title'], $mail['mail_content']);

	if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
		unset($_POST['Action']);
		if (($_POST['qid'] != 0) && ($_POST['qid'] != '')) {
			_showsucceed($lang['send_pass_succ'], 'a.php?qid=' . $_POST['qid'] . '&qlang=' . strtolower($language));
		}
		else {
			_showsucceed($lang['send_pass_succ'], 'Android/index.php');
		}
	}
	else {
		if (($_POST['qid'] != 0) && ($_POST['qid'] != '')) {
			_showsucceed($lang['send_pass_succ'], 'q.php?qid=' . $_POST['qid'] . '&qlang=' . strtolower($language));
		}
		else {
			_showsucceed($lang['send_pass_succ'], '/');
		}
	}
}

if (1 <= substr_count($_SERVER['HTTP_USER_AGENT'], 'rexsee')) {
	$EnableQCoreClass->setTemplateFile('GetPasswordPageFile', 'uGetPasswordAndroid.html');
}
else {
	include_once ROOT_PATH . 'Includes/MobileDetect.php';
	include_once ROOT_PATH . 'License/License.xml';
	$detect = new Mobile_Detect();
	$isMobile = (($License['isMobile'] == 1) && ($detect->isMobile() || $detect->isTablet()) ? true : false);

	if ($isMobile) {
		$EnableQCoreClass->setTemplateFile('GetPasswordPageFile', 'mGetPassword.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('GetPasswordPageFile', 'uGetPassword.html');
	}
}

$EnableQCoreClass->replace('siteName', $Config['siteName']);
$EnableQCoreClass->replace('qlang', strtolower($language));
$EnableQCoreClass->replace('qid', (int) $_GET['qid']);
$commonReplace = $EnableQCoreClass->parse('GetPassword', 'GetPasswordPageFile');
echo $commonReplace;
exit();

?>

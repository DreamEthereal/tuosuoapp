<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
$thisProg = 'SendMailNotes.php';
_checkroletype('1|2|5');

if ($_POST['Action'] == 'EmailSendSubmit') {
	@set_time_limit(0);
	$SendMembersEmail = explode(';', $_POST['panelUserName']);
	$SendMembersEmail = array_unique($SendMembersEmail);
	ob_end_clean();
	$style = '<style>' . "\n" . '';
	$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
	$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
	$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
	$style .= '</style>' . "\n" . '';
	echo $style;
	flush();
	$scroll = '<SCRIPT type=text/javascript>window.scrollTo(0,document.body.scrollHeight);</SCRIPT>';
	$prefix = '';
	$i = 0;

	for (; $i < 300; $i++) {
		$prefix .= ' ' . "\n" . '';
	}

	$theSuccMail = $theFailMail = array();
	if ((0 < count($SendMembersEmail)) && !empty($SendMembersEmail)) {
		$theSendMailContent = stripslashes($_POST['emailContent']);
		$ProgURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -24);
		$theSendMailContent = str_replace($ProgURL, '', $theSendMailContent);

		if ($Config['dataDomainName'] != '') {
			$dataURL = 'http://' . $Config['dataDomainName'] . '/';
			$theSendMailContent = str_replace($dataURL, '', $theSendMailContent);
		}
		else {
			$dataURL = $ProgURL;
		}

		$theSendMailContent = str_replace('Images/', $dataURL . 'Images/', $theSendMailContent);
		$theSendMailContent = str_replace('q.php?qname=', $dataURL . 'q.php?qname=', $theSendMailContent);
		$theSendMailContent = str_replace('PerUserData/', $dataURL . 'PerUserData/', $theSendMailContent);
		$SendMailTitle = trim($_POST['emailTitle']);
		_sendmailinit();
		$theSuccNum = $theFailNum = 0;

		foreach ($SendMembersEmail as $sendMailName) {
			ob_end_clean();
			$sendMailName = trim($sendMailName);

			if ($sendMailName != '') {
				if (checkemail($sendMailName)) {
					$theSQL = ' SELECT nickName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . trim($sendMailName) . '\' LIMIT 1 ';
					$theRow = $DB->queryFirstRow($theSQL);

					if ($theRow) {
						$SendMailContent = str_replace('{userName}', $theRow['nickName'], $theSendMailContent);
					}
					else {
						$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
					}

					$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName))));
					$SendMailContent = str_replace('{userEmail}', $theUserEmailURL, $SendMailContent);

					if (_sendmailcontent(strtolower($sendMailName), $SendMailTitle, $SendMailContent, '', '', 1)) {
						$str = '<div class="tipsinfo">' . $lang['send_email_succ'] . '<b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
						$theSuccNum++;
						$theSuccMail[] = strtolower($sendMailName);
						echo $prefix . $str . $scroll;
					}
					else {
						$str = '<div class="tipsinfo">' . $lang['send_email_fail'] . '<b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
						$theFailNum++;
						$theFailMail[] = strtolower($sendMailName);
						echo $prefix . $str . $scroll;
					}
				}
				else {
					$str = '<div class="tipsinfo">' . $lang['send_email_fail'] . '<b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
					$theFailNum++;
					$theFailMail[] = strtolower($sendMailName);
					echo $prefix . $str . $scroll;
				}
			}

			flush();
		}

		ob_end_clean();
		echo '<div class="tipsinfo">' . $lang['send_email_succ'] . '<b>' . $theSuccNum . '</b>&nbsp;&nbsp;' . $lang['send_email_fail'] . '<b>' . $theFailNum . '</b></div>' . "\n" . '';
		flush();
		unset($sendmail);
	}
	else {
		ob_end_clean();
		$str = '<div class="tipsinfo">' . $lang['no_send_email'] . '</div>' . "\n" . '';
		echo $str;
		flush();
	}

	$SQL = ' INSERT INTO ' . MAILLIST_TABLE . ' SET mailTitle=\'' . trim($_POST['emailTitle']) . '\',sendMailName=\'' . implode(';', $theSuccMail) . '\',sendFailName=\'' . implode(';', $theFailMail) . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=1,createDate=\'' . time() . '\' ';
	$DB->query($SQL);
	writetolog($lang['send_email_notes']);
	unset($SendMembersEmail);
	unset($SendMembersName);
	echo '<script>parent._showCloseWindowButton();</script>';
	exit();
}

if ($_POST['Action'] == 'EmailSaveSubmit') {
	$SQL = ' INSERT INTO ' . MAILLIST_TABLE . ' SET mailTitle=\'' . trim($_POST['emailTitle']) . '\',sendMailName=\'' . $_POST['panelUserName'] . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=0,createDate=\'' . time() . '\' ';
	$DB->query($SQL);
	writetolog($lang['save_email_notes']);
	echo '<script>parent.parent.hidePopWin();</script>';
	exit();
}

$EnableQCoreClass->setTemplateFile('SendMailNotesFile', 'SendMailNotes.html');
$EnableQCoreClass->replace('siteName', $Config['siteName']);
$EnableQCoreClass->replace('joinTime', date('Y-m-d'));
$EnableQCoreClass->parse('SendMailNotesPage', 'SendMailNotesFile');
$EnableQCoreClass->output('SendMailNotesPage');

?>

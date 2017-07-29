<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
$ConfigRow['topicNum'] = 30;
$_GET['stat'] = (int) $_GET['stat'];
$thisProg = 'ShowMailList.php?stat=' . $_GET['stat'];
_checkroletype('1|2|3|5|7');

if ($_POST['Action'] == 'EmailSendSubmit') {
	@set_time_limit(0);
	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
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
		$ProgURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23);
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

			if (trim($sendMailName) != '') {
				if (checkemail($sendMailName)) {
					if (!$BaseRow || ($BaseRow['isUseOriPassport'] == '1')) {
						$theSQL = ' SELECT nickName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . trim($sendMailName) . '\' LIMIT 1 ';
						$theRow = $DB->queryFirstRow($theSQL);

						if ($theRow) {
							$SendMailContent = str_replace('{userName}', $theRow['nickName'], $theSendMailContent);
						}
						else {
							$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
						}
					}
					else {
						$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
					}

					$SendMailContent = str_replace('{userEmail}', escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName)))), $SendMailContent);

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

	if ($_POST['mailType'] == 1) {
		$SQL = ' INSERT INTO ' . MAILLIST_TABLE . ' SET mailTitle=\'' . trim($_POST['emailTitle']) . '\',sendMailName=\'' . implode(';', $theSuccMail) . '\',sendFailName=\'' . implode(';', $theFailMail) . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=1,createDate=\'' . time() . '\' ';
	}
	else {
		$SQL = ' UPDATE ' . MAILLIST_TABLE . ' SET mailTitle=\'' . trim($_POST['emailTitle']) . '\',sendMailName=\'' . implode(';', $theSuccMail) . '\',sendFailName=\'' . implode(';', $theFailMail) . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=1,createDate=\'' . time() . '\' WHERE maillistID =\'' . $_POST['maillistID'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['send_email_notes']);
	unset($SendMembersEmail);
	unset($SendMembersName);
	echo '<script>parent._showCloseWindowButton();</script>';
	exit();
}

if ($_POST['Action'] == 'EmailSaveSubmit') {
	if ($_POST['mailType'] == 1) {
		$SQL = ' INSERT INTO ' . MAILLIST_TABLE . ' SET mailTitle=\'' . trim($_POST['emailTitle']) . '\',sendMailName=\'' . $_POST['panelUserName'] . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=0,createDate=\'' . time() . '\' ';
	}
	else {
		$SQL = ' UPDATE ' . MAILLIST_TABLE . ' SET mailTitle=\'' . trim($_POST['emailTitle']) . '\',sendMailName=\'' . $_POST['panelUserName'] . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=0,createDate=\'' . time() . '\' WHERE maillistID =\'' . $_POST['maillistID'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['save_email_notes']);
	echo '<script>parent.parent.hidePopWin();parent.parent.referIframeSrc(\'ShowMailList.php?stat=0\');</script>';
	exit();
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf(' DELETE FROM ' . MAILLIST_TABLE . ' WHERE (maillistID IN (\'%s\'))', @join('\',\'', $_POST['maillistID']));
	$DB->query($SQL);
	writetolog($lang['delete_maillist']);
	_showsucceed($lang['delete_maillist'], $thisProg);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . MAILLIST_TABLE . ' WHERE maillistID=\'' . $_GET['maillistID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_maillist']);
	_showsucceed($lang['delete_maillist'], $thisProg);
}

if ($_GET['Action'] == 'View') {
	$EnableQCoreClass->setTemplateFile('MailListFile', 'MailView.html');
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . MAILLIST_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.maillistID =\'' . $_GET['maillistID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('sendName', $Row['administratorsName']);
	$EnableQCoreClass->replace('sendMailName', str_replace(';', '<br/>', $Row['sendMailName']));
	$EnableQCoreClass->replace('sendFailName', str_replace(';', '<br/>', $Row['sendFailName']));
	$EnableQCoreClass->replace('mailTitle', $Row['mailTitle']);
	$EnableQCoreClass->replace('sendMailContent', $Row['sendMailContent']);
	$EnableQCoreClass->replace('createDate', date('Y-m-d H:i:s', $Row['createDate']));
	$MailListPage = $EnableQCoreClass->parse('MailList', 'MailListFile');

	if ($Row['mailType'] == 0) {
		$MailListPage = preg_replace('/<!-- BEGIN FAIL -->(.*)<!-- END FAIL -->/s', '', $MailListPage);
	}

	echo $MailListPage;
	exit();
}

if ($_GET['Action'] == 'Send') {
	$EnableQCoreClass->setTemplateFile('MailListFile', 'MailSend.html');
	$SQL = ' SELECT * FROM ' . MAILLIST_TABLE . ' WHERE maillistID =\'' . $_GET['maillistID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('sendMailName', $Row['sendMailName']);
	$EnableQCoreClass->replace('emailTitle', $Row['mailTitle']);
	$EnableQCoreClass->replace('sendMailContent', stripslashes($Row['sendMailContent']));
	$EnableQCoreClass->replace('maillistID', $Row['maillistID']);
	$EnableQCoreClass->replace('mailType', $Row['mailType']);
	$MailListPage = $EnableQCoreClass->parse('MailList', 'MailListFile');
	echo $MailListPage;
	exit();
}

if ($_GET['stat'] == 0) {
	$EnableQCoreClass->setTemplateFile('MailListFile', 'MailListStat0.html');
}
else {
	$EnableQCoreClass->setTemplateFile('MailListFile', 'MailListStat1.html');
}

$EnableQCoreClass->set_CycBlock('MailListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . MAILLIST_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.mailType =\'' . $_GET['stat'] . '\' ';
	break;

case '2':
case '3':
case '7':
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . MAILLIST_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID AND a.administratorsID=\'' . $_SESSION['administratorsID'] . '\' AND a.mailType =\'' . $_GET['stat'] . '\' ';
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . MAILLIST_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.mailType =\'' . $_GET['stat'] . '\' AND a.administratorsID IN (' . $UserIDList . ') ';
	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY a.maillistID DESC  ';
$SQL .= ' LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('createDate', date('Y-m-d H:i:s', $Row['createDate']));
	$EnableQCoreClass->replace('maillistID', $Row['maillistID']);
	$EnableQCoreClass->replace('sendName', $Row['administratorsName']);

	if ($Row['sendMailName'] == '') {
		$EnableQCoreClass->replace('recName', substr($Row['sendFailName'], 0, 30) . '...');
	}
	else {
		$EnableQCoreClass->replace('recName', substr($Row['sendMailName'], 0, 30) . '...');
	}

	$EnableQCoreClass->replace('mailTitle', $Row['mailTitle']);
	$EnableQCoreClass->replace('viewURL', $thisProg . '&Action=View&maillistID=' . $Row['maillistID'] . ' ');
	$EnableQCoreClass->replace('sendURL', $thisProg . '&Action=Send&maillistID=' . $Row['maillistID'] . ' ');
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&Action=Delete&maillistID=' . $Row['maillistID'] . ' ');
	$EnableQCoreClass->parse('list', 'LIST', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('MailList', 'MailListFile');
$EnableQCoreClass->output('MailList');

?>

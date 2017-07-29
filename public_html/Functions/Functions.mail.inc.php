<?php
//dezend by http://www.yunlu99.com/
function _sendmail($sendTo, $mailSubject = '', $mailBody = '', $sendName = '', $template = '', $isReturnError = 0)
{
	global $Config;
	global $DB;
	require_once ROOT_PATH . 'Includes/Mailer.class.php';
	$mail = new phpmailer();
	$SQL = ' SELECT sendFrom,sendName,mailServer,smtp25,smtpName,smtpPassword,isSmtp FROM ' . BASESETTING_TABLE . ' ';
	$MailRow = $DB->queryFirstRow($SQL);
	if (!$MailRow || ($MailRow['mailServer'] == '')) {
		$mail->From = $Config['sendFrom'];
		$mail->FromName = $Config['sendName'];
		$mail->Host = $Config['mailServer'];
		$mail->Port = $Config['smtp25'];
		$mail->SMTPAuth;
		$mail->Username = $Config['smtpName'];
		$mail->Password = $Config['smtpPassword'];
		$mail->Mailer = 'smtp';
		$mail->WordWrap = 50000;

		if ($Config['isSmtp'] == 'y') {
			$mail->SMTPAuth = true;
		}
		else {
			$mail->SMTPAuth = false;
		}
	}
	else {
		$mail->From = $MailRow['sendFrom'];
		$mail->FromName = $MailRow['sendName'];
		$mail->Host = $MailRow['mailServer'];
		$mail->Port = $MailRow['smtp25'];
		$mail->SMTPAuth;
		$mail->Username = $MailRow['smtpName'];
		$mail->Password = $MailRow['smtpPassword'];
		$mail->Mailer = 'smtp';
		$mail->WordWrap = 50000;

		if ($MailRow['isSmtp'] == 'y') {
			$mail->SMTPAuth = true;
		}
		else {
			$mail->SMTPAuth = false;
		}
	}

	$mail->IsSMTP();
	$mail->IsHTML(true);
	$mail->Subject = $mailSubject;
	$mail->Body = $mailBody;

	if ($sendName = '') {
		$sendName = $mail->FromName;
	}

	$sendToArray = explode(';', $sendTo);
	$sendToNum = count($sendToArray);
	$i = 0;

	for (; $i < $sendToNum; $i++) {
		if ($sendToArray[$i] != '') {
			$mail->AddAddress($sendToArray[$i], $sendName);
		}
	}

	if ($isReturnError == 1) {
		return $mail->Send();
	}
	else {
		$mail->Send();
	}

	unset($mail);
}

function _sendmailinit()
{
	global $Config;
	global $DB;
	global $sendmail;
	require_once ROOT_PATH . 'Includes/Mailer.class.php';
	$sendmail = new phpmailer();
	$SQL = ' SELECT sendFrom,sendName,mailServer,smtp25,smtpName,smtpPassword,isSmtp FROM ' . BASESETTING_TABLE . ' ';
	$MailRow = $DB->queryFirstRow($SQL);
	if (!$MailRow || ($MailRow['mailServer'] == '')) {
		$sendmail->From = $Config['sendFrom'];
		$sendmail->FromName = $Config['sendName'];
		$sendmail->Host = $Config['mailServer'];
		$sendmail->Port = $Config['smtp25'];
		$sendmail->SMTPAuth;
		$sendmail->Username = $Config['smtpName'];
		$sendmail->Password = $Config['smtpPassword'];
		$sendmail->Mailer = 'smtp';
		$sendmail->WordWrap = 50000;

		if ($Config['isSmtp'] == 'y') {
			$sendmail->SMTPAuth = true;
		}
		else {
			$sendmail->SMTPAuth = false;
		}
	}
	else {
		$sendmail->From = $MailRow['sendFrom'];
		$sendmail->FromName = $MailRow['sendName'];
		$sendmail->Host = $MailRow['mailServer'];
		$sendmail->Port = $MailRow['smtp25'];
		$sendmail->SMTPAuth;
		$sendmail->Username = $MailRow['smtpName'];
		$sendmail->Password = $MailRow['smtpPassword'];
		$sendmail->Mailer = 'smtp';
		$sendmail->WordWrap = 50000;

		if ($MailRow['isSmtp'] == 'y') {
			$sendmail->SMTPAuth = true;
		}
		else {
			$sendmail->SMTPAuth = false;
		}
	}

	$sendmail->IsSMTP();
	$sendmail->IsHTML(true);
}

function _sendmailcontent($sendTo, $mailSubject = '', $mailBody = '', $sendName = '', $template = '', $isReturnError = 0)
{
	global $sendmail;
	$sendmail->Subject = $mailSubject;
	$sendmail->Body = $mailBody;
	$sendmail->to = array();
	$_obf_DGMg4ShGQ5Hpmnw_ = explode(';', $sendTo);
	$_obf_D4HpAYeHoOrk = count($_obf_DGMg4ShGQ5Hpmnw_);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < $_obf_D4HpAYeHoOrk; $_obf_7w__++) {
		if ($_obf_DGMg4ShGQ5Hpmnw_[$_obf_7w__] != '') {
			$sendmail->AddAddress($_obf_DGMg4ShGQ5Hpmnw_[$_obf_7w__], $sendName);
		}
	}

	if ($isReturnError == 1) {
		return $sendmail->Send();
	}
	else {
		$sendmail->Send();
	}
}

function checkemail($email)
{
	$_obf_NqDQBqo_ = '\'\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*\'si';
	$_obf_8GMhP4Q_ = '\'\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\'si';
	$email = trim($email);
	if (preg_match_all($_obf_NqDQBqo_, $email, $_obf_63rLGbiIZg__, PREG_SET_ORDER) || preg_match_all($_obf_8GMhP4Q_, $email, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_R2_b = array(' ', '!', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '|', '\\', '{', '[', ']', '}', ':', ';', '\'', '<', ',', '>', '?', '/', '"');
		$_obf_xs33Yt_k = true;
		$_obf_7w__ = 1;

		for (; $_obf_7w__ < count($email); $_obf_7w__++) {
			$_obf_nGgYVm3CFTaH_2rA = substr($email, $_obf_7w__, 1);

			if (in_array($_obf_nGgYVm3CFTaH_2rA, $_obf_R2_b)) {
				$_obf_xs33Yt_k = false;
				break;
			}
		}

		return $_obf_xs33Yt_k;
	}
	else {
		return false;
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

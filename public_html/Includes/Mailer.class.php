<?php
//dezend by http://www.yunlu99.com/
class PHPMailer
{
	/**
     * Email priority (1 = High, 3 = Normal, 5 = low).
     * @var int
     */
	public $Priority = 3;
	/**
     * Sets the CharSet of the message.
     * @var string
     */
	public $CharSet = 'GBK';
	/**
     * Sets the Content-type of the message.
     * @var string
     */
	public $ContentType = 'text/plain';
	/**
     * Sets the Encoding of the message. Options for this are "8bit",
     * "7bit", "binary", "base64", and "quoted-printable".
     * @var string
     */
	public $Encoding = '8bit';
	/**
     * Holds the most recent mailer error message.
     * @var string
     */
	public $ErrorInfo = '';
	/**
     * Sets the From email address for the message.
     * @var string
     */
	public $From = 'root@localhost';
	/**
     * Sets the From name of the message.
     * @var string
     */
	public $FromName = 'Root User';
	/**
     * Sets the Sender email (Return-Path) of the message.  If not empty,
     * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     * @var string
     */
	public $Sender = '';
	/**
     * Sets the Subject of the message.
     * @var string
     */
	public $Subject = '';
	/**
     * Sets the Body of the message.  This can be either an HTML or text body.
     * If HTML then run IsHTML(true).
     * @var string
     */
	public $Body = '';
	/**
     * Sets the text-only body of the message.  This automatically sets the
     * email to multipart/alternative.  This body can be read by mail
     * clients that do not have HTML email capability such as mutt. Clients
     * that can read HTML will view the normal Body.
     * @var string
     */
	public $AltBody = '';
	/**
     * Sets word wrapping on the body of the message to a given number of 
     * characters.
     * @var int
     */
	public $WordWrap = 0;
	/**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
	public $Mailer = 'mail';
	/**
     * Sets the path of the sendmail program.
     * @var string
     */
	public $Sendmail = '/usr/sbin/sendmail';
	/**
     * Path to PHPMailer plugins.  This is now only useful if the SMTP class 
     * is in a different directory than the PHP include path.  
     * @var string
     */
	public $PluginDir = '';
	/**
     *  Holds PHPMailer version.
     *  @var string
     */
	public $Version = '1.31';
	/**
     * Sets the email address that a reading confirmation will be sent.
     * @var string
     */
	public $ConfirmReadingTo = '';
	/**
     *  Sets the hostname to use in Message-Id and Received headers
     *  and as default HELO string. If empty, the value returned
     *  by SERVER_NAME is used or 'localhost.localdomain'.
     *  @var string
     */
	public $Hostname = '';
	/**
     *  Sets the SMTP hosts.  All hosts must be separated by a
     *  semicolon.  You can also specify a different port
     *  for each host by using this format: [hostname:port]
     *  (e.g. "smtp1.example.com:25;smtp2.example.com").
     *  Hosts will be tried in order.
     *  @var string
     */
	public $Host = 'localhost';
	/**
     *  Sets the default SMTP server port.
     *  @var int
     */
	public $Port = 25;
	/**
     *  Sets the SMTP HELO of the message (Default is $Hostname).
     *  @var string
     */
	public $Helo = 'Hello';
	/**
     *  Sets SMTP authentication. Utilizes the Username and Password variables.
     *  @var bool
     */
	public $SMTPAuth = false;
	/**
     *  Sets SMTP username.
     *  @var string
     */
	public $Username = '';
	/**
     *  Sets SMTP password.
     *  @var string
     */
	public $Password = '';
	/**
     *  Sets the SMTP server timeout in seconds. This function will not 
     *  work with the win32 version.
     *  @var int
     */
	public $Timeout = 10;
	/**
     *  Sets SMTP class debugging on or off.
     *  @var bool
     */
	public $SMTPDebug = false;
	/**
     * Prevents the SMTP connection from being closed after each mail 
     * sending.  If this is set to true then to close the connection 
     * requires an explicit call to SmtpClose(). 
     * @var bool
     */
	public $SMTPKeepAlive = false;
	public $smtp;
	public $to = array();
	public $cc = array();
	public $bcc = array();
	public $ReplyTo = array();
	public $attachment = array();
	public $CustomHeader = array();
	public $message_type = '';
	public $boundary = array();
	public $language = array();
	public $error_count = 0;
	public $LE = "\n";

	public function IsHTML($bool)
	{
		if ($bool == true) {
			$this->ContentType = 'text/html';
		}
		else {
			$this->ContentType = 'text/plain';
		}
	}

	public function IsSMTP()
	{
		$this->Mailer = 'smtp';
	}

	public function IsMail()
	{
		$this->Mailer = 'mail';
	}

	public function IsSendmail()
	{
		$this->Mailer = 'sendmail';
	}

	public function IsQmail()
	{
		$this->Sendmail = '/var/qmail/bin/sendmail';
		$this->Mailer = 'sendmail';
	}

	public function AddAddress($address, $name = '')
	{
		$_obf_1oit = count($this->to);
		$this->to[$_obf_1oit][0] = trim($address);
		$this->to[$_obf_1oit][1] = $name;
	}

	public function AddCC($address, $name = '')
	{
		$_obf_1oit = count($this->cc);
		$this->cc[$_obf_1oit][0] = trim($address);
		$this->cc[$_obf_1oit][1] = $name;
	}

	public function AddBCC($address, $name = '')
	{
		$_obf_1oit = count($this->bcc);
		$this->bcc[$_obf_1oit][0] = trim($address);
		$this->bcc[$_obf_1oit][1] = $name;
	}

	public function AddReplyTo($address, $name = '')
	{
		$_obf_1oit = count($this->ReplyTo);
		$this->ReplyTo[$_obf_1oit][0] = trim($address);
		$this->ReplyTo[$_obf_1oit][1] = $name;
	}

	public function Send()
	{
		$_obf_YfrY8VEd = '';
		$_obf_5nVJEQ__ = '';
		$_obf_xs33Yt_k = true;

		if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1) {
			$this->SetError($this->Lang('provide_address'));
			return false;
		}

		if (!empty($this->AltBody)) {
			$this->ContentType = 'multipart/alternative';
		}

		$this->error_count = 0;
		$this->SetMessageType();
		$_obf_YfrY8VEd .= $this->CreateHeader();
		$_obf_5nVJEQ__ = $this->CreateBody();

		if ($_obf_5nVJEQ__ == '') {
			return false;
		}

		switch ($this->Mailer) {
		case 'sendmail':
			$_obf_xs33Yt_k = $this->SendmailSend($_obf_YfrY8VEd, $_obf_5nVJEQ__);
			break;

		case 'mail':
			$_obf_xs33Yt_k = $this->MailSend($_obf_YfrY8VEd, $_obf_5nVJEQ__);
			break;

		case 'smtp':
			$_obf_xs33Yt_k = $this->SmtpSend($_obf_YfrY8VEd, $_obf_5nVJEQ__);
			break;

		default:
			$this->SetError($this->Mailer . $this->Lang('mailer_not_supported'));
			$_obf_xs33Yt_k = false;
			break;
		}

		return $_obf_xs33Yt_k;
	}

	public function SendmailSend($header, $body)
	{
		if ($this->Sender != '') {
			$_obf_o63aBs1_lKI_ = sprintf('%s -oi -f %s -t', $this->Sendmail, $this->Sender);
		}
		else {
			$_obf_o63aBs1_lKI_ = sprintf('%s -oi -t', $this->Sendmail);
		}

		if (!@$_obf_1VvhBg__ = popen($_obf_o63aBs1_lKI_, 'w')) {
			$this->SetError($this->Lang('execute') . $this->Sendmail);
			return false;
		}

		fputs($_obf_1VvhBg__, $header);
		fputs($_obf_1VvhBg__, $body);
		$_obf_xs33Yt_k = (pclose($_obf_1VvhBg__) >> 8) & 255;

		if ($_obf_xs33Yt_k != 0) {
			$this->SetError($this->Lang('execute') . $this->Sendmail);
			return false;
		}

		return true;
	}

	public function MailSend($header, $body)
	{
		$to = '';
		$i = 0;

		for (; $i < count($this->to); $i++) {
			if ($i != 0) {
				$to .= ', ';
			}

			$to .= $this->to[$i][0];
		}

		if (($this->Sender != '') && (strlen(ini_get('safe_mode')) < 1)) {
			$old_from = ini_get('sendmail_from');
			ini_set('sendmail_from', $this->Sender);
			$params = sprintf('-oi -f %s', $this->Sender);
			$rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header, $params);
		}
		else {
			$rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header);
		}

		if (isset($old_from)) {
			ini_set('sendmail_from', $old_from);
		}

		if (!$rt) {
			$this->SetError($this->Lang('instantiate'));
			return false;
		}

		return true;
	}

	public function SmtpSend($header, $body)
	{
		include_once ROOT_PATH . 'Includes/SMTP.class.php';
		$error = '';
		$bad_rcpt = array();

		if (!$this->SmtpConnect()) {
			return false;
		}

		$smtp_from = ($this->Sender == '' ? $this->From : $this->Sender);

		if (!$this->smtp->Mail($smtp_from)) {
			$error = $this->Lang('from_failed') . $smtp_from;
			$this->SetError($error);
			$this->smtp->Reset();
			return false;
		}

		$i = 0;

		for (; $i < count($this->to); $i++) {
			if (!$this->smtp->Recipient($this->to[$i][0])) {
				$bad_rcpt[] = $this->to[$i][0];
			}
		}

		$i = 0;

		for (; $i < count($this->cc); $i++) {
			if (!$this->smtp->Recipient($this->cc[$i][0])) {
				$bad_rcpt[] = $this->cc[$i][0];
			}
		}

		$i = 0;

		for (; $i < count($this->bcc); $i++) {
			if (!$this->smtp->Recipient($this->bcc[$i][0])) {
				$bad_rcpt[] = $this->bcc[$i][0];
			}
		}

		if (0 < count($bad_rcpt)) {
			$i = 0;

			for (; $i < count($bad_rcpt); $i++) {
				if ($i != 0) {
					$error .= ', ';
				}

				$error .= $bad_rcpt[$i];
			}

			$error = $this->Lang('recipients_failed') . $error;
			$this->SetError($error);
			$this->smtp->Reset();
			return false;
		}

		if (!$this->smtp->Data($header . $body)) {
			$this->SetError($this->Lang('data_not_accepted'));
			$this->smtp->Reset();
			return false;
		}

		if ($this->SMTPKeepAlive == true) {
			$this->smtp->Reset();
		}
		else {
			$this->SmtpClose();
		}

		return true;
	}

	public function SmtpConnect()
	{
		if ($this->smtp == NULL) {
			$this->smtp = new SMTP();
		}

		$this->smtp->do_debug = $this->SMTPDebug;
		$_obf_7aA9GTE_ = explode(';', $this->Host);
		$_obf_A_dJMFE_ = 0;
		$_obf_JHi7UNq8nu0YmQ__ = $this->smtp->Connected();

		while (($_obf_A_dJMFE_ < count($_obf_7aA9GTE_)) && ($_obf_JHi7UNq8nu0YmQ__ == false)) {
			if (strstr($_obf_7aA9GTE_[$_obf_A_dJMFE_], ':')) {
				list($_obf_D9yo3A__, $_obf_4Honjw__) = explode(':', $_obf_7aA9GTE_[$_obf_A_dJMFE_]);
			}
			else {
				$_obf_D9yo3A__ = $_obf_7aA9GTE_[$_obf_A_dJMFE_];
				$_obf_4Honjw__ = $this->Port;
			}

			if ($this->smtp->Connect($_obf_D9yo3A__, $_obf_4Honjw__, $this->Timeout)) {
				if ($this->Helo != '') {
					$this->smtp->Hello($this->Helo);
				}
				else {
					$this->smtp->Hello($this->ServerHostname());
				}

				if ($this->SMTPAuth) {
					if (!$this->smtp->Authenticate($this->Username, $this->Password)) {
						$this->SetError($this->Lang('authenticate'));
						$this->smtp->Reset();
						$_obf_JHi7UNq8nu0YmQ__ = false;
					}
				}

				$_obf_JHi7UNq8nu0YmQ__ = true;
			}

			$_obf_A_dJMFE_++;
		}

		if (!$_obf_JHi7UNq8nu0YmQ__) {
			$this->SetError($this->Lang('connect_host'));
		}

		return $_obf_JHi7UNq8nu0YmQ__;
	}

	public function SmtpClose()
	{
		if ($this->smtp != NULL) {
			if ($this->smtp->Connected()) {
				$this->smtp->Quit();
				$this->smtp->Close();
			}
		}
	}

	public function SetLanguage($lang_type, $lang_path = '../Lang/CN/')
	{
		include ROOT_PATH . 'Lang/CN/Lang.mail.inc.php';
		$this->language = $PHPMAILER_LANG;
		return true;
	}

	public function AddrAppend($type, $addr)
	{
		$_obf_dKNpRIJEOo4_ = $type . ': ';
		$_obf_dKNpRIJEOo4_ .= $this->AddrFormat($addr[0]);

		if (1 < count($addr)) {
			$_obf_7w__ = 1;

			for (; $_obf_7w__ < count($addr); $_obf_7w__++) {
				$_obf_dKNpRIJEOo4_ .= ', ' . $this->AddrFormat($addr[$_obf_7w__]);
			}
		}

		$_obf_dKNpRIJEOo4_ .= $this->LE;
		return $_obf_dKNpRIJEOo4_;
	}

	public function AddrFormat($addr)
	{
		if (empty($addr[1])) {
			$_obf_sAlSKonNCEv0 = $addr[0];
		}
		else {
			$_obf_sAlSKonNCEv0 = $this->EncodeHeader($addr[1], 'phrase') . ' <' . $addr[0] . '>';
		}

		return $_obf_sAlSKonNCEv0;
	}

	public function WrapText($message, $length, $qp_mode = false)
	{
		$_obf_dohyoHQ47WuTsQ__ = ($qp_mode ? sprintf(' =%s', $this->LE) : $this->LE);
		$message = $this->FixEOL($message);

		if (substr($message, -1) == $this->LE) {
			$message = substr($message, 0, -1);
		}

		$_obf_CFGoDA__ = explode($this->LE, $message);
		$message = '';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < count($_obf_CFGoDA__); $_obf_7w__++) {
			$_obf__5yIHEo7Jd1W = explode(' ', $_obf_CFGoDA__[$_obf_7w__]);
			$_obf_qQs0 = '';
			$_obf_hA__ = 0;

			for (; $_obf_hA__ < count($_obf__5yIHEo7Jd1W); $_obf_hA__++) {
				$_obf_sxJqFA__ = $_obf__5yIHEo7Jd1W[$_obf_hA__];
				if ($qp_mode && ($length < strlen($_obf_sxJqFA__))) {
					$_obf_VU4lvm0_e_cOlA__ = $length - strlen($_obf_qQs0) - 1;

					if ($_obf_hA__ != 0) {
						if (20 < $_obf_VU4lvm0_e_cOlA__) {
							$_obf_mc2H = $_obf_VU4lvm0_e_cOlA__;

							if (substr($_obf_sxJqFA__, $_obf_mc2H - 1, 1) == '=') {
								$_obf_mc2H--;
							}
							else if (substr($_obf_sxJqFA__, $_obf_mc2H - 2, 1) == '=') {
								$_obf_mc2H -= 2;
							}

							$_obf_hKhKKw__ = substr($_obf_sxJqFA__, 0, $_obf_mc2H);
							$_obf_sxJqFA__ = substr($_obf_sxJqFA__, $_obf_mc2H);
							$_obf_qQs0 .= ' ' . $_obf_hKhKKw__;
							$message .= $_obf_qQs0 . sprintf('=%s', $this->LE);
						}
						else {
							$message .= $_obf_qQs0 . $_obf_dohyoHQ47WuTsQ__;
						}

						$_obf_qQs0 = '';
					}

					while (0 < strlen($_obf_sxJqFA__)) {
						$_obf_mc2H = $length;

						if (substr($_obf_sxJqFA__, $_obf_mc2H - 1, 1) == '=') {
							$_obf_mc2H--;
						}
						else if (substr($_obf_sxJqFA__, $_obf_mc2H - 2, 1) == '=') {
							$_obf_mc2H -= 2;
						}

						$_obf_hKhKKw__ = substr($_obf_sxJqFA__, 0, $_obf_mc2H);
						$_obf_sxJqFA__ = substr($_obf_sxJqFA__, $_obf_mc2H);

						if (0 < strlen($_obf_sxJqFA__)) {
							$message .= $_obf_hKhKKw__ . sprintf('=%s', $this->LE);
						}
						else {
							$_obf_qQs0 = $_obf_hKhKKw__;
						}
					}
				}
				else {
					$_obf_88RlMh4_ = $_obf_qQs0;
					$_obf_qQs0 .= ($_obf_hA__ == 0 ? $_obf_sxJqFA__ : ' ' . $_obf_sxJqFA__);
					if (($length < strlen($_obf_qQs0)) && ($_obf_88RlMh4_ != '')) {
						$message .= $_obf_88RlMh4_ . $_obf_dohyoHQ47WuTsQ__;
						$_obf_qQs0 = $_obf_sxJqFA__;
					}
				}
			}

			$message .= $_obf_qQs0 . $this->LE;
		}

		return $message;
	}

	public function SetWordWrap()
	{
		if ($this->WordWrap < 1) {
			return NULL;
		}

		switch ($this->message_type) {
		case 'alt':
		case 'alt_attachments':
			$this->AltBody = $this->WrapText($this->AltBody, $this->WordWrap);
			break;

		default:
			$this->Body = $this->WrapText($this->Body, $this->WordWrap);
			break;
		}
	}

	public function CreateHeader()
	{
		$_obf_xs33Yt_k = '';
		$_obf_tKgmJD_YAA__ = md5(uniqid(time()));
		$this->boundary[1] = 'b1_' . $_obf_tKgmJD_YAA__;
		$this->boundary[2] = 'b2_' . $_obf_tKgmJD_YAA__;
		$_obf_xs33Yt_k .= $this->HeaderLine('Date', $this->RFCDate());

		if ($this->Sender == '') {
			$_obf_xs33Yt_k .= $this->HeaderLine('Return-Path', trim($this->From));
		}
		else {
			$_obf_xs33Yt_k .= $this->HeaderLine('Return-Path', trim($this->Sender));
		}

		if ($this->Mailer != 'mail') {
			if (0 < count($this->to)) {
				$_obf_xs33Yt_k .= $this->AddrAppend('To', $this->to);
			}
			else if (count($this->cc) == 0) {
				$_obf_xs33Yt_k .= $this->HeaderLine('To', 'undisclosed-recipients:;');
			}

			if (0 < count($this->cc)) {
				$_obf_xs33Yt_k .= $this->AddrAppend('Cc', $this->cc);
			}
		}

		$_obf_v_holQ__ = array();
		$_obf_v_holQ__[0][0] = trim($this->From);
		$_obf_v_holQ__[0][1] = $this->FromName;
		$_obf_xs33Yt_k .= $this->AddrAppend('From', $_obf_v_holQ__);
		if ((($this->Mailer == 'sendmail') || ($this->Mailer == 'mail')) && (0 < count($this->bcc))) {
			$_obf_xs33Yt_k .= $this->AddrAppend('Bcc', $this->bcc);
		}

		if (0 < count($this->ReplyTo)) {
			$_obf_xs33Yt_k .= $this->AddrAppend('Reply-to', $this->ReplyTo);
		}

		if ($this->Mailer != 'mail') {
			$_obf_xs33Yt_k .= $this->HeaderLine('Subject', $this->EncodeHeader(trim($this->Subject)));
		}

		$_obf_xs33Yt_k .= sprintf('Message-ID: <%s@%s>%s', $_obf_tKgmJD_YAA__, $this->ServerHostname(), $this->LE);
		$_obf_xs33Yt_k .= $this->HeaderLine('X-Priority', $this->Priority);
		$_obf_xs33Yt_k .= $this->HeaderLine('X-Mailer', 'EnableQMailer [version ' . $this->Version . ']');

		if ($this->ConfirmReadingTo != '') {
			$_obf_xs33Yt_k .= $this->HeaderLine('Disposition-Notification-To', '<' . trim($this->ConfirmReadingTo) . '>');
		}

		$_obf_A_dJMFE_ = 0;

		for (; $_obf_A_dJMFE_ < count($this->CustomHeader); $_obf_A_dJMFE_++) {
			$_obf_xs33Yt_k .= $this->HeaderLine(trim($this->CustomHeader[$_obf_A_dJMFE_][0]), $this->EncodeHeader(trim($this->CustomHeader[$_obf_A_dJMFE_][1])));
		}

		$_obf_xs33Yt_k .= $this->HeaderLine('MIME-Version', '1.0');

		switch ($this->message_type) {
		case 'plain':
			$_obf_xs33Yt_k .= $this->HeaderLine('Content-Transfer-Encoding', $this->Encoding);
			$_obf_xs33Yt_k .= sprintf('Content-Type: %s; charset="%s"', $this->ContentType, $this->CharSet);
			break;

		case 'attachments':
		case 'alt_attachments':
			if ($this->InlineImageExists()) {
				$_obf_xs33Yt_k .= sprintf('Content-Type: %s;%s	type="text/html";%s	boundary="%s"%s', 'multipart/related', $this->LE, $this->LE, $this->boundary[1], $this->LE);
			}
			else {
				$_obf_xs33Yt_k .= $this->HeaderLine('Content-Type', 'multipart/mixed;');
				$_obf_xs33Yt_k .= $this->TextLine('	boundary="' . $this->boundary[1] . '"');
			}

			break;

		case 'alt':
			$_obf_xs33Yt_k .= $this->HeaderLine('Content-Type', 'multipart/alternative;');
			$_obf_xs33Yt_k .= $this->TextLine('	boundary="' . $this->boundary[1] . '"');
			break;
		}

		if ($this->Mailer != 'mail') {
			$_obf_xs33Yt_k .= $this->LE . $this->LE;
		}

		return $_obf_xs33Yt_k;
	}

	public function CreateBody()
	{
		$_obf_xs33Yt_k = '';
		$this->SetWordWrap();

		switch ($this->message_type) {
		case 'alt':
			$_obf_xs33Yt_k .= $this->GetBoundary($this->boundary[1], '', 'text/plain', '');
			$_obf_xs33Yt_k .= $this->EncodeString($this->AltBody, $this->Encoding);
			$_obf_xs33Yt_k .= $this->LE . $this->LE;
			$_obf_xs33Yt_k .= $this->GetBoundary($this->boundary[1], '', 'text/html', '');
			$_obf_xs33Yt_k .= $this->EncodeString($this->Body, $this->Encoding);
			$_obf_xs33Yt_k .= $this->LE . $this->LE;
			$_obf_xs33Yt_k .= $this->EndBoundary($this->boundary[1]);
			break;

		case 'plain':
			$_obf_xs33Yt_k .= $this->EncodeString($this->Body, $this->Encoding);
			break;

		case 'attachments':
			$_obf_xs33Yt_k .= $this->GetBoundary($this->boundary[1], '', '', '');
			$_obf_xs33Yt_k .= $this->EncodeString($this->Body, $this->Encoding);
			$_obf_xs33Yt_k .= $this->LE;
			$_obf_xs33Yt_k .= $this->AttachAll();
			break;

		case 'alt_attachments':
			$_obf_xs33Yt_k .= sprintf('--%s%s', $this->boundary[1], $this->LE);
			$_obf_xs33Yt_k .= sprintf('Content-Type: %s;%s' . '	boundary="%s"%s', 'multipart/alternative', $this->LE, $this->boundary[2], $this->LE . $this->LE);
			$_obf_xs33Yt_k .= $this->GetBoundary($this->boundary[2], '', 'text/plain', '') . $this->LE;
			$_obf_xs33Yt_k .= $this->EncodeString($this->AltBody, $this->Encoding);
			$_obf_xs33Yt_k .= $this->LE . $this->LE;
			$_obf_xs33Yt_k .= $this->GetBoundary($this->boundary[2], '', 'text/html', '') . $this->LE;
			$_obf_xs33Yt_k .= $this->EncodeString($this->Body, $this->Encoding);
			$_obf_xs33Yt_k .= $this->LE . $this->LE;
			$_obf_xs33Yt_k .= $this->EndBoundary($this->boundary[2]);
			$_obf_xs33Yt_k .= $this->AttachAll();
			break;
		}

		if ($this->IsError()) {
			$_obf_xs33Yt_k = '';
		}

		return $_obf_xs33Yt_k;
	}

	public function GetBoundary($boundary, $charSet, $contentType, $encoding)
	{
		$_obf_xs33Yt_k = '';

		if ($charSet == '') {
			$charSet = $this->CharSet;
		}

		if ($contentType == '') {
			$contentType = $this->ContentType;
		}

		if ($encoding == '') {
			$encoding = $this->Encoding;
		}

		$_obf_xs33Yt_k .= $this->TextLine('--' . $boundary);
		$_obf_xs33Yt_k .= sprintf('Content-Type: %s; charset = "%s"', $contentType, $charSet);
		$_obf_xs33Yt_k .= $this->LE;
		$_obf_xs33Yt_k .= $this->HeaderLine('Content-Transfer-Encoding', $encoding);
		$_obf_xs33Yt_k .= $this->LE;
		return $_obf_xs33Yt_k;
	}

	public function EndBoundary($boundary)
	{
		return $this->LE . '--' . $boundary . '--' . $this->LE;
	}

	public function SetMessageType()
	{
		if ((count($this->attachment) < 1) && (strlen($this->AltBody) < 1)) {
			$this->message_type = 'plain';
		}
		else {
			if (0 < count($this->attachment)) {
				$this->message_type = 'attachments';
			}

			if ((0 < strlen($this->AltBody)) && (count($this->attachment) < 1)) {
				$this->message_type = 'alt';
			}

			if ((0 < strlen($this->AltBody)) && (0 < count($this->attachment))) {
				$this->message_type = 'alt_attachments';
			}
		}
	}

	public function HeaderLine($name, $value)
	{
		return $name . ': ' . $value . $this->LE;
	}

	public function TextLine($value)
	{
		return $value . $this->LE;
	}

	public function AddAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
	{
		if (!@is_file($path)) {
			$this->SetError($this->Lang('file_access') . $path);
			return false;
		}

		$_obf_JTe7jJ4eGW8_ = basename($path);

		if ($name == '') {
			$name = $_obf_JTe7jJ4eGW8_;
		}

		$_obf_1oit = count($this->attachment);
		$this->attachment[$_obf_1oit][0] = $path;
		$this->attachment[$_obf_1oit][1] = $_obf_JTe7jJ4eGW8_;
		$this->attachment[$_obf_1oit][2] = $name;
		$this->attachment[$_obf_1oit][3] = $encoding;
		$this->attachment[$_obf_1oit][4] = $type;
		$this->attachment[$_obf_1oit][5] = false;
		$this->attachment[$_obf_1oit][6] = 'attachment';
		$this->attachment[$_obf_1oit][7] = 0;
		return true;
	}

	public function AttachAll()
	{
		$_obf_vUN7_g__ = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < count($this->attachment); $_obf_7w__++) {
			$_obf_w4EDCuUaeA__ = $this->attachment[$_obf_7w__][5];

			if ($_obf_w4EDCuUaeA__) {
				$_obf_xyiNieq6 = $this->attachment[$_obf_7w__][0];
			}
			else {
				$_obf_pp9pYw__ = $this->attachment[$_obf_7w__][0];
			}

			$_obf_JTe7jJ4eGW8_ = $this->attachment[$_obf_7w__][1];
			$_obf_3gn_eQ__ = $this->attachment[$_obf_7w__][2];
			$_obf_4dDCn5lFjwg_ = $this->attachment[$_obf_7w__][3];
			$_obf_LeS8hw__ = $this->attachment[$_obf_7w__][4];
			$_obf_CJd_GKJqMhehJw0_ = $this->attachment[$_obf_7w__][6];
			$_obf_KBWh = $this->attachment[$_obf_7w__][7];
			$_obf_vUN7_g__[] = sprintf('--%s%s', $this->boundary[1], $this->LE);
			$_obf_vUN7_g__[] = sprintf('Content-Type: %s; name="%s"%s', $_obf_LeS8hw__, $_obf_3gn_eQ__, $this->LE);
			$_obf_vUN7_g__[] = sprintf('Content-Transfer-Encoding: %s%s', $_obf_4dDCn5lFjwg_, $this->LE);

			if ($_obf_CJd_GKJqMhehJw0_ == 'inline') {
				$_obf_vUN7_g__[] = sprintf('Content-ID: <%s>%s', $_obf_KBWh, $this->LE);
			}

			$_obf_vUN7_g__[] = sprintf('Content-Disposition: %s; filename="%s"%s', $_obf_CJd_GKJqMhehJw0_, $_obf_3gn_eQ__, $this->LE . $this->LE);

			if ($_obf_w4EDCuUaeA__) {
				$_obf_vUN7_g__[] = $this->EncodeString($_obf_xyiNieq6, $_obf_4dDCn5lFjwg_);

				if ($this->IsError()) {
					return '';
				}

				$_obf_vUN7_g__[] = $this->LE . $this->LE;
			}
			else {
				$_obf_vUN7_g__[] = $this->EncodeFile($_obf_pp9pYw__, $_obf_4dDCn5lFjwg_);

				if ($this->IsError()) {
					return '';
				}

				$_obf_vUN7_g__[] = $this->LE . $this->LE;
			}
		}

		$_obf_vUN7_g__[] = sprintf('--%s--%s', $this->boundary[1], $this->LE);
		return join('', $_obf_vUN7_g__);
	}

	public function EncodeFile($path, $encoding = 'base64')
	{
		if (!@$_obf_UI8_ = fopen($path, 'rb')) {
			$this->SetError($this->Lang('file_open') . $path);
			return '';
		}

		if (version_compare(phpversion(), '5.3.0', '<')) {
			$_obf_UsGy3TJYhWKuFPpY = get_magic_quotes_runtime();
			set_magic_quotes_runtime(0);
			$_obf_fltqUDb6L8Kr9kI_ = fread($_obf_UI8_, filesize($path));
			$_obf_fltqUDb6L8Kr9kI_ = $this->EncodeString($_obf_fltqUDb6L8Kr9kI_, $encoding);
			fclose($_obf_UI8_);
			set_magic_quotes_runtime($_obf_UsGy3TJYhWKuFPpY);
		}
		else {
			ini_set('magic_quotes_runtime', 0);
			$_obf_fltqUDb6L8Kr9kI_ = fread($_obf_UI8_, filesize($path));
			$_obf_fltqUDb6L8Kr9kI_ = $this->EncodeString($_obf_fltqUDb6L8Kr9kI_, $encoding);
			fclose($_obf_UI8_);
		}

		return $_obf_fltqUDb6L8Kr9kI_;
	}

	public function EncodeString($str, $encoding = 'base64')
	{
		$_obf_Lh_5oYDEsQ__ = '';

		switch (strtolower($encoding)) {
		case 'base64':
			$_obf_Lh_5oYDEsQ__ = chunk_split(base64_encode($str), 76, $this->LE);
			break;

		case '7bit':
		case '8bit':
			$_obf_Lh_5oYDEsQ__ = $this->FixEOL($str);

			if (substr($_obf_Lh_5oYDEsQ__, 0 - strlen($this->LE)) != $this->LE) {
				$_obf_Lh_5oYDEsQ__ .= $this->LE;
			}

			break;

		case 'binary':
			$_obf_Lh_5oYDEsQ__ = $str;
			break;

		case 'quoted-printable':
			$_obf_Lh_5oYDEsQ__ = $this->EncodeQP($str);
			break;

		default:
			$this->SetError($this->Lang('encoding') . $encoding);
			break;
		}

		return $_obf_Lh_5oYDEsQ__;
	}

	public function EncodeHeader($str, $position = 'text')
	{
		$_obf_5Q__ = 0;

		switch (strtolower($position)) {
		case 'phrase':
			if (!preg_match('/[\\200-\\377]/', $str)) {
				$_obf_Lh_5oYDEsQ__ = addcslashes($str, '' . "\0" . '..\\"');
				if (($str == $_obf_Lh_5oYDEsQ__) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\\/=?^_`{|}~ -]/', $str)) {
					return $_obf_Lh_5oYDEsQ__;
				}
				else {
					return '"' . $_obf_Lh_5oYDEsQ__ . '"';
				}
			}

			$_obf_5Q__ = preg_match_all('/[^\\040\\041\\043-\\133\\135-\\176]/', $str, $_obf_8UmnTppRcA__);
			break;

		case 'comment':
			$_obf_5Q__ = preg_match_all('/[()"]/', $str, $_obf_8UmnTppRcA__);
		case 'text':
		default:
			$_obf_5Q__ += preg_match_all('/[\\000-\\010\\013\\014\\016-\\037\\177-\\377]/', $str, $_obf_8UmnTppRcA__);
			break;
		}

		if ($_obf_5Q__ == 0) {
			return $str;
		}

		$_obf_t_Zyr3dY = 75 - 7 - strlen($this->CharSet);

		if ((strlen($str) / 3) < $_obf_5Q__) {
			$_obf_4dDCn5lFjwg_ = 'B';
			$_obf_Lh_5oYDEsQ__ = base64_encode($str);
			$_obf_t_Zyr3dY -= $_obf_t_Zyr3dY % 4;
			$_obf_Lh_5oYDEsQ__ = trim(chunk_split($_obf_Lh_5oYDEsQ__, $_obf_t_Zyr3dY, "\n"));
		}
		else {
			$_obf_4dDCn5lFjwg_ = 'Q';
			$_obf_Lh_5oYDEsQ__ = $this->EncodeQ($str, $position);
			$_obf_Lh_5oYDEsQ__ = $this->WrapText($_obf_Lh_5oYDEsQ__, $_obf_t_Zyr3dY, true);
			$_obf_Lh_5oYDEsQ__ = str_replace('=' . $this->LE, "\n", trim($_obf_Lh_5oYDEsQ__));
		}

		$_obf_Lh_5oYDEsQ__ = preg_replace('/^(.*)$/m', ' =?' . $this->CharSet . '?' . $_obf_4dDCn5lFjwg_ . '?\\1?=', $_obf_Lh_5oYDEsQ__);
		$_obf_Lh_5oYDEsQ__ = trim(str_replace("\n", $this->LE, $_obf_Lh_5oYDEsQ__));
		return $_obf_Lh_5oYDEsQ__;
	}

	public function EncodeQP($str)
	{
		$_obf_Lh_5oYDEsQ__ = $this->FixEOL($str);

		if (substr($_obf_Lh_5oYDEsQ__, 0 - strlen($this->LE)) != $this->LE) {
			$_obf_Lh_5oYDEsQ__ .= $this->LE;
		}

		$_obf_Lh_5oYDEsQ__ = preg_replace('/([\\000-\\010\\013\\014\\016-\\037\\075\\177-\\377])/e', '\'=\'.sprintf(\'%02X\', ord(\'\\1\'))', $_obf_Lh_5oYDEsQ__);
		$_obf_Lh_5oYDEsQ__ = preg_replace('/([	 ])' . $this->LE . '/e', '\'=\'.sprintf(\'%02X\', ord(\'\\1\')).\'' . $this->LE . '\'', $_obf_Lh_5oYDEsQ__);
		$_obf_Lh_5oYDEsQ__ = $this->WrapText($_obf_Lh_5oYDEsQ__, 74, true);
		return $_obf_Lh_5oYDEsQ__;
	}

	public function EncodeQ($str, $position = 'text')
	{
		$_obf_Lh_5oYDEsQ__ = preg_replace('[' . "\r\n" . ']', '', $str);

		switch (strtolower($position)) {
		case 'phrase':
			$_obf_Lh_5oYDEsQ__ = preg_replace('/([^A-Za-z0-9!*+\\/ -])/e', '\'=\'.sprintf(\'%02X\', ord(\'\\1\'))', $_obf_Lh_5oYDEsQ__);
			break;

		case 'comment':
			$_obf_Lh_5oYDEsQ__ = preg_replace('/([\\(\\)"])/e', '\'=\'.sprintf(\'%02X\', ord(\'\\1\'))', $_obf_Lh_5oYDEsQ__);
		case 'text':
		default:
			$_obf_Lh_5oYDEsQ__ = preg_replace('/([\\000-\\011\\013\\014\\016-\\037\\075\\077\\137\\177-\\377])/e', '\'=\'.sprintf(\'%02X\', ord(\'\\1\'))', $_obf_Lh_5oYDEsQ__);
			break;
		}

		$_obf_Lh_5oYDEsQ__ = str_replace(' ', '_', $_obf_Lh_5oYDEsQ__);
		return $_obf_Lh_5oYDEsQ__;
	}

	public function AddStringAttachment($string, $filename, $encoding = 'base64', $type = 'application/octet-stream')
	{
		$_obf_1oit = count($this->attachment);
		$this->attachment[$_obf_1oit][0] = $string;
		$this->attachment[$_obf_1oit][1] = $filename;
		$this->attachment[$_obf_1oit][2] = $filename;
		$this->attachment[$_obf_1oit][3] = $encoding;
		$this->attachment[$_obf_1oit][4] = $type;
		$this->attachment[$_obf_1oit][5] = true;
		$this->attachment[$_obf_1oit][6] = 'attachment';
		$this->attachment[$_obf_1oit][7] = 0;
	}

	public function AddEmbeddedImage($path, $cid, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
	{
		if (!@is_file($path)) {
			$this->SetError($this->Lang('file_access') . $path);
			return false;
		}

		$_obf_JTe7jJ4eGW8_ = basename($path);

		if ($name == '') {
			$name = $_obf_JTe7jJ4eGW8_;
		}

		$_obf_1oit = count($this->attachment);
		$this->attachment[$_obf_1oit][0] = $path;
		$this->attachment[$_obf_1oit][1] = $_obf_JTe7jJ4eGW8_;
		$this->attachment[$_obf_1oit][2] = $name;
		$this->attachment[$_obf_1oit][3] = $encoding;
		$this->attachment[$_obf_1oit][4] = $type;
		$this->attachment[$_obf_1oit][5] = false;
		$this->attachment[$_obf_1oit][6] = 'inline';
		$this->attachment[$_obf_1oit][7] = $cid;
		return true;
	}

	public function InlineImageExists()
	{
		$_obf_xs33Yt_k = false;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < count($this->attachment); $_obf_7w__++) {
			if ($this->attachment[$_obf_7w__][6] == 'inline') {
				$_obf_xs33Yt_k = true;
				break;
			}
		}

		return $_obf_xs33Yt_k;
	}

	public function ClearAddresses()
	{
		$this->to = array();
	}

	public function ClearCCs()
	{
		$this->cc = array();
	}

	public function ClearBCCs()
	{
		$this->bcc = array();
	}

	public function ClearReplyTos()
	{
		$this->ReplyTo = array();
	}

	public function ClearAllRecipients()
	{
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
	}

	public function ClearAttachments()
	{
		$this->attachment = array();
	}

	public function ClearCustomHeaders()
	{
		$this->CustomHeader = array();
	}

	public function SetError($msg)
	{
		global $Config;
		$this->error_count++;
		$this->ErrorInfo = $msg;
		$_obf_gE3s0aARMA__ = $Config['absolutenessPath'] . 'PerUserData/log/MailError.txt';
		if (file_exists($_obf_gE3s0aARMA__) && (2097152 <= filesize($_obf_gE3s0aARMA__))) {
			@unlink($_obf_gE3s0aARMA__);
		}

		$_obf__WwKzYz1wA__ = date('Y-m-d H:i:s') . '     ' . $msg . '' . "\r\n" . '' . "\r\n" . '';
		$_obf_fGUXVVIMpmivTB6M = fopen($_obf_gE3s0aARMA__, 'a');
		fwrite($_obf_fGUXVVIMpmivTB6M, $_obf__WwKzYz1wA__);
		fclose($_obf_fGUXVVIMpmivTB6M);
	}

	public function RFCDate()
	{
		$_obf_B8E_ = date('Z');
		$_obf_8oHl = ($_obf_B8E_ < 0 ? '-' : '+');
		$_obf_B8E_ = abs($_obf_B8E_);
		$_obf_B8E_ = (($_obf_B8E_ / 3600) * 100) + (($_obf_B8E_ % 3600) / 60);
		$_obf_xs33Yt_k = sprintf('%s %s%04d', date('D, j M Y H:i:s'), $_obf_8oHl, $_obf_B8E_);
		return $_obf_xs33Yt_k;
	}

	public function ServerVar($varName)
	{
		global $HTTP_SERVER_VARS;
		global $HTTP_ENV_VARS;

		if (!isset($_SERVER)) {
			$_SERVER = $HTTP_SERVER_VARS;

			if (!isset($_SERVER['REMOTE_ADDR'])) {
				$_SERVER = $HTTP_ENV_VARS;
			}
		}

		if (isset($_SERVER[$varName])) {
			return $_SERVER[$varName];
		}
		else {
			return '';
		}
	}

	public function ServerHostname()
	{
		if ($this->Hostname != '') {
			$_obf_xs33Yt_k = $this->Hostname;
		}
		else if ($this->ServerVar('SERVER_NAME') != '') {
			$_obf_xs33Yt_k = $this->ServerVar('SERVER_NAME');
		}
		else {
			$_obf_xs33Yt_k = 'localhost.localdomain';
		}

		return $_obf_xs33Yt_k;
	}

	public function Lang($key)
	{
		if (count($this->language) < 1) {
			$this->SetLanguage('en');
		}

		if (isset($this->language[$key])) {
			return $this->language[$key];
		}
		else {
			return 'Language string failed to load: ' . $key;
		}
	}

	public function IsError()
	{
		return 0 < $this->error_count;
	}

	public function FixEOL($str)
	{
		$str = str_replace("\r\n", "\n", $str);
		$str = str_replace("\r", "\n", $str);
		$str = str_replace("\n", $this->LE, $str);
		return $str;
	}

	public function AddCustomHeader($custom_header)
	{
		$this->CustomHeader[] = explode(':', $custom_header, 2);
	}
}


?>

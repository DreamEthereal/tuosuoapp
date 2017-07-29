<?php
//dezend by http://www.yunlu99.com/
class SMTP
{
	/**
     *  SMTP server port
     *  @var int
     */
	public $SMTP_PORT = 25;
	/**
     *  SMTP reply line ending
     *  @var string
     */
	public $CRLF = "\r\n";
	/**
     *  Sets whether debugging is turned on
     *  @var bool
     */
	public $do_debug;
	public $smtp_conn;
	public $error;
	public $helo_rply;

	public function SMTP()
	{
		$this->smtp_conn = 0;
		$this->error = NULL;
		$this->helo_rply = NULL;
		$this->do_debug = 0;
	}

	public function Connect($host, $port = 0, $tval = 30)
	{
		$this->error = NULL;

		if ($this->connected()) {
			$this->error = array('error' => 'Already connected to a server');
			return false;
		}

		if (empty($port)) {
			$port = $this->SMTP_PORT;
		}

		if (!function_exists('fsockopen')) {
			if (!function_exists('pfsockopen')) {
				$this->smtp_conn = stream_socket_client($host . ':' . $port, $errno, $errstr, $tval);
			}
			else {
				$this->smtp_conn = pfsockopen($host, $port, $errno, $errstr, $tval);
			}
		}
		else {
			$this->smtp_conn = fsockopen($host, $port, $errno, $errstr, $tval);
		}

		if (empty($this->smtp_conn)) {
			$this->error = array('error' => 'Failed to connect to server', 'errno' => $errno, 'errstr' => $errstr);

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $errstr . ' (' . $errno . ')' . $this->CRLF;
			}

			return false;
		}

		if (substr(PHP_OS, 0, 3) != 'WIN') {
			socket_set_timeout($this->smtp_conn, $tval, 0);
		}

		$announce = $this->get_lines();

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $announce;
		}

		return true;
	}

	public function Authenticate($username, $password)
	{
		fputs($this->smtp_conn, 'AUTH LOGIN' . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if ($_obf_olwD8Q__ != 334) {
			$this->error = array('error' => 'AUTH not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		fputs($this->smtp_conn, base64_encode($username) . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if ($_obf_olwD8Q__ != 334) {
			$this->error = array('error' => 'Username not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		fputs($this->smtp_conn, base64_encode($password) . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if ($_obf_olwD8Q__ != 235) {
			$this->error = array('error' => 'Password not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Connected()
	{
		if (!empty($this->smtp_conn)) {
			$_obf_tE8OEYAuu5YXuP4_ = socket_get_status($this->smtp_conn);

			if ($_obf_tE8OEYAuu5YXuP4_['eof']) {
				if (1 <= $this->do_debug) {
					echo 'SMTP -> NOTICE:' . $this->CRLF . 'EOF caught while checking if connected';
				}

				$this->Close();
				return false;
			}

			return true;
		}

		return false;
	}

	public function Close()
	{
		$this->error = NULL;
		$this->helo_rply = NULL;

		if (!empty($this->smtp_conn)) {
			fclose($this->smtp_conn);
			$this->smtp_conn = 0;
		}
	}

	public function Data($msg_data)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Data() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'DATA' . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $rply;
		}

		if ($code != 354) {
			$this->error = array('error' => 'DATA command not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr($rply, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF;
			}

			return false;
		}

		$msg_data = str_replace("\r\n", "\n", $msg_data);
		$msg_data = str_replace("\r", "\n", $msg_data);
		$lines = explode("\n", $msg_data);
		$field = substr($lines[0], 0, strpos($lines[0], ':'));
		$in_headers = false;
		if (!empty($field) && !strstr($field, ' ')) {
			$in_headers = true;
		}

		$max_line_length = 998;

		while (list(, $line) = @each($lines)) {
			$lines_out = NULL;
			if (($line == '') && $in_headers) {
				$in_headers = false;
			}

			while ($max_line_length < strlen($line)) {
				$pos = strrpos(substr($line, 0, $max_line_length), ' ');

				if (!$pos) {
					$pos = $max_line_length - 1;
				}

				$lines_out[] = substr($line, 0, $pos);
				$line = substr($line, $pos + 1);

				if ($in_headers) {
					$line = '	' . $line;
				}
			}

			$lines_out[] = $line;

			while (list(, $line_out) = @each($lines_out)) {
				if (0 < strlen($line_out)) {
					if (substr($line_out, 0, 1) == '.') {
						$line_out = '.' . $line_out;
					}
				}

				fputs($this->smtp_conn, $line_out . $this->CRLF);
			}
		}

		fputs($this->smtp_conn, $this->CRLF . '.' . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $rply;
		}

		if ($code != 250) {
			$this->error = array('error' => 'DATA not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr($rply, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Expand($name)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Expand() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'EXPN ' . $name . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'EXPN not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		$_obf_OqDlGQs0ew__ = explode($this->CRLF, $_obf_fmDH_A__);

		while (list(, $A) = @each($_obf_OqDlGQs0ew__)) {
			$_obf_fMfssw__[] = substr($A, 4);
		}

		return $_obf_fMfssw__;
	}

	public function Hello($host = '')
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Hello() without being connected');
			return false;
		}

		if (empty($host)) {
			$host = 'localhost';
		}

		if (!$this->SendHello('EHLO', $host)) {
			if (!$this->SendHello('HELO', $host)) {
				return false;
			}
		}

		return true;
	}

	public function SendHello($hello, $host)
	{
		fputs($this->smtp_conn, $hello . ' ' . $host . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER: ' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => $hello . ' not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		$this->helo_rply = $_obf_fmDH_A__;
		return true;
	}

	public function Help($keyword = '')
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Help() without being connected');
			return false;
		}

		$extra = '';

		if (!empty($keyword)) {
			$extra = ' ' . $keyword;
		}

		fputs($this->smtp_conn, 'HELP' . $extra . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $rply;
		}

		if (($code != 211) && ($code != 214)) {
			$this->error = array('error' => 'HELP not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr($rply, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF;
			}

			return false;
		}

		return $rply;
	}

	public function Mail($from)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Mail() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'MAIL FROM:<' . $from . '>' . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'MAIL not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Noop()
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Noop() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'NOOP' . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'NOOP not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Quit($close_on_error = true)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Quit() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'quit' . $this->CRLF);
		$byemsg = $this->get_lines();

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $byemsg;
		}

		$rval = true;
		$e = NULL;
		$code = substr($byemsg, 0, 3);

		if ($code != 221) {
			$e = array('error' => 'SMTP server rejected quit command', 'smtp_code' => $code, 'smtp_rply' => substr($byemsg, 4));
			$rval = false;

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $e['error'] . ': ' . $byemsg . $this->CRLF;
			}
		}

		if (empty($e) || $close_on_error) {
			$this->Close();
		}

		return $rval;
	}

	public function Recipient($to)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Recipient() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'RCPT TO:<' . $to . '>' . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if (($_obf_olwD8Q__ != 250) && ($_obf_olwD8Q__ != 251)) {
			$this->error = array('error' => 'RCPT not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Reset()
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Reset() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'RSET' . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'RSET failed', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Send($from)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Send() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'SEND FROM:' . $from . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'SEND not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function SendAndMail($from)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called SendAndMail() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'SAML FROM:' . $from . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'SAML not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function SendOrMail($from)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called SendOrMail() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'SOML FROM:' . $from . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if ($_obf_olwD8Q__ != 250) {
			$this->error = array('error' => 'SOML not accepted from server', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return true;
	}

	public function Turn()
	{
		$this->error = array('error' => 'This method, TURN, of the SMTP ' . 'is not implemented');

		if (1 <= $this->do_debug) {
			echo 'SMTP -> NOTICE: ' . $this->error['error'] . $this->CRLF;
		}

		return false;
	}

	public function Verify($name)
	{
		$this->error = NULL;

		if (!$this->connected()) {
			$this->error = array('error' => 'Called Verify() without being connected');
			return false;
		}

		fputs($this->smtp_conn, 'VRFY ' . $name . $this->CRLF);
		$_obf_fmDH_A__ = $this->get_lines();
		$_obf_olwD8Q__ = substr($_obf_fmDH_A__, 0, 3);

		if (2 <= $this->do_debug) {
			echo 'SMTP -> FROM SERVER:' . $this->CRLF . $_obf_fmDH_A__;
		}

		if (($_obf_olwD8Q__ != 250) && ($_obf_olwD8Q__ != 251)) {
			$this->error = array('error' => 'VRFY failed on name \'' . $name . '\'', 'smtp_code' => $_obf_olwD8Q__, 'smtp_msg' => substr($_obf_fmDH_A__, 4));

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $_obf_fmDH_A__ . $this->CRLF;
			}

			return false;
		}

		return $_obf_fmDH_A__;
	}

	public function get_lines()
	{
		$_obf_6RYLWQ__ = '';

		while (!feof($this->smtp_conn) && ($_obf_R2_b = fgets($this->smtp_conn, 515))) {
			if (4 <= $this->do_debug) {
				echo 'SMTP -> get_lines(): $data was "' . $_obf_6RYLWQ__ . '"' . $this->CRLF;
				echo 'SMTP -> get_lines(): $str is "' . $_obf_R2_b . '"' . $this->CRLF;
			}

			$_obf_6RYLWQ__ .= $_obf_R2_b;

			if (4 <= $this->do_debug) {
				echo 'SMTP -> get_lines(): $data is "' . $_obf_6RYLWQ__ . '"' . $this->CRLF;
			}

			if (substr($_obf_R2_b, 3, 1) == ' ') {
				break;
			}
		}

		return $_obf_6RYLWQ__;
	}
}


?>

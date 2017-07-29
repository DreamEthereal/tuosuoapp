<?php
//dezend by http://www.yunlu99.com/
class MySQL_Backup
{
	public $server = 'localhost';
	public $port = 3306;
	public $username = 'root';
	public $password = '';
	public $database = '';
	public $link_id = -1;
	public $connected = false;
	public $tables = array();
	public $drop_tables = true;
	public $struct_only = false;
	public $comments = true;
	public $backup_dir = '';
	public $enableqver = '';
	public $fname_format = 'm_d_Y_H_i_s';
	public $error = '';

	public function Execute($task = MSB_STRING, $fname = '', $compress = false)
	{
		if (!($sql = $this->_Retrieve())) {
			return false;
		}

		if ($task == MSB_SAVE) {
			if (empty($fname)) {
				$fname = $this->backup_dir;
				$fname .= date($this->fname_format) . '_' . rand(0, 9999) . '_' . $this->enableqver;
				$fname .= ($compress ? '.sql.gz' : '.sql');
			}

			return $this->_SaveToFile($fname, $sql, $compress);
		}
		else if ($task == MSB_DOWNLOAD) {
			if (empty($fname)) {
				$fname = date($this->fname_format);
				$fname .= ($compress ? '.sql.gz' : '.sql');
			}

			return $this->_DownloadFile($fname, $sql, $compress);
		}
		else {
			return $sql;
		}
	}

	public function _Connect()
	{
		$_obf_VgKtFeg_ = false;

		if (!$this->connected) {
			$_obf_D9yo3A__ = $this->server . ':' . $this->port;
			$this->link_id = mysql_connect($_obf_D9yo3A__, $this->username, $this->password);
		}

		if ($this->link_id) {
			if (empty($this->database)) {
				$_obf_VgKtFeg_ = true;
			}
			else if ($this->link_id !== -1) {
				$_obf_VgKtFeg_ = mysql_select_db($this->database, $this->link_id);
			}
			else {
				$_obf_VgKtFeg_ = mysql_select_db($this->database);
			}
		}

		if (!$_obf_VgKtFeg_) {
			$this->error = mysql_error();
		}

		return $_obf_VgKtFeg_;
	}

	public function _Query($sql)
	{
		if ($this->link_id !== -1) {
			$_obf_xs33Yt_k = mysql_query($sql, $this->link_id);
		}
		else {
			$_obf_xs33Yt_k = mysql_query($sql);
		}

		if (!$_obf_xs33Yt_k) {
			$this->error = mysql_error();
		}

		return $_obf_xs33Yt_k;
	}

	public function _GetTables()
	{
		$_obf_VgKtFeg_ = array();

		if (!($_obf_xs33Yt_k = $this->_Query('SHOW TABLES'))) {
			return false;
		}

		while ($_obf_g_kt = mysql_fetch_row($_obf_xs33Yt_k)) {
			if (empty($this->tables) || in_array($_obf_g_kt[0], $this->tables)) {
				$_obf_VgKtFeg_[] = $_obf_g_kt[0];
			}
		}

		if (!sizeof($_obf_VgKtFeg_)) {
			$this->error = 'No tables found in database.';
			return false;
		}

		return $_obf_VgKtFeg_;
	}

	public function _DumpTable($table)
	{
		$_obf_VgKtFeg_ = '';
		$this->_Query('LOCK TABLES ' . $table . ' WRITE');

		if ($this->comments) {
			$_obf_VgKtFeg_ .= '#' . MSB_NL;
			$_obf_VgKtFeg_ .= '# Table structure for table `' . $table . '`' . MSB_NL;
			$_obf_VgKtFeg_ .= '#' . MSB_NL . MSB_NL;
		}

		if ($this->drop_tables) {
			$_obf_VgKtFeg_ .= 'DROP TABLE IF EXISTS `' . $table . '`;' . MSB_NL;
		}

		if (!($_obf_xs33Yt_k = $this->_Query('SHOW CREATE TABLE ' . $table))) {
			return false;
		}

		$_obf_g_kt = mysql_fetch_assoc($_obf_xs33Yt_k);
		$_obf_VgKtFeg_ .= str_replace("\n", MSB_NL, $_obf_g_kt['Create Table']) . ';';
		$_obf_VgKtFeg_ .= MSB_NL . MSB_NL;

		if (!$this->struct_only) {
			if ($this->comments) {
				$_obf_VgKtFeg_ .= '#' . MSB_NL;
				$_obf_VgKtFeg_ .= '# Dumping data for table `' . $table . '`' . MSB_NL;
				$_obf_VgKtFeg_ .= '#' . MSB_NL . MSB_NL;
			}

			$_obf_VgKtFeg_ .= $this->_GetInserts($table);
		}

		$_obf_VgKtFeg_ .= MSB_NL . MSB_NL;
		$this->_Query('UNLOCK TABLES');
		return $_obf_VgKtFeg_;
	}

	public function _GetInserts($table)
	{
		$_obf_VgKtFeg_ = '';

		if (!($_obf_xs33Yt_k = $this->_Query('SELECT * FROM ' . $table))) {
			return false;
		}

		while ($_obf_g_kt = mysql_fetch_row($_obf_xs33Yt_k)) {
			$_obf_hv2rm1_p = '';

			foreach ($_obf_g_kt as $_obf_6RYLWQ__) {
				$_obf_hv2rm1_p .= '\'' . addslashes($_obf_6RYLWQ__) . '\', ';
			}

			$_obf_hv2rm1_p = substr($_obf_hv2rm1_p, 0, -2);
			$_obf_VgKtFeg_ .= 'INSERT INTO ' . $table . ' VALUES (' . $_obf_hv2rm1_p . ');' . MSB_NL;
		}

		return $_obf_VgKtFeg_;
	}

	public function _Retrieve()
	{
		$_obf_VgKtFeg_ = '';

		if (!$this->_Connect()) {
			return false;
		}

		if ($this->comments) {
			$_obf_VgKtFeg_ .= '#' . MSB_NL;
			$_obf_VgKtFeg_ .= '# MySQL database dump' . MSB_NL;
			$_obf_VgKtFeg_ .= '# Created by EnableQ System Mysql Backup' . MSB_NL;
			$_obf_VgKtFeg_ .= '#' . MSB_NL;
			$_obf_VgKtFeg_ .= '# Host: ' . $this->server . MSB_NL;
			$_obf_VgKtFeg_ .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . MSB_NL;
			$_obf_VgKtFeg_ .= '# MySQL version: ' . mysql_get_server_info() . MSB_NL;
			$_obf_VgKtFeg_ .= '# PHP version: ' . phpversion() . MSB_NL;

			if (!empty($this->database)) {
				$_obf_VgKtFeg_ .= '#' . MSB_NL;
				$_obf_VgKtFeg_ .= '# Database: `' . $this->database . '`' . MSB_NL;
			}

			$_obf_VgKtFeg_ .= '#' . MSB_NL . MSB_NL . MSB_NL;
		}

		if (!($_obf_7tOOEsHR = $this->_GetTables())) {
			return false;
		}

		foreach ($_obf_7tOOEsHR as $_obf_3tiDsnM_) {
			if (!($_obf_GAIIOnYioWnFmg__ = $this->_DumpTable($_obf_3tiDsnM_))) {
				$this->error = mysql_error();
				return false;
			}

			$_obf_VgKtFeg_ .= $_obf_GAIIOnYioWnFmg__;
		}

		return $_obf_VgKtFeg_;
	}

	public function _SaveToFile($fname, $sql, $compress)
	{
		if ($compress) {
			if (!($_obf___0_ = gzopen($fname, 'w9'))) {
				$this->error = 'Can\'t create the output file.';
				return false;
			}

			gzwrite($_obf___0_, $sql);
			gzclose($_obf___0_);
		}
		else {
			if (!($_obf_6Q__ = fopen($fname, 'w'))) {
				$this->error = 'Can\'t create the output file.';
				return false;
			}

			fwrite($_obf_6Q__, $sql);
			fclose($_obf_6Q__);
		}

		return true;
	}

	public function _DownloadFile($fname, $sql, $compress)
	{
		header('Content-disposition: filename=' . $fname);
		header('Content-type: application/octetstream');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $compress ? gzencode($sql) : $sql;
		return true;
	}
}

define('MSB_NL', "\r\n");
define('MSB_STRING', 0);
define('MSB_DOWNLOAD', 1);
define('MSB_SAVE', 2);

?>

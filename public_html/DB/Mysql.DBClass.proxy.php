<?php
//dezend by http://www.yunlu99.com/
class DB
{
	public $connection;
	public $query_id = 0;
	public $query_count = 0;
	public $query_time = 0;
	public $dbType;
	public $query_array = array();
	public $table_fields = array();
	public $lastmysqlrow = -1;
	public $cur_db = '';
	public $ro_exist = false;
	public $link_ro;
	public $link_rw;

	public function DB()
	{
	}

	public function connect($db_host, $db_user, $db_password = '', $db_name = '', $db_lang = '', $db_pconnect = 0, $halt = true)
	{
		$_obf_QVc3iMN0WuDQbLIjsbA_ = ($db_pconnect ? 'mysql_pconnect' : 'mysql_connect');

		if (!$this->connection = $_obf_QVc3iMN0WuDQbLIjsbA_($db_host, $db_user, $db_password)) {
			$this->error('Could not connect to the database server (' . $db_host . ', ' . $db_user . ').', 1);
		}

		if (!$this->connection && !$halt) {
			return false;
		}

		if ($this->link_rw == NULL) {
			$this->link_rw = $this->connection;
		}

		if ('4.1' < $this->version()) {
			if ($db_lang != '') {
				@mysql_query('SET character_set_connection=\'' . $db_lang . '\',character_set_results=\'' . $db_lang . '\',character_set_client=binary', $this->connection);
			}

			if ('5.0.1' < $this->version()) {
				@mysql_query('SET sql_mode=\'\'', $this->connection);
			}
		}

		if ($db_name != '') {
			if (!$this->select_db($db_name)) {
				@mysql_close($this->connection);
				$this->error('Could not select database (' . $db_name . ').', 1);
			}
		}
	}

	public function connect_ro($db_host, $db_user, $db_password, $db_name = '', $db_lang = '', $pconnect = 0)
	{
		if ($this->link_rw == NULL) {
			$this->link_rw = $this->connection;
		}

		$this->connection = NULL;
		$this->connect($db_host, $db_user, $db_password, $db_name, $db_lang, $pconnect, false);

		if ($this->connection) {
			$this->ro_exist = true;
			$this->link_ro = $this->connection;

			if ($this->cur_db) {
				if (!@mysql_select_db($this->cur_db, $this->link_ro)) {
					@mysql_close($this->connection);
					$this->error('Could not select database (' . $this->cur_db . ').', 1);
				}
			}
		}
		else {
			$this->connection = &$this->link_rw;
		}
	}

	public function set_ro_list($ro_list)
	{
		if (is_array($ro_list)) {
			$_obf_8m8_AvKUrA__ = $ro_list[array_rand($ro_list)];
			$this->connect_ro($_obf_8m8_AvKUrA__['db_host'], $_obf_8m8_AvKUrA__['db_user'], $_obf_8m8_AvKUrA__['db_pw'], $_obf_8m8_AvKUrA__['db_name'], $_obf_8m8_AvKUrA__['db_lang']);
		}
	}

	public function select_db($db_name)
	{
		$this->cur_db = $db_name;

		if ($this->ro_exist) {
			if (!@mysql_select_db($db_name, $this->link_ro)) {
				@mysql_close($this->connection);
				$this->error('Could not select database (' . $db_name . ').', 1);
			}
		}

		return @mysql_select_db($db_name, $this->link_rw);
	}

	public function __destruct()
	{
		$this->close();
	}

	public function version()
	{
		return mysql_get_server_info($this->connection);
	}

	public function close()
	{
		if ($this->connection) {
			if ($this->query_id) {
				@mysql_free_result($this->query_id);
			}

			return @mysql_close($this->connection);
		}
		else {
			return false;
		}
	}

	public function query($query = '')
	{
		$this->connection = &$this->link_rw;
		if ($this->ro_exist && preg_match('/^(\\s*)select/i', $query)) {
			$this->connection = &$this->link_ro;
		}

		unset($this->query_id);

		if ($query != '') {
			if (!$this->query_id = @mysql_query($query, $this->connection)) {
				$this->error('<b>Bad SQL Query</b>: ' . htmlentities($query) . '<br><b>' . mysql_error() . '</b>');
			}

			$this->query_count++;
			return $this->query_id;
		}
	}

	public function queryArray($query_id = -1, $assoc = 0)
	{
		if ($query_id != -1) {
			$this->query_id = $query_id;
		}

		if ($this->query_id) {
			return $assoc ? mysql_fetch_assoc($this->query_id) : mysql_fetch_array($this->query_id);
		}
	}

	public function freeResult($query_id = -1)
	{
		if ($query_id != -1) {
			$this->query_id = $query_id;
		}

		return @mysql_free_result($this->query_id);
	}

	public function queryFirstRow($query = '')
	{
		if ($query != '') {
			$this->query($query);
		}

		$_obf_xs33Yt_k = $this->queryArray($this->query_id);
		$this->freeResult();
		return $_obf_xs33Yt_k;
	}

	public function _GetNumRows($query_id = -1)
	{
		if ($query_id != -1) {
			$this->query_id = $query_id;
		}

		return @mysql_num_rows($this->query_id);
	}

	public function _GetInsertID()
	{
		return $this->connection ? @mysql_insert_id($this->connection) : 0;
	}

	public function _GetNextID($column = '', $table = '')
	{
		if (!empty($column) && !empty($table)) {
			$SQL = ' SELECT MAX(' . $column . ') AS max_id FROM ' . $table . ' ';
			$Row = $this->query_firstrow($SQL);
			return 0 < ($Row['max_id'] + 1) ? $Row['max_id'] + 1 : 1;
		}
		else {
			return NULL;
		}
	}

	public function _GetNumFields($query_id = -1)
	{
		if ($query_id != -1) {
			$this->query_id = $query_id;
		}

		return @mysql_num_fields($this->query_id);
	}

	public function _GetFieldName($query_id = -1, $offset)
	{
		if ($query_id != -1) {
			$this->query_id = $query_id;
		}

		return @mysql_field_name($this->query_id, $offset);
	}

	public function _GetFieldType($query_id = -1, $offset)
	{
		if ($query_id != -1) {
			$this->query_id = $query_id;
		}

		return @mysql_field_type($this->query_id, $offset);
	}

	public function _GetTableFields($table)
	{
		if (!empty($this->table_fields[$table])) {
			return $this->table_fields[$table];
		}

		$this->table_fields[$table] = array();
		$_obf_xs33Yt_k = $this->query(' SHOW FIELDS FROM ' . $table . ' ');

		while ($_obf_9WwQ = $this->queryArray($_obf_xs33Yt_k)) {
			$this->table_fields[$table][$_obf_9WwQ['Field']] = $_obf_9WwQ['Type'];
		}

		return $this->table_fields[$table];
	}

	public function affectedRows()
	{
		return $this->connection ? @mysql_affected_rows($this->connection) : 0;
	}

	public function _IsEmpty($query = '')
	{
		if ($query != '') {
			$this->query($query);
		}

		return !mysql_num_rows($this->query_id) ? 1 : 0;
	}

	public function notEmpty($query = '')
	{
		if ($query != '') {
			$this->query($query);
		}

		return !mysql_num_rows($this->query_id) ? 0 : 1;
	}

	public function Exportquery($sql, $dieOnError = false, $msg = '')
	{
		$_obf_xs33Yt_k = &mysql_query($sql);
		$this->lastmysqlrow = -1;
		return $_obf_xs33Yt_k;
	}

	public function getFieldsArray(&$result)
	{
		$field_array = array();
		if (!isset($result) || empty($result)) {
			return 0;
		}

		$i = 0;

		while ($i < mysql_num_fields($result)) {
			$meta = mysql_fetch_field($result, $i);

			if (!$meta) {
				return 0;
			}

			array_push($field_array, $meta->name);
			$i++;
		}

		return $field_array;
	}

	public function fetchByAssoc(&$result, $rowNum = -1, $encode = true)
	{
		if (isset($result) && ($rowNum < 0)) {
			$row = @mysql_fetch_assoc($result);
			if ($encode && is_array($row)) {
				return array_map('to_html', $row);
			}

			return $row;
		}
		else {
			if ($rowNum < $this->getRowCount($result)) {
				@mysql_data_seek($result, $rowNum);
			}

			$this->lastmysqlrow = $rowNum;
			$row = @mysql_fetch_assoc($result);
			if ($encode && is_array($row)) {
				return array_map('to_html', $row);
			}

			return $row;
		}
	}

	public function getRowCount(&$result)
	{
		if (isset($result) && !empty($result)) {
			if ($this->dbType == 'mysql') {
				return mysql_numrows($result);
			}
			else {
				return $result->numRows();
			}
		}

		return 0;
	}

	public function error($errmsg, $halt = 0)
	{
		global $Config;
		global $ItEnableCoreClass;
		global $lang;

		if ($Config['SQLerrorIsDisplay'] == '1') {
			if ($Config['showSQLError'] == '1') {
				_showerror($lang['MySQL_error'], $errmsg);
			}
			else {
				$_obf_gE3s0aARMA__ = $Config['absolutenessPath'] . 'PerUserData/log/DBError_' . date('YmdHis') . '_' . rand(1, 9999999) . '.txt';
				if (file_exists($_obf_gE3s0aARMA__) && (2097152 <= filesize($_obf_gE3s0aARMA__))) {
					@unlink($_obf_gE3s0aARMA__);
				}

				$_obf__WwKzYz1wA__ = date('Y-m-d H:i:s') . '     ' . $errmsg . '' . "\r\n" . '' . "\r\n" . '';
				$_obf_fGUXVVIMpmivTB6M = fopen($_obf_gE3s0aARMA__, 'a');
				fwrite($_obf_fGUXVVIMpmivTB6M, $_obf__WwKzYz1wA__);
				fclose($_obf_fGUXVVIMpmivTB6M);
				_showerror($lang['MySQL_error'], 'Database System Error: Bad SQL Query');
			}

			if ($halt) {
				exit();
			}
		}

		if ($Config['SQLerrorIsSend'] == '1') {
			_sendmail($Config['SQLerrorSendMail'], $lang['MySQL_error'], $errmsg);
		}
	}
}


?>

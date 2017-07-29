<?php
//dezend by http://www.yunlu99.com/
class AuthLdap
{
	public $server;
	public $dn;
	public $searchUser;
	public $searchPassword;
	public $ldapErrorCode;
	public $ldapErrorText;
	public $connection;
	public $result;
	public $_bind;

	public function AuthLdap($options = array())
	{
		if (0 < count($options)) {
			if (array_key_exists('base_dn', $options)) {
				$this->dn = $options['base_dn'];
			}

			if (array_key_exists('domain_controllers', $options)) {
				$this->server = $options['domain_controllers'];
			}

			if (array_key_exists('ad_username', $options)) {
				$this->searchUser = $options['ad_username'];
			}

			if (array_key_exists('ad_password', $options)) {
				$this->searchPassword = $options['ad_password'];
			}
		}

		if (!$this->connect()) {
			_showerror('System Error', 'Unable to connect to any LDAP server.');
		}

		ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
		ldap_set_option($this->connection, LDAP_OPT_SIZELIMIT, 5000);
		if (($this->searchUser != '') && ($this->searchPassword != '')) {
			$this->_bind = @ldap_bind($this->connection, $this->searchUser, $this->searchPassword);

			if (!$this->_bind) {
				if ($this->_use_ssl) {
					_showerror('System Error', 'FATAL: AD bind failed. Either the LDAPS connection failed or the login credentials are incorrect.');
				}
				else {
					_showerror('System Error', 'FATAL: AD bind failed. Check the login credentials.');
				}
			}
		}

		return true;
	}

	public function connect()
	{
		foreach ($this->server as $_obf_Vwty => $_obf_D9yo3A__) {
			$this->connection = ldap_connect($_obf_D9yo3A__);

			if ($this->connection) {
				ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($this->connection, LDAP_OPT_REFERRALS, false);
				return true;
			}
		}

		$this->ldapErrorCode = -1;
		$this->ldapErrorText = 'Unable to connect to any LDAP server';
		return false;
	}

	public function __destruct()
	{
		ldap_close($this->connection);
	}

	public function authBind($bindDn, $pass)
	{
		if (($bindDn == '') || ($pass == '')) {
			return false;
		}

		$this->_bind = @ldap_bind($this->connection, $bindDn, $pass);

		if (!$this->_bind) {
			$this->ldapErrorCode = ldap_errno($this->connection);
			$this->ldapErrorText = ldap_error($this->connection);
			return false;
		}

		return true;
	}

	public function getUserMail($dn)
	{
		if ($dn == '') {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = iconv('gbk', 'UTF-8', $dn);
		$_obf_PzY5asTZ1uWE3TLpyhqB = '(|(objectclass=user)(objectclass=person)(objectclass=inetOrgPerson)(objectclass=organizationalPerson)(objectClass=posixAccount))';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '';
		$_obf_1KAWkECv = '(&' . $_obf_PzY5asTZ1uWE3TLpyhqB . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('mail');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->connection, $_obf_kVY_);
		return $_obf_PIH1pKEr_jw_[0]['mail'][0];
	}

	public function getUserInfo($dn)
	{
		if ($dn == '') {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = $dn;
		$_obf_PzY5asTZ1uWE3TLpyhqB = '(|(objectclass=user)(objectclass=person)(objectclass=inetOrgPerson)(objectclass=organizationalPerson)(objectClass=posixAccount))';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '';
		$_obf_1KAWkECv = '(&' . $_obf_PzY5asTZ1uWE3TLpyhqB . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('sn', 'givenName', 'mail', 'dn');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->connection, $_obf_kVY_);
		return $_obf_PIH1pKEr_jw_;
	}

	public function checkInOU($dn, $ouDn)
	{
		if (($dn == '') || ($ouDn == '')) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = iconv('gbk', 'UTF-8', $ouDn);
		$_obf_PzY5asTZ1uWE3TLpyhqB = '(|(objectclass=user)(objectclass=person)(objectclass=inetOrgPerson)(objectclass=organizationalPerson)(objectClass=posixAccount))';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '(cn=*)';
		$_obf_1KAWkECv = '(&' . $_obf_PzY5asTZ1uWE3TLpyhqB . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('dn');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->connection, $_obf_kVY_);
		$_obf_uKTapHA_ = array();

		foreach ($_obf_PIH1pKEr_jw_ as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_Sd4jqCFecw__ = strtolower(trim($_obf_U_H_AKjX_Xkr['dn']));

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_uKTapHA_[] = $_obf_Sd4jqCFecw__;
			}
		}

		if (in_array($dn, $_obf_uKTapHA_)) {
			return true;
		}

		return false;
	}

	public function all_user_ou($ouDn, $type = 'dn')
	{
		if ($ouDn == '') {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = iconv('gbk', 'UTF-8', $ouDn);
		$_obf_PzY5asTZ1uWE3TLpyhqB = '(|(objectclass=user)(objectclass=person)(objectclass=inetOrgPerson)(objectclass=organizationalPerson)(objectClass=posixAccount))';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '(cn=*)';
		$_obf_1KAWkECv = '(&' . $_obf_PzY5asTZ1uWE3TLpyhqB . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('dn', 'mail');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->connection, $_obf_kVY_);
		$_obf_uKTapHA_ = array();
		$_obf_aOt7t9Evwgg_ = array();

		foreach ($_obf_PIH1pKEr_jw_ as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_Sd4jqCFecw__ = trim($_obf_U_H_AKjX_Xkr['dn']);
			$_obf_AJrZ9Q__ = trim($_obf_U_H_AKjX_Xkr['mail'][0]);
			if (($_obf_Sd4jqCFecw__ != '') && ($_obf_AJrZ9Q__ != '')) {
				$_obf_aOt7t9Evwgg_[$_obf_Sd4jqCFecw__] = $_obf_AJrZ9Q__;
			}

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_uKTapHA_[] = $_obf_Sd4jqCFecw__;
			}
		}

		if ($type == 'dn') {
			if (0 < count($_obf_uKTapHA_)) {
				sort($_obf_uKTapHA_);
			}

			return $_obf_uKTapHA_;
		}
		else {
			return $_obf_aOt7t9Evwgg_;
		}
	}

	public function all_users($include_desc = false, $search = '*', $sorted = true)
	{
		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = $this->dn;
		$_obf_PzY5asTZ1uWE3TLpyhqB = '(|(objectclass=user)(objectclass=person)(objectclass=inetOrgPerson)(objectclass=organizationalPerson)(objectClass=posixAccount))';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '(|(sn=*' . $search . '*)(givenName=*' . $search . '*)(uid=*' . $search . '*)(cn=*' . $search . '*))';
		$_obf_1KAWkECv = '(&' . $_obf_PzY5asTZ1uWE3TLpyhqB . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('sn', 'givenName', 'uid', 'mail', 'dn');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->connection, $_obf_kVY_);
		$_obf_uKTapHA_ = array();

		foreach ($_obf_PIH1pKEr_jw_ as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_tOo_ = $_obf_U_H_AKjX_Xkr['dn'];
			$_obf_LpAQdkyIZQ__ = explode(',', $_obf_tOo_);
			$_obf_AJrZ9Q__ = $_obf_U_H_AKjX_Xkr['mail'][0];
			$_obf_Sd4jqCFecw__ = $_obf_LpAQdkyIZQ__[0];
			$_obf_FOCH3p8cGwPH = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['givenname'][0];
			$_obf_H_0gdPEplcY_ = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['sn'][0];
			$_obf_UabuFXXRNAQ_ = $_obf_Sd4jqCFecw__ . '#' . $_obf_tOo_ . '#' . $_obf_AJrZ9Q__ . '#' . $_obf_H_0gdPEplcY_ . '#' . $_obf_FOCH3p8cGwPH;

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_uKTapHA_[] = iconv('UTF-8', 'gbk', $_obf_UabuFXXRNAQ_);
			}
		}

		if (0 < count($_obf_uKTapHA_)) {
			sort($_obf_uKTapHA_);
		}

		return $_obf_uKTapHA_;
	}

	public function all_ous($include_desc = false, $search = '*', $sorted = true)
	{
		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = $this->dn;
		$_obf_B7RdHENosz0BcfRxBA__ = '(objectclass=organizationalUnit)';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '(|(cn=*' . $search . '*)(ou=*' . $search . '*)(dn=*' . $search . '*))';
		$_obf_1KAWkECv = '(&' . $_obf_B7RdHENosz0BcfRxBA__ . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('dn');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_CkmRxPwlXM2o = ldap_get_entries($this->connection, $_obf_kVY_);
		$_obf_sI9T = array();

		foreach ($_obf_CkmRxPwlXM2o as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_tOo_ = $_obf_U_H_AKjX_Xkr['dn'];
			$_obf_LpAQdkyIZQ__ = explode(',', $_obf_tOo_);
			$_obf_Sd4jqCFecw__ = $_obf_LpAQdkyIZQ__[0];
			$_obf_UabuFXXRNAQ_ = $_obf_Sd4jqCFecw__ . '#' . $_obf_tOo_;

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_sI9T[] = iconv('UTF-8', 'gbk', $_obf_UabuFXXRNAQ_);
			}
		}

		if (0 < count($_obf_sI9T)) {
			sort($_obf_sI9T);
		}

		return $_obf_sI9T;
	}

	public function all_groups($include_desc = false, $search = '*', $sorted = true)
	{
		if (!$this->_bind) {
			return false;
		}

		$_obf_yXlfUB_Ujg__ = $this->dn;
		$_obf_PoeL98A4LiSYuBguVzPRlQ__ = '(|(objectclass=group)(objectclass=groupofnames)(objectclass=groupofuniquenames)(objectClass=posixGroup))';
		$_obf_T5mSQU1iskrJGnxFXunAZ0k_ = '(|(dc=*' . $search . '*)(o=*' . $search . '*)(ou=*' . $search . '*)(cn=*' . $search . '*)(uid=*' . $search . '*))';
		$_obf_1KAWkECv = '(&' . $_obf_PoeL98A4LiSYuBguVzPRlQ__ . $_obf_T5mSQU1iskrJGnxFXunAZ0k_ . ')';
		$_obf_tjILu7ZH = array('cn', 'dn');
		$_obf_kVY_ = ldap_search($this->connection, $_obf_yXlfUB_Ujg__, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_CkmRxPwlXM2o = ldap_get_entries($this->connection, $_obf_kVY_);
		$_obf_5AyxXElF = array();

		foreach ($_obf_CkmRxPwlXM2o as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_Sd4jqCFecw__ = $_obf_U_H_AKjX_Xkr['cn'][0];
			$_obf_tOo_ = $_obf_U_H_AKjX_Xkr['dn'];
			$_obf_UabuFXXRNAQ_ = $_obf_Sd4jqCFecw__ . '#' . $_obf_tOo_;

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_5AyxXElF[] = iconv('UTF-8', 'gbk', $_obf_UabuFXXRNAQ_);
			}
		}

		if (0 < count($_obf_5AyxXElF)) {
			sort($_obf_5AyxXElF);
		}

		return $_obf_5AyxXElF;
	}
}


?>

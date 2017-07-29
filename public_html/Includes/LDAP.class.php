<?php
//dezend by http://www.yunlu99.com/
class LDAPCLASS
{
	public $_account_suffix = '@mydomain.local';
	public $_base_dn = 'DC=mydomain,DC=local';
	public $_domain_controllers = array('dc01.mydomain.local');
	public $_ad_username;
	public $_ad_password;
	public $_real_primarygroup = true;
	public $_use_ssl = false;
	public $_recursive_groups = true;
	public $_conn;
	public $_bind;

	public function LDAPCLASS($options = array())
	{
		if (0 < count($options)) {
			if (array_key_exists('account_suffix', $options)) {
				$this->_account_suffix = $options['account_suffix'];
			}

			if (array_key_exists('base_dn', $options)) {
				$this->_base_dn = $options['base_dn'];
			}

			if (array_key_exists('domain_controllers', $options)) {
				$this->_domain_controllers = $options['domain_controllers'];
			}

			if (array_key_exists('ad_username', $options)) {
				$this->_ad_username = $options['ad_username'];
			}

			if (array_key_exists('ad_password', $options)) {
				$this->_ad_password = $options['ad_password'];
			}

			if (array_key_exists('real_primarygroup', $options)) {
				$this->_real_primarygroup = $options['real_primarygroup'];
			}

			if (array_key_exists('use_ssl', $options)) {
				$this->_use_ssl = $options['use_ssl'];
			}

			if (array_key_exists('recursive_groups', $options)) {
				$this->_recursive_groups = $options['recursive_groups'];
			}
		}

		$Vq = $this->random_controller();

		if ($this->_use_ssl) {
			$this->_conn = ldap_connect('ldaps://' . $Vq);
		}
		else {
			$this->_conn = ldap_connect($Vq);
		}

		ldap_set_option($this->_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->_conn, LDAP_OPT_REFERRALS, 0);
		ldap_set_option($this->_conn, LDAP_OPT_SIZELIMIT, 5000);
		if (($this->_ad_username != NULL) && ($this->_ad_password != NULL)) {
			$this->_bind = @ldap_bind($this->_conn, $this->_ad_username . $this->_account_suffix, $this->_ad_password);

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

	public function __destruct()
	{
		ldap_close($this->_conn);
	}

	public function authenticate($username, $password, $prevent_rebind = false)
	{
		if (($username == NULL) || ($password == NULL)) {
			return false;
		}

		$this->_bind = @ldap_bind($this->_conn, $username . $this->_account_suffix, $password);

		if (!$this->_bind) {
			return false;
		}

		if (($this->_ad_username != NULL) && !$prevent_rebind) {
			$this->_bind = @ldap_bind($this->_conn, $this->_ad_username . $this->_account_suffix, $this->_ad_password);

			if (!$this->_bind) {
				return false;
			}
		}

		return true;
	}

	public function user_info($username, $fields = NULL)
	{
		if ($username == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = 'samaccountname=' . $username;

		if ($fields == NULL) {
			$fields = array('samaccountname', 'mail', 'memberof', 'department', 'displayname', 'telephonenumber', 'primarygroupid');
		}

		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $fields);
		$_obf_OqDlGQs0ew__ = ldap_get_entries($this->_conn, $_obf_kVY_);

		if ($this->_real_primarygroup) {
			$_obf_OqDlGQs0ew__[0]['memberof'][] = $this->group_cn($_obf_OqDlGQs0ew__[0]['primarygroupid'][0]);
		}
		else {
			$_obf_OqDlGQs0ew__[0]['memberof'][] = 'CN=Domain Users,CN=Users,' . $this->_base_dn;
		}

		$_obf_OqDlGQs0ew__[0]['memberof']['count']++;
		return $_obf_OqDlGQs0ew__;
	}

	public function all_group_group($group_dn)
	{
		if ($group_dn == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(objectCategory=group)(distinguishedName=' . $this->ldap_slashes($group_dn) . '))';
		$_obf_tjILu7ZH = array('memberof');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_Cgn6dLcLLqOL6wcZ = ldap_get_entries($this->_conn, $_obf_kVY_);
		$_obf_kXT0gG5YRzY_ = array();

		foreach ($_obf_Cgn6dLcLLqOL6wcZ as $_obf_Vwty => $_obf_k3jfsTPvk9YMSn0_) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_Cgn6dLcLLqOL6wcZ[$_obf_Vwty]['memberof']['count']; $_obf_7w__++) {
				$_obf_kXT0gG5YRzY_[] = $this->get_cn_name($_obf_Cgn6dLcLLqOL6wcZ[$_obf_Vwty]['memberof'][$_obf_7w__]);
				$this_up_Groups = $this->all_group_group($_obf_Cgn6dLcLLqOL6wcZ[$_obf_Vwty]['memberof'][$_obf_7w__]);
				$_obf_kXT0gG5YRzY_ = array_merge($_obf_kXT0gG5YRzY_, $this_up_Groups);
			}
		}

		$_obf_kXT0gG5YRzY_ = array_unique($_obf_kXT0gG5YRzY_);

		if (0 < count($_obf_kXT0gG5YRzY_)) {
			sort($_obf_kXT0gG5YRzY_);
		}

		return $_obf_kXT0gG5YRzY_;
	}

	public function all_user_group($username)
	{
		if ($username == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$username = iconv('gbk', 'UTF-8', $username);
		$_obf_Cgn6dLcLLqOL6wcZ = $this->user_info($username, array('memberof', 'primarygroupid'));
		$_obf_fT_7MOhy = array();

		foreach ($_obf_Cgn6dLcLLqOL6wcZ as $_obf_Vwty => $_obf_k3jfsTPvk9YMSn0_) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_Cgn6dLcLLqOL6wcZ[$_obf_Vwty]['memberof']['count']; $_obf_7w__++) {
				$_obf_fT_7MOhy[] = $this->get_cn_name($_obf_Cgn6dLcLLqOL6wcZ[$_obf_Vwty]['memberof'][$_obf_7w__]);
				$this_present_groups = $this->all_group_group($_obf_Cgn6dLcLLqOL6wcZ[$_obf_Vwty]['memberof'][$_obf_7w__]);
				$_obf_fT_7MOhy = array_merge($_obf_fT_7MOhy, $this_present_groups);
			}
		}

		$_obf_fT_7MOhy = array_unique($_obf_fT_7MOhy);

		if (0 < count($_obf_fT_7MOhy)) {
			sort($_obf_fT_7MOhy);
		}

		return $_obf_fT_7MOhy;
	}

	public function user_in_group($username, $group)
	{
		if ($username == NULL) {
			return false;
		}

		if ($group == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$this_all_group = $this->all_user_group($username);

		if (in_array($group, $this_all_group)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function all_users($include_desc = false, $search = '*', $sorted = true)
	{
		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(objectClass=user)(samaccounttype=' . ADLDAP_NORMAL_ACCOUNT . ')(objectCategory=person)(|(sn=*' . $search . '*)(givenname=*' . $search . '*)(samaccountname=*' . $search . '*)))';
		$_obf_tjILu7ZH = array('sn', 'givenname', 'samaccountname', 'mail', 'department', 'useraccountcontrol');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->_conn, $_obf_kVY_);
		$_obf_uKTapHA_ = array();

		foreach ($_obf_PIH1pKEr_jw_ as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_FOCH3p8cGwPH = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['givenname'][0];
			$_obf_H_0gdPEplcY_ = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['sn'][0];
			$_obf_Sd4jqCFecw__ = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['samaccountname'][0];
			$_obf_AJrZ9Q__ = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['mail'][0];
			$_obf_WXK7_FHN8udT3Q__ = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['department'][0];
			$_obf_FdbFaKM_ = $_obf_PIH1pKEr_jw_[$_obf_Vwty]['useraccountcontrol'][0];
			$_obf_FdbFaKM_ = decbin($_obf_FdbFaKM_) . '';

			if ($this->mid($_obf_FdbFaKM_, strlen($_obf_FdbFaKM_) - 1, 1) == '1') {
				$_obf_FdbFaKM_ = 1;
			}
			else {
				$_obf_FdbFaKM_ = 0;
			}

			$_obf_U_H_AKjX_Xkr = $_obf_H_0gdPEplcY_ . '#' . $_obf_FOCH3p8cGwPH . '#' . $_obf_Sd4jqCFecw__ . '#' . $_obf_AJrZ9Q__ . '#' . $_obf_WXK7_FHN8udT3Q__ . '#' . $_obf_FdbFaKM_;

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_uKTapHA_[] = iconv('UTF-8', 'gbk', $_obf_U_H_AKjX_Xkr);
			}
		}

		if (0 < count($_obf_uKTapHA_)) {
			sort($_obf_uKTapHA_);
		}

		return $_obf_uKTapHA_;
	}

	public function is_group($name)
	{
		if ($name == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(objectCategory=group)(distinguishedName=' . $this->ldap_slashes($name) . '))';
		$_obf_tjILu7ZH = array('samaccountname');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_xs33Yt_k = ldap_get_entries($this->_conn, $_obf_kVY_);

		if ($_obf_xs33Yt_k['count'] == 0) {
			return false;
		}
		else {
			return true;
		}
	}

	public function is_user($name)
	{
		if ($name == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(objectClass=user)(samaccounttype=' . ADLDAP_NORMAL_ACCOUNT . ')(objectCategory=person)(distinguishedName=' . $this->ldap_slashes($name) . '))';
		$_obf_tjILu7ZH = array('samaccountname');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_xs33Yt_k = ldap_get_entries($this->_conn, $_obf_kVY_);

		if ($_obf_xs33Yt_k['count'] == 0) {
			return false;
		}
		else {
			return true;
		}
	}

	public function get_cn_name($name)
	{
		if ($name == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(distinguishedName=' . $this->ldap_slashes($name) . '))';
		$_obf_tjILu7ZH = array('samaccountname');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_xs33Yt_k = ldap_get_entries($this->_conn, $_obf_kVY_);
		return iconv('UTF-8', 'gbk', $_obf_xs33Yt_k[0]['samaccountname'][0]);
	}

	public function all_pri_group_users($primarygroupid)
	{
		if ($primarygroupid == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(objectClass=user)(samaccounttype=' . ADLDAP_NORMAL_ACCOUNT . ')(objectCategory=person)(primarygroupid=' . $primarygroupid . '))';
		$_obf_tjILu7ZH = array('samaccountname', 'useraccountcontrol');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_PIH1pKEr_jw_ = ldap_get_entries($this->_conn, $_obf_kVY_);
		$_obf_uKTapHA_ = array();

		foreach ($_obf_PIH1pKEr_jw_ as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			if ($_obf_PIH1pKEr_jw_[$_obf_Vwty]['samaccountname'][0] != '') {
				$_obf_uKTapHA_[] = iconv('UTF-8', 'gbk', $_obf_PIH1pKEr_jw_[$_obf_Vwty]['samaccountname'][0]);
			}
		}

		if (0 < count($_obf_uKTapHA_)) {
			sort($_obf_uKTapHA_);
		}

		return $_obf_uKTapHA_;
	}

	public function all_group_users($group, $sorted = true)
	{
		if ($group == NULL) {
			return false;
		}

		if (!$this->_bind) {
			return false;
		}

		$_obf_iZlMjqJhPMfLmg__ = iconv('gbk', 'UTF-8', $group);
		$_obf_1KAWkECv = '(&(objectCategory=group)(samaccountname=' . $this->ldap_slashes($_obf_iZlMjqJhPMfLmg__) . '))';
		$_obf_tjILu7ZH = array('member', 'primarygrouptoken');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_wD1mCmS3Cfvjmw__ = ldap_get_entries($this->_conn, $_obf_kVY_);
		$_obf_uKTapHA_ = array();
		$_obf_x_7mbVWwFcRZiQS1zQ__ = $this->all_pri_group_users($_obf_wD1mCmS3Cfvjmw__[0]['primarygrouptoken'][0]);
		$_obf_uKTapHA_ = array_merge($_obf_uKTapHA_, $_obf_x_7mbVWwFcRZiQS1zQ__);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_wD1mCmS3Cfvjmw__[0]['member']['count']; $_obf_7w__++) {
			if (!$this->is_group($_obf_wD1mCmS3Cfvjmw__[0]['member'][$_obf_7w__])) {
				if ($this->is_user($_obf_wD1mCmS3Cfvjmw__[0]['member'][$_obf_7w__])) {
					$_obf_uKTapHA_[] = $this->get_cn_name($_obf_wD1mCmS3Cfvjmw__[0]['member'][$_obf_7w__]);
				}
			}
			else {
				$_obf_lvuXSIf5HcSLOw__ = $this->get_cn_name($_obf_wD1mCmS3Cfvjmw__[0]['member'][$_obf_7w__]);
				$_obf_cXhUQxBeDg3iiAw_ = $this->all_group_users($_obf_lvuXSIf5HcSLOw__);
				$_obf_uKTapHA_ = array_merge($_obf_uKTapHA_, $_obf_cXhUQxBeDg3iiAw_);
			}
		}

		$_obf_uKTapHA_ = array_unique($_obf_uKTapHA_);

		if (0 < count($_obf_uKTapHA_)) {
			sort($_obf_uKTapHA_);
		}

		return $_obf_uKTapHA_;
	}

	public function all_groups($include_desc = false, $search = '*', $sorted = true)
	{
		if (!$this->_bind) {
			return false;
		}

		$_obf_1KAWkECv = '(&(objectCategory=group)(samaccountname=*' . $search . '*))';
		$_obf_tjILu7ZH = array('samaccountname', 'canonicalname');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_CkmRxPwlXM2o = ldap_get_entries($this->_conn, $_obf_kVY_);
		$_obf_5AyxXElF = array();

		foreach ($_obf_CkmRxPwlXM2o as $_obf_Vwty => $_obf_U_H_AKjX_Xkr) {
			$_obf_Sd4jqCFecw__ = $_obf_CkmRxPwlXM2o[$_obf_Vwty]['samaccountname'][0];
			$_obf_R6MEYOlriKRa = $_obf_CkmRxPwlXM2o[$_obf_Vwty]['canonicalname'][0];
			$_obf_U_H_AKjX_Xkr = $_obf_Sd4jqCFecw__ . '#' . $_obf_R6MEYOlriKRa;

			if ($_obf_Sd4jqCFecw__ != '') {
				$_obf_5AyxXElF[] = iconv('UTF-8', 'gbk', $_obf_U_H_AKjX_Xkr);
			}
		}

		if (0 < count($_obf_5AyxXElF)) {
			sort($_obf_5AyxXElF);
		}

		return $_obf_5AyxXElF;
	}

	public function group_cn($gid)
	{
		if ($gid == NULL) {
			return false;
		}

		$_obf_OQ__ = false;
		$_obf_1KAWkECv = '(&(objectCategory=group))';
		$_obf_tjILu7ZH = array('primarygrouptoken', 'samaccountname', 'distinguishedname');
		$_obf_kVY_ = ldap_search($this->_conn, $this->_base_dn, $_obf_1KAWkECv, $_obf_tjILu7ZH);
		$_obf_OqDlGQs0ew__ = ldap_get_entries($this->_conn, $_obf_kVY_);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_OqDlGQs0ew__['count']; $_obf_7w__++) {
			if ($_obf_OqDlGQs0ew__[$_obf_7w__]['primarygrouptoken'][0] == $gid) {
				$_obf_OQ__ = $_obf_OqDlGQs0ew__[$_obf_7w__]['distinguishedname'][0];
				$_obf_7w__ = $_obf_OqDlGQs0ew__['count'];
			}
		}

		return $_obf_OQ__;
	}

	public function ldap_slashes($str)
	{
		return preg_replace('/([\\x00-\\x1F\\*\\(\\)\\\\])/e', '"\\\\\\".join("",unpack("H2","$1"))', $str);
	}

	public function random_controller()
	{
		mt_srand(doubleval(microtime()) * 100000000);
		return $this->_domain_controllers[array_rand($this->_domain_controllers)];
	}

	public function mid($value, $Depart, $NbChar)
	{
		return substr($value, $Depart - 1, $NbChar);
	}
}

define('ADLDAP_NORMAL_ACCOUNT', 805306368);
define('ADLDAP_WORKSTATION_TRUST', 805306369);
define('ADLDAP_INTERDOMAIN_TRUST', 805306370);
define('ADLDAP_SECURITY_GLOBAL_GROUP', 268435456);
define('ADLDAP_DISTRIBUTION_GROUP', 268435457);
define('ADLDAP_SECURITY_LOCAL_GROUP', 536870912);
define('ADLDAP_DISTRIBUTION_LOCAL_GROUP', 536870913);

?>

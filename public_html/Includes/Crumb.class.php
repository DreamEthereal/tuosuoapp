<?php
//dezend by http://www.yunlu99.com/
class Crumb
{
	const SALT = 'your-secret-salt';

	static public $ttl = 7200;

	static public function challenge($data)
	{
		return hash_hmac('md5', $data, self::SALT);
	}

	static public function issueCrumb($uid, $action = -1)
	{
		$_obf_7w__ = ceil(time() / self::$ttl);
		return substr(self::challenge($_obf_7w__ . $action . $uid), -12, 10);
	}

	static public function verifyCrumb($uid, $crumb, $action = -1)
	{
		$_obf_7w__ = ceil(time() / self::$ttl);
		if ((substr(self::challenge($_obf_7w__ . $action . $uid), -12, 10) == $crumb) || (substr(self::challenge(($_obf_7w__ - 1) . $action . $uid), -12, 10) == $crumb)) {
			return true;
		}

		return false;
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

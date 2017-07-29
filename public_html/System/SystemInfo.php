<?php
//dezend by http://www.yunlu99.com/
function get_php_setting($val)
{
	$_obf_OQ__ = (ini_get($val) == '1' ? 1 : 0);
	return $_obf_OQ__ ? _CHECKUP_ON : _CHECKUP_OFF;
}

function dir_wriable($file)
{
	if (is_dir($file)) {
		$_obf_Fwl1 = $file;

		if ($_obf_YBY_ = @fopen($_obf_Fwl1 . '/test.txt', 'w')) {
			@fclose($_obf_YBY_);
			@unlink($_obf_Fwl1 . '/test.txt');
			$_obf_uqZhtc5bmtrM = 1;
		}
		else {
			$_obf_uqZhtc5bmtrM = 0;
		}
	}
	else if ($_obf_YBY_ = @fopen($file, 'a+')) {
		@fclose($_obf_YBY_);
		$_obf_uqZhtc5bmtrM = 1;
	}
	else {
		$_obf_uqZhtc5bmtrM = 0;
	}

	return $_obf_uqZhtc5bmtrM;
}

function convert($size)
{
	$_obf_S1rUnw__ = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
	return @round($size / pow(1024, $_obf_7w__ = floor(log($size, 1024))), 2) . ' ' . $_obf_S1rUnw__[$_obf_7w__];
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1');
$EnableQCoreClass->setTemplateFile('MainFile', 'SystemInfo.html');
$PHPVersion = phpversion();
$ZendVersion = zend_version();
$MySqlVersion = mysql_get_server_info();

if (isset($_SERVER)) {
	$SystemSoftWare = $_SERVER['SERVER_SOFTWARE'];
}
else {
	$SystemSoftWare = getenv('SERVER_SOFTWARE');
}

$OS_SOFTWARE = getenv('OS');
$theAutoPath = substr($_SERVER['SCRIPT_FILENAME'], 0, -21);
$theConfigPath = str_replace('\\', '/', $Config['absolutenessPath']);
$isWindows = 0;

if ($OS_SOFTWARE != '') {
	$EnableQCoreClass->replace('OS_SOFTWARE', $OS_SOFTWARE);

	if (preg_match('/Windows/i', $OS_SOFTWARE)) {
		$isWindows = strtolower($theAutoPath) == strtolower($theConfigPath);
	}
	else {
		$isWindows = $theAutoPath == $theConfigPath;
	}
}
else {
	$EnableQCoreClass->replace('OS_SOFTWARE', 'Non Windows');
	$isWindows = $theAutoPath == $theConfigPath;
}

$EnableQCoreClass->replace('SERVER_SOFTWARE', $SystemSoftWare);
$EnableQCoreClass->replace('PHPVersion', $PHPVersion);
$EnableQCoreClass->replace('ZendVersion', $ZendVersion);
$EnableQCoreClass->replace('MySqlVersion', $MySqlVersion);
$EnableQCoreClass->replace('version', $Config['version']);
$EnableQCoreClass->replace('SystemIPAddress', $License['MasterAddress'] . ' (' . $License['MinorAddress'] . ' )');
define('_CHECKUP_YES', 'Yes');
define('_CHECKUP_NO', 'No');
define('_CHECKUP_ON', 'On');
define('_CHECKUP_OFF', 'Off');
$EnableQCoreClass->replace('absolutenessPath', $theConfigPath);
$EnableQCoreClass->replace('auto_Path', $theAutoPath);

if ($isWindows) {
	$EnableQCoreClass->replace('path_cssClass', 'checkupSuccess');
	$EnableQCoreClass->replace('path_check_state', _CHECKUP_YES);
}
else {
	$EnableQCoreClass->replace('path_cssClass', 'checkupFailure');
	$EnableQCoreClass->replace('path_check_state', _CHECKUP_NO);
}

$allSettings[] = array('label' => 'PHP version >= 5.2.0', 'state' => version_compare(phpversion(), '5.2.0', '>=') ? _CHECKUP_YES : _CHECKUP_NO);
$allSettings[] = array('label' => '  - GD2 extension support', 'state' => extension_loaded('gd') ? _CHECKUP_YES : _CHECKUP_NO);
$allSettings[] = array('label' => '  - MySQL support', 'state' => function_exists('mysql_connect') ? _CHECKUP_YES : _CHECKUP_NO);
$recommendedSettings = array(
	array('short_open_tag ', 'short_open_tag', _CHECKUP_ON),
	array('y2k_compliance', 'y2k_compliance', _CHECKUP_ON),
	array('display_errors', 'display_errors', _CHECKUP_ON),
	array('register_globals', 'register_globals', _CHECKUP_OFF),
	array('magic_quotes_gpc', 'magic_quotes_gpc', _CHECKUP_ON),
	array('file_uploads', 'file_uploads', _CHECKUP_ON),
	array('allow_url_fopen', 'allow_url_fopen', _CHECKUP_ON),
	array('session.use_cookies', 'session.use_cookies', _CHECKUP_ON),
	array('session.auto_start', 'session.auto_start', _CHECKUP_OFF),
	array('session.use_trans_sid', 'session.use_trans_sid', _CHECKUP_OFF)
	);

foreach ($recommendedSettings as $setting) {
	$phpSettings[] = array('label' => $setting[0], 'setting' => $setting[2], 'actual' => get_php_setting($setting[1]), 'state' => get_php_setting($setting[1]) == $setting[2] ? _CHECKUP_YES : _CHECKUP_NO);
}

$phpSettings[] = array('label' => 'Image Support', 'setting' => _CHECKUP_ON, 'actual' => function_exists('imagecreatetruecolor'), 'state' => function_exists('imagecreatetruecolor') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'JPEG Support', 'setting' => _CHECKUP_ON, 'actual' => function_exists('imagejpeg') && function_exists('imagecreatefromjpeg'), 'state' => function_exists('imagejpeg') && function_exists('imagecreatefromjpeg') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'ICONV Support', 'setting' => _CHECKUP_ON, 'actual' => function_exists('iconv'), 'state' => function_exists('iconv') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'Mbstring Support', 'setting' => _CHECKUP_ON, 'actual' => function_exists('mb_detect_encoding'), 'state' => function_exists('mb_detect_encoding') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'BCMATH Enabled', 'setting' => _CHECKUP_ON, 'actual' => function_exists('bcmod'), 'state' => function_exists('bcmod') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'Zlib Support', 'setting' => _CHECKUP_ON, 'actual' => function_exists('gzcompress'), 'state' => function_exists('gzcompress') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'LDAP Support (connect to Active Directory)', 'setting' => _CHECKUP_ON, 'actual' => function_exists('ldap_connect'), 'state' => function_exists('ldap_connect') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'CURL Support', 'setting' => _CHECKUP_ON, 'actual' => function_exists('curl_init'), 'state' => function_exists('curl_init') ? _CHECKUP_YES : _CHECKUP_NO);
$phpSettings[] = array('label' => 'Apache Header', 'setting' => _CHECKUP_ON, 'actual' => function_exists('apache_request_headers'), 'state' => function_exists('apache_request_headers') ? _CHECKUP_YES : _CHECKUP_NO);
$EnableQCoreClass->set_CycBlock('MainFile', 'SYSCHECK', 'syscheck');
$EnableQCoreClass->replace('syscheck', '');

foreach ($allSettings as $setting) {
	$cssClass = ($setting['state'] == _CHECKUP_NO ? 'checkupFailure' : 'checkupSuccess');
	$EnableQCoreClass->replace('check_title', $setting['label']);
	$EnableQCoreClass->replace('cssClass', $cssClass);
	$EnableQCoreClass->replace('check_state', $setting['state']);
	$EnableQCoreClass->parse('syscheck', 'SYSCHECK', true);
}

$EnableQCoreClass->set_CycBlock('MainFile', 'PHPCHECK', 'phpcheck');
$EnableQCoreClass->replace('phpcheck', '');

foreach ($phpSettings as $setting) {
	$cssClass = ($setting['state'] == _CHECKUP_NO ? 'checkupFailure' : 'checkupSuccess');
	$EnableQCoreClass->replace('php_check_title', $setting['label']);
	$EnableQCoreClass->replace('php_rem_setting', $setting['setting']);
	$EnableQCoreClass->replace('php_cssClass', $cssClass);
	$EnableQCoreClass->replace('php_check_state', $setting['state']);
	$EnableQCoreClass->parse('phpcheck', 'PHPCHECK', true);
}

if (is_writable('../Config/config.php')) {
	$EnableQCoreClass->replace('file_cssClass', 'checkupSuccess');
	$EnableQCoreClass->replace('file_check_state', _CHECKUP_YES);
}
else {
	$EnableQCoreClass->replace('file_cssClass', 'checkupFailure');
	$EnableQCoreClass->replace('file_check_state', _CHECKUP_NO);
}

if (dir_wriable('../PerUserData/')) {
	$EnableQCoreClass->replace('dir1_cssClass', 'checkupSuccess');
	$EnableQCoreClass->replace('dir1_check_state', _CHECKUP_YES);
}
else {
	$EnableQCoreClass->replace('dir1_cssClass', 'checkupFailure');
	$EnableQCoreClass->replace('dir1_check_state', _CHECKUP_NO);
}

if (dir_wriable('../Templates/CN/')) {
	$EnableQCoreClass->replace('dir2_cssClass', 'checkupSuccess');
	$EnableQCoreClass->replace('dir2_check_state', _CHECKUP_YES);
}
else {
	$EnableQCoreClass->replace('dir2_cssClass', 'checkupFailure');
	$EnableQCoreClass->replace('dir2_check_state', _CHECKUP_NO);
}

if (dir_wriable('../License/')) {
	$EnableQCoreClass->replace('dir3_cssClass', 'checkupSuccess');
	$EnableQCoreClass->replace('dir3_check_state', _CHECKUP_YES);
}
else {
	$EnableQCoreClass->replace('dir3_cssClass', 'checkupFailure');
	$EnableQCoreClass->replace('dir3_check_state', _CHECKUP_NO);
}

if (dir_wriable('../' . $Config['cacheDirectory'] . '/')) {
	$EnableQCoreClass->replace('dir4_cssClass', 'checkupSuccess');
	$EnableQCoreClass->replace('dir4_check_state', _CHECKUP_YES);
}
else {
	$EnableQCoreClass->replace('dir4_cssClass', 'checkupFailure');
	$EnableQCoreClass->replace('dir4_check_state', _CHECKUP_NO);
}

ob_start();
phpinfo(INFO_GENERAL);
$string = ob_get_contents();
ob_end_clean();
$pieces = explode('<h2', $string);
$settings = array();

foreach ($pieces as $val) {
	preg_match('/<a name="module_([^<>]*)">/', $val, $sub_key);
	preg_match_all('/<tr[^>]*>' . "\r\n" . '								   <td[^>]*>(.*)<\\/td>' . "\r\n" . '								   <td[^>]*>(.*)<\\/td>/Ux', $val, $sub);
	preg_match_all('/<tr[^>]*>' . "\r\n" . '								   <td[^>]*>(.*)<\\/td>' . "\r\n" . '								   <td[^>]*>(.*)<\\/td>' . "\r\n" . '								   <td[^>]*>(.*)<\\/td>/Ux', $val, $sub_ext);

	foreach ($sub[0] as $key => $val) {
		if (preg_match('/Configuration File \\(php.ini\\) Path /', $val)) {
			$val = preg_replace('/Configuration File \\(php.ini\\) Path /', '', $val);
			$phpini = strip_tags($val);
		}
	}
}

$EnableQCoreClass->replace('phpini_local', $phpini);
$EnableQCoreClass->replace('max_execution_time', ini_get('max_execution_time'));
$EnableQCoreClass->replace('max_input_time', ini_get('max_input_time'));
$EnableQCoreClass->replace('memory_limit', ini_get('memory_limit'));
$EnableQCoreClass->replace('gc_maxlifetime', ini_get('session.gc_maxlifetime'));
$EnableQCoreClass->replace('upload_max_filesize', ini_get('upload_max_filesize'));
$EnableQCoreClass->replace('post_max_size', ini_get('post_max_size'));
$EnableQCoreClass->replace('max_file_uploads', ini_get('max_file_uploads'));

if (version_compare(phpversion(), '5.3.3', '>=')) {
	$EnableQCoreClass->replace('max_input_vars', ini_get('max_input_vars'));
}
else {
	$EnableQCoreClass->replace('max_input_vars', 'php>=5.3.3');
}

$EnableQCoreClass->parse('Main', 'MainFile');
$EnableQCoreClass->output('Main');

?>

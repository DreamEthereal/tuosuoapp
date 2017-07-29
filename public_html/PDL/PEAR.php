<?php
//dezend by http://www.yunlu99.com/
class PEAR
{
	/**
     * Whether to enable internal debug messages.
     *
     * @var     bool
     * @access  private
     */
	public $_debug = false;
	/**
     * Default error mode for this object.
     *
     * @var     int
     * @access  private
     */
	public $_default_error_mode;
	/**
     * Default error options used for this object when error mode
     * is PEAR_ERROR_TRIGGER.
     *
     * @var     int
     * @access  private
     */
	public $_default_error_options;
	/**
     * Default error handler (callback) for this object, if error mode is
     * PEAR_ERROR_CALLBACK.
     *
     * @var     string
     * @access  private
     */
	public $_default_error_handler = '';
	/**
     * Which class to use for error objects.
     *
     * @var     string
     * @access  private
     */
	public $_error_class = 'PEAR_Error';
	/**
     * An array of expected errors.
     *
     * @var     array
     * @access  private
     */
	public $_expected_errors = array();

	public function PEAR($error_class = NULL)
	{
		$_obf_Rd7GU4TWqNDo = strtolower(get_class($this));

		if ($this->_debug) {
			print('PEAR constructor called, class=' . $_obf_Rd7GU4TWqNDo . "\n");
		}

		if ($error_class !== NULL) {
			$this->_error_class = $error_class;
		}

		while ($_obf_Rd7GU4TWqNDo && strcasecmp($_obf_Rd7GU4TWqNDo, 'pear')) {
			$_obf_Vy7Z6ag_zEIQfA__ = '_' . $_obf_Rd7GU4TWqNDo;

			if (method_exists($this, $_obf_Vy7Z6ag_zEIQfA__)) {
				global $_PEAR_destructor_object_list;
				$_PEAR_destructor_object_list[] = &$this;

				if (!isset($GLOBALS['_PEAR_SHUTDOWN_REGISTERED'])) {
					register_shutdown_function('_PEAR_call_destructors');
					$GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
				}

				break;
			}
			else {
				$_obf_Rd7GU4TWqNDo = get_parent_class($_obf_Rd7GU4TWqNDo);
			}
		}
	}

	public function _PEAR()
	{
		if ($this->_debug) {
			printf('PEAR destructor called, class=%s' . "\n" . '', strtolower(get_class($this)));
		}
	}

	public function getStaticProperty($class, $var)
	{
		static $properties;

		if (!isset($properties[$class])) {
			$properties[$class] = array();
		}

		if (!array_key_exists($var, $properties[$class])) {
			$properties[$class][$var] = NULL;
		}

		return $properties[$class][$var];
	}

	public function registerShutdownFunc($func, $args = array())
	{
		if (!isset($GLOBALS['_PEAR_SHUTDOWN_REGISTERED'])) {
			register_shutdown_function('_PEAR_call_destructors');
			$GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
		}

		$GLOBALS['_PEAR_shutdown_funcs'][] = array($func, $args);
	}

	public function isError($data, $code = NULL)
	{
		if (!is_a($data, 'PEAR_Error')) {
			return false;
		}

		if (is_null($code)) {
			return true;
		}
		else if (is_string($code)) {
			return $data->getMessage() == $code;
		}

		return $data->getCode() == $code;
	}

	public function setErrorHandling($mode = NULL, $options = NULL)
	{
		if (isset($this) && is_a($this, 'PEAR')) {
			$setmode = &$this->_default_error_mode;
			$setoptions = &$this->_default_error_options;
		}
		else {
			$setmode = &$GLOBALS['_PEAR_default_error_mode'];
			$setoptions = &$GLOBALS['_PEAR_default_error_options'];
		}

		switch ($mode) {
		case PEAR_ERROR_EXCEPTION:
		case PEAR_ERROR_RETURN:
		case PEAR_ERROR_PRINT:
		case PEAR_ERROR_TRIGGER:
		case PEAR_ERROR_DIE:
		case NULL:
			$setmode = $mode;
			$setoptions = $options;
			break;

		case PEAR_ERROR_CALLBACK:
			$setmode = $mode;

			if (is_callable($options)) {
				$setoptions = $options;
			}
			else {
				trigger_error('invalid error callback', 512);
			}

			break;

		default:
			trigger_error('invalid error mode', 512);
			break;
		}
	}

	public function expectError($code = '*')
	{
		if (is_array($code)) {
			array_push($this->_expected_errors, $code);
		}
		else {
			array_push($this->_expected_errors, array($code));
		}

		return count($this->_expected_errors);
	}

	public function popExpect()
	{
		return array_pop($this->_expected_errors);
	}

	public function _checkDelExpect($error_code)
	{
		$_obf_vgVDbmJKqA__ = false;

		foreach ($this->_expected_errors as $_obf_Vwty => $_obf_wvmTSDu0jgKknik_) {
			if (in_array($error_code, $_obf_wvmTSDu0jgKknik_)) {
				unset($this->_expected_errors[$_obf_Vwty][array_search($error_code, $_obf_wvmTSDu0jgKknik_)]);
				$_obf_vgVDbmJKqA__ = true;
			}

			if (0 == count($this->_expected_errors[$_obf_Vwty])) {
				unset($this->_expected_errors[$_obf_Vwty]);
			}
		}

		return $_obf_vgVDbmJKqA__;
	}

	public function delExpect($error_code)
	{
		$deleted = false;
		if (is_array($error_code) && (0 != count($error_code))) {
			foreach ($error_code as $key => $error) {
				$deleted = ($this->_checkDelExpect($error) ? true : false);
			}

			return $deleted ? true : PEAR::raiseError('The expected error you submitted does not exist');
		}
		else if (!empty($error_code)) {
			if ($this->_checkDelExpect($error_code)) {
				return true;
			}

			return PEAR::raiseError('The expected error you submitted does not exist');
		}

		return PEAR::raiseError('The expected error you submitted is empty');
	}

	public function raiseError($message = NULL, $code = NULL, $mode = NULL, $options = NULL, $userinfo = NULL, $error_class = NULL, $skipmsg = false)
	{
		if (is_object($message)) {
			$code = $message->getCode();
			$userinfo = $message->getUserInfo();
			$error_class = $message->getType();
			$message->error_message_prefix = '';
			$message = $message->getMessage();
		}

		if (isset($this) && isset($this->_expected_errors) && (0 < count($this->_expected_errors)) && count($exp = end($this->_expected_errors))) {
			if (($exp[0] == '*') || (is_int(reset($exp)) && in_array($code, $exp)) || (is_string(reset($exp)) && in_array($message, $exp))) {
				$mode = PEAR_ERROR_RETURN;
			}
		}

		if ($mode === NULL) {
			if (isset($this) && isset($this->_default_error_mode)) {
				$mode = $this->_default_error_mode;
				$options = $this->_default_error_options;
			}
			else if (isset($GLOBALS['_PEAR_default_error_mode'])) {
				$mode = $GLOBALS['_PEAR_default_error_mode'];
				$options = $GLOBALS['_PEAR_default_error_options'];
			}
		}

		if ($error_class !== NULL) {
			$ec = $error_class;
		}
		else {
			if (isset($this) && isset($this->_error_class)) {
				$ec = $this->_error_class;
			}
			else {
				$ec = 'PEAR_Error';
			}
		}

		if (intval(PHP_VERSION) < 5) {
			include 'FixPHP5PEARWarnings.php';
			return $a;
		}

		if ($skipmsg) {
			$a = new $ec($code, $mode, $options, $userinfo);
		}
		else {
			$a = new $ec($message, $code, $mode, $options, $userinfo);
		}

		return $a;
	}

	public function throwError($message = NULL, $code = NULL, $userinfo = NULL)
	{
		if (isset($this) && is_a($this, 'PEAR')) {
			$a = &$this->raiseError($message, $code, NULL, NULL, $userinfo);
			return $a;
		}

		$a = &PEAR::raiseError($message, $code, NULL, NULL, $userinfo);
		return $a;
	}

	public function staticPushErrorHandling($mode, $options = NULL)
	{
		$_obf_ibbFGbE_ = &$GLOBALS['_PEAR_error_handler_stack'];
		$_obf_mGlTfjtG3tM_ = &$GLOBALS['_PEAR_default_error_mode'];
		$_obf_BBX_PdzwmVY_2tg_ = &$GLOBALS['_PEAR_default_error_options'];
		$_obf_ibbFGbE_[] = array($_obf_mGlTfjtG3tM_, $_obf_BBX_PdzwmVY_2tg_);

		switch ($mode) {
		case PEAR_ERROR_EXCEPTION:
		case PEAR_ERROR_RETURN:
		case PEAR_ERROR_PRINT:
		case PEAR_ERROR_TRIGGER:
		case PEAR_ERROR_DIE:
		case NULL:
			$_obf_mGlTfjtG3tM_ = $mode;
			$_obf_BBX_PdzwmVY_2tg_ = $options;
			break;

		case PEAR_ERROR_CALLBACK:
			$_obf_mGlTfjtG3tM_ = $mode;

			if (is_callable($options)) {
				$_obf_BBX_PdzwmVY_2tg_ = $options;
			}
			else {
				trigger_error('invalid error callback', 512);
			}

			break;

		default:
			trigger_error('invalid error mode', 512);
			break;
		}

		$_obf_ibbFGbE_[] = array($mode, $options);
		return true;
	}

	public function staticPopErrorHandling()
	{
		$_obf_ibbFGbE_ = &$GLOBALS['_PEAR_error_handler_stack'];
		$_obf_qBbfORdVTw__ = &$GLOBALS['_PEAR_default_error_mode'];
		$_obf_AQkuGrILjSrmCw__ = &$GLOBALS['_PEAR_default_error_options'];
		array_pop($_obf_ibbFGbE_);
		list($_obf_eLlzdw__, $_obf__KoRwJJudw__) = $_obf_ibbFGbE_[sizeof($_obf_ibbFGbE_) - 1];
		array_pop($_obf_ibbFGbE_);

		switch ($_obf_eLlzdw__) {
		case PEAR_ERROR_EXCEPTION:
		case PEAR_ERROR_RETURN:
		case PEAR_ERROR_PRINT:
		case PEAR_ERROR_TRIGGER:
		case PEAR_ERROR_DIE:
		case NULL:
			$_obf_qBbfORdVTw__ = $_obf_eLlzdw__;
			$_obf_AQkuGrILjSrmCw__ = $_obf__KoRwJJudw__;
			break;

		case PEAR_ERROR_CALLBACK:
			$_obf_qBbfORdVTw__ = $_obf_eLlzdw__;

			if (is_callable($_obf__KoRwJJudw__)) {
				$_obf_AQkuGrILjSrmCw__ = $_obf__KoRwJJudw__;
			}
			else {
				trigger_error('invalid error callback', 512);
			}

			break;

		default:
			trigger_error('invalid error mode', 512);
			break;
		}

		return true;
	}

	public function pushErrorHandling($mode, $options = NULL)
	{
		$stack = &$GLOBALS['_PEAR_error_handler_stack'];
		if (isset($this) && is_a($this, 'PEAR')) {
			$def_mode = &$this->_default_error_mode;
			$def_options = &$this->_default_error_options;
		}
		else {
			$def_mode = &$GLOBALS['_PEAR_default_error_mode'];
			$def_options = &$GLOBALS['_PEAR_default_error_options'];
		}

		$stack[] = array($def_mode, $def_options);
		if (isset($this) && is_a($this, 'PEAR')) {
			$this->setErrorHandling($mode, $options);
		}
		else {
			PEAR::setErrorHandling($mode, $options);
		}

		$stack[] = array($mode, $options);
		return true;
	}

	public function popErrorHandling()
	{
		$stack = &$GLOBALS['_PEAR_error_handler_stack'];
		array_pop($stack);
		list($mode, $options) = $stack[sizeof($stack) - 1];
		array_pop($stack);
		if (isset($this) && is_a($this, 'PEAR')) {
			$this->setErrorHandling($mode, $options);
		}
		else {
			PEAR::setErrorHandling($mode, $options);
		}

		return true;
	}

	public function loadExtension($ext)
	{
		if (extension_loaded($ext)) {
			return true;
		}

		if ((function_exists('dl') === false) || (ini_get('enable_dl') != 1) || (ini_get('safe_mode') == 1)) {
			return false;
		}

		if (OS_WINDOWS) {
			$_obf_PbTSEaR6 = '.dll';
		}
		else if (PHP_OS == 'HP-UX') {
			$_obf_PbTSEaR6 = '.sl';
		}
		else if (PHP_OS == 'AIX') {
			$_obf_PbTSEaR6 = '.a';
		}
		else if (PHP_OS == 'OSX') {
			$_obf_PbTSEaR6 = '.bundle';
		}
		else {
			$_obf_PbTSEaR6 = '.so';
		}

		return @dl('php_' . $ext . $_obf_PbTSEaR6) || @dl($ext . $_obf_PbTSEaR6);
	}
}

class PEAR_Error
{
	public $error_message_prefix = '';
	public $mode = PEAR_ERROR_RETURN;
	public $level = E_USER_NOTICE;
	public $code = -1;
	public $message = '';
	public $userinfo = '';
	public $backtrace;

	public function PEAR_Error($message = 'unknown error', $code = NULL, $mode = NULL, $options = NULL, $userinfo = NULL)
	{
		if ($mode === NULL) {
			$mode = PEAR_ERROR_RETURN;
		}

		$this->message = $message;
		$this->code = $code;
		$this->mode = $mode;
		$this->userinfo = $userinfo;

		if (PEAR_ZE2) {
			$skiptrace = PEAR5::getStaticProperty('PEAR_Error', 'skiptrace');
		}
		else {
			$skiptrace = PEAR::getStaticProperty('PEAR_Error', 'skiptrace');
		}

		if (!$skiptrace) {
			$this->backtrace = debug_backtrace();
			if (isset($this->backtrace[0]) && isset($this->backtrace[0]['object'])) {
				unset($this->backtrace[0]['object']);
			}
		}

		if ($mode & PEAR_ERROR_CALLBACK) {
			$this->level = 1024;
			$this->callback = $options;
		}
		else {
			if ($options === NULL) {
				$options = 1024;
			}

			$this->level = $options;
			$this->callback = NULL;
		}

		if ($this->mode & PEAR_ERROR_PRINT) {
			if (is_null($options) || is_int($options)) {
				$format = '%s';
			}
			else {
				$format = $options;
			}

			printf($format, $this->getMessage());
		}

		if ($this->mode & PEAR_ERROR_TRIGGER) {
			trigger_error($this->getMessage(), $this->level);
		}

		if ($this->mode & PEAR_ERROR_DIE) {
			$msg = $this->getMessage();
			if (is_null($options) || is_int($options)) {
				$format = '%s';

				if (substr($msg, -1) != "\n") {
					$msg .= "\n";
				}
			}
			else {
				$format = $options;
			}

			exit(sprintf($format, $msg));
		}

		if (($this->mode & PEAR_ERROR_CALLBACK) && is_callable($this->callback)) {
			call_user_func($this->callback, $this);
		}

		if ($this->mode & PEAR_ERROR_EXCEPTION) {
			trigger_error('PEAR_ERROR_EXCEPTION is obsolete, use class PEAR_Exception for exceptions', 512);
			eval ('$e = new Exception($this->message, $this->code);throw($e);');
		}
	}

	public function getMode()
	{
		return $this->mode;
	}

	public function getCallback()
	{
		return $this->callback;
	}

	public function getMessage()
	{
		return $this->error_message_prefix . $this->message;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getType()
	{
		return get_class($this);
	}

	public function getUserInfo()
	{
		return $this->userinfo;
	}

	public function getDebugInfo()
	{
		return $this->getUserInfo();
	}

	public function getBacktrace($frame = NULL)
	{
		if (defined('PEAR_IGNORE_BACKTRACE')) {
			return NULL;
		}

		if ($frame === NULL) {
			return $this->backtrace;
		}

		return $this->backtrace[$frame];
	}

	public function addUserInfo($info)
	{
		if (empty($this->userinfo)) {
			$this->userinfo = $info;
		}
		else {
			$this->userinfo .= ' ** ' . $info;
		}
	}

	public function __toString()
	{
		return $this->getMessage();
	}

	public function toString()
	{
		$_obf_Yk4dF8k_ = array();
		$_obf_7wvDhu7G = array(1024 => 'notice', 512 => 'warning', 256 => 'error');

		if ($this->mode & PEAR_ERROR_CALLBACK) {
			if (is_array($this->callback)) {
				$_obf_8Svggs52liY_ = (is_object($this->callback[0]) ? strtolower(get_class($this->callback[0])) : $this->callback[0]) . '::' . $this->callback[1];
			}
			else {
				$_obf_8Svggs52liY_ = $this->callback;
			}

			return sprintf('[%s: message="%s" code=%d mode=callback ' . 'callback=%s prefix="%s" info="%s"]', strtolower(get_class($this)), $this->message, $this->code, $_obf_8Svggs52liY_, $this->error_message_prefix, $this->userinfo);
		}

		if ($this->mode & PEAR_ERROR_PRINT) {
			$_obf_Yk4dF8k_[] = 'print';
		}

		if ($this->mode & PEAR_ERROR_TRIGGER) {
			$_obf_Yk4dF8k_[] = 'trigger';
		}

		if ($this->mode & PEAR_ERROR_DIE) {
			$_obf_Yk4dF8k_[] = 'die';
		}

		if ($this->mode & PEAR_ERROR_RETURN) {
			$_obf_Yk4dF8k_[] = 'return';
		}

		return sprintf('[%s: message="%s" code=%d mode=%s level=%s ' . 'prefix="%s" info="%s"]', strtolower(get_class($this)), $this->message, $this->code, implode('|', $_obf_Yk4dF8k_), $_obf_7wvDhu7G[$this->level], $this->error_message_prefix, $this->userinfo);
	}
}

function _obf_eiN1PyQ0bm4WCjEOZXQ7ZnAwZDEkGA__()
{
	global $_PEAR_destructor_object_list;
	if (is_array($_PEAR_destructor_object_list) && sizeof($_PEAR_destructor_object_list)) {
		reset($_PEAR_destructor_object_list);

		if (PEAR_ZE2) {
			$_obf_H0YSlHxERyOCgB6_k7iVYQ9Q = PEAR5::getStaticProperty('PEAR', 'destructlifo');
		}
		else {
			$_obf_H0YSlHxERyOCgB6_k7iVYQ9Q = PEAR::getStaticProperty('PEAR', 'destructlifo');
		}

		if ($_obf_H0YSlHxERyOCgB6_k7iVYQ9Q) {
			$_PEAR_destructor_object_list = array_reverse($_PEAR_destructor_object_list);
		}

		while (list($_obf_5w__, $_obf_E4WT4LyG) = each($_PEAR_destructor_object_list)) {
			$_obf_Rd7GU4TWqNDo = get_class($_obf_E4WT4LyG);

			while ($_obf_Rd7GU4TWqNDo) {
				$_obf_Vy7Z6ag_zEIQfA__ = '_' . $_obf_Rd7GU4TWqNDo;

				if (method_exists($_obf_E4WT4LyG, $_obf_Vy7Z6ag_zEIQfA__)) {
					$_obf_E4WT4LyG->$_obf_Vy7Z6ag_zEIQfA__();
					break;
				}
				else {
					$_obf_Rd7GU4TWqNDo = get_parent_class($_obf_Rd7GU4TWqNDo);
				}
			}
		}

		$_PEAR_destructor_object_list = array();
	}

	if (isset($GLOBALS['_PEAR_shutdown_funcs']) && is_array($GLOBALS['_PEAR_shutdown_funcs']) && !empty($GLOBALS['_PEAR_shutdown_funcs'])) {
		foreach ($GLOBALS['_PEAR_shutdown_funcs'] as $_obf_VgKtFeg_) {
			call_user_func_array($_obf_VgKtFeg_[0], $_obf_VgKtFeg_[1]);
		}
	}
}

define('PEAR_ERROR_RETURN', 1);
define('PEAR_ERROR_PRINT', 2);
define('PEAR_ERROR_TRIGGER', 4);
define('PEAR_ERROR_DIE', 8);
define('PEAR_ERROR_CALLBACK', 16);
define('PEAR_ERROR_EXCEPTION', 32);
define('PEAR_ZE2', function_exists('version_compare') && version_compare(zend_version(), '2-dev', 'ge'));

if (substr(PHP_OS, 0, 3) == 'WIN') {
	define('OS_WINDOWS', true);
	define('OS_UNIX', false);
	define('PEAR_OS', 'Windows');
}
else {
	define('OS_WINDOWS', false);
	define('OS_UNIX', true);
	define('PEAR_OS', 'Unix');
}

$GLOBALS['_PEAR_default_error_mode'] = PEAR_ERROR_RETURN;
$GLOBALS['_PEAR_default_error_options'] = 1024;
$GLOBALS['_PEAR_destructor_object_list'] = array();
$GLOBALS['_PEAR_shutdown_funcs'] = array();
$GLOBALS['_PEAR_error_handler_stack'] = array();
@ini_set('track_errors', true);

if (PEAR_ZE2) {
	include_once 'PEAR5.php';
}

?>

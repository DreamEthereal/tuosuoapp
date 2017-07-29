<?php
//dezend by http://www.yunlu99.com/
class EnableQCoreClass
{
	/**
	 * Serialization helper, the name of this class.
	 * 
	 * @var string 
	 * @access public 
	 */
	public $classname = 'EnableQCoreClass';
	/**
	 * Determines how much debugging output EnableQCoreClass will produce.
	 * This is a bitwise mask of available debug levels:
	 * 0 = no debugging
	 * 1 = debug variable assignments
	 * 2 = debug calls to get variable
	 * 4 = debug internals (outputs all function calls with parameters).
	 * 
	 * Note: setting $this->debug = true will enable debugging of variable
	 * assignments only which is the same behaviour as versions up to release 7.2d.
	 * 
	 * @var int 
	 * @access public 
	 */
	public $debug = false;
	/**
	 * The base directory from which EnableQCoreClass files are loaded.
	 * 
	 * @var string 
	 * @access private 
	 * @see set_dirpath
	 */
	public $root = '.';
	/**
	 * A hash of strings forming a translation table which translates variable names
	 * into names of files containing the variable content.
	 * $file[varname] = "filename";
	 * 
	 * @var array 
	 * @access private 
	 * @see setTemplateFile
	 */
	public $file = array();
	/**
	 * A hash of strings forming a translation table which translates variable names
	 * into regular expressions for themselves.
	 * $varkeys[varname] = "/varname/"
	 * 
	 * @var array 
	 * @access private 
	 * @see replace
	 */
	public $varkeys = array();
	/**
	 * A hash of strings forming a translation table which translates variable names
	 * into values for their respective varkeys.
	 * $varvals[varname] = "value"
	 * 
	 * @var array 
	 * @access private 
	 * @see replace
	 */
	public $varvals = array();
	/**
	 * Determines how to output variable tags with no assigned value in EnableQCoreClasss.
	 * 
	 * @var string 
	 * @access private 
	 * @see set_unknowns
	 */
	public $unknowns = 'remove';
	/**
	 * Determines how EnableQCoreClass handles error conditions.
	 * "yes"      = the error is reported, then execution is halted
	 * "report"   = the error is reported, then execution continues by returning "false"
	 * "no"       = errors are silently ignored, and execution resumes reporting "false"
	 * 
	 * @var string 
	 * @access public 
	 * @see halt
	 */
	public $halt_on_error = 'yes';
	/**
	 * The last error message is retained in this variable.
	 * 
	 * @var string 
	 * @access public 
	 * @see halt
	 */
	public $last_error = '';

	public function EnableQCoreClass($root = '.', $unknowns = 'remove')
	{
		if ($this->debug & 4) {
			echo '<p><b>EnableQCoreClass:</b> root = ' . $root . ', unknowns = ' . $unknowns . '</p>' . "\n" . '';
		}

		$this->set_dirpath($root);
		$this->set_unknowns($unknowns);
	}

	public function set_dirpath($root)
	{
		if ($this->debug & 4) {
			echo '<p><b>set_dirpath:</b> root = ' . $root . '</p>' . "\n" . '';
		}

		if (!is_dir($root)) {
			$this->halt('set_dirpath: ' . $root . ' is not a directory.');
			return false;
		}

		$this->root = $root;
		return true;
	}

	public function set_unknowns($unknowns = 'remove')
	{
		if ($this->debug & 4) {
			echo '<p><b>unknowns:</b> unknowns = ' . $unknowns . '</p>' . "\n" . '';
		}

		$this->unknowns = $unknowns;
	}

	public function setTemplateFile($varname, $filename = '')
	{
		if (!is_array($varname)) {
			if ($this->debug & 4) {
				echo '<p><b>setTemplateFile:</b> (with scalar) varname = ' . $varname . ', filename = ' . $filename . '</p>' . "\n" . '';
			}

			if ($filename == '') {
				_shownotes('System Error', 'setTemplateFile: For varname ' . $varname . ' filename is empty.', 'File I/O Error');
				return false;
			}

			$this->file[$varname] = $this->filename($filename);
		}
		else {
			reset($varname);

			while (list($_obf_6A__, $_obf_6Q__) = each($varname)) {
				if ($this->debug & 4) {
					echo '<p><b>setTemplateFile:</b> (with array) varname = ' . $_obf_6A__ . ', filename = ' . $_obf_6Q__ . '</p>' . "\n" . '';
				}

				if ($_obf_6Q__ == '') {
					_shownotes('System Error', 'setTemplateFile: For varname ' . $_obf_6A__ . ' filename is empty.', 'File I/O Error');
					return false;
				}

				$this->file[$_obf_6A__] = $this->filename($_obf_6Q__);
			}
		}

		return true;
	}

	public function set_CycBlock($parent, $varname, $name = '')
	{
		if ($this->debug & 4) {
			echo '<p><b>set_CycBlock:</b> parent = ' . $parent . ', varname = ' . $varname . ', name = ' . $name . '</p>' . "\n" . '';
		}

		if (!$this->loadfile($parent)) {
			$this->halt('set_CycBlock: unable to load ' . $parent . '.');
			return false;
		}
		if ($name == '') {
			$name = $varname;
		}

		$_obf_R2_b = $this->get_var($parent);


		$_obf_QaWJ = '/[ 	]*<!--\\s+BEGIN ' . $varname . '\\s+-->\\s*?' . "\n" . '?(\\s*.*?' . "\n" . '?)\\s*<!--\\s+END ' . $varname . '\\s+-->\\s*?' . "\n" . '?/sm';
		preg_match_all($_obf_QaWJ, $_obf_R2_b, $_obf_Ag__);
		$_obf_R2_b = preg_replace($_obf_QaWJ, '{' . $name . '}', $_obf_R2_b);
		$this->replace($varname, $_obf_Ag__[1][0]);
		$this->replace($parent, $_obf_R2_b);
		return true;
	}

	public function replace($varname, $value = '', $append = false)
	{
		if (!is_array($varname)) {
			if (!empty($varname)) {
				if ($this->debug & 1) {
					printf('<b>replace:</b> (with scalar) <b>%s</b> = \'%s\'<br>' . "\n" . '', $varname, htmlentities($value));
				}

				$this->varkeys[$varname] = '/' . $this->varname($varname) . '/';
				// $this->dump($this->varkeys);
				if ($append && isset($this->varvals[$varname])) {
					$this->varvals[$varname] .= $value;
				}
				else {
					$this->varvals[$varname] = $value;
				}
				// $this->dump($this->varvals);
				// echo "<hr>";

			}
		}
		else {
			reset($varname);

			while (list($k, $v) = each($varname)) {
				if (!empty($k)) {
					if ($this->debug & 1) {
						printf('<b>replace:</b> (with array) <b>%s</b> = \'%s\'<br>' . "\n" . '', $k, htmlentities($v));
					}

					$this->varkeys[$k] = '/' . $this->varname($k) . '/';
					if ($append && isset($this->varvals[$k])) {
						$this->varvals[$k] .= $v;
					}
					else {
						$this->varvals[$k] = $v;
					}
				}
			}
		}
	}

	public function clear_var($varname)
	{
		if (!is_array($varname)) {
			if (!empty($varname)) {
				if ($this->debug & 1) {
					printf('<b>clear_var:</b> (with scalar) <b>%s</b><br>' . "\n" . '', $varname);
				}

				$this->replace($varname, '');
			}
		}
		else {
			reset($varname);

			while (list($k, $v) = each($varname)) {
				if (!empty($v)) {
					if ($this->debug & 1) {
						printf('<b>clear_var:</b> (with array) <b>%s</b><br>' . "\n" . '', $v);
					}

					$this->replace($v, '');
				}
			}
		}
	}

	public function unreplace($varname)
	{
		if (!is_array($varname)) {
			if (!empty($varname)) {
				if ($this->debug & 1) {
					printf('<b>unreplace:</b> (with scalar) <b>%s</b><br>' . "\n" . '', $varname);
				}

				unset($this->varkeys[$varname]);
				unset($this->varvals[$varname]);
			}
		}
		else {
			reset($varname);

			while (list($k, $v) = each($varname)) {
				if (!empty($v)) {
					if ($this->debug & 1) {
						printf('<b>unreplace:</b> (with array) <b>%s</b><br>' . "\n" . '', $v);
					}

					unset($this->varkeys[$v]);
					unset($this->varvals[$v]);
				}
			}
		}
	}

	public function subst($varname)
	{
		$_obf_yrHm5lzx1_FOUXeghtM_ = array();

		if ($this->debug & 4) {
			echo '<p><b>subst:</b> varname = ' . $varname . '</p>' . "\n" . '';
		}

		if (!$this->loadfile($varname)) {
			$this->halt('subst: unable to load ' . $varname . '.');
			return false;
		}

		reset($this->varvals);

		while (list($_obf_5w__, $_obf_6A__) = each($this->varvals)) {
			$_obf_yrHm5lzx1_FOUXeghtM_[$_obf_5w__] = preg_replace(array('/\\\\/', '/\\$/'), array('\\\\\\\\', '\\\\$'), $_obf_6A__);
		}

		$_obf_R2_b = $this->get_var($varname);
		$_obf_R2_b = preg_replace($this->varkeys, $_obf_yrHm5lzx1_FOUXeghtM_, $_obf_R2_b);
		return $_obf_R2_b;
	}

	public function psubst($varname)
	{
		if ($this->debug & 4) {
			echo '<p><b>psubst:</b> varname = ' . $varname . '</p>' . "\n" . '';
		}

		print($this->subst($varname));
		return false;
	}

	public function parse($target, $varname, $append = false)
	{
		if (!is_array($varname)) {
			if ($this->debug & 4) {
				echo '<p><b>parse:</b> (with scalar) target = ' . $target . ', varname = ' . $varname . ', append = ' . $append . '</p>' . "\n" . '';
			}

			$_obf_R2_b = $this->subst($varname);

			if ($append) {
				$this->replace($target, $this->get_var($target) . $_obf_R2_b);
			}
			else {
				$this->replace($target, $_obf_R2_b);
			}
		}
		else {
			reset($varname);

			while (list($_obf_7w__, $_obf_6A__) = each($varname)) {
				if ($this->debug & 4) {
					echo '<p><b>parse:</b> (with array) target = ' . $target . ', i = ' . $_obf_7w__ . ', varname = ' . $_obf_6A__ . ', append = ' . $append . '</p>' . "\n" . '';
				}

				$_obf_R2_b = $this->subst($_obf_6A__);

				if ($append) {
					$this->replace($target, $this->get_var($target) . $_obf_R2_b);
				}
				else {
					$this->replace($target, $_obf_R2_b);
				}
			}
		}

		if ($this->debug & 4) {
			echo '<p><b>parse:</b> completed</p>' . "\n" . '';
		}

		return $_obf_R2_b;
	}

	public function pparse($target, $varname, $append = false)
	{
		if ($this->debug & 4) {
			echo '<p><b>pparse:</b> passing parameters to parse...</p>' . "\n" . '';
		}

		print($this->finish($this->parse($target, $varname, $append)));
		return false;
	}

	public function get_vars()
	{
		if ($this->debug & 4) {
			echo '<p><b>get_vars:</b> constructing array of vars...</p>' . "\n" . '';
		}

		reset($this->varkeys);

		while (list($_obf_5w__, $_obf_6A__) = each($this->varkeys)) {
			$_obf_xs33Yt_k[$_obf_5w__] = $this->get_var($_obf_5w__);
		}

		return $_obf_xs33Yt_k;
	}

	public function get_var($varname)
	{
		if (!is_array($varname)) {
			if (isset($this->varvals[$varname])) {
				$_obf_R2_b = $this->varvals[$varname];
			}
			else {
				$_obf_R2_b = '';
			}

			if ($this->debug & 2) {
				printf('<b>get_var</b> (with scalar) <b>%s</b> = \'%s\'<br>' . "\n" . '', $varname, htmlentities($_obf_R2_b));
			}

			return $_obf_R2_b;
		}
		else {
			reset($varname);

			while (list($_obf_5w__, $_obf_6A__) = each($varname)) {
				if (isset($this->varvals[$_obf_6A__])) {
					$_obf_R2_b = $this->varvals[$_obf_6A__];
				}
				else {
					$_obf_R2_b = '';
				}

				if ($this->debug & 2) {
					printf('<b>get_var:</b> (with array) <b>%s</b> = \'%s\'<br>' . "\n" . '', $_obf_6A__, htmlentities($_obf_R2_b));
				}

				$_obf_xs33Yt_k[$_obf_6A__] = $_obf_R2_b;
			}

			return $_obf_xs33Yt_k;
		}
	}

	public function get_undefined($varname)
	{
		if ($this->debug & 4) {
			echo '<p><b>get_undefined:</b> varname = ' . $varname . '</p>' . "\n" . '';
		}

		if (!$this->loadfile($varname)) {
			$this->halt('get_undefined: unable to load ' . $varname . '.');
			return false;
		}

		preg_match_all('/{([^ 	' . "\r\n" . '}]+)}/', $this->get_var($varname), $_obf_Ag__);
		$_obf_Ag__ = $_obf_Ag__[1];

		if (!is_array($_obf_Ag__)) {
			return false;
		}

		reset($_obf_Ag__);

		while (list($_obf_5w__, $_obf_6A__) = each($_obf_Ag__)) {
			if (!isset($this->varkeys[$_obf_6A__])) {
				if ($this->debug & 4) {
					echo '<p><b>get_undefined:</b> undefined: ' . $_obf_6A__ . '</p>' . "\n" . '';
				}

				$_obf_xs33Yt_k[$_obf_6A__] = $_obf_6A__;
			}
		}

		if (count($_obf_xs33Yt_k)) {
			return $_obf_xs33Yt_k;
		}
		else {
			return false;
		}
	}

	public function finish($str)
	{
		switch ($this->unknowns) {
		case 'keep':
			break;

		case 'remove':
			$str = preg_replace('/{[^ \\t\\r\\n}]+}/', '', $str);
			break;

		case 'comment':
			$str = preg_replace('/{([^ \\t\\r\\n}]+)}/', '<!-- EnableQCoreClass variable \\1 undefined -->', $str);
			break;
		}

		return $str;
	}

	public function obStart()
	{
		global $Config;
		global $do_gzip_compress;
		$do_gzip_compress = false;

		if ($Config['gzCompress'] = true) {
			$_obf_ACc6Y1zd = phpversion();
			$_obf_tRA_IPlo_YGM = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $_obf_s_Viq9M0PS_2YCShfjAt);
			if (('4.0.4pl1' <= $_obf_ACc6Y1zd) && (strstr($_obf_tRA_IPlo_YGM, 'compatible') || strstr($_obf_tRA_IPlo_YGM, 'Gecko'))) {
				if (extension_loaded('zlib')) {
					ob_start('ob_gzhandler');
				}
			}
			else if ('4.0' < $_obf_ACc6Y1zd) {
				if (strstr($_obf_pcGkdOh3b7taBWlCg19QLA__['HTTP_ACCEPT_ENCODING'], 'gzip')) {
					if (extension_loaded('zlib')) {
						$do_gzip_compress = true;
						ob_start();
						ob_implicit_flush(0);
						header('Content-Encoding: gzip');
					}
				}
			}
		}
	}

	public function ObOutput()
	{
		global $do_gzip_compress;

		if ($do_gzip_compress) {
			$_obf_5bF9IGQh5bu2yqEKcw__ = ob_get_contents();
			ob_end_clean();
			$_obf_McJ8a6WeM1LQ = strlen($_obf_5bF9IGQh5bu2yqEKcw__);
			$_obf_9dEv3yBrAA8_ = crc32($_obf_5bF9IGQh5bu2yqEKcw__);
			$_obf_5bF9IGQh5bu2yqEKcw__ = gzcompress($_obf_5bF9IGQh5bu2yqEKcw__, 9);
			$_obf_5bF9IGQh5bu2yqEKcw__ = substr($_obf_5bF9IGQh5bu2yqEKcw__, 0, strlen($_obf_5bF9IGQh5bu2yqEKcw__) - 4);
			echo '�' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '';
			echo $_obf_5bF9IGQh5bu2yqEKcw__;
			echo pack('V', $_obf_9dEv3yBrAA8_);
			echo pack('V', $_obf_McJ8a6WeM1LQ);
		}
	}

	public function output($varname, $isDebug = false)
	{
		global $TIMER;
		global $DB;
		global $Config;
  
		if ($isDebug == '') {
			$isDebug = $Config['is_debug'];
		}

		print($this->finish($this->get_var($varname)));

		if ($isDebug == true) {
			@${�n�&�}->stop();
		}

		exit();
	}

	public function get($varname)
	{
		return $this->finish($this->get_var($varname));
	}

	public function filename($filename)
	{
		if ($this->debug & 4) {
			echo '<p><b>filename:</b> filename = ' . $filename . '</p>' . "\n" . '';
		}

		if (substr($filename, 0, 1) != '/') {
			$filename = $this->root . '/' . $filename;
		}

		if (!file_exists($filename)) {
			_showerror('System Error', 'Filename: file ' . $filename . ' does not exist.', 'File I/O Error');
		}

		return $filename;
	}

	public function varname($varname)
	{
		return preg_quote('{' . $varname . '}');
	}

	public function loadfile($varname)
	{
		if ($this->debug & 4) {
			echo '<p><b>loadfile:</b> varname = ' . $varname . '</p>' . "\n" . '';
		}

		if (!isset($this->file[$varname])) {
			if ($this->debug & 4) {
				echo '<p><b>loadfile:</b> varname ' . $varname . ' does not reference a file</p>' . "\n" . '';
			}

			return true;
		}

		if (isset($this->varvals[$varname])) {
			if ($this->debug & 4) {
				echo '<p><b>loadfile:</b> varname ' . $varname . ' is already loaded</p>' . "\n" . '';
			}

			return true;
		}

		// var_dump($varname);
		// echo "<hr>";
		// var_dump($this->varvals);
		// echo "<hr>";
		// var_dump($this->file);die;

		$filename = $this->file[$varname];
		$str = file_get_contents($filename);


		if (empty($str)) {
			$this->halt('loadfile: While loading ' . $varname . ', ' . $filename . ' does not exist or is empty.');
			return false;
		}

		if ($this->debug & 4) {
			printf('<b>loadfile:</b> loaded ' . $filename . ' into ' . $varname . '<br>' . "\n" . '');
		}

		$this->replace($varname, $str);
		// var_dump($varname);
		// echo "<hr>";

		// var_dump($this->varkeys);
		// echo "<hr>";
		// var_dump($this->varvals);
		// echo "<hr>";
		// var_dump($this->file);die;


		return true;
	}

	public function savetofile($dir, $varname)
	{
		$_obf_6RYLWQ__ = $this->finish($this->get_var($varname));
		$_obf_YBY_ = fopen($dir, 'w+');
		fwrite($_obf_YBY_, $_obf_6RYLWQ__);
	}

	public function renew()
	{
		$this->varkeys = array();
		$this->varvals = array();
		$this->file = array();
	}

	public function halt($msg)
	{
		$this->last_error = $msg;

		if ($this->halt_on_error != 'no') {
			$this->haltmsg($msg);
		}

		if ($this->halt_on_error == 'yes') {
			exit('<b>ERROR</b>');
		}

		return false;
	}

	public function haltmsg($msg)
	{
		printf('<b><font color=red>EnableQCoreClass Error:</font></b> %s<br>' . "\n" . '', $msg);
	}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output; 
}



}


?>

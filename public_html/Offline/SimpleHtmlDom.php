<?php
//dezend by http://www.yunlu99.com/
class simple_html_dom_node
{
	public $nodetype = HDOM_TYPE_TEXT;
	public $tag = 'text';
	public $attr = array();
	public $children = array();
	public $nodes = array();
	public $parent;
	public $_ = array();
	public $tag_start = 0;
	private $dom;

	public function __construct($dom)
	{
		$this->dom = $dom;
		$dom->nodes[] = $this;
	}

	public function __destruct()
	{
		$this->clear();
	}

	public function __toString()
	{
		return $this->outertext();
	}

	public function clear()
	{
		$this->dom = NULL;
		$this->nodes = NULL;
		$this->parent = NULL;
		$this->children = NULL;
	}

	public function dump($show_attr = true, $deep = 0)
	{
		$_obf_f4jf7w__ = str_repeat('    ', $deep);
		echo $_obf_f4jf7w__ . $this->tag;
		if ($show_attr && (0 < count($this->attr))) {
			echo '(';

			foreach ($this->attr as $_obf_5w__ => $_obf_6A__) {
				echo '[' . $_obf_5w__ . ']=>"' . $this->$_obf_5w__ . '", ';
			}

			echo ')';
		}

		echo "\n";

		foreach ($this->nodes as $_obf_KQ__) {
			$_obf_KQ__->dump($show_attr, $deep + 1);
		}
	}

	public function dump_node()
	{
		echo $this->tag;

		if (0 < count($this->attr)) {
			echo '(';

			foreach ($this->attr as $_obf_5w__ => $_obf_6A__) {
				echo '[' . $_obf_5w__ . ']=>"' . $this->$_obf_5w__ . '", ';
			}

			echo ')';
		}

		if (0 < count($this->attr)) {
			echo ' $_ (';

			foreach ($this->_ as $_obf_5w__ => $_obf_6A__) {
				if (is_array($_obf_6A__)) {
					echo '[' . $_obf_5w__ . ']=>(';

					foreach ($_obf_6A__ as $_obf_ClA_ => $_obf_bRQ_) {
						echo '[' . $_obf_ClA_ . ']=>"' . $_obf_bRQ_ . '", ';
					}

					echo ')';
				}
				else {
					echo '[' . $_obf_5w__ . ']=>"' . $_obf_6A__ . '", ';
				}
			}

			echo ')';
		}

		if (isset($this->text)) {
			echo ' text: (' . $this->text . ')';
		}

		echo ' children: ' . count($this->children);
		echo ' nodes: ' . count($this->nodes);
		echo ' tag_start: ' . $this->tag_start;
		echo "\n";
	}

	public function parent()
	{
		return $this->parent;
	}

	public function children($idx = -1)
	{
		if ($idx === -1) {
			return $this->children;
		}

		if (isset($this->children[$idx])) {
			return $this->children[$idx];
		}

		return NULL;
	}

	public function first_child()
	{
		if (0 < count($this->children)) {
			return $this->children[0];
		}

		return NULL;
	}

	public function last_child()
	{
		if (0 < ($_obf_gftfagw_ = count($this->children))) {
			return $this->children[$_obf_gftfagw_ - 1];
		}

		return NULL;
	}

	public function next_sibling()
	{
		if ($this->parent === NULL) {
			return NULL;
		}

		$_obf_Fv_U = 0;
		$_obf_gftfagw_ = count($this->parent->children);

		while (($_obf_Fv_U < $_obf_gftfagw_) && ($this !== $this->parent->children[$_obf_Fv_U])) {
			++$_obf_Fv_U;
		}

		if ($_obf_gftfagw_ <= ++$_obf_Fv_U) {
			return NULL;
		}

		return $this->parent->children[$_obf_Fv_U];
	}

	public function prev_sibling()
	{
		if ($this->parent === NULL) {
			return NULL;
		}

		$_obf_Fv_U = 0;
		$_obf_gftfagw_ = count($this->parent->children);

		while (($_obf_Fv_U < $_obf_gftfagw_) && ($this !== $this->parent->children[$_obf_Fv_U])) {
			++$_obf_Fv_U;
		}

		if (--$_obf_Fv_U < 0) {
			return NULL;
		}

		return $this->parent->children[$_obf_Fv_U];
	}

	public function find_ancestor_tag($tag)
	{
		global $debugObject;

		if (is_object($debugObject)) {
			$debugObject->debugLogEntry(1);
		}

		$_obf_uMoITVKGMi4H = $this;

		while (!is_null($_obf_uMoITVKGMi4H)) {
			if (is_object($debugObject)) {
				$debugObject->debugLog(2, 'Current tag is: ' . $_obf_uMoITVKGMi4H->tag);
			}

			if ($_obf_uMoITVKGMi4H->tag == $tag) {
				break;
			}

			$_obf_uMoITVKGMi4H = $_obf_uMoITVKGMi4H->parent;
		}

		return $_obf_uMoITVKGMi4H;
	}

	public function innertext()
	{
		if (isset($this->_[HDOM_INFO_INNER])) {
			return $this->_[HDOM_INFO_INNER];
		}

		if (isset($this->_[HDOM_INFO_TEXT])) {
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		}

		$_obf_Xtyr = '';

		foreach ($this->nodes as $_obf_FQ__) {
			$_obf_Xtyr .= $_obf_FQ__->outertext();
		}

		return $_obf_Xtyr;
	}

	public function outertext()
	{
		global $debugObject;

		if (is_object($debugObject)) {
			$_obf_aNcpmA__ = '';

			if ($this->tag == 'text') {
				if (!empty($this->text)) {
					$_obf_aNcpmA__ = ' with text: ' . $this->text;
				}
			}

			$debugObject->debugLog(1, 'Innertext of tag: ' . $this->tag . $_obf_aNcpmA__);
		}

		if ($this->tag === 'root') {
			return $this->innertext();
		}

		if ($this->dom && ($this->dom->callback !== NULL)) {
			call_user_func_array($this->dom->callback, array($this));
		}

		if (isset($this->_[HDOM_INFO_OUTER])) {
			return $this->_[HDOM_INFO_OUTER];
		}

		if (isset($this->_[HDOM_INFO_TEXT])) {
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		}

		if ($this->dom && $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]) {
			$_obf_Xtyr = $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]->makeup();
		}
		else {
			$_obf_Xtyr = '';
		}

		if (isset($this->_[HDOM_INFO_INNER])) {
			if ($this->tag != 'br') {
				$_obf_Xtyr .= $this->_[HDOM_INFO_INNER];
			}
		}
		else if ($this->nodes) {
			foreach ($this->nodes as $_obf_FQ__) {
				$_obf_Xtyr .= $this->convert_text($_obf_FQ__->outertext());
			}
		}

		if (isset($this->_[HDOM_INFO_END]) && ($this->_[HDOM_INFO_END] != 0)) {
			$_obf_Xtyr .= '</' . $this->tag . '>';
		}

		return $_obf_Xtyr;
	}

	public function text()
	{
		if (isset($this->_[HDOM_INFO_INNER])) {
			return $this->_[HDOM_INFO_INNER];
		}

		switch ($this->nodetype) {
		case HDOM_TYPE_TEXT:
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		case HDOM_TYPE_COMMENT:
			return '';
		case HDOM_TYPE_UNKNOWN:
			return '';
		}

		if (strcasecmp($this->tag, 'script') === 0) {
			return '';
		}

		if (strcasecmp($this->tag, 'style') === 0) {
			return '';
		}

		$_obf_Xtyr = '';

		if (!is_null($this->nodes)) {
			foreach ($this->nodes as $_obf_FQ__) {
				$_obf_Xtyr .= $this->convert_text($_obf_FQ__->text());
			}
		}

		return $_obf_Xtyr;
	}

	public function xmltext()
	{
		$_obf_Xtyr = $this->innertext();
		$_obf_Xtyr = str_ireplace('<![CDATA[', '', $_obf_Xtyr);
		$_obf_Xtyr = str_replace(']]>', '', $_obf_Xtyr);
		return $_obf_Xtyr;
	}

	public function makeup()
	{
		if (isset($this->_[HDOM_INFO_TEXT])) {
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		}

		$_obf_Xtyr = '<' . $this->tag;
		$_obf_7w__ = -1;

		foreach ($this->attr as $_obf_Vwty => $_obf_TAxu) {
			++$_obf_7w__;
			if (($_obf_TAxu === NULL) || ($_obf_TAxu === false)) {
				continue;
			}

			$_obf_Xtyr .= $this->_[HDOM_INFO_SPACE][$_obf_7w__][0];

			if ($_obf_TAxu === true) {
				$_obf_Xtyr .= $_obf_Vwty;
			}
			else {
				switch ($this->_[HDOM_INFO_QUOTE][$_obf_7w__]) {
				case HDOM_QUOTE_DOUBLE:
					$_obf_CxIjRh4_ = '"';
					break;

				case HDOM_QUOTE_SINGLE:
					$_obf_CxIjRh4_ = '\'';
					break;

				default:
					$_obf_CxIjRh4_ = '';
				}

				$_obf_Xtyr .= $_obf_Vwty . $this->_[HDOM_INFO_SPACE][$_obf_7w__][1] . '=' . $this->_[HDOM_INFO_SPACE][$_obf_7w__][2] . $_obf_CxIjRh4_ . $_obf_TAxu . $_obf_CxIjRh4_;
			}
		}

		$_obf_Xtyr = $this->dom->restore_noise($_obf_Xtyr);
		return $_obf_Xtyr . $this->_[HDOM_INFO_ENDSPACE] . '>';
	}

	public function find($selector, $idx = NULL, $lowercase = false)
	{
		$_obf_AuxE5l2oR_bB = $this->parse_selector($selector);

		if (($_obf_gftfagw_ = count($_obf_AuxE5l2oR_bB)) === 0) {
			return array();
		}

		$_obf_xhgoAemMXhBQOQ__ = array();
		$_obf_KQ__ = 0;

		for (; $_obf_KQ__ < $_obf_gftfagw_; ++$_obf_KQ__) {
			if (($_obf_xKQDVI4_ = count($_obf_AuxE5l2oR_bB[$_obf_KQ__])) === 0) {
				return array();
			}

			if (!isset($this->_[HDOM_INFO_BEGIN])) {
				return array();
			}

			$_obf__o37TQ__ = array($this->_[HDOM_INFO_BEGIN] => 1);
			$A = 0;

			for (; $A < $_obf_xKQDVI4_; ++$A) {
				$_obf_Xtyr = array();

				foreach ($_obf__o37TQ__ as $_obf_5w__ => $_obf_6A__) {
					$_obf_FQ__ = ($_obf_5w__ === -1 ? $this->dom->root : $this->dom->nodes[$_obf_5w__]);
					$_obf_FQ__->seek($_obf_AuxE5l2oR_bB[$_obf_KQ__][$A], $_obf_Xtyr, $lowercase);
				}

				$_obf__o37TQ__ = $_obf_Xtyr;
			}

			foreach ($_obf__o37TQ__ as $_obf_5w__ => $_obf_6A__) {
				if (!isset($_obf_xhgoAemMXhBQOQ__[$_obf_5w__])) {
					$_obf_xhgoAemMXhBQOQ__[$_obf_5w__] = 1;
				}
			}
		}

		ksort($_obf_xhgoAemMXhBQOQ__);
		$_obf_CpEUByo_ = array();

		foreach ($_obf_xhgoAemMXhBQOQ__ as $_obf_5w__ => $_obf_6A__) {
			$_obf_CpEUByo_[] = $this->dom->nodes[$_obf_5w__];
		}

		if (is_null($idx)) {
			return $_obf_CpEUByo_;
		}
		else if ($idx < 0) {
			$idx = count($_obf_CpEUByo_) + $idx;
		}

		return isset($_obf_CpEUByo_[$idx]) ? $_obf_CpEUByo_[$idx] : NULL;
	}

	protected function seek($selector, &$ret, $lowercase = false)
	{
		global $debugObject;

		if (is_object($debugObject)) {
			$debugObject->debugLogEntry(1);
		}

		list($tag, $key, $val, $exp, $no_key) = $selector;
		if ($tag && $key && is_numeric($key)) {
			$count = 0;

			foreach ($this->children as $c) {
				if (($tag === '*') || ($tag === $c->tag)) {
					if (++$count == $key) {
						$ret[$c->_[HDOM_INFO_BEGIN]] = 1;
						return NULL;
					}
				}
			}

			return NULL;
		}

		$end = (!empty($this->_[HDOM_INFO_END]) ? $this->_[HDOM_INFO_END] : 0);

		if ($end == 0) {
			$parent = $this->parent;

			while (!isset($parent->_[HDOM_INFO_END]) && ($parent !== NULL)) {
				$end -= 1;
				$parent = $parent->parent;
			}

			$end += $parent->_[HDOM_INFO_END];
		}

		$i = $this->_[HDOM_INFO_BEGIN] + 1;

		for (; $i < $end; ++$i) {
			$node = $this->dom->nodes[$i];
			$pass = true;
			if (($tag === '*') && !$key) {
				if (in_array($node, $this->children, true)) {
					$ret[$i] = 1;
				}

				continue;
			}

			if ($tag && ($tag != $node->tag) && ($tag !== '*')) {
				$pass = false;
			}

			if ($pass && $key) {
				if ($no_key) {
					if (isset($node->attr[$key])) {
						$pass = false;
					}
				}
				else {
					if (($key != 'plaintext') && !isset($node->attr[$key])) {
						$pass = false;
					}
				}
			}

			if ($pass && $key && $val && ($val !== '*')) {
				if ($key == 'plaintext') {
					$nodeKeyValue = $node->text();
				}
				else {
					$nodeKeyValue = $node->attr[$key];
				}

				if (is_object($debugObject)) {
					$debugObject->debugLog(2, 'testing node: ' . $node->tag . ' for attribute: ' . $key . $exp . $val . ' where nodes value is: ' . $nodeKeyValue);
				}

				if ($lowercase) {
					$check = $this->match($exp, strtolower($val), strtolower($nodeKeyValue));
				}
				else {
					$check = $this->match($exp, $val, $nodeKeyValue);
				}

				if (is_object($debugObject)) {
					$debugObject->debugLog(2, 'after match: ' . ($check ? 'true' : 'false'));
				}

				if (!$check && (strcasecmp($key, 'class') === 0)) {
					foreach (explode(' ', $node->attr[$key]) as $k) {
						if (!empty($k)) {
							if ($lowercase) {
								$check = $this->match($exp, strtolower($val), strtolower($k));
							}
							else {
								$check = $this->match($exp, $val, $k);
							}

							if ($check) {
								break;
							}
						}
					}
				}

				if (!$check) {
					$pass = false;
				}
			}

			if ($pass) {
				$ret[$i] = 1;
			}

			unset($node);
		}

		if (is_object($debugObject)) {
			$debugObject->debugLog(1, 'EXIT - ret: ', $ret);
		}
	}

	protected function match($exp, $pattern, $value)
	{
		global $debugObject;

		if (is_object($debugObject)) {
			$debugObject->debugLogEntry(1);
		}

		switch ($exp) {
		case '=':
			return $value === $pattern;
		case '!=':
			return $value !== $pattern;
		case '^=':
			return preg_match('/^' . preg_quote($pattern, '/') . '/', $value);
		case '$=':
			return preg_match('/' . preg_quote($pattern, '/') . '$/', $value);
		case '*=':
			if ($pattern[0] == '/') {
				return preg_match($pattern, $value);
			}

			return preg_match('/' . $pattern . '/i', $value);
		}

		return false;
	}

	protected function parse_selector($selector_string)
	{
		global $debugObject;

		if (is_object($debugObject)) {
			$debugObject->debugLogEntry(1);
		}

		$_obf_VGqEVoP33g__ = '/([\\w-:\\*]*)(?:\\#([\\w-]+)|\\.([\\w-]+))?(?:\\[@?(!?[\\w-:]+)(?:([!*^$]?=)["\']?(.*?)["\']?)?\\])?([\\/, ]+)/is';
		preg_match_all($_obf_VGqEVoP33g__, trim($selector_string) . ' ', $_obf_8UmnTppRcA__, PREG_SET_ORDER);

		if (is_object($debugObject)) {
			$debugObject->debugLog(2, 'Matches Array: ', $_obf_8UmnTppRcA__);
		}

		$_obf_AuxE5l2oR_bB = array();
		$_obf_xs33Yt_k = array();

		foreach ($_obf_8UmnTppRcA__ as $_obf_Ag__) {
			$_obf_Ag__[0] = trim($_obf_Ag__[0]);
			if (($_obf_Ag__[0] === '') || ($_obf_Ag__[0] === '/') || ($_obf_Ag__[0] === '//')) {
				continue;
			}

			if ($_obf_Ag__[1] === 'tbody') {
				continue;
			}

			list($_obf_kLNZ, $_obf_Vwty, $_obf_TAxu, $_obf_1dNy, $_obf_6hQ__SHd) = array($_obf_Ag__[1], NULL, NULL, '=', false);

			if (!empty($_obf_Ag__[2])) {
				$_obf_Vwty = 'id';
				$_obf_TAxu = $_obf_Ag__[2];
			}

			if (!empty($_obf_Ag__[3])) {
				$_obf_Vwty = 'class';
				$_obf_TAxu = $_obf_Ag__[3];
			}

			if (!empty($_obf_Ag__[4])) {
				$_obf_Vwty = $_obf_Ag__[4];
			}

			if (!empty($_obf_Ag__[5])) {
				$_obf_1dNy = $_obf_Ag__[5];
			}

			if (!empty($_obf_Ag__[6])) {
				$_obf_TAxu = $_obf_Ag__[6];
			}

			if ($this->dom->lowercase) {
				$_obf_kLNZ = strtolower($_obf_kLNZ);
				$_obf_Vwty = strtolower($_obf_Vwty);
			}

			if (isset($_obf_Vwty[0]) && ($_obf_Vwty[0] === '!')) {
				$_obf_Vwty = substr($_obf_Vwty, 1);
				$_obf_6hQ__SHd = true;
			}

			$_obf_xs33Yt_k[] = array($_obf_kLNZ, $_obf_Vwty, $_obf_TAxu, $_obf_1dNy, $_obf_6hQ__SHd);

			if (trim($_obf_Ag__[7]) === ',') {
				$_obf_AuxE5l2oR_bB[] = $_obf_xs33Yt_k;
				$_obf_xs33Yt_k = array();
			}
		}

		if (0 < count($_obf_xs33Yt_k)) {
			$_obf_AuxE5l2oR_bB[] = $_obf_xs33Yt_k;
		}

		return $_obf_AuxE5l2oR_bB;
	}

	public function __get($name)
	{
		if (isset($this->attr[$name])) {
			return $this->convert_text($this->attr[$name]);
		}

		switch ($name) {
		case 'outertext':
			return $this->outertext();
		case 'innertext':
			return $this->innertext();
		case 'plaintext':
			return $this->text();
		case 'xmltext':
			return $this->xmltext();
		default:
			return array_key_exists($name, $this->attr);
		}
	}

	public function __set($name, $value)
	{
		switch ($name) {
		case 'outertext':
			return $this->_[HDOM_INFO_OUTER] = $value;
		case 'innertext':
			if (isset($this->_[HDOM_INFO_TEXT])) {
				return $this->_[HDOM_INFO_TEXT] = $value;
			}

			return $this->_[HDOM_INFO_INNER] = $value;
		}

		if (!isset($this->attr[$name])) {
			$this->_[HDOM_INFO_SPACE][] = array(' ', '', '');
			$this->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
		}

		$this->attr[$name] = $value;
	}

	public function __isset($name)
	{
		switch ($name) {
		case 'outertext':
			return true;
		case 'innertext':
			return true;
		case 'plaintext':
			return true;
		}

		return array_key_exists($name, $this->attr) ? true : isset($this->attr[$name]);
	}

	public function __unset($name)
	{
		if (isset($this->attr[$name])) {
			unset($this->attr[$name]);
		}
	}

	public function convert_text($text)
	{
		global $debugObject;

		if (is_object($debugObject)) {
			$debugObject->debugLogEntry(1);
		}

		$converted_text = $text;
		$sourceCharset = '';
		$targetCharset = '';

		if ($this->dom) {
			$sourceCharset = strtoupper($this->dom->_charset);
			$targetCharset = strtoupper($this->dom->_target_charset);
		}

		if (is_object($debugObject)) {
			$debugObject->debugLog(3, 'source charset: ' . $sourceCharset . ' target charaset: ' . $targetCharset);
		}

		if (!empty($sourceCharset) && !empty($targetCharset) && (strcasecmp($sourceCharset, $targetCharset) != 0)) {
			if ((strcasecmp($targetCharset, 'UTF-8') == 0) && $this->is_utf8($text)) {
				$converted_text = $text;
			}
			else {
				$converted_text = iconv($sourceCharset, $targetCharset, $text);
			}
		}

		return $converted_text;
	}

	public function is_utf8($string)
	{
		return utf8_encode(utf8_decode($string)) == $string;
	}

	public function getAllAttributes()
	{
		return $this->attr;
	}

	public function getAttribute($name)
	{
		return $this->__get($name);
	}

	public function setAttribute($name, $value)
	{
		$this->__set($name, $value);
	}

	public function hasAttribute($name)
	{
		return $this->__isset($name);
	}

	public function removeAttribute($name)
	{
		$this->__set($name, NULL);
	}

	public function getElementById($id)
	{
		return $this->find('#' . $id, 0);
	}

	public function getElementsById($id, $idx = NULL)
	{
		return $this->find('#' . $id, $idx);
	}

	public function getElementByTagName($name)
	{
		return $this->find($name, 0);
	}

	public function getElementsByTagName($name, $idx = NULL)
	{
		return $this->find($name, $idx);
	}

	public function parentNode()
	{
		return $this->parent();
	}

	public function childNodes($idx = -1)
	{
		return $this->children($idx);
	}

	public function firstChild()
	{
		return $this->first_child();
	}

	public function lastChild()
	{
		return $this->last_child();
	}

	public function nextSibling()
	{
		return $this->next_sibling();
	}

	public function previousSibling()
	{
		return $this->prev_sibling();
	}
}

class simple_html_dom
{
	public $root;
	public $nodes = array();
	public $callback;
	public $lowercase = false;
	public $size;
	protected $pos;
	protected $doc;
	protected $char;
	protected $cursor;
	protected $parent;
	protected $noise = array();
	protected $token_blank = ' 	' . "\r\n" . '';
	protected $token_equal = ' =/>';
	protected $token_slash = ' />' . "\r\n" . '	';
	protected $token_attr = ' >';
	protected $_charset = '';
	protected $_target_charset = '';
	protected $default_br_text = '';
	protected $self_closing_tags = array('img' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'link' => 1, 'hr' => 1, 'base' => 1, 'embed' => 1, 'spacer' => 1);
	protected $block_tags = array('root' => 1, 'body' => 1, 'form' => 1, 'div' => 1, 'span' => 1, 'table' => 1);
	protected $optional_closing_tags = array(
		'tr'   => array('tr' => 1, 'td' => 1, 'th' => 1),
		'th'   => array('th' => 1),
		'td'   => array('td' => 1),
		'li'   => array('li' => 1),
		'dt'   => array('dt' => 1, 'dd' => 1),
		'dd'   => array('dd' => 1, 'dt' => 1),
		'dl'   => array('dd' => 1, 'dt' => 1),
		'p'    => array('p' => 1),
		'nobr' => array('nobr' => 1),
		'b'    => array('b' => 1)
		);

	public function __construct($str = NULL, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT)
	{
		if ($str) {
			if (preg_match('/^http:\\/\\//i', $str) || is_file($str)) {
				$this->load_file($str);
			}
			else {
				$this->load($str, $lowercase, $stripRN, $defaultBRText);
			}
		}

		if (!$forceTagsClosed) {
			$this->optional_closing_array = array();
		}

		$this->_target_charset = $target_charset;
	}

	public function __destruct()
	{
		$this->clear();
	}

	public function load($str, $lowercase = true, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT)
	{
		global $debugObject;
		$this->prepare($str, $lowercase, $stripRN, $defaultBRText);
		$this->remove_noise('\'<!--(.*?)-->\'is');
		$this->remove_noise('\'<!\\[CDATA\\[(.*?)\\]\\]>\'is', true);
		$this->remove_noise('\'<\\s*script[^>]*[^/]>(.*?)<\\s*/\\s*script\\s*>\'is');
		$this->remove_noise('\'<\\s*script\\s*>(.*?)<\\s*/\\s*script\\s*>\'is');
		$this->remove_noise('\'<\\s*style[^>]*[^/]>(.*?)<\\s*/\\s*style\\s*>\'is');
		$this->remove_noise('\'<\\s*style\\s*>(.*?)<\\s*/\\s*style\\s*>\'is');
		$this->remove_noise('\'<\\s*(?:code)[^>]*>(.*?)<\\s*/\\s*(?:code)\\s*>\'is');
		$this->remove_noise('\'(<\\?)(.*?)(\\?>)\'s', true);
		$this->remove_noise('\'(\\{\\w)(.*?)(\\})\'s', true);

		while ($this->parse()) {
		}

		$this->root->_[HDOM_INFO_END] = $this->cursor;
		$this->parse_charset();
	}

	public function load_file()
	{
		$_obf_yCTIeg__ = func_get_args();
		$this->load(call_user_func_array('file_get_contents', $_obf_yCTIeg__), true);

		if (($_obf_rixiYSg_ = error_get_last()) !== NULL) {
			$this->clear();
			return false;
		}
	}

	public function set_callback($function_name)
	{
		$this->callback = $function_name;
	}

	public function remove_callback()
	{
		$this->callback = NULL;
	}

	public function save($filepath = '')
	{
		$_obf_Xtyr = $this->root->innertext();

		if ($filepath !== '') {
			file_put_contents($filepath, $_obf_Xtyr, LOCK_EX);
		}

		return $_obf_Xtyr;
	}

	public function find($selector, $idx = NULL, $lowercase = false)
	{
		return $this->root->find($selector, $idx, $lowercase);
	}

	public function clear()
	{
		foreach ($this->nodes as $_obf_FQ__) {
			$_obf_FQ__->clear();
			$_obf_FQ__ = NULL;
		}

		if (isset($this->children)) {
			foreach ($this->children as $_obf_FQ__) {
				$_obf_FQ__->clear();
				$_obf_FQ__ = NULL;
			}
		}

		if (isset($this->parent)) {
			$this->parent->clear();
			unset($this->parent);
		}

		if (isset($this->root)) {
			$this->root->clear();
			unset($this->root);
		}

		unset($this->doc);
		unset($this->noise);
	}

	public function dump($show_attr = true)
	{
		$this->root->dump($show_attr);
	}

	protected function prepare($str, $lowercase = true, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT)
	{
		$this->clear();
		$this->size = strlen($str);

		if ($stripRN) {
			$str = str_replace("\r", ' ', $str);
			$str = str_replace("\n", ' ', $str);
		}

		$this->doc = $str;
		$this->pos = 0;
		$this->cursor = 1;
		$this->noise = array();
		$this->nodes = array();
		$this->lowercase = $lowercase;
		$this->default_br_text = $defaultBRText;
		$this->root = new simple_html_dom_node($this);
		$this->root->tag = 'root';
		$this->root->_[HDOM_INFO_BEGIN] = -1;
		$this->root->nodetype = HDOM_TYPE_ROOT;
		$this->parent = $this->root;

		if (0 < $this->size) {
			$this->char = $this->doc[0];
		}
	}

	protected function parse()
	{
		if (($p = $this->copy_until_char('<')) === '') {
			return $this->read_tag();
		}

		$_obf_WKs3DA__ = new simple_html_dom_node($this);
		++$this->cursor;
		$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = $p;
		$this->link_nodes($_obf_WKs3DA__, false);
		return true;
	}

	protected function parse_charset()
	{
		global $debugObject;
		$charset = NULL;

		if (function_exists('get_last_retrieve_url_contents_content_type')) {
			$contentTypeHeader = get_last_retrieve_url_contents_content_type();
			$success = preg_match('/charset=(.+)/', $contentTypeHeader, $matches);

			if ($success) {
				$charset = $matches[1];

				if (is_object($debugObject)) {
					$debugObject->debugLog(2, 'header content-type found charset of: ' . $charset);
				}
			}
		}

		if (empty($charset)) {
			$el = $this->root->find('meta[http-equiv=Content-Type]', 0);

			if (!empty($el)) {
				$fullvalue = $el->content;

				if (is_object($debugObject)) {
					$debugObject->debugLog(2, 'meta content-type tag found' . $fullValue);
				}

				if (!empty($fullvalue)) {
					$success = preg_match('/charset=(.+)/', $fullvalue, $matches);

					if ($success) {
						$charset = $matches[1];
					}
					else {
						if (is_object($debugObject)) {
							$debugObject->debugLog(2, 'meta content-type tag couldn\'t be parsed. using iso-8859 default.');
						}

						$charset = 'ISO-8859-1';
					}
				}
			}
		}

		if (empty($charset)) {
			$charset = mb_detect_encoding($this->root->plaintext . 'ascii', $encoding_list = array('UTF-8', 'CP1252'));

			if (is_object($debugObject)) {
				$debugObject->debugLog(2, 'mb_detect found: ' . $charset);
			}

			if ($charset === false) {
				if (is_object($debugObject)) {
					$debugObject->debugLog(2, 'since mb_detect failed - using default of utf-8');
				}

				$charset = 'UTF-8';
			}
		}

		if ((strtolower($charset) == strtolower('ISO-8859-1')) || (strtolower($charset) == strtolower('Latin1')) || (strtolower($charset) == strtolower('Latin-1'))) {
			if (is_object($debugObject)) {
				$debugObject->debugLog(2, 'replacing ' . $charset . ' with CP1252 as its a superset');
			}

			$charset = 'CP1252';
		}

		if (is_object($debugObject)) {
			$debugObject->debugLog(1, 'EXIT - ' . $charset);
		}

		return $this->_charset = $charset;
	}

	protected function read_tag()
	{
		if ($this->char !== '<') {
			$this->root->_[HDOM_INFO_END] = $this->cursor;
			return false;
		}

		$_obf_Pj51Q8QpWl4XTZtC4Q__ = $this->pos;
		$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;

		if ($this->char === '/') {
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			$this->skip($this->token_blank);
			$_obf_kLNZ = $this->copy_until_char('>');

			if (($_obf_LmSv = strpos($_obf_kLNZ, ' ')) !== false) {
				$_obf_kLNZ = substr($_obf_kLNZ, 0, $_obf_LmSv);
			}

			$_obf_dvHxc7TWpCJxaYZa = strtolower($this->parent->tag);
			$_obf_AS3_BaBgNT8c = strtolower($_obf_kLNZ);

			if ($_obf_dvHxc7TWpCJxaYZa !== $_obf_AS3_BaBgNT8c) {
				if (isset($this->optional_closing_tags[$_obf_dvHxc7TWpCJxaYZa]) && isset($this->block_tags[$_obf_AS3_BaBgNT8c])) {
					$this->parent->_[HDOM_INFO_END] = 0;
					$_obf_Jd4LhalilWTOtQ__ = $this->parent;

					while ($this->parent->parent && (strtolower($this->parent->tag) !== $_obf_AS3_BaBgNT8c)) {
						$this->parent = $this->parent->parent;
					}

					if (strtolower($this->parent->tag) !== $_obf_AS3_BaBgNT8c) {
						$this->parent = $_obf_Jd4LhalilWTOtQ__;

						if ($this->parent->parent) {
							$this->parent = $this->parent->parent;
						}

						$this->parent->_[HDOM_INFO_END] = $this->cursor;
						return $this->as_text_node($_obf_kLNZ);
					}
				}
				else {
					if ($this->parent->parent && isset($this->block_tags[$_obf_AS3_BaBgNT8c])) {
						$this->parent->_[HDOM_INFO_END] = 0;
						$_obf_Jd4LhalilWTOtQ__ = $this->parent;

						while ($this->parent->parent && (strtolower($this->parent->tag) !== $_obf_AS3_BaBgNT8c)) {
							$this->parent = $this->parent->parent;
						}

						if (strtolower($this->parent->tag) !== $_obf_AS3_BaBgNT8c) {
							$this->parent = $_obf_Jd4LhalilWTOtQ__;
							$this->parent->_[HDOM_INFO_END] = $this->cursor;
							return $this->as_text_node($_obf_kLNZ);
						}
					}
					else {
						if ($this->parent->parent && (strtolower($this->parent->parent->tag) === $_obf_AS3_BaBgNT8c)) {
							$this->parent->_[HDOM_INFO_END] = 0;
							$this->parent = $this->parent->parent;
						}
						else {
							return $this->as_text_node($_obf_kLNZ);
						}
					}
				}
			}

			$this->parent->_[HDOM_INFO_END] = $this->cursor;

			if ($this->parent->parent) {
				$this->parent = $this->parent->parent;
			}

			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			return true;
		}

		$_obf_WKs3DA__ = new simple_html_dom_node($this);
		$_obf_WKs3DA__->_[HDOM_INFO_BEGIN] = $this->cursor;
		++$this->cursor;
		$_obf_kLNZ = $this->copy_until($this->token_slash);
		$_obf_WKs3DA__->tag_start = $_obf_Pj51Q8QpWl4XTZtC4Q__;
		if (isset($_obf_kLNZ[0]) && ($_obf_kLNZ[0] === '!')) {
			$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = '<' . $_obf_kLNZ . $this->copy_until_char('>');
			if (isset($_obf_kLNZ[2]) && ($_obf_kLNZ[1] === '-') && ($_obf_kLNZ[2] === '-')) {
				$_obf_WKs3DA__->nodetype = HDOM_TYPE_COMMENT;
				$_obf_WKs3DA__->tag = 'comment';
			}
			else {
				$_obf_WKs3DA__->nodetype = HDOM_TYPE_UNKNOWN;
				$_obf_WKs3DA__->tag = 'unknown';
			}

			if ($this->char === '>') {
				$_obf_WKs3DA__->_[HDOM_INFO_TEXT] .= '>';
			}

			$this->link_nodes($_obf_WKs3DA__, true);
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			return true;
		}

		if ($_obf_LmSv = strpos($_obf_kLNZ, '<') !== false) {
			$_obf_kLNZ = '<' . substr($_obf_kLNZ, 0, -1);
			$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = $_obf_kLNZ;
			$this->link_nodes($_obf_WKs3DA__, false);
			$this->char = $this->doc[--$this->pos];
			return true;
		}

		if (!preg_match('/^[\\w-:]+$/', $_obf_kLNZ)) {
			$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = '<' . $_obf_kLNZ . $this->copy_until('<>');

			if ($this->char === '<') {
				$this->link_nodes($_obf_WKs3DA__, false);
				return true;
			}

			if ($this->char === '>') {
				$_obf_WKs3DA__->_[HDOM_INFO_TEXT] .= '>';
			}

			$this->link_nodes($_obf_WKs3DA__, false);
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			return true;
		}

		$_obf_WKs3DA__->nodetype = HDOM_TYPE_ELEMENT;
		$_obf_AS3_BaBgNT8c = strtolower($_obf_kLNZ);
		$_obf_WKs3DA__->tag = $this->lowercase ? $_obf_AS3_BaBgNT8c : $_obf_kLNZ;

		if (isset($this->optional_closing_tags[$_obf_AS3_BaBgNT8c])) {
			while (isset($this->optional_closing_tags[$_obf_AS3_BaBgNT8c][strtolower($this->parent->tag)])) {
				$this->parent->_[HDOM_INFO_END] = 0;
				$this->parent = $this->parent->parent;
			}

			$_obf_WKs3DA__->parent = $this->parent;
		}

		$_obf_uD_4p6Y_ = 0;
		$_obf_jFMFw9s_ = array($this->copy_skip($this->token_blank), '', '');

		do {
			if (($this->char !== NULL) && ($_obf_jFMFw9s_[0] === '')) {
				break;
			}

			$_obf_3gn_eQ__ = $this->copy_until($this->token_equal);

			if ($_obf_uD_4p6Y_ === $this->pos) {
				$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
				continue;
			}

			$_obf_uD_4p6Y_ = $this->pos;
			if ((($this->size - 1) <= $this->pos) && ($this->char !== '>')) {
				$_obf_WKs3DA__->nodetype = HDOM_TYPE_TEXT;
				$_obf_WKs3DA__->_[HDOM_INFO_END] = 0;
				$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = '<' . $_obf_kLNZ . $_obf_jFMFw9s_[0] . $_obf_3gn_eQ__;
				$_obf_WKs3DA__->tag = 'text';
				$this->link_nodes($_obf_WKs3DA__, false);
				return true;
			}

			if ($this->doc[$this->pos - 1] == '<') {
				$_obf_WKs3DA__->nodetype = HDOM_TYPE_TEXT;
				$_obf_WKs3DA__->tag = 'text';
				$_obf_WKs3DA__->attr = array();
				$_obf_WKs3DA__->_[HDOM_INFO_END] = 0;
				$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = substr($this->doc, $_obf_Pj51Q8QpWl4XTZtC4Q__, $this->pos - $_obf_Pj51Q8QpWl4XTZtC4Q__ - 1);
				$this->pos -= 2;
				$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
				$this->link_nodes($_obf_WKs3DA__, false);
				return true;
			}

			if (($_obf_3gn_eQ__ !== '/') && ($_obf_3gn_eQ__ !== '')) {
				$_obf_jFMFw9s_[1] = $this->copy_skip($this->token_blank);
				$_obf_3gn_eQ__ = $this->restore_noise($_obf_3gn_eQ__);

				if ($this->lowercase) {
					$_obf_3gn_eQ__ = strtolower($_obf_3gn_eQ__);
				}

				if ($this->char === '=') {
					$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
					$this->parse_attr($_obf_WKs3DA__, $_obf_3gn_eQ__, $_obf_jFMFw9s_);
				}
				else {
					$_obf_WKs3DA__->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
					$_obf_WKs3DA__->attr[$_obf_3gn_eQ__] = true;

					if ($this->char != '>') {
						$this->char = $this->doc[--$this->pos];
					}
				}

				$_obf_WKs3DA__->_[HDOM_INFO_SPACE][] = $_obf_jFMFw9s_;
				$_obf_jFMFw9s_ = array($this->copy_skip($this->token_blank), '', '');
			}
			else {
				break;
			}

		} while (($this->char !== '>') && ($this->char !== '/'));

		$this->link_nodes($_obf_WKs3DA__, true);
		$_obf_WKs3DA__->_[HDOM_INFO_ENDSPACE] = $_obf_jFMFw9s_[0];

		if ($this->copy_until_char_escape('>') === '/') {
			$_obf_WKs3DA__->_[HDOM_INFO_ENDSPACE] .= '/';
			$_obf_WKs3DA__->_[HDOM_INFO_END] = 0;
		}
		else if (!isset($this->self_closing_tags[strtolower($_obf_WKs3DA__->tag)])) {
			$this->parent = $_obf_WKs3DA__;
		}

		$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;

		if ($_obf_WKs3DA__->tag == 'br') {
			$_obf_WKs3DA__->_[HDOM_INFO_INNER] = $this->default_br_text;
		}

		return true;
	}

	protected function parse_attr($node, $name, &$space)
	{
		if (isset($node->attr[$name])) {
			return NULL;
		}

		$space[2] = $this->copy_skip($this->token_blank);

		switch ($this->char) {
		case '"':
			$node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			$node->attr[$name] = $this->restore_noise($this->copy_until_char_escape('"'));
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			break;

		case '\'':
			$node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_SINGLE;
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			$node->attr[$name] = $this->restore_noise($this->copy_until_char_escape('\''));
			$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
			break;

		default:
			$node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
			$node->attr[$name] = $this->restore_noise($this->copy_until($this->token_attr));
		}

		$node->attr[$name] = str_replace("\r", '', $node->attr[$name]);
		$node->attr[$name] = str_replace("\n", '', $node->attr[$name]);

		if ($name == 'class') {
			$node->attr[$name] = trim($node->attr[$name]);
		}
	}

	protected function link_nodes(&$node, $is_child)
	{
		$node->parent = $this->parent;
		$this->parent->nodes[] = $node;

		if ($is_child) {
			$this->parent->children[] = $node;
		}
	}

	protected function as_text_node($tag)
	{
		$_obf_WKs3DA__ = new simple_html_dom_node($this);
		++$this->cursor;
		$_obf_WKs3DA__->_[HDOM_INFO_TEXT] = '</' . $tag . '>';
		$this->link_nodes($_obf_WKs3DA__, false);
		$this->char = ++$this->pos < $this->size ? $this->doc[$this->pos] : NULL;
		return true;
	}

	protected function skip($chars)
	{
		$this->pos += strspn($this->doc, $chars, $this->pos);
		$this->char = $this->pos < $this->size ? $this->doc[$this->pos] : NULL;
	}

	protected function copy_skip($chars)
	{
		$_obf_LmSv = $this->pos;
		$_obf_mc2H = strspn($this->doc, $chars, $_obf_LmSv);
		$this->pos += $_obf_mc2H;
		$this->char = $this->pos < $this->size ? $this->doc[$this->pos] : NULL;

		if ($_obf_mc2H === 0) {
			return '';
		}

		return substr($this->doc, $_obf_LmSv, $_obf_mc2H);
	}

	protected function copy_until($chars)
	{
		$_obf_LmSv = $this->pos;
		$_obf_mc2H = strcspn($this->doc, $chars, $_obf_LmSv);
		$this->pos += $_obf_mc2H;
		$this->char = $this->pos < $this->size ? $this->doc[$this->pos] : NULL;
		return substr($this->doc, $_obf_LmSv, $_obf_mc2H);
	}

	protected function copy_until_char($char)
	{
		if ($this->char === NULL) {
			return '';
		}

		if (($_obf_LmSv = strpos($this->doc, $char, $this->pos)) === false) {
			$_obf_Xtyr = substr($this->doc, $this->pos, $this->size - $this->pos);
			$this->char = NULL;
			$this->pos = $this->size;
			return $_obf_Xtyr;
		}

		if ($_obf_LmSv === $this->pos) {
			return '';
		}

		$_obf_KJ_fYnfpyA__ = $this->pos;
		$this->char = $this->doc[$_obf_LmSv];
		$this->pos = $_obf_LmSv;
		return substr($this->doc, $_obf_KJ_fYnfpyA__, $_obf_LmSv - $_obf_KJ_fYnfpyA__);
	}

	protected function copy_until_char_escape($char)
	{
		if ($this->char === NULL) {
			return '';
		}

		$_obf_mV9HBLY_ = $this->pos;

		while (1) {
			if (($_obf_LmSv = strpos($this->doc, $char, $_obf_mV9HBLY_)) === false) {
				$_obf_Xtyr = substr($this->doc, $this->pos, $this->size - $this->pos);
				$this->char = NULL;
				$this->pos = $this->size;
				return $_obf_Xtyr;
			}

			if ($_obf_LmSv === $this->pos) {
				return '';
			}

			if ($this->doc[$_obf_LmSv - 1] === '\\') {
				$_obf_mV9HBLY_ = $_obf_LmSv + 1;
				continue;
			}

			$_obf_KJ_fYnfpyA__ = $this->pos;
			$this->char = $this->doc[$_obf_LmSv];
			$this->pos = $_obf_LmSv;
			return substr($this->doc, $_obf_KJ_fYnfpyA__, $_obf_LmSv - $_obf_KJ_fYnfpyA__);
		}
	}

	protected function remove_noise($pattern, $remove_tag = false)
	{
		$_obf_gftfagw_ = preg_match_all($pattern, $this->doc, $_obf_8UmnTppRcA__, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
		$_obf_7w__ = $_obf_gftfagw_ - 1;

		for (; -1 < $_obf_7w__; --$_obf_7w__) {
			$_obf_Vwty = '___noise___' . sprintf('% 3d', count($this->noise) + 100);
			$_obf_Fv_U = ($remove_tag ? 0 : 1);
			$this->noise[$_obf_Vwty] = $_obf_8UmnTppRcA__[$_obf_7w__][$_obf_Fv_U][0];
			$this->doc = substr_replace($this->doc, $_obf_Vwty, $_obf_8UmnTppRcA__[$_obf_7w__][$_obf_Fv_U][1], strlen($_obf_8UmnTppRcA__[$_obf_7w__][$_obf_Fv_U][0]));
		}

		$this->size = strlen($this->doc);

		if (0 < $this->size) {
			$this->char = $this->doc[0];
		}
	}

	public function restore_noise($text)
	{
		while (($_obf_LmSv = strpos($text, '___noise___')) !== false) {
			$_obf_Vwty = '___noise___' . $text[$_obf_LmSv + 11] . $text[$_obf_LmSv + 12] . $text[$_obf_LmSv + 13];

			if (isset($this->noise[$_obf_Vwty])) {
				$text = substr($text, 0, $_obf_LmSv) . $this->noise[$_obf_Vwty] . substr($text, $_obf_LmSv + 14);
			}
		}

		return $text;
	}

	public function __toString()
	{
		return $this->root->innertext();
	}

	public function __get($name)
	{
		switch ($name) {
		case 'outertext':
			return $this->root->innertext();
		case 'innertext':
			return $this->root->innertext();
		case 'plaintext':
			return $this->root->text();
		case 'charset':
			return $this->_charset;
		case 'target_charset':
			return $this->_target_charset;
		}
	}

	public function childNodes($idx = -1)
	{
		return $this->root->childNodes($idx);
	}

	public function firstChild()
	{
		return $this->root->first_child();
	}

	public function lastChild()
	{
		return $this->root->last_child();
	}

	public function getElementById($id)
	{
		return $this->find('#' . $id, 0);
	}

	public function getElementsById($id, $idx = NULL)
	{
		return $this->find('#' . $id, $idx);
	}

	public function getElementByTagName($name)
	{
		return $this->find($name, 0);
	}

	public function getElementsByTagName($name, $idx = -1)
	{
		return $this->find($name, $idx);
	}

	public function loadFile()
	{
		$_obf_yCTIeg__ = func_get_args();
		$this->load_file($_obf_yCTIeg__);
	}
}

function _obf_fncyFF9zCAc_JGBeeA__($url, $use_include_path = false, $context = NULL, $offset = -1, $maxLen = -1, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT)
{
	$dom = new simple_html_dom(NULL, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
	$contents = file_get_contents($url, $use_include_path, $context, $offset);

	if (empty($contents)) {
		return false;
	}

	$dom->load($contents, $lowercase, $stripRN);
	return $dom;
}

function str_get_html($str, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT)
{
	$dom = new simple_html_dom(NULL, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);

	if (empty($str)) {
		$dom->clear();
		return false;
	}

	$dom->load($str, $lowercase, $stripRN);
	return $dom;
}

function _obf_ZB55Y29sdW4edw5lX2U_($node, $show_attr = true, $deep = 0)
{
	$node->dump($node);
}

define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT', 3);
define('HDOM_TYPE_ENDTAG', 4);
define('HDOM_TYPE_ROOT', 5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO', 3);
define('HDOM_INFO_BEGIN', 0);
define('HDOM_INFO_END', 1);
define('HDOM_INFO_QUOTE', 2);
define('HDOM_INFO_SPACE', 3);
define('HDOM_INFO_TEXT', 4);
define('HDOM_INFO_INNER', 5);
define('HDOM_INFO_OUTER', 6);
define('HDOM_INFO_ENDSPACE', 7);
define('DEFAULT_TARGET_CHARSET', 'UTF-8');
define('DEFAULT_BR_TEXT', "\r\n");

?>

<?php
//dezend by http://www.yunlu99.com/
function eq_xss_clean_post($str)
{
	if (is_array($str)) {
		foreach ($str as $_obf_Vwty => $_obf_TAxu) {
			$str[$_obf_Vwty] = eq_xss_clean_post($_obf_TAxu);
		}
	}
	else {
		$str = xss_clean($str);
	}

	return $str;
}

function eq_xss_clean_get($str)
{
	if (is_array($str)) {
		foreach ($str as $_obf_Vwty => $_obf_TAxu) {
			$str[$_obf_Vwty] = eq_xss_clean_get($_obf_TAxu);
		}
	}
	else {
		$str = xss_clean($str);
		$str = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $str);

		if (strpos($str, '&amp;#') !== false) {
			$str = preg_replace('/&amp;((#(\\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $str);
		}
	}

	return $str;
}

function xss_clean($val)
{
	global $Config;

	if (strlen(urldecode($val)) != strlen($val)) {
		$val = preg_replace('/%27/', '\\\'', $val);
		$val = preg_replace('/%22/', '\\"', $val);
		$val = urldecode($val);
	}

	if ($Config['xssCleanForce'] == 1) {
		$_obf_qUc_ = $Config['xssForceWord'];
	}
	else {
		$_obf_qUc_ = $Config['xssWord'];
	}

	$_obf_CpEUByo_ = true;

	while ($_obf_CpEUByo_ == true) {
		$_obf_n_afJDMAcsoVJw__ = $val;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < sizeof($_obf_qUc_); $_obf_7w__++) {
			$_obf_VGqEVoP33g__ = '/';
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < strlen($_obf_qUc_[$_obf_7w__]); $_obf_XA__++) {
				if (0 < $_obf_XA__) {
					$_obf_VGqEVoP33g__ .= '(';
					$_obf_VGqEVoP33g__ .= '(&#[xX]0{0,8}([9ab]);)';
					$_obf_VGqEVoP33g__ .= '|';
					$_obf_VGqEVoP33g__ .= '|(&#0{0,8}([9|10|13]);)';
					$_obf_VGqEVoP33g__ .= ')*';
				}

				$_obf_VGqEVoP33g__ .= $_obf_qUc_[$_obf_7w__][$_obf_XA__];
			}

			$_obf_VGqEVoP33g__ .= '/i';
			$_obf_h1VxS5clwThuqtQ_ = substr($_obf_qUc_[$_obf_7w__], 0, 2) . '<x>' . substr($_obf_qUc_[$_obf_7w__], 2);
			$val = preg_replace($_obf_VGqEVoP33g__, $_obf_h1VxS5clwThuqtQ_, $val);

			if ($_obf_n_afJDMAcsoVJw__ == $val) {
				$_obf_CpEUByo_ = false;
			}
		}
	}

	return $val;
}


?>

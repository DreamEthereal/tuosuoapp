<?php
//dezend by http://www.yunlu99.com/
function browser_detection($which_test, $test_excludes = '', $external_ua_string = '')
{
	script_time();
	static $a_full_assoc_data;
	static $a_khtml_data;
	static $a_mobile_data;
	static $a_moz_data;
	static $a_engine_data;
	static $a_trident_data;
	static $a_webkit_data;
	static $b_dom_browser;
	static $b_repeat;
	static $b_safe_browser;
	static $browser_name;
	static $browser_number;
	static $browser_math_number;
	static $browser_user_agent;
	static $browser_working;
	static $html_type;
	static $ie_version;
	static $khtml_type;
	static $khtml_type_number;
	static $mobile_test;
	static $moz_type_number;
	static $moz_rv;
	static $moz_rv_full;
	static $moz_release_date;
	static $moz_type;
	static $os_number;
	static $os_type;
	static $layout_engine;
	static $layout_engine_nu;
	static $layout_engine_nu_full;
	static $trident_type;
	static $trident_type_number;
	static $true_ie_number;
	static $ua_type;
	static $webkit_type;
	static $webkit_type_number;

	if ($external_ua_string) {
		$b_repeat = false;
	}

	if (!$b_repeat) {
		$_obf_5mSJM4gI_1JcScMo9HK3j_JWiSSV = '';
		$a_full_assoc_data = '';
		$_obf_PfZxtqChqptVJPI_ = '';
		$a_khtml_data = '';
		$a_mobile_data = '';
		$a_moz_data = '';
		$_obf_mt27FeIwkPy3 = '';
		$a_trident_data = '';
		$_obf_nfo6rrzMzcWaYPzO4wJhUI_APQ__ = '';
		$a_webkit_data = '';
		$b_dom_browser = false;
		$_obf__NTofMF8GoBl = true;
		$_obf_Yra3N98SvSNw8Dw8JA__ = true;
		$b_safe_browser = false;
		$_obf_FHbv_3jBsllr = false;
		$browser_math_number = '';
		$_obf_d4Z1EwXfE647FNyn = '';
		$browser_working = '';
		$browser_number = '';
		$html_type = '';
		$_obf_QAe1_EhZwsaQ2h_c9v9VwVostuU_ = '';
		$ie_version = '';
		$layout_engine = '';
		$layout_engine_nu = '';
		$layout_engine_nu_full = '';
		$khtml_type = '';
		$khtml_type_number = '';
		$mobile_test = '';
		$moz_release_date = '';
		$moz_rv = '';
		$moz_rv_full = '';
		$moz_type = '';
		$moz_type_number = '';
		$os_number = '';
		$os_type = '';
		$_obf_psUELaSFshc_ = '';
		$trident_type = '';
		$trident_type_number = '';
		$true_ie_number = '';
		$ua_type = 'bot';
		$webkit_type = '';
		$webkit_type_number = '';

		if ($test_excludes) {
			switch ($test_excludes) {
			case '1':
				$_obf__NTofMF8GoBl = false;
				break;

			case '2':
				$_obf_Yra3N98SvSNw8Dw8JA__ = false;
				break;

			case '3':
				$_obf__NTofMF8GoBl = false;
				$_obf_Yra3N98SvSNw8Dw8JA__ = false;
				break;

			default:
				exit('Error: bad $test_excludes parameter 2 used: ' . $test_excludes);
				break;
			}
		}

		if ($external_ua_string) {
			$browser_user_agent = strtolower($external_ua_string);
		}
		else if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$browser_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		}
		else {
			$browser_user_agent = '';
		}

		$_obf__e_nm6raP4kH8DjBs5gd = array(
			array('msie', true, 'ie', 'bro'),
			array('trident', true, 'ie', 'bro'),
			array('opr/', true, 'op', 'bro'),
			array('webkit', true, 'webkit', 'bro'),
			array('opera', true, 'op', 'bro'),
			array('khtml', true, 'khtml', 'bro'),
			array('gecko', true, 'moz', 'bro'),
			array('netpositive', false, 'netp', 'bbro'),
			array('lynx', false, 'lynx', 'bbro'),
			array('elinks ', false, 'elinks', 'bbro'),
			array('elinks', false, 'elinks', 'bbro'),
			array('links2', false, 'links2', 'bbro'),
			array('links ', false, 'links', 'bbro'),
			array('links', false, 'links', 'bbro'),
			array('w3m', false, 'w3m', 'bbro'),
			array('webtv', false, 'webtv', 'bbro'),
			array('amaya', false, 'amaya', 'bbro'),
			array('dillo', false, 'dillo', 'bbro'),
			array('ibrowse', false, 'ibrowse', 'bbro'),
			array('icab', false, 'icab', 'bro'),
			array('crazy browser', true, 'ie', 'bro'),
			array('answerbus', false, 'answerbus', 'bot'),
			array('ask jeeves', false, 'ask', 'bot'),
			array('teoma', false, 'ask', 'bot'),
			array('baiduspider', false, 'baidu', 'bot'),
			array('bingbot', false, 'bing', 'bot'),
			array('boitho.com-dc', false, 'boitho', 'bot'),
			array('exabot', false, 'exabot', 'bot'),
			array('fast-webcrawler', false, 'fast', 'bot'),
			array('ia_archiver', false, 'ia_archiver', 'bot'),
			array('googlebot', false, 'google', 'bot'),
			array('google web preview', false, 'googlewp', 'bot'),
			array('mediapartners-google', false, 'adsense', 'bot'),
			array('msnbot', false, 'msn', 'bot'),
			array('objectssearch', false, 'objectsearch', 'bot'),
			array('scooter', false, 'scooter', 'bot'),
			array('yahoo-verticalcrawler', false, 'yahoo', 'bot'),
			array('yahoo! slurp', false, 'yahoo', 'bot'),
			array('yahoo-mm', false, 'yahoomm', 'bot'),
			array('inktomi', false, 'inktomi', 'bot'),
			array('slurp', false, 'inktomi', 'bot'),
			array('zyborg', false, 'looksmart', 'bot'),
			array('almaden', false, 'ibm', 'bot'),
			array('comodospider', false, 'comodospider', 'bot'),
			array('gigabot', false, 'gigabot', 'bot'),
			array('iltrovatore-setaccio', false, 'il-set', 'bot'),
			array('lexxebotr', false, 'lexxebotr', 'bot'),
			array('magpie-crawlero', false, 'magpie-crawler', 'bot'),
			array('naverbot', false, 'naverbot', 'bot'),
			array('omgilibot', false, 'omgilibot', 'bot'),
			array('openbot', false, 'openbot', 'bot'),
			array('psbot', false, 'psbot', 'bot'),
			array('sogou', false, 'sogou', 'bot'),
			array('sosospider', false, 'sosospider', 'bot'),
			array('sohu-search', false, 'sohu', 'bot'),
			array('surveybot', false, 'surveybot', 'bot'),
			array('vbseo', false, 'vbseo', 'bot'),
			array('w3c_validator', false, 'w3c', 'lib'),
			array('wdg_validator', false, 'wdg', 'lib'),
			array('libwww-perl', false, 'libwww-perl', 'lib'),
			array('jakarta commons-httpclient', false, 'jakarta', 'lib'),
			array('python-urllib', false, 'python-urllib', 'lib'),
			array('getright', false, 'getright', 'dow'),
			array('wget', false, 'wget', 'dow'),
			array('mozilla/4.', false, 'ns', 'bbro'),
			array('mozilla/3.', false, 'ns', 'bbro'),
			array('mozilla/2.', false, 'ns', 'bbro')
			);
		$_obf_bhde3LJW2JVyYjoTig__ = array('chrome', 'opr/');
		$_obf_I7TIY5zU2_FWYt8KdQ__ = array('bonecho', 'camino', 'conkeror', 'epiphany', 'fennec', 'firebird', 'flock', 'galeon', 'iceape', 'icecat', 'k-meleon', 'minimo', 'multizilla', 'phoenix', 'skyfire', 'songbird', 'swiftfox', 'seamonkey', 'shadowfox', 'shiretoko', 'iceweasel', 'firefox', 'minefield', 'netscape6', 'netscape', 'rv');
		$_obf_ElRcukZChNG4QGcGhQ__ = array('konqueror', 'khtml');
		$_obf_A_VAA77sgToXnEHpAnS3 = array('ucbrowser', 'ucweb', 'msie');
		$_obf_qm5CXrDtdBOJk5TpAiU_ = array('arora', 'bolt', 'beamrise', 'chromium', 'puffin', 'chrome', 'crios', 'dooble', 'epiphany', 'gtklauncher', 'icab', 'konqueror', 'maxthon', 'midori', 'omniweb', 'opera', 'qupzilla', 'rekonq', 'rocketmelt', 'silk', 'uzbl', 'ucbrowser', 'ucweb', 'shiira', 'sputnik', 'steel', 'teashark', 'safari', 'applewebkit', 'webos', 'xxxterm', 'webkit');
		$_obf_8hyjR5U8uQ__ = count($_obf__e_nm6raP4kH8DjBs5gd);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_8hyjR5U8uQ__; $_obf_7w__++) {
			$_obf_d4Z1EwXfE647FNyn = $_obf__e_nm6raP4kH8DjBs5gd[$_obf_7w__][0];

			if (strstr($browser_user_agent, $_obf_d4Z1EwXfE647FNyn)) {
				$b_safe_browser = true;
				$browser_name = $_obf_d4Z1EwXfE647FNyn;
				$b_dom_browser = $_obf__e_nm6raP4kH8DjBs5gd[$_obf_7w__][1];
				$browser_working = $_obf__e_nm6raP4kH8DjBs5gd[$_obf_7w__][2];
				$ua_type = $_obf__e_nm6raP4kH8DjBs5gd[$_obf_7w__][3];

				switch ($browser_working) {
				case 'ns':
					$b_safe_browser = false;
					$browser_number = get_item_version($browser_user_agent, 'mozilla');
					break;

				case 'khtml':
					$browser_number = get_item_version($browser_user_agent, $browser_name);
					$layout_engine = 'khtml';
					$layout_engine_nu = get_item_math_number($browser_number);
					$layout_engine_nu_full = $browser_number;
					$_obf_al0CU2ExnQ__ = count($_obf_ElRcukZChNG4QGcGhQ__);
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
						if (strstr($browser_user_agent, $_obf_ElRcukZChNG4QGcGhQ__[$_obf_XA__])) {
							$khtml_type = $_obf_ElRcukZChNG4QGcGhQ__[$_obf_XA__];
							$khtml_type_number = get_item_version($browser_user_agent, $khtml_type);
							$browser_name = $_obf_ElRcukZChNG4QGcGhQ__[$_obf_XA__];
							$browser_number = get_item_version($browser_user_agent, $browser_name);
							break;
						}
					}

					break;

				case 'moz':
					get_set_count('set', 0);
					$moz_rv_full = get_item_version($browser_user_agent, 'rv:');
					$moz_rv = floatval($moz_rv_full);
					$_obf_al0CU2ExnQ__ = count($_obf_I7TIY5zU2_FWYt8KdQ__);
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
						if (strstr($browser_user_agent, $_obf_I7TIY5zU2_FWYt8KdQ__[$_obf_XA__])) {
							$moz_type = $_obf_I7TIY5zU2_FWYt8KdQ__[$_obf_XA__];
							$moz_type_number = get_item_version($browser_user_agent, $moz_type);
							break;
						}
					}

					if (!$moz_rv) {
						$moz_rv = floatval($moz_type_number);
						$moz_rv_full = $moz_type_number;
					}

					if ($moz_type == 'rv') {
						$moz_type = 'mozilla';
					}

					$browser_number = $moz_rv;
					get_set_count('set', 0);
					$moz_release_date = get_item_version($browser_user_agent, 'gecko/');
					$layout_engine = 'gecko';
					$layout_engine_nu = $moz_rv;
					$layout_engine_nu_full = $moz_rv_full;
					if (($moz_release_date < 20020400) || ($moz_rv < 1)) {
						$b_safe_browser = false;
					}

					break;

				case 'ie':
					$_obf_UQbJ_3YPdUF8ag__ = false;

					if (strstr($browser_user_agent, 'rv:')) {
						$browser_name = 'msie';
						$_obf_UQbJ_3YPdUF8ag__ = true;
						get_set_count('set', 0);
						$browser_number = get_item_version($browser_user_agent, 'rv:', '', '');
					}
					else {
						$browser_number = get_item_version($browser_user_agent, $browser_name, true, 'trident/');
					}

					get_set_count('set', 0);
					$layout_engine_nu_full = get_item_version($browser_user_agent, 'trident/', '', '');

					if ($layout_engine_nu_full) {
						$layout_engine_nu = get_item_math_number($layout_engine_nu_full);
						$layout_engine = 'trident';
						if (strstr($browser_number, '7.') && !$_obf_UQbJ_3YPdUF8ag__) {
							$true_ie_number = get_item_math_number($browser_number) + (intval($layout_engine_nu) - 3);
						}
						else {
							$true_ie_number = $browser_number;
						}

						$_obf_al0CU2ExnQ__ = count($_obf_A_VAA77sgToXnEHpAnS3);
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
							if (strstr($browser_user_agent, $_obf_A_VAA77sgToXnEHpAnS3[$_obf_XA__])) {
								$trident_type = $_obf_A_VAA77sgToXnEHpAnS3[$_obf_XA__];
								$trident_type_number = get_item_version($browser_user_agent, $trident_type);
								break;
							}
						}

						if (!$trident_type && $_obf_UQbJ_3YPdUF8ag__) {
							$trident_type = 'msie';
							$trident_type_number = $browser_number;
						}
					}
					else {
						if ((intval($browser_number) <= 7) && (4 <= intval($browser_number))) {
							$layout_engine = 'trident';

							if (intval($browser_number) == 7) {
								$layout_engine_nu_full = '3.1';
								$layout_engine_nu = '3.1';
							}
						}
					}

					if (9 <= $browser_number) {
						$ie_version = 'ie9x';
					}
					else if (7 <= $browser_number) {
						$ie_version = 'ie7x';
					}
					else if (strstr($browser_user_agent, 'mac')) {
						$ie_version = 'ieMac';
					}
					else if (5 <= $browser_number) {
						$ie_version = 'ie5x';
					}
					else {
						if ((3 < $browser_number) && ($browser_number < 5)) {
							$b_dom_browser = false;
							$ie_version = 'ie4';
							$b_safe_browser = true;
						}
						else {
							$ie_version = 'old';
							$b_dom_browser = false;
							$b_safe_browser = false;
						}
					}

					break;

				case 'op':
					if ($browser_name == 'opr/') {
						$browser_name = 'opr';
					}

					$browser_number = get_item_version($browser_user_agent, $browser_name);
					if (strstr($browser_number, '9.') && strstr($browser_user_agent, 'version/')) {
						get_set_count('set', 0);
						$browser_number = get_item_version($browser_user_agent, 'version/');
					}

					get_set_count('set', 0);
					$layout_engine_nu_full = get_item_version($browser_user_agent, 'presto/');

					if ($layout_engine_nu_full) {
						$layout_engine = 'presto';
						$layout_engine_nu = get_item_math_number($layout_engine_nu_full);
					}

					if (!$layout_engine_nu_full && ($browser_name == 'opr')) {
						if (strstr($browser_user_agent, 'blink')) {
							$layout_engine_nu_full = get_item_version($browser_user_agent, 'blink');
						}
						else {
							$layout_engine_nu_full = get_item_version($browser_user_agent, 'webkit');
						}

						$layout_engine_nu = get_item_math_number($layout_engine_nu_full);
						$layout_engine = 'blink';
						$browser_name = 'opera';
					}

					if ($browser_number < 5) {
						$b_safe_browser = false;
					}

					break;

				case 'webkit':
					$browser_number = get_item_version($browser_user_agent, $browser_name);
					$layout_engine = 'webkit';
					$layout_engine_nu = get_item_math_number($browser_number);
					$layout_engine_nu_full = $browser_number;
					$_obf_al0CU2ExnQ__ = count($_obf_qm5CXrDtdBOJk5TpAiU_);
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
						if (strstr($browser_user_agent, $_obf_qm5CXrDtdBOJk5TpAiU_[$_obf_XA__])) {
							$webkit_type = $_obf_qm5CXrDtdBOJk5TpAiU_[$_obf_XA__];

							if ($webkit_type == 'omniweb') {
								get_set_count('set', 2);
							}

							$webkit_type_number = get_item_version($browser_user_agent, $webkit_type);

							if ($_obf_qm5CXrDtdBOJk5TpAiU_[$_obf_XA__] == 'gtklauncher') {
								$browser_name = 'epiphany';
							}
							else {
								$browser_name = $_obf_qm5CXrDtdBOJk5TpAiU_[$_obf_XA__];
							}

							if (($_obf_qm5CXrDtdBOJk5TpAiU_[$_obf_XA__] == 'chrome') && (28 <= get_item_math_number($webkit_type_number))) {
								if (strstr($browser_user_agent, 'blink')) {
									$layout_engine_nu_full = get_item_version($browser_user_agent, 'blink');
									$layout_engine_nu = get_item_math_number($layout_engine_nu_full);
								}

								$layout_engine = 'blink';
							}

							$browser_number = get_item_version($browser_user_agent, $browser_name);
							break;
						}
					}

					break;

				default:
					$browser_number = get_item_version($browser_user_agent, $browser_name);
					break;
				}

				$_obf_FHbv_3jBsllr = true;
				break;
			}
		}

		if (!$_obf_FHbv_3jBsllr) {
			$browser_name = substr($browser_user_agent, 0, strcspn($browser_user_agent, '();'));
			if ($browser_name && preg_match('/[^0-9][a-z]*-*\\ *[a-z]*\\ *[a-z]*/', $browser_name, $_obf_nfo6rrzMzcWaYPzO4wJhUI_APQ__)) {
				$browser_name = $_obf_nfo6rrzMzcWaYPzO4wJhUI_APQ__[0];

				if ($browser_name == 'blackberry') {
					get_set_count('set', 0);
				}

				$browser_number = get_item_version($browser_user_agent, $browser_name);
			}
			else {
				$browser_name = 'NA';
				$browser_number = 'NA';
			}
		}

		if ($_obf__NTofMF8GoBl) {
			$_obf_mt27FeIwkPy3 = get_os_data($browser_user_agent, $browser_working, $browser_number);
			$os_type = $_obf_mt27FeIwkPy3[0];
			$os_number = $_obf_mt27FeIwkPy3[1];
		}

		$b_repeat = true;
		$browser_math_number = get_item_math_number($browser_number);

		if ($_obf_Yra3N98SvSNw8Dw8JA__) {
			$mobile_test = check_is_mobile($browser_user_agent);

			if ($mobile_test) {
				$a_mobile_data = get_mobile_data($browser_user_agent);
				$ua_type = 'mobile';
			}
		}
	}

	switch ($which_test) {
	case 'math_number':
		$which_test = 'browser_math_number';
		break;

	case 'number':
		$which_test = 'browser_number';
		break;

	case 'browser':
		$which_test = 'browser_working';
		break;

	case 'moz_version':
		$which_test = 'moz_data';
		break;

	case 'true_msie_version':
		$which_test = 'true_ie_number';
		break;

	case 'type':
		$which_test = 'ua_type';
		break;

	case 'webkit_version':
		$which_test = 'webkit_data';
		break;
	}

	if (!$a_engine_data) {
		$a_engine_data = array($layout_engine, $layout_engine_nu_full, $layout_engine_nu);
	}

	if (!$a_khtml_data) {
		$a_khtml_data = array($khtml_type, $khtml_type_number, $browser_number);
	}

	if (!$a_moz_data) {
		$a_moz_data = array($moz_type, $moz_type_number, $moz_rv, $moz_rv_full, $moz_release_date);
	}

	if (!$a_webkit_data) {
		$a_webkit_data = array($webkit_type, $webkit_type_number, $browser_number);
	}

	if (!$a_trident_data) {
		$a_trident_data = array($trident_type, $trident_type_number, $layout_engine_nu, $browser_number);
	}

	$_obf_psUELaSFshc_ = script_time();

	if ($layout_engine_nu) {
		$html_type = get_html_level($layout_engine, $layout_engine_nu);
	}

	if (!$a_full_assoc_data) {
		$a_full_assoc_data = array('browser_working' => $browser_working, 'browser_number' => $browser_number, 'ie_version' => $ie_version, 'dom' => $b_dom_browser, 'safe' => $b_safe_browser, 'os' => $os_type, 'os_number' => $os_number, 'browser_name' => $browser_name, 'ua_type' => $ua_type, 'browser_math_number' => $browser_math_number, 'moz_data' => $a_moz_data, 'webkit_data' => $a_webkit_data, 'mobile_test' => $mobile_test, 'mobile_data' => $a_mobile_data, 'true_ie_number' => $true_ie_number, 'run_time' => $_obf_psUELaSFshc_, 'html_type' => $html_type, 'engine_data' => $a_engine_data, 'trident_data' => $a_trident_data);
	}

	switch ($which_test) {
	case 'full':
		$_obf_PfZxtqChqptVJPI_ = array($browser_working, $browser_number, $ie_version, $b_dom_browser, $b_safe_browser, $os_type, $os_number, $browser_name, $ua_type, $browser_math_number, $a_moz_data, $a_webkit_data, $mobile_test, $a_mobile_data, $true_ie_number, $_obf_psUELaSFshc_, $html_type, $a_engine_data, $a_trident_data);
		return $_obf_PfZxtqChqptVJPI_;
		break;

	case 'full_assoc':
		return $a_full_assoc_data;
		break;

	case 'header_data':
		if (!headers_sent()) {
			if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml')) {
			}
		}

		break;

	default:
		if (isset($a_full_assoc_data[$which_test])) {
			return $a_full_assoc_data[$which_test];
		}
		else {
			exit('You passed the browser detector an unsupported option for parameter 1: ' . $which_test);
		}

		break;
	}
}

function get_item_math_number($pv_browser_number)
{
	$_obf_IgF5EkLLwc8pv29pKT_NTyIWZA__ = '';
	if ($pv_browser_number && preg_match('/^[0-9]*\\.*[0-9]*/', $pv_browser_number, $_obf_5mSJM4gI_1JcScMo9HK3j_JWiSSV)) {
		$_obf_IgF5EkLLwc8pv29pKT_NTyIWZA__ = $_obf_5mSJM4gI_1JcScMo9HK3j_JWiSSV[0];
	}

	return $_obf_IgF5EkLLwc8pv29pKT_NTyIWZA__;
}

function get_os_data($pv_browser_string, $pv_browser_name, $pv_version_number)
{
	$_obf_jTQIvhAMBuEg1mIeMHam = '';
	$_obf_YV7VjbTBEweooBCyC0SFPnw_ = '';
	$_obf_OjF7BF8_ = array('intel mac', 'OS X', 'ppc mac', 'mac68k');
	$_obf_weMX6_ydXOWcJrgH = array('dragonfly', 'freebsd', 'openbsd', 'netbsd', 'bsd', 'unixware', 'solaris', 'sunos', 'sun4', 'sun5', 'suni86', 'sun', 'irix5', 'irix6', 'irix', 'hpux9', 'hpux10', 'hpux11', 'hpux', 'hp-ux', 'aix1', 'aix2', 'aix3', 'aix4', 'aix5', 'aix', 'sco', 'unixware', 'mpras', 'reliant', 'dec', 'sinix', 'unix');
	$_obf_TOoEodAI9FlBvj8gnqLF = array(' cros ', 'ubuntu', 'kubuntu', 'xubuntu', 'mepis', 'xandros', 'linspire', 'winspire', 'jolicloud', 'sidux', 'kanotix', 'debian', 'opensuse', 'suse', 'fedora', 'redhat', 'slackware', 'slax', 'mandrake', 'mandriva', 'gentoo', 'sabayon', 'linux');
	$_obf_NRZRF_IWapLT1rpRn7BZ = array('i386', 'i586', 'i686', 'x86_64');
	$_obf_T1cZoBJCTVzlZw__ = array('android', 'blackberry', 'iphone', 'palmos', 'palmsource', 'symbian', 'beos', 'os2', 'amiga', 'webtv', 'macintosh', 'mac_', 'mac ', 'nt', 'win', $_obf_weMX6_ydXOWcJrgH, $_obf_TOoEodAI9FlBvj8gnqLF);
	$_obf_8hyjR5U8uQ__ = count($_obf_T1cZoBJCTVzlZw__);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < $_obf_8hyjR5U8uQ__; $_obf_7w__++) {
		$_obf_Snp6H2Q7rllQnCgBZzkO = $_obf_T1cZoBJCTVzlZw__[$_obf_7w__];
		if (!is_array($_obf_Snp6H2Q7rllQnCgBZzkO) && strstr($pv_browser_string, $_obf_Snp6H2Q7rllQnCgBZzkO) && !strstr($pv_browser_string, 'linux')) {
			$_obf_jTQIvhAMBuEg1mIeMHam = $_obf_Snp6H2Q7rllQnCgBZzkO;

			switch ($_obf_jTQIvhAMBuEg1mIeMHam) {
			case 'nt':
				preg_match('/nt ([0-9]+[\\.]?[0-9]?)/', $pv_browser_string, $_obf_2j5uKasXABNp8ngx);

				if (isset($_obf_2j5uKasXABNp8ngx[1])) {
					$_obf_YV7VjbTBEweooBCyC0SFPnw_ = $_obf_2j5uKasXABNp8ngx[1];
				}

				break;

			case 'win':
				if (strstr($pv_browser_string, 'vista')) {
					$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 6;
					$_obf_jTQIvhAMBuEg1mIeMHam = 'nt';
				}
				else if (strstr($pv_browser_string, 'xp')) {
					$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 5.1;
					$_obf_jTQIvhAMBuEg1mIeMHam = 'nt';
				}
				else if (strstr($pv_browser_string, '2003')) {
					$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 5.2;
					$_obf_jTQIvhAMBuEg1mIeMHam = 'nt';
				}
				else if (strstr($pv_browser_string, 'windows ce')) {
					$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 'ce';
					$_obf_jTQIvhAMBuEg1mIeMHam = 'nt';
				}
				else if (strstr($pv_browser_string, '95')) {
					$_obf_YV7VjbTBEweooBCyC0SFPnw_ = '95';
				}
				else {
					if (strstr($pv_browser_string, '9x 4.9') || strstr($pv_browser_string, ' me')) {
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 'me';
					}
					else if (strstr($pv_browser_string, '98')) {
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = '98';
					}
					else if (strstr($pv_browser_string, '2000')) {
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 5;
						$_obf_jTQIvhAMBuEg1mIeMHam = 'nt';
					}
				}

				break;

			case 'mac ':
			case 'mac_':
			case 'macintosh':
				$_obf_jTQIvhAMBuEg1mIeMHam = 'mac';

				if (strstr($pv_browser_string, 'os x')) {
					if (strstr($pv_browser_string, 'os x ')) {
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = str_replace('_', '.', get_item_version($pv_browser_string, 'os x'));
					}
					else {
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 10;
					}
				}
				else {
					if (($pv_browser_name == 'saf') || ($pv_browser_name == 'cam') || (($pv_browser_name == 'moz') && (1.3 <= $pv_version_number)) || (($pv_browser_name == 'ie') && (5.2 <= $pv_version_number))) {
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 10;
					}
				}

				break;

			case 'iphone':
				$_obf_YV7VjbTBEweooBCyC0SFPnw_ = 10;
				break;

			default:
				break;
			}

			break;
		}
		else {
			if (is_array($_obf_Snp6H2Q7rllQnCgBZzkO) && ($_obf_7w__ == $_obf_8hyjR5U8uQ__ - 2)) {
				$_obf_al0CU2ExnQ__ = count($_obf_Snp6H2Q7rllQnCgBZzkO);
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
					if (strstr($pv_browser_string, $_obf_Snp6H2Q7rllQnCgBZzkO[$_obf_XA__])) {
						$_obf_jTQIvhAMBuEg1mIeMHam = 'unix';
						$_obf_YV7VjbTBEweooBCyC0SFPnw_ = ($_obf_Snp6H2Q7rllQnCgBZzkO[$_obf_XA__] != 'unix' ? $_obf_Snp6H2Q7rllQnCgBZzkO[$_obf_XA__] : '');
						break;
					}
				}
			}
			else {
				if (is_array($_obf_Snp6H2Q7rllQnCgBZzkO) && ($_obf_7w__ == $_obf_8hyjR5U8uQ__ - 1)) {
					$_obf_al0CU2ExnQ__ = count($_obf_Snp6H2Q7rllQnCgBZzkO);
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
						if (strstr($pv_browser_string, $_obf_Snp6H2Q7rllQnCgBZzkO[$_obf_XA__])) {
							$_obf_jTQIvhAMBuEg1mIeMHam = 'lin';
							$_obf_YV7VjbTBEweooBCyC0SFPnw_ = ($_obf_Snp6H2Q7rllQnCgBZzkO[$_obf_XA__] != 'linux' ? $_obf_Snp6H2Q7rllQnCgBZzkO[$_obf_XA__] : '');
							break;
						}
					}
				}
			}
		}
	}

	$_obf_mt27FeIwkPy3 = array($_obf_jTQIvhAMBuEg1mIeMHam, $_obf_YV7VjbTBEweooBCyC0SFPnw_);
	return $_obf_mt27FeIwkPy3;
}

function get_item_version($pv_browser_user_agent, $pv_search_string, $pv_b_break_last = '', $pv_extra_search = '')
{
	$_obf_j3FpNVT4hrHpJFfEp6_a4g__ = 15;
	$_obf__MTdqKtLRRbh = 0;
	$_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7 = '';
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < 4; $_obf_7w__++) {
		if (strpos($pv_browser_user_agent, $pv_search_string, $_obf__MTdqKtLRRbh) !== false) {
			$_obf__MTdqKtLRRbh = strpos($pv_browser_user_agent, $pv_search_string, $_obf__MTdqKtLRRbh) + strlen($pv_search_string);
			if (!$pv_b_break_last || ($pv_extra_search && strstr($pv_browser_user_agent, $pv_extra_search))) {
				break;
			}
		}
		else {
			break;
		}
	}

	$_obf__MTdqKtLRRbh += get_set_count('get');
	$_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7 = substr($pv_browser_user_agent, $_obf__MTdqKtLRRbh, $_obf_j3FpNVT4hrHpJFfEp6_a4g__);
	$_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7 = substr($_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7, 0, strcspn($_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7, ' );/'));

	if (!is_numeric(substr($_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7, 0, 1))) {
		$_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7 = '';
	}

	return $_obf_OU6ymmeYXljeiv1Gm0zqqT9XopZ7;
}

function get_set_count($pv_type, $pv_value = '')
{
	static $slice_increment;
	$_obf_RU4qsmi50d29uKYl = '';

	switch ($pv_type) {
	case 'get':
		if (is_null($slice_increment)) {
			$slice_increment = 1;
		}

		$_obf_RU4qsmi50d29uKYl = $slice_increment;
		$slice_increment = 1;
		return $_obf_RU4qsmi50d29uKYl;
		break;

	case 'set':
		$slice_increment = $pv_value;
		break;
	}
}

function check_is_mobile($pv_browser_user_agent)
{
	$_obf_wwwhO7nidlXXBGzsbQAmpssQNw__ = '';
	$_obf_kxKfkBFdfGobn8_FRS50 = array('android', 'blackberry', 'epoc', 'linux armv', 'palmos', 'palmsource', 'windows ce', 'windows phone os', 'symbianos', 'symbian os', 'symbian', 'webos', 'benq', 'blackberry', 'danger hiptop', 'ddipocket', ' droid', 'ipad', 'ipod', 'iphone', 'kindle', 'kobo', 'lge-cx', 'lge-lx', 'lge-mx', 'lge vx', 'lge ', 'lge-', 'lg;lx', 'nexus', 'nintendo wii', 'nokia', 'nook', 'palm', 'pdxgw', 'playstation', 'rim', 'sagem', 'samsung', 'sec-sgh', 'sharp', 'sonyericsson', 'sprint', 'zune', 'j-phone', 'n410', 'mot 24', 'mot-', 'htc-', 'htc_', 'htc ', 'playbook', 'sec-', 'sie-m', 'sie-s', 'spv ', 'touchpad', 'vodaphone', 'smartphone', 'armv', 'midp', 'mobilephone', 'avantgo', 'blazer', 'elaine', 'eudoraweb', 'fennec', 'iemobile', 'minimo', 'mobile safari', 'mobileexplorer', 'opera mobi', 'opera mini', 'netfront', 'opwv', 'polaris', 'puffin', 'semc-browser', 'skyfire', 'up.browser', 'ucweb', 'ucbrowser', 'webpro/', 'wms pie', 'xiino', 'astel', 'docomo', 'novarra-vision', 'portalmmm', 'reqwirelessweb', 'vodafone');
	$_obf_al0CU2ExnQ__ = count($_obf_kxKfkBFdfGobn8_FRS50);
	$_obf_XA__ = 0;

	for (; $_obf_XA__ < $_obf_al0CU2ExnQ__; $_obf_XA__++) {
		if (strstr($pv_browser_user_agent, $_obf_kxKfkBFdfGobn8_FRS50[$_obf_XA__])) {
			if (($_obf_kxKfkBFdfGobn8_FRS50[$_obf_XA__] != 'zune') || strstr($pv_browser_user_agent, 'iemobile')) {
				$_obf_wwwhO7nidlXXBGzsbQAmpssQNw__ = $_obf_kxKfkBFdfGobn8_FRS50[$_obf_XA__];
				break;
			}
		}
	}

	return $_obf_wwwhO7nidlXXBGzsbQAmpssQNw__;
}

function get_mobile_data($pv_browser_user_agent)
{
	$mobile_browser = '';
	$mobile_browser_number = '';
	$mobile_device = '';
	$mobile_device_number = '';
	$mobile_os = '';
	$mobile_os_number = '';
	$mobile_server = '';
	$mobile_server_number = '';
	$mobile_tablet = '';
	$a_mobile_browser = array('avantgo', 'blazer', 'crios', 'elaine', 'eudoraweb', 'fennec', 'iemobile', 'minimo', 'ucweb', 'ucbrowser', 'mobile safari', 'mobileexplorer', 'opera mobi', 'opera mini', 'netfront', 'opwv', 'polaris', 'puffin', 'semc-browser', 'silk', 'steel', 'ultralight', 'up.browser', 'webos', 'webpro/', 'wms pie', 'xiino');
	$a_mobile_device = array('benq', 'blackberry', 'danger hiptop', 'ddipocket', ' droid', 'htc_dream', 'htc espresso', 'htc hero', 'htc halo', 'htc huangshan', 'htc legend', 'htc liberty', 'htc paradise', 'htc supersonic', 'htc tattoo', 'ipad', 'ipod', 'iphone', 'kindle', 'kobo', 'lge-cx', 'lge-lx', 'lge-mx', 'lge vx', 'lg;lx', 'nexus', 'nintendo wii', 'nokia', 'nook', 'palm', 'pdxgw', 'playstation', 'sagem', 'samsung', 'sec-sgh', 'sharp', 'sonyericsson', 'sprint', 'j-phone', 'milestone', 'n410', 'mot 24', 'mot-', 'htc-', 'htc_', 'htc ', 'lge ', 'lge-', 'sec-', 'sie-m', 'sie-s', 'spv ', 'smartphone', 'armv', 'midp', 'mobilephone', 'wp', 'zunehd', 'zune');
	$a_mobile_os = array('android', 'blackberry', 'epoc', 'cpu os', 'iphone os', 'palmos', 'palmsource', 'windows phone os', 'windows ce', 'symbianos', 'symbian os', 'symbian', 'webos', 'linux armv');
	$a_mobile_server = array('astel', 'docomo', 'novarra-vision', 'portalmmm', 'reqwirelessweb', 'vodafone');
	$a_mobile_tablet = array('ipad', 'android 3', ' gt-p', 'kindle', 'kobo', 'nook', 'playbook', 'silk', 'touchpad', ' sch-i');
	$k_count = count($a_mobile_browser);
	$k = 0;

	for (; $k < $k_count; $k++) {
		if (strstr($pv_browser_user_agent, $a_mobile_browser[$k])) {
			$mobile_browser = $a_mobile_browser[$k];
			$mobile_browser_number = get_item_version($pv_browser_user_agent, $mobile_browser);
			break;
		}
	}

	$k_count = count($a_mobile_device);
	$k = 0;

	for (; $k < $k_count; $k++) {
		if (strstr($pv_browser_user_agent, $a_mobile_device[$k])) {
			$mobile_device = trim($a_mobile_device[$k], '-_');

			if ($mobile_device == 'blackberry') {
				get_set_count('set', 0);
			}

			$mobile_device_number = get_item_version($pv_browser_user_agent, $mobile_device);
			$mobile_device = trim($mobile_device);
			break;
		}
	}

	$k_count = count($a_mobile_os);
	$k = 0;

	for (; $k < $k_count; $k++) {
		if (strstr($pv_browser_user_agent, $a_mobile_os[$k])) {
			$mobile_os = $a_mobile_os[$k];

			if ($mobile_os != 'blackberry') {
				$mobile_os_number = str_replace('_', '.', get_item_version($pv_browser_user_agent, $mobile_os));
			}
			else {
				$mobile_os_number = str_replace('_', '.', get_item_version($pv_browser_user_agent, 'version'));

				if (empty($mobile_os_number)) {
					get_set_count('set', 5);
					$mobile_os_number = str_replace('_', '.', get_item_version($pv_browser_user_agent, $mobile_os));
				}
			}

			break;
		}
	}

	$k_count = count($a_mobile_server);
	$k = 0;

	for (; $k < $k_count; $k++) {
		if (strstr($pv_browser_user_agent, $a_mobile_server[$k])) {
			$mobile_server = $a_mobile_server[$k];
			$mobile_server_number = get_item_version($pv_browser_user_agent, $mobile_server);
			break;
		}
	}

	$pattern = '/android[[:space:]]*[4-9]/';
	if (preg_match($pattern, $pv_browser_user_agent) && !stristr($pv_browser_user_agent, 'mobile')) {
		$mobile_tablet = 'android tablet';
	}
	else {
		$k_count = count($a_mobile_tablet);
		$k = 0;

		for (; $k < $k_count; $k++) {
			if (strstr($pv_browser_user_agent, $a_mobile_tablet[$k])) {
				$mobile_tablet = trim($a_mobile_tablet[$k]);
				if (($mobile_tablet == 'gt-p') || ($mobile_tablet == 'sch-i')) {
					$mobile_tablet = 'galaxy-' . $mobile_tablet;
				}
				else if ($mobile_tablet == 'silk') {
					$mobile_tablet = 'kindle fire';
				}

				break;
			}
		}
	}

	if (!$mobile_os && ($mobile_browser || $mobile_device || $mobile_server) && strstr($pv_browser_user_agent, 'linux')) {
		$mobile_os = 'linux';
		$mobile_os_number = get_item_version($pv_browser_user_agent, 'linux');
	}

	$a_mobile_data = array($mobile_device, $mobile_browser, $mobile_browser_number, $mobile_os, $mobile_os_number, $mobile_server, $mobile_server_number, $mobile_device_number, $mobile_tablet);
	return $a_mobile_data;
}

function get_html_level($pv_render_engine, $pv_render_engine_nu)
{
	$_obf_duWAGj6X6P4IpbA_ = 1;
	$_obf_8hbgsvvkUWE5 = $pv_render_engine_nu;
	$_obf_VeE1CJfDe_Dmip3RNQ__ = array('blink' => 10, 'gecko' => 20, 'khtml' => 45, 'presto' => 20, 'trident' => 50, 'webkit' => 5250);
	$_obf_j8bkpHryKfFEvXanNw__ = array('blink' => 10, 'gecko' => 20, 'khtml' => 50, 'presto' => 20, 'trident' => 60, 'webkit' => 5280);
	$_obf_8hbgsvvkUWE5 = intval(10 * floatval($_obf_8hbgsvvkUWE5));
	if (array_key_exists($pv_render_engine, $_obf_j8bkpHryKfFEvXanNw__) && ($_obf_j8bkpHryKfFEvXanNw__[$pv_render_engine] <= $_obf_8hbgsvvkUWE5)) {
		$_obf_duWAGj6X6P4IpbA_ = 3;
	}
	else {
		if (array_key_exists($pv_render_engine, $_obf_VeE1CJfDe_Dmip3RNQ__) && ($_obf_VeE1CJfDe_Dmip3RNQ__[$pv_render_engine] <= $_obf_8hbgsvvkUWE5)) {
			$_obf_duWAGj6X6P4IpbA_ = 2;
		}
	}

	return $_obf_duWAGj6X6P4IpbA_;
}

function script_time()
{
	static $script_time;
	$_obf_KfRGFRyIzWqEafre = '';

	if (5 <= sprintf('%01.1f', phpversion())) {
		if (is_null($script_time)) {
			$script_time = microtime(true);
		}
		else {
			$_obf_KfRGFRyIzWqEafre = microtime(true) - $script_time;
			$_obf_KfRGFRyIzWqEafre = sprintf('%01.8f', $_obf_KfRGFRyIzWqEafre);
			$script_time = NULL;
			return $_obf_KfRGFRyIzWqEafre;
		}
	}
}


?>

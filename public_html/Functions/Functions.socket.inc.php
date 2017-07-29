<?php

//dezend by http://www.yunlu99.com/
function post_data_to_host($url, $data)
{
	$url = parse_url($url);

	if (!$url) {
		return 'couldn\'t parse url';
	}

	if (!isset($url['port'])) {
		$url['port'] = '';
	}

	if (!isset($url['query'])) {
		$url['query'] = '';
	}

	$_obf_Lh_5oYDEsQ__ = '';

	while (list($_obf_5w__, $_obf_6A__) = each($data)) {
		$_obf_Lh_5oYDEsQ__ .= ($_obf_Lh_5oYDEsQ__ ? '&' : '');
		$_obf_Lh_5oYDEsQ__ .= rawurlencode($_obf_5w__) . '=' . iconv('utf-8', 'gbk', $_obf_6A__);
	}

	$_obf_4Honjw__ = ($url['port'] ? $url['port'] : 80);

	if (!function_exists('fsockopen')) {
		if (!function_exists('pfsockopen')) {
			$_obf_YBY_ = stream_socket_client($url['host'] . ':' . $_obf_4Honjw__);
		}
		else {
			$_obf_YBY_ = pfsockopen($url['host'], $_obf_4Honjw__);
		}
	}
	else {
		$_obf_YBY_ = fsockopen($url['host'], $_obf_4Honjw__);
	}

	if (!$_obf_YBY_) {
		return 'Failed to open socket to ' . $url['host'];
	}

	fputs($_obf_YBY_, sprintf('POST %s%s%s HTTP/1.0' . "\n" . '', $url['path'], $url['query'] ? '?' : '', $url['query']));
	fputs($_obf_YBY_, 'Host: ' . $url['host'] . "\n");
	fputs($_obf_YBY_, 'Content-type: application/x-www-form-urlencoded' . "\n" . '');
	fputs($_obf_YBY_, 'Content-length: ' . strlen($_obf_Lh_5oYDEsQ__) . "\n");
	fputs($_obf_YBY_, 'Connection: close' . "\n" . '' . "\n" . '');
	fputs($_obf_YBY_, $_obf_Lh_5oYDEsQ__ . "\n");
	$_obf_CFGoDA__ = fgets($_obf_YBY_, 1024);

	if (!eregi('^HTTP/1\\.. 200', $_obf_CFGoDA__)) {
		return NULL;
	}

	$_obf_IYNgQ9c9nw__ = '';
	$_obf_H7hNj4Ibc28_ = 1;

	while (!feof($_obf_YBY_)) {
		$_obf_CFGoDA__ = fgets($_obf_YBY_, 1024);
		if ($_obf_H7hNj4Ibc28_ && (($_obf_CFGoDA__ == "\n") || ($_obf_CFGoDA__ == "\r\n"))) {
			$_obf_H7hNj4Ibc28_ = 0;
		}
		else if (!$_obf_H7hNj4Ibc28_) {
			$_obf_IYNgQ9c9nw__ .= $_obf_CFGoDA__;
		}
	}

	fclose($_obf_YBY_);
	return $_obf_IYNgQ9c9nw__;
}

function post_gbk_data_to_host($url, $data)
{
	$url = parse_url($url);

	if (!$url) {
		return 'couldn\'t parse url';
	}

	if (!isset($url['port'])) {
		$url['port'] = '';
	}

	if (!isset($url['query'])) {
		$url['query'] = '';
	}

	$_obf_Lh_5oYDEsQ__ = '';

	while (list($_obf_5w__, $_obf_6A__) = each($data)) {
		$_obf_Lh_5oYDEsQ__ .= ($_obf_Lh_5oYDEsQ__ ? '&' : '');
		$_obf_Lh_5oYDEsQ__ .= rawurlencode($_obf_5w__) . '=' . rawurlencode($_obf_6A__);
	}

	$_obf_4Honjw__ = ($url['port'] ? $url['port'] : 80);

	if (!function_exists('fsockopen')) {
		if (!function_exists('pfsockopen')) {
			$_obf_YBY_ = stream_socket_client($url['host'] . ':' . $_obf_4Honjw__);
		}
		else {
			$_obf_YBY_ = pfsockopen($url['host'], $_obf_4Honjw__);
		}
	}
	else {
		$_obf_YBY_ = fsockopen($url['host'], $_obf_4Honjw__);
	}

	if (!$_obf_YBY_) {
		return 'Failed to open socket to ' . $url['host'];
	}

	fputs($_obf_YBY_, sprintf('POST %s%s%s HTTP/1.0' . "\n" . '', $url['path'], $url['query'] ? '?' : '', $url['query']));
	fputs($_obf_YBY_, 'Host: ' . $url['host'] . "\n");
	fputs($_obf_YBY_, 'Content-type: application/x-www-form-urlencoded' . "\n" . '');
	fputs($_obf_YBY_, 'Content-length: ' . strlen($_obf_Lh_5oYDEsQ__) . "\n");
	fputs($_obf_YBY_, 'Connection: close' . "\n" . '' . "\n" . '');
	fputs($_obf_YBY_, $_obf_Lh_5oYDEsQ__ . "\n");
	$_obf_CFGoDA__ = fgets($_obf_YBY_, 1024);

	if (!eregi('^HTTP/1\\.. 200', $_obf_CFGoDA__)) {
		return NULL;
	}

	$_obf_IYNgQ9c9nw__ = '';
	$_obf_H7hNj4Ibc28_ = 1;

	while (!feof($_obf_YBY_)) {
		$_obf_CFGoDA__ = fgets($_obf_YBY_, 1024);
		if ($_obf_H7hNj4Ibc28_ && (($_obf_CFGoDA__ == "\n") || ($_obf_CFGoDA__ == "\r\n"))) {
			$_obf_H7hNj4Ibc28_ = 0;
		}
		else if (!$_obf_H7hNj4Ibc28_) {
			$_obf_IYNgQ9c9nw__ .= $_obf_CFGoDA__;
		}
	}

	fclose($_obf_YBY_);
	return $_obf_IYNgQ9c9nw__;
}

function require_remote_service($rType, $rFileName)
{
	$_obf_uTwxnUyG = &$GLOBALS['Config'];

	if (count($_obf_uTwxnUyG['serverAddress']) != 0) {
		$_obf_dSmbwm7Eieo_ = array();
		$_obf_dSmbwm7Eieo_['type'] = $rType;
		$_obf_dSmbwm7Eieo_['fileName'] = $rFileName;
		$_obf_dSmbwm7Eieo_['key'] = $_obf_uTwxnUyG['socket_token'];
		$_obf_dSmbwm7Eieo_['sign'] = md5($_obf_dSmbwm7Eieo_['type'] . '-' . $_obf_dSmbwm7Eieo_['fileName'] . '-' . $_obf_dSmbwm7Eieo_['key'] . '-' . date('Y-m-d'));

		foreach ($_obf_uTwxnUyG['serverAddress'] as $_obf_erY_mfv_V8E_PgDEnq7_Eg__) {
			$_obf_SXcWBxKm3FoF4A__ = 'http://' . $_obf_erY_mfv_V8E_PgDEnq7_Eg__ . '/RepData/DataService.php';
			post_data_to_host($_obf_SXcWBxKm3FoF4A__, $_obf_dSmbwm7Eieo_);
		}
	}
}


?>

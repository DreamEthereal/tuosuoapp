<?php
//dezend by http://www.yunlu99.com/
function _writeapidata($APIRow, $new_responseID, $csq, $joinTheTime, $endTheTime = 0)
{
	if (trim($APIRow['apiURL']) == '') {
		return true;
	}

	if (strpos(trim($APIRow['apiURL']), '?') === false) {
		$_obf_fqu3XpDYx8Zfcg__ = trim($APIRow['apiURL']) . '?' . $_SERVER['QUERY_STRING'];
	}
	else {
		$_obf_fqu3XpDYx8Zfcg__ = trim($APIRow['apiURL']) . '&' . $_SERVER['QUERY_STRING'];
	}

	$_obf_iFC8707BhQzZ61Z3_N70rJ1B = explode('#', $APIRow['apiVarName']);
	if (isset($_obf_iFC8707BhQzZ61Z3_N70rJ1B[7]) && ($_obf_iFC8707BhQzZ61Z3_N70rJ1B[7] != '')) {
		$_obf_f2cgyv5gMbv33DY_ = explode('|', $_obf_iFC8707BhQzZ61Z3_N70rJ1B[7]);
		$_obf_UGNMt3cWvFPtWWA_ = ($_obf_f2cgyv5gMbv33DY_[0] != '' ? $_obf_f2cgyv5gMbv33DY_[0] : 'c');
		$_obf_JI0m1Z_ENZbB = ($_obf_f2cgyv5gMbv33DY_[1] != '' ? $_obf_f2cgyv5gMbv33DY_[1] : 's');
		$_obf_jkTKk19fzFM_ = ($_obf_f2cgyv5gMbv33DY_[2] != '' ? $_obf_f2cgyv5gMbv33DY_[2] : 'q');
	}
	else {
		$_obf_UGNMt3cWvFPtWWA_ = 'c';
		$_obf_JI0m1Z_ENZbB = 's';
		$_obf_jkTKk19fzFM_ = 'q';
	}

	$_obf_gTjrXlLOoGWhbg__ = 0;

	if ($endTheTime == 0) {
		$endTheTime = time();
	}

	foreach ($_obf_iFC8707BhQzZ61Z3_N70rJ1B as $_obf_OgqLxwAcTxGhrOyzIQ__) {
		$_obf_gTjrXlLOoGWhbg__++;

		switch ($_obf_gTjrXlLOoGWhbg__) {
		case 1:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $APIRow['surveyID'];
			break;

		case 2:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . urlencode($APIRow['surveyName']);
			break;

		case 3:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $APIRow['lang'];
			break;

		case 4:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $new_responseID;
			break;

		case 5:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . date('Y-m-d%20H:i:s', $joinTheTime);
			break;

		case 6:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . date('Y-m-d%20H:i:s', $endTheTime);
			break;

		case 7:
			switch ($csq) {
			case 1:
				$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $_obf_UGNMt3cWvFPtWWA_;
				break;

			case 2:
				$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $_obf_JI0m1Z_ENZbB;
				break;

			case 3:
				$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $_obf_jkTKk19fzFM_;
				break;
			}

			break;
		}
	}

	$_obf_1vYUa9ECyg__ = fopen($_obf_fqu3XpDYx8Zfcg__, 'r');
	fclose($_obf_1vYUa9ECyg__);
}

function _getstatstat($APIRow, $new_responseID, $csq, $joinTheTime, $endTheTime = 0)
{
	$_obf_iFC8707BhQzZ61Z3_N70rJ1B = explode('#', $APIRow['apiVarName']);
	if (isset($_obf_iFC8707BhQzZ61Z3_N70rJ1B[7]) && ($_obf_iFC8707BhQzZ61Z3_N70rJ1B[7] != '')) {
		$_obf_f2cgyv5gMbv33DY_ = explode('|', $_obf_iFC8707BhQzZ61Z3_N70rJ1B[7]);
		$_obf_UGNMt3cWvFPtWWA_ = ($_obf_f2cgyv5gMbv33DY_[0] != '' ? $_obf_f2cgyv5gMbv33DY_[0] : 'c');
		$_obf_JI0m1Z_ENZbB = ($_obf_f2cgyv5gMbv33DY_[1] != '' ? $_obf_f2cgyv5gMbv33DY_[1] : 's');
		$_obf_jkTKk19fzFM_ = ($_obf_f2cgyv5gMbv33DY_[2] != '' ? $_obf_f2cgyv5gMbv33DY_[2] : 'q');
	}
	else {
		$_obf_UGNMt3cWvFPtWWA_ = 'c';
		$_obf_JI0m1Z_ENZbB = 's';
		$_obf_jkTKk19fzFM_ = 'q';
	}

	$_obf_gTjrXlLOoGWhbg__ = 0;
	$endTheTime = time();
	$_obf_fqu3XpDYx8Zfcg__ = '';

	foreach ($_obf_iFC8707BhQzZ61Z3_N70rJ1B as $_obf_OgqLxwAcTxGhrOyzIQ__) {
		$_obf_gTjrXlLOoGWhbg__++;

		switch ($_obf_gTjrXlLOoGWhbg__) {
		case 1:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $APIRow['surveyID'];
			break;

		case 2:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . urlencode($APIRow['surveyName']);
			break;

		case 3:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $APIRow['lang'];
			break;

		case 4:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $new_responseID;
			break;

		case 5:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . date('Y-m-d%20H:i:s', $joinTheTime);
			break;

		case 6:
			$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . date('Y-m-d%20H:i:s', $endTheTime);
			break;

		case 7:
			switch ($csq) {
			case 1:
				$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $_obf_UGNMt3cWvFPtWWA_;
				break;

			case 2:
				$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $_obf_JI0m1Z_ENZbB;
				break;

			case 3:
				$_obf_fqu3XpDYx8Zfcg__ .= '&' . $_obf_OgqLxwAcTxGhrOyzIQ__ . '=' . $_obf_jkTKk19fzFM_;
				break;
			}

			break;
		}
	}

	return $_obf_fqu3XpDYx8Zfcg__;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

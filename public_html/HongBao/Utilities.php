<?php
//dezend by http://www.yunlu99.com/
class CommonUtil
{
	public function genAllUrl($toURL, $paras)
	{
		$_obf_IifGnxA4 = NULL;

		if (NULL == $toURL) {
			exit('toURL is null');
		}

		if (strripos($toURL, '?') == '') {
			$_obf_IifGnxA4 = $toURL . '?' . $paras;
		}
		else {
			$_obf_IifGnxA4 = $toURL . '&' . $paras;
		}

		return $_obf_IifGnxA4;
	}

	public function splitParaStr($src, $token)
	{
		$_obf_jr1hp3cR = array();
		$_obf_AGk1QY4_ = explode($token, $src);

		foreach ($_obf_AGk1QY4_ as $_obf_LQ8UKg__) {
			$_obf_FJi5LqnllJBinlhQ = explode('=', $_obf_LQ8UKg__);

			if ($_obf_FJi5LqnllJBinlhQ != '') {
				$_obf_jr1hp3cR[$_obf_FJi5LqnllJBinlhQ[0]] = $_obf_rk5wFV2ABn7VHn4m_5g_[1];
			}
		}

		return $_obf_jr1hp3cR;
	}

	static public function trimString($value)
	{
		$_obf_Xtyr = NULL;

		if (NULL != $value) {
			$_obf_Xtyr = $value;

			if (strlen($_obf_Xtyr) == 0) {
				$_obf_Xtyr = NULL;
			}
		}

		return $_obf_Xtyr;
	}

	public function formatQueryParaMap($paraMap, $urlencode)
	{
		$_obf_8caxIg__ = '';
		ksort($paraMap);

		foreach ($paraMap as $_obf_5w__ => $_obf_6A__) {
			if ((NULL != $_obf_6A__) && ('null' != $_obf_6A__) && ('sign' != $_obf_5w__)) {
				if ($urlencode) {
					$_obf_6A__ = urlencode($_obf_6A__);
				}

				$_obf_8caxIg__ .= $_obf_5w__ . '=' . $_obf_6A__ . '&';
			}
		}

		if (0 < strlen($_obf_8caxIg__)) {
			$_obf_NpceJEYA = substr($_obf_8caxIg__, 0, strlen($_obf_8caxIg__) - 1);
		}

		return $_obf_NpceJEYA;
	}

	public function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$_obf_8caxIg__ = '';
		ksort($paraMap);

		foreach ($paraMap as $_obf_5w__ => $_obf_6A__) {
			if ($urlencode) {
				$_obf_6A__ = urlencode($_obf_6A__);
			}

			$_obf_8caxIg__ .= strtolower($_obf_5w__) . '=' . $_obf_6A__ . '&';
		}

		if (0 < strlen($_obf_8caxIg__)) {
			$_obf_NpceJEYA = substr($_obf_8caxIg__, 0, strlen($_obf_8caxIg__) - 1);
		}

		return $_obf_NpceJEYA;
	}

	public function arrayToXml($arr)
	{
		$_obf_dw4x = '<xml>';

		foreach ($arr as $_obf_Vwty => $_obf_TAxu) {
			if (is_numeric($_obf_TAxu)) {
				$_obf_dw4x .= '<' . $_obf_Vwty . '>' . $_obf_TAxu . '</' . $_obf_Vwty . '>';
			}
			else {
				$_obf_dw4x .= '<' . $_obf_Vwty . '><![CDATA[' . $_obf_TAxu . ']]></' . $_obf_Vwty . '>';
			}
		}

		$_obf_dw4x .= '</xml>';
		return $_obf_dw4x;
	}
}


?>

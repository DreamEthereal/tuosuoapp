<?php
//dezend by http://www.yunlu99.com/
function unescape($str)
{
	$_obf_Xtyr = '';
	$_obf_mc2H = strlen($str);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < $_obf_mc2H; $_obf_7w__++) {
		if (($str[$_obf_7w__] == '%') && ($str[$_obf_7w__ + 1] == 'u')) {
			$_obf_TAxu = hexdec(substr($str, $_obf_7w__ + 2, 4));

			if ($_obf_TAxu < 127) {
				$_obf_Xtyr .= chr($_obf_TAxu);
			}
			else if ($_obf_TAxu < 2048) {
				$_obf_Xtyr .= chr(192 | ($_obf_TAxu >> 6)) . chr(128 | ($_obf_TAxu & 63));
			}
			else {
				$_obf_Xtyr .= chr(224 | ($_obf_TAxu >> 12)) . chr(128 | (($_obf_TAxu >> 6) & 63)) . chr(128 | ($_obf_TAxu & 63));
			}

			$_obf_7w__ += 5;
		}
		else if ($str[$_obf_7w__] == '%') {
			$_obf_Xtyr .= urldecode(substr($str, $_obf_7w__, 3));
			$_obf_7w__ += 2;
		}
		else {
			$_obf_Xtyr .= $str[$_obf_7w__];
		}
	}

	return $_obf_Xtyr;
}

function escape($str)
{
	preg_match_all('/[Â-ß][€-¿]+|[à-ï][€-¿]{2}|[ð-ÿ][€-¿]{3}|[-]+/e', $str, $_obf_OQ__);
	$str = $_obf_OQ__[0];
	$A = count($str);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < $A; $_obf_7w__++) {
		$_obf_VgKtFeg_ = ord($str[$_obf_7w__][0]);

		if ($_obf_VgKtFeg_ < 223) {
			$str[$_obf_7w__] = rawurlencode(utf8_decode($str[$_obf_7w__]));
		}
		else {
			$str[$_obf_7w__] = '%u' . strtoupper(bin2hex(iconv('UTF-8', 'UCS-2', $str[$_obf_7w__])));
		}
	}

	return join('', $str);
}


?>

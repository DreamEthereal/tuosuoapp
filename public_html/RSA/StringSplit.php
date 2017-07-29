<?php
//dezend by http://www.yunlu99.com/
function php_compat_str_split($string, $split_length = 1)
{
	if (!is_scalar($split_length)) {
		user_error('str_split() expects parameter 2 to be long, ' . gettype($split_length) . ' given', 512);
		return false;
	}

	$split_length = (int) $split_length;

	if ($split_length < 1) {
		user_error('str_split() The length of each segment must be greater than zero', 512);
		return false;
	}

	if ($split_length < 65536) {
		preg_match_all('/.{1,' . $split_length . '}/s', $string, $_obf_8UmnTppRcA__);
		return $_obf_8UmnTppRcA__[0];
	}
	else {
		$_obf_Jrp1 = array();
		$_obf_Fv_U = 0;
		$_obf_LmSv = 0;
		$_obf_mc2H = strlen($string);

		while (0 < $_obf_mc2H) {
			$_obf__Dar = ($_obf_mc2H < $split_length ? $_obf_mc2H : $split_length);
			$_obf_Jrp1[$_obf_Fv_U++] = substr($string, $_obf_LmSv, $_obf__Dar);
			$_obf_LmSv += $_obf__Dar;
			$_obf_mc2H -= $_obf__Dar;
		}

		return $_obf_Jrp1;
	}
}


?>

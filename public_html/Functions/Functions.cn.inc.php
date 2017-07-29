<?php
//dezend by http://www.yunlu99.com/
function cnsubstr($str, $start = 0, $end = 0, $flag = 0)
{
	$_obf_u_c_ = chr(127);
	$_obf_8w__ = array('/[-þ]([-þ]|[@-þ])/', '/[-w]/');
	$_obf_OQ__ = array('', '');

	if (2 < func_num_args()) {
		$end = func_get_arg(2);
	}
	else {
		$end = strlen($str);
	}

	if ($start < 0) {
		$start += $end;
	}

	if (0 < $start) {
		$p = substr($str, 0, $start);

		if ($_obf_u_c_ < $p[strlen($p) - 1]) {
			$p = preg_replace($_obf_8w__, $_obf_OQ__, $p);
			$start += strlen($p);
		}
	}

	$p = substr($str, $start, $end * 2);
	$end = strlen($p);

	if ($_obf_u_c_ < $p[$end - 1]) {
		$p = preg_replace($_obf_8w__, $_obf_OQ__, $p);
		$end += strlen($p);
	}

	$_obf_4VWt = substr($str, $start, $end);
	if (($end < strlen($str)) && ($flag == 1)) {
		$_obf_4VWt .= '...';
	}

	return $_obf_4VWt;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

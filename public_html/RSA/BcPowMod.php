<?php
//dezend by http://www.yunlu99.com/
function php_compat_bc_pow_mod($x, $y, $modulus, $scale = 0)
{
	if (!is_scalar($x)) {
		user_error('bcpowmod() expects parameter 1 to be string, ' . gettype($x) . ' given', 512);
		return false;
	}

	if (!is_scalar($y)) {
		user_error('bcpowmod() expects parameter 2 to be string, ' . gettype($y) . ' given', 512);
		return false;
	}

	if (!is_scalar($modulus)) {
		user_error('bcpowmod() expects parameter 3 to be string, ' . gettype($modulus) . ' given', 512);
		return false;
	}

	if (!is_scalar($scale)) {
		user_error('bcpowmod() expects parameter 4 to be integer, ' . gettype($scale) . ' given', 512);
		return false;
	}

	$_obf_lw__ = '1';

	while (bccomp($y, '0')) {
		if (bccomp(bcmod($y, '2'), '0')) {
			$_obf_lw__ = bcmod(bcmul($_obf_lw__, $x), $modulus);
			$y = bcsub($y, '1');
		}

		$x = bcmod(bcmul($x, $x), $modulus);
		$y = bcdiv($y, '2');
	}

	return $_obf_lw__;
}


?>

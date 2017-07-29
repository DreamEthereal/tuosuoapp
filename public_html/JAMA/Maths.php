<?php
//dezend by http://www.yunlu99.com/
function hypo($a, $b)
{
	if (abs($b) < abs($a)) {
		$_obf_OQ__ = $b / $a;
		$_obf_OQ__ = abs($a) * sqrt(1 + ($_obf_OQ__ * $_obf_OQ__));
	}
	else if ($b != 0) {
		$_obf_OQ__ = $a / $b;
		$_obf_OQ__ = abs($b) * sqrt(1 + ($_obf_OQ__ * $_obf_OQ__));
	}
	else {
		$_obf_OQ__ = 0;
	}

	return $_obf_OQ__;
}


?>

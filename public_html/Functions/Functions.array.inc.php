<?php
//dezend by http://www.yunlu99.com/
function arraydiff($array_1, $array_2)
{
	$array_2 = array_flip($array_2);

	foreach ($array_1 as $_obf_Vwty => $_obf_LQ8UKg__) {
		if (isset($array_2[$_obf_LQ8UKg__])) {
			unset($array_1[$_obf_Vwty]);
		}
	}

	return $array_1;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

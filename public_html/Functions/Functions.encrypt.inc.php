<?php
//dezend by http://www.yunlu99.com/
function encrypt($string = '', $skey = 'enableq')
{
	$_obf__CcfMFHI = str_split(base64_encode($string));
	$_obf_b3z1FMsMGC0_ = count($_obf__CcfMFHI);

	foreach (str_split($skey) as $_obf_Vwty => $_obf_VgKtFeg_) {
		($_obf_Vwty < $_obf_b3z1FMsMGC0_) && ($_obf__CcfMFHI[$_obf_Vwty] .= $_obf_VgKtFeg_);
	}

	return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $_obf__CcfMFHI));
}

function decrypt($string = '', $skey = 'enableq')
{
	$_obf__CcfMFHI = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
	$_obf_b3z1FMsMGC0_ = count($_obf__CcfMFHI);

	foreach (str_split($skey) as $_obf_Vwty => $_obf_VgKtFeg_) {
		($_obf_Vwty <= $_obf_b3z1FMsMGC0_) && ($_obf__CcfMFHI[$_obf_Vwty][1] === $_obf_VgKtFeg_) && $_obf__CcfMFHI[$_obf_Vwty] = $_obf__CcfMFHI[$_obf_Vwty][0];
	}

	return base64_decode(join('', $_obf__CcfMFHI));
}


?>

<?php
//dezend by http://www.yunlu99.com/
function _getserveripaddress()
{
	if (isset($_SERVER)) {
		if ($_SERVER['SERVER_ADDR'] != '') {
			$_obf_Q1tIgXk5b9Ug = $_SERVER['SERVER_ADDR'];
		}
		else if ($_SERVER['LOCAL_ADDR'] != '') {
			$_obf_Q1tIgXk5b9Ug = $_SERVER['LOCAL_ADDR'];
		}
		else {
			$_obf_Q1tIgXk5b9Ug = gethostbyname($_SERVER['SERVER_NAME']);
		}
	}
	else if (getenv('SERVER_ADDR')) {
		$_obf_Q1tIgXk5b9Ug = getenv('SERVER_ADDR');
	}
	else if (getenv('LOCAL_ADDR')) {
		$_obf_Q1tIgXk5b9Ug = getenv('LOCAL_ADDR');
	}
	else {
		$_obf_Q1tIgXk5b9Ug = gethostbyname(getenv('SERVER_NAME'));
	}

	return $_obf_Q1tIgXk5b9Ug;
}

function f9391164fhdd20582371460dbe4fbae86fghk20100930()
{
	return true;
	global $Config;
	global $License;
	global $ItEnable;
	$_obf_kxit1jmP_1acrQ__ = '';

	foreach ($License as $_obf_QpVL1e_pe1UobPrH) {
		$_obf_kxit1jmP_1acrQ__ .= $_obf_QpVL1e_pe1UobPrH . '-';
	}

	if (md5($_obf_kxit1jmP_1acrQ__ . $Config['LicenseCharText'] . '-' . $Config['LicenseKey'] . '-' . $Config['key_d'] . '-' . $Config['key_n']) != fd85a91d5abeba05e78b178ed412c814fck20100930($ItEnable['License'])) {
		return false;
	}

	return true;
}

function write_to_file($file_name, $data, $method = 'w')
{
	$_obf_wWFgG_EIcg__ = fopen($file_name, $method);
	flock($_obf_wWFgG_EIcg__, LOCK_EX);
	$_obf_fPX93OEFX6y0 = fwrite($_obf_wWFgG_EIcg__, $data);
	fclose($_obf_wWFgG_EIcg__);
	return $_obf_fPX93OEFX6y0;
}

include_once ROOT_PATH . 'Functions/Functions.extend.inc.php';

?>

<?php
//dezend by http://www.yunlu99.com/
function dateAdd($interval, $number, $date)
{
	$_obf_dRDdIxfHB5VA4ILoFrM4 = getdate($date);
	$_obf_G6KopQA_ = $_obf_dRDdIxfHB5VA4ILoFrM4['hours'];
	$_obf_CWa_uXufcA__ = $_obf_dRDdIxfHB5VA4ILoFrM4['minutes'];
	$_obf_C1vtRojMsA__ = $_obf_dRDdIxfHB5VA4ILoFrM4['seconds'];
	$_obf_GVmXNr0_ = $_obf_dRDdIxfHB5VA4ILoFrM4['mon'];
	$_obf_BuoE = $_obf_dRDdIxfHB5VA4ILoFrM4['mday'];
	$_obf__aimHg__ = $_obf_dRDdIxfHB5VA4ILoFrM4['year'];

	switch ($interval) {
	case 'yyyy':
		$_obf__aimHg__ += $number;
		break;

	case 'q':
		$_obf_GVmXNr0_ += $number * 3;
		break;

	case 'm':
		$_obf_GVmXNr0_ += $number;
		break;

	case 'y':
	case 'd':
	case 'w':
		$_obf_BuoE += $number;
		break;

	case 'ww':
		$_obf_BuoE += $number * 7;
		break;

	case 'h':
		$_obf_G6KopQA_ += $number;
		break;

	case 'n':
		$_obf_CWa_uXufcA__ += $number;
		break;

	case 's':
		$_obf_C1vtRojMsA__ += $number;
		break;
	}

	$_obf_o4_4PtANwFB4 = mktime($_obf_G6KopQA_, $_obf_CWa_uXufcA__, $_obf_C1vtRojMsA__, $_obf_GVmXNr0_, $_obf_BuoE, $_obf__aimHg__);
	return $_obf_o4_4PtANwFB4;
}

function dateDiff($interval, $date1, $date2)
{
	$_obf_GikmP3L3bXixVsC0Ooo_ = $date2 - $date1;

	switch ($interval) {
	case 'w':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 604800);
		break;

	case 'd':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 86400);
		break;

	case 'h':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 3600);
		break;

	case 'n':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 60);
		break;

	case 's':
		$_obf_cG4B7L_a = $_obf_GikmP3L3bXixVsC0Ooo_;
		break;
	}

	return $_obf_cG4B7L_a;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

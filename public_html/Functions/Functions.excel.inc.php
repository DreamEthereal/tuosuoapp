<?php
//dezend by http://www.yunlu99.com/
function xlsbof()
{
	echo pack('ssssss', 2057, 8, 0, 16, 0, 0);
	return NULL;
}

function xlseof()
{
	echo pack('ss', 10, 0);
	return NULL;
}

function _obf_Ej0_PGU7M24ZKTdnL3Q_($Row, $Col, $Value)
{
	echo pack('sssss', 515, 14, $Row, $Col, 0);
	echo pack('d', $Value);
	return NULL;
}

function xlswritelabel($Row, $Col, $Value)
{
	$_obf_ng__ = strlen($Value);
	echo pack('ssssss', 516, 8 + $_obf_ng__, $Row, $Col, 0, $_obf_ng__);
	echo $Value;
	return NULL;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

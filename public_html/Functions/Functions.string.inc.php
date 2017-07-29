<?php
//dezend by http://www.yunlu99.com/
function qnoscriptstring($string)
{
	$string = str_replace('<=', '&lt;=', $string);
	$string = strip_tags($string, '<img><embed>');
	$string = addslashes($string);
	$string = str_replace('&quot;', '"', $string);
	$string = str_replace('&lt;=', '<=', $string);
	return trim($string);
}

function qnospecialchar($string)
{
	$string = str_replace('&quot;', '"', $string);
	$string = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $string);
	$string = str_replace('\\r', '', $string);
	return $string;
}

function qshowquotestring($string)
{
	$string = str_replace('"', '&#34;', $string);
	$string = str_replace('\'', '&#39;', $string);
	return $string;
}

function qshowquotechar($string)
{
	$string = str_replace('&quot;', '"', $string);
	return $string;
}

function qshowexportquotechar($string)
{
	$string = strip_tags($string);
	$string = str_replace('"', '""', $string);
	$string = str_replace('&quot;', '""', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

function qnl2br($string, $force = 0)
{
	if (is_array($string)) {
		foreach ($string as $_obf_Vwty => $_obf_TAxu) {
			$string[$_obf_Vwty] = qnl2br($_obf_TAxu);
		}
	}
	else {
		$string = nl2br($string);
		$string = str_replace("\r", '', $string);
		$string = str_replace("\n", '', $string);
	}

	return $string;
}

function qbr2nl($string)
{
	if (is_array($string)) {
		foreach ($string as $_obf_Vwty => $_obf_TAxu) {
			$string[$_obf_Vwty] = qbr2nl($_obf_TAxu);
		}
	}
	else {
		$string = preg_replace('/\\<br(\\s*)?\\/?\\>/i', "\n", $string);
	}

	return $string;
}

function qhtmlspecialchars($string)
{
	if (is_array($string)) {
		foreach ($string as $_obf_Vwty => $_obf_TAxu) {
			$string[$_obf_Vwty] = qhtmlspecialchars($_obf_TAxu);
		}
	}
	else {
		$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);

		if (strpos($string, '&amp;#') !== false) {
			$string = preg_replace('/&amp;((#(\\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
	}

	return $string;
}

function qaddslashes($string, $force = 0)
{
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if (!MAGIC_QUOTES_GPC || $force) {
		if (is_array($string)) {
			foreach ($string as $_obf_Vwty => $_obf_TAxu) {
				$string[$_obf_Vwty] = qaddslashes($_obf_TAxu, $force);
			}
		}
		else {
			$string = addslashes($string);
		}
	}

	return $string;
}

function taddslashes($string)
{
	return str_replace('&quot;', '\\&quot;', addslashes($string));
}

function qquotetostring($string)
{
	if (is_array($string)) {
		foreach ($string as $_obf_Vwty => $_obf_TAxu) {
			$string[$_obf_Vwty] = qquotetostring($_obf_TAxu);
		}
	}
	else {
		$string = str_replace('"', '&quot;', $string);
		$string = str_replace('\\', '\\\\', $string);
	}

	return $string;
}

function qquoteconvertstring($string)
{
	global $oldSurveyID;
	global $newSurveyID;
	global $FullPath;

	if (is_array($string)) {
		foreach ($string as $_obf_Vwty => $_obf_TAxu) {
			$string[$_obf_Vwty] = qquoteconvertstring($_obf_TAxu);
		}
	}
	else {
		$string = str_replace('&quot;', '"', $string);
		$string = str_replace('{#d' . $oldSurveyID . '#}', 'd' . $newSurveyID, $string);
		$string = str_replace('{#PerUserData#}', $FullPath . 'PerUserData', $string);
	}

	return $string;
}

function qnohtmltag($htmlName, $flag = 0)
{
	$htmlName = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $htmlName);

	if ($flag == 1) {
		$htmlName = str_replace('\\r', '', $htmlName);
	}

	return trim($htmlName);
}

function qlisttag($htmlName, $flag = 0)
{
	$htmlName = str_replace(array('<', '>'), array('&lt;', '&gt;'), $htmlName);

	if ($flag == 1) {
		$htmlName = str_replace('\\r', '', $htmlName);
	}

	return trim($htmlName);
}

function qnoreturnchar($htmlName)
{
	$htmlName = str_replace("\n", '', $htmlName);
	$htmlName = str_replace("\r", '', $htmlName);
	return trim($htmlName);
}

function qshowmessajax($htmlName, $flag = 0)
{
	$htmlName = str_replace(array('&', '"'), array('&amp;', '&quot;'), $htmlName);

	if ($flag == 1) {
		$htmlName = str_replace('\\r', '', $htmlName);
	}

	return trim($htmlName);
}

function qshowchartname($string)
{
	$string = strip_tags($string);
	$string = str_replace('&quot;', '', $string);
	$string = str_replace('\\', '', $string);
	$string = str_replace('\\r', '', $string);
	return $string;
}

function qshowchartqtnname($string)
{
	$string = qnohtmltag(qshowquotechar($string), 1);
	return $string;
}

function qcrossqtnname($string)
{
	return str_replace('&amp;', '&', qnospecialchar(strip_tags($string)));
}

function qimportstring($string)
{
	$string = strip_tags($string);
	$string = str_replace('&quot;', '""', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

function _getdomainfromurl($URL)
{
	$_obf_ZsbR6YinmvTQBQ__ = parse_url($URL);
	$_obf_OGC2QaZq9vaULw__ = $_obf_ZsbR6YinmvTQBQ__['host'];
	return $_obf_OGC2QaZq9vaULw__;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

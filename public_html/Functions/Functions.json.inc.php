<?php
//dezend by http://www.yunlu99.com/
function php_json_decode($json)
{
	$json = str_replace(array('\\\\', '\\"'), array('&#92;', '&#34;'), $json);
	$parts = preg_split('@("[^"]*")|([\\[\\]\\{\\},:])|\\s@is', $json, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

	foreach ($parts as $index => $part) {
		if (strlen($part) == 1) {
			switch ($part) {
			case '[':
			case '{':
				$parts[$index] = 'array(';
				break;

			case ']':
			case '}':
				$parts[$index] = ')';
				break;

			case ':':
				$parts[$index] = '=>';
				break;

			case ',':
				break;

			default:
				return NULL;
			}
		}
		else {
			if ((substr($part, 0, 1) != '"') || (substr($part, -1, 1) != '"')) {
				return NULL;
			}
		}
	}

	$json = str_replace(array('&#92;', '&#34;', '$'), array('\\\\', '\\"', '\\$'), implode('', $parts));
	return eval ('return ' . $json . ';');
}

function php_json_decode_main($json)
{
	$comment = false;
	$out = '$x=';
	$i = 0;

	for (; $i < strlen($json); $i++) {
		if (!$comment) {
			if (($json[$i] == '{') || ($json[$i] == '[')) {
				$out .= ' array(';
			}
			else {
				if (($json[$i] == '}') || ($json[$i] == ']')) {
					$out .= ')';
				}
				else if ($json[$i] == ':') {
					$out .= '=>';
				}
				else {
					$out .= $json[$i];
				}
			}
		}
		else {
			$out .= $json[$i];
		}

		if (($json[$i] == '"') && ($json[$i - 1] != '\\')) {
			$comment = !$comment;
		}
	}

	eval ($out . ';');
	return $x;
}

function getdbstring($string)
{
	$string = str_replace('\\\\b', '\\b', $string);
	$string = str_replace('\\	', '	', $string);
	$string = str_replace('\\' . "\r" . '', "\r", $string);
	$string = str_replace('\\' . "\n" . '', "\n", $string);
	$string = str_replace('\\f', '', $string);
	$string = str_replace('&apos;', '\\\'', $string);
	$string = str_replace('&quot;', '"', $string);
	$string = str_replace('&lt;', '<', $string);
	$string = str_replace('&gt;', '>', $string);
	$string = str_replace('&amp;', '&', $string);
	return $string;
}

function json($array)
{
	$_obf_LB6BqQ__ = php_json_encode($array);
	return urldecode($_obf_LB6BqQ__);
}

function json_replace($sourceStr)
{
	global $theSID;
	$sourceStr = getlocalresources($sourceStr, $theSID);
	return json_string($sourceStr);
}

function json_string($sourceStr)
{
	$sourceStr = str_replace('\\', '\\\\', $sourceStr);
	$sourceStr = str_replace('\\b', '\\\\b', $sourceStr);
	$sourceStr = str_replace('	', '\\	', $sourceStr);
	$sourceStr = str_replace("\n", '\\' . "\n" . '', $sourceStr);
	$sourceStr = str_replace('', '\\', $sourceStr);
	$sourceStr = str_replace("\r", '\\' . "\r" . '', $sourceStr);
	$sourceStr = str_replace('"', '\\"', $sourceStr);
	return urlencode($sourceStr);
}

function php_json_encode($data)
{
	switch ($_obf_LeS8hw__ = gettype($data)) {
	case 'NULL':
		return 'null';
	case 'boolean':
		return $data ? 'true' : 'false';
	case 'integer':
	case 'double':
	case 'float':
		return $data;
	case 'string':
		return '"' . addslashes($data) . '"';
	case 'object':
		$data = get_object_vars($data);
	case 'array':
		$_obf_Tao6wHcYmUV4KRCf7ihoqEyr = 0;
		$_obf_xluG_NU80nIh2ud5fF0_ = array();
		$_obf_Q0l9ogKFTfHx1Xt_bFRJr1pZ = array();

		foreach ($data as $_obf_Vwty => $_obf_VgKtFeg_) {
			$_obf_xluG_NU80nIh2ud5fF0_[] = php_json_encode($_obf_VgKtFeg_);
			$_obf_Q0l9ogKFTfHx1Xt_bFRJr1pZ[] = php_json_encode($_obf_Vwty) . ':' . php_json_encode($_obf_VgKtFeg_);
			if (($_obf_Tao6wHcYmUV4KRCf7ihoqEyr !== NULL) && ($_obf_Tao6wHcYmUV4KRCf7ihoqEyr++ !== $_obf_Vwty)) {
				$_obf_Tao6wHcYmUV4KRCf7ihoqEyr = NULL;
			}
		}

		if ($_obf_Tao6wHcYmUV4KRCf7ihoqEyr !== NULL) {
			return '[' . implode(',', $_obf_xluG_NU80nIh2ud5fF0_) . ']';
		}
		else {
			return '{' . implode(',', $_obf_Q0l9ogKFTfHx1Xt_bFRJr1pZ) . '}';
		}
	default:
		return '';
	}
}

function findtreechild($ar, $id = 'id', $pid = 'pid')
{
	$_obf_0YX3Ek2kgcBtWXqSRQ__ = array();

	foreach ($ar as $_obf_5w__ => $_obf_LQ8UKg__) {
		if ($_obf_LQ8UKg__[$pid]) {
			$ar[$_obf_LQ8UKg__[$pid]]['child'][$_obf_LQ8UKg__[$id]] = &$ar[$_obf_5w__];
			$_obf_0YX3Ek2kgcBtWXqSRQ__[] = $_obf_5w__;
		}
	}

	foreach ($_obf_0YX3Ek2kgcBtWXqSRQ__ as $_obf_juwe) {
		unset($ar[$_obf_juwe]);
	}

	return $ar;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

<?php
//dezend by http://www.yunlu99.com/
function _fgetcsv(&$handle, $length = NULL, $d = ',', $e = '"')
{
	$d = preg_quote($d);
	$e = preg_quote($e);
	$_line = '';
	$eof = false;

	while ($eof != true) {
		$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
		$itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);

		if (($itemcnt % 2) == 0) {
			$eof = true;
		}
		else {
			break;
		}
	}

	$_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
	$_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
	preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
	$_csv_data = $_csv_matches[1];
	$_csv_i = 0;

	for (; $_csv_i < count($_csv_data); $_csv_i++) {
		$_csv_data[$_csv_i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1', $_csv_data[$_csv_i]);
		$_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
	}

	return empty($_line) ? false : $_csv_data;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

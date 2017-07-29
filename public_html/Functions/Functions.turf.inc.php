<?php
//dezend by http://www.yunlu99.com/
function combination($arr, $m = 1)
{
	$result = array();

	if ($m == 1) {
		return $arr;
	}

	if ($m == count($arr)) {
		$result[] = implode(',', $arr);
		return $result;
	}

	$temp_firstelement = $arr[0];
	unset($arr[0]);
	$arr = array_values($arr);
	$temp_list1 = combination($arr, $m - 1);

	foreach ($temp_list1 as $s) {
		$s = $temp_firstelement . ',' . $s;
		$result[] = $s;
	}

	unset($temp_list1);
	$temp_list2 = combination($arr, $m);

	foreach ($temp_list2 as $s) {
		$result[] = $s;
	}

	unset($temp_list2);
	return $result;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>

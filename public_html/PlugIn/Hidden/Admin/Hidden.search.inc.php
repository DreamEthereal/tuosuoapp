<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '"><option value=1>���� =</option><option value=2>С�� <</option><option value=3 selected>С�ڵ��� <=</option><option value=4>���� ></option><option value=5>���ڵ��� >=</option><option value=6>������ !=</option><option value=7>����</option></select>&nbsp;';
$optionList .= '&nbsp;<input name="queryValue_' . $questionID . '_' . $thisNo . '" id="queryValue_' . $questionID . '_' . $thisNo . '" size=20>';
echo $optionList;

?>

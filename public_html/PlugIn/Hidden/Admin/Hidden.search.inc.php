<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '"><option value=1>等于 =</option><option value=2>小于 <</option><option value=3 selected>小于等于 <=</option><option value=4>大于 ></option><option value=5>大于等于 >=</option><option value=6>不等于 !=</option><option value=7>包含</option></select>&nbsp;';
$optionList .= '&nbsp;<input name="queryValue_' . $questionID . '_' . $thisNo . '" id="queryValue_' . $questionID . '_' . $thisNo . '" size=20>';
echo $optionList;

?>

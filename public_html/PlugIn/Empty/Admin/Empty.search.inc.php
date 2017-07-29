<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" size=3><option value="1" selected>µÈÓÚ</option></select>';
$optionList .= '&nbsp;<select name="queryValue_' . $questionID . '_' . $thisNo . '" id="queryValue_' . $questionID . '_' . $thisNo . '" size=3><option value="1">True</option><option value="0">False</option></select>';
echo $optionList;

?>

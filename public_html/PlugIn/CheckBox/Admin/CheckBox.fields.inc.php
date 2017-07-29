<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$this_fields_list .= 'option_' . $questionID . '|';
$this_fileds_type .= 'multichar|';
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	$this_fileds_type .= 'otherchar|';
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_info'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";
$content .= qshowquotechar(str_replace("\n", '', str_replace("\r", '', $InfoListArray[$questionID]['optionName']))) . "\r\n";

?>

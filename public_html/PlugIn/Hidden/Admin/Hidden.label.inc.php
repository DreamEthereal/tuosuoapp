<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['hiddenVarName'] != '') {
	$VarName = $theQtnArray['hiddenVarName'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$content .= ' VARIABLE LABELS ' . $VarName . ' \'' . qconverionlabel($theQtnArray['questionName']) . '\'.' . "\r\n" . '';

?>

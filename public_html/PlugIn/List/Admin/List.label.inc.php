<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$i = 1;

for (; $i <= $theQtnArray['rows']; $i++) {
	$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . $i . '\'.' . "\r\n" . '';
}

?>

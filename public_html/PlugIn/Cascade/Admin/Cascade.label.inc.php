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

$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $theQtnArray['maxSize']; $i++) {
	$tmp = $i - 1;
	$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theUnitText[$tmp]) . '\'.' . "\r\n" . '';
	$content .= ' VALUE LABELS ' . $VarName . '_' . $i . ' ';

	foreach ($CascadeArray[$questionID] as $nodeID => $theQuestionArray) {
		if ($theQuestionArray['level'] == $i) {
			$content .= $nodeID . ' \'' . qconverionlabel($theQuestionArray['nodeName']) . '\' ';
		}
	}

	$content .= '.' . "\r\n" . '';
}

?>

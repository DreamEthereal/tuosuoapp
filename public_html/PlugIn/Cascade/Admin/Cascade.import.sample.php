<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $QtnListArray[$questionID]['maxSize']; $i++) {
	$tmp = $i - 1;
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theUnitText[$tmp]));
	$theXlsCols = 0;
	$count = 1;
	$theNodeName = '';

	if ($Config['cascade_in_excel'] == 1) {
		foreach ($CascadeArray[$questionID] as $nodeID => $theQuestionArray) {
			if ($theQuestionArray['level'] == $i) {
				$theNodeName .= $nodeID . ':' . qimportstring($theQuestionArray['nodeName']) . ';';

				if (($count % $Config['max_excel_col']) == 0) {
					xlswritelabel($xlsRow, 2 + $theXlsCols, substr($theNodeName, 0, -1));
					$theXlsCols++;
					$theNodeName = '';
				}

				$count++;
			}
		}
	}
	else {
		xlswritelabel($xlsRow, 2, '变量说明可能过长超过Excel限制，请咨询原问卷设计员变量释义');
	}
}

?>

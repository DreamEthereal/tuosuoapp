<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == '0') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']));
	$theXlsCols = 2;
	$question_order_id = 1;

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		if ($theQuestionArray['itemCode'] != 0) {
			xlswritelabel($xlsRow, $theXlsCols, $theQuestionArray['itemCode'] . ':' . qimportstring($theQuestionArray['optionName']));
		}
		else {
			xlswritelabel($xlsRow, $theXlsCols, $question_order_id . ':' . qimportstring($theQuestionArray['optionName']));
		}

		$question_order_id++;
		$theXlsCols++;
	}
}
else {
	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['isSelect'] == '1') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']));
	$theXlsCols = 2;
	$question_order_id = 1;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		if ($theQuestionArray['itemCode'] != 0) {
			xlswritelabel($xlsRow, $theXlsCols, $theQuestionArray['itemCode'] . ':' . qimportstring($theQuestionArray['optionName']));
		}
		else {
			xlswritelabel($xlsRow, $theXlsCols, $question_order_id . ':' . qimportstring($theQuestionArray['optionName']));
		}

		$question_order_id++;
		$theXlsCols++;
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		if ($theBaseQtnArray['otherCode'] != 0) {
			xlswritelabel($xlsRow, $theXlsCols, '' . $theBaseQtnArray['otherCode'] . ':' . qimportstring($theBaseQtnArray['otherText']));
		}
		else {
			xlswritelabel($xlsRow, $theXlsCols, '99:' . qimportstring($theBaseQtnArray['otherText']));
		}
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qimportstring($theQtnArray['allowType']));
		$theXlsCols++;
		xlswritelabel($xlsRow, $theXlsCols, '99999:' . $negText);
	}
}
else {
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']));
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qimportstring($theQtnArray['allowType']));
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . $negText);
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}
}

?>

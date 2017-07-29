<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
	$question_order_id = 1;
	$theXlsCols = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		if ($theAnswerArray['itemCode'] != 0) {
			xlswritelabel($xlsRow, 2 + $theXlsCols, $theAnswerArray['itemCode'] . ':' . qimportstring($theAnswerArray['optionAnswer']));
		}
		else {
			xlswritelabel($xlsRow, 2 + $theXlsCols, $question_order_id . ':' . qimportstring($theAnswerArray['optionAnswer']));
		}

		$question_order_id++;
		$theXlsCols++;
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']));
	$question_order_id = 1;
	$theXlsCols = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		if ($theAnswerArray['itemCode'] != 0) {
			xlswritelabel($xlsRow, 2 + $theXlsCols, $theAnswerArray['itemCode'] . ':' . qimportstring($theAnswerArray['optionAnswer']));
		}
		else {
			xlswritelabel($xlsRow, 2 + $theXlsCols, $question_order_id . ':' . qimportstring($theAnswerArray['optionAnswer']));
		}

		$question_order_id++;
		$theXlsCols++;
	}
}

?>

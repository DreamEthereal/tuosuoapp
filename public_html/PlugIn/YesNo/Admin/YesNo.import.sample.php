<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theCsvColNum++;
$xlsRow++;
xlswritelabel($xlsRow, 0, $theCsvColNum);
xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']));
$question_order_id = 1;
$theXlsCols = 0;

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		xlswritelabel($xlsRow, 2 + $theXlsCols, $theQuestionArray['itemCode'] . ':' . qimportstring($theQuestionArray['optionName']));
	}
	else {
		xlswritelabel($xlsRow, 2 + $theXlsCols, $question_order_id . ':' . qimportstring($theQuestionArray['optionName']));
	}

	$question_order_id++;
	$theXlsCols++;
}

?>

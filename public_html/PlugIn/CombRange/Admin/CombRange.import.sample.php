<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - ' . qimportstring($theLabelArray['optionLabel']));
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
}

?>

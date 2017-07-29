<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - ' . qimportstring($theAnswerArray['optionAnswer']));
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']) . ' - ' . qimportstring($theAnswerArray['optionAnswer']));
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}
}

?>

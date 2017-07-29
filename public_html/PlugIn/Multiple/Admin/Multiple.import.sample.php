<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$tmp = 0;
$lastOptionId = count($OptionListArray[$questionID]) - 1;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - ' . qimportstring($theAnswerArray['optionAnswer']));
		xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
		xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	}

	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, $lang['import_openText']);
	}

	$tmp++;
}

?>

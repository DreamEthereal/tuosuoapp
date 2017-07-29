<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$tmp = 0;
$lastOptionId = count($RankListArray[$questionID]) - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
	xlswritelabel($xlsRow, 2, $lang['import_number_text']);
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - Text');
		xlswritelabel($xlsRow, 2, $lang['import_openText']);
	}

	$tmp++;
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theRankNumber = count($RankListArray[$questionID]);

if ($theQtnArray['isHaveOther'] == '1') {
	$theRankNumber++;
}

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
	xlswritelabel($xlsRow, 2, $lang['import_number'] . '1-' . $theRankNumber);
}

if ($theQtnArray['isHaveOther'] == '1') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQtnArray['otherText']));
	xlswritelabel($xlsRow, 2, $lang['import_number'] . '1-' . $theRankNumber);
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQtnArray['otherText']) . ' - Text');
	xlswritelabel($xlsRow, 2, $lang['import_openText']);
}

if ($theQtnArray['isHaveWhy'] == '1') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . $lang['why_your_order'] . ' - Text');
	xlswritelabel($xlsRow, 2, $lang['import_openText']);
}

?>

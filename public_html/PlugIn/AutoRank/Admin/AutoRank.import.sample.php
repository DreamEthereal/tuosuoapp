<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$theRankNumber = count($theCheckBoxListArray);

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$theRankNumber++;
}

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
	xlswritelabel($xlsRow, 2, $lang['import_number'] . '1-' . $theRankNumber);
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']));
	xlswritelabel($xlsRow, 2, $lang['import_number'] . '1-' . $theRankNumber);
}

?>

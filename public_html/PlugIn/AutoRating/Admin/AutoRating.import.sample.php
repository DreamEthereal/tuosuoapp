<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

switch ($QtnListArray[$questionID]['isSelect']) {
case '1':
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['startScale'] . '-' . $QtnListArray[$questionID]['endScale']);

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - ' . $lang['why_your_rating'] . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['startScale'] . '-' . $QtnListArray[$questionID]['endScale']);

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']) . ' - ' . $lang['why_your_rating'] . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}
	}

	break;

case '0':
	$theRankNumber = '';
	$i = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $i; $i--) {
		$theRankNumber .= ($QtnListArray[$questionID]['weight'] * $i) . ',';
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$theRankNumber .= '99,';
	}

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . substr($theRankNumber, 0, -1));

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - ' . $lang['why_your_rating'] . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . substr($theRankNumber, 0, -1));

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']) . ' - ' . $lang['why_your_rating'] . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}
	}

	break;

case '2':
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['startScale'] . '-' . $QtnListArray[$questionID]['endScale']);
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theBaseQtnArray['otherText']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['startScale'] . '-' . $QtnListArray[$questionID]['endScale']);
	}

	break;
}

?>

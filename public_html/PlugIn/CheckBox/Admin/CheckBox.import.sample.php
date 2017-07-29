<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
	xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
	xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
	$xlsCols++;
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQtnArray['otherText']));
	xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
	xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
}

if ($theQtnArray['isNeg'] == '1') {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	$negText = ($QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qimportstring($QtnListArray[$questionID]['allowType']));
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . $negText);
	xlswritelabel($xlsRow, 2, '0:' . $lang['checkbox_no_checked']);
	xlswritelabel($xlsRow, 3, '1:' . $lang['checkbox_checked']);
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$theCsvColNum++;
	$xlsRow++;
	xlswritelabel($xlsRow, 0, $theCsvColNum);
	xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQtnArray['otherText']) . ' - Text');

	switch ($QtnListArray[$questionID]['isCheckType']) {
	case '1':
		xlswritelabel($xlsRow, 2, $lang['import_email_text']);
		break;

	case '4':
		xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['minSize'] . '-' . $QtnListArray[$questionID]['maxSize']);
		break;

	case '6':
		xlswritelabel($xlsRow, 2, $lang['import_date_text']);
		break;

	case '7':
		xlswritelabel($xlsRow, 2, $lang['import_ic_text']);
		break;

	case '5':
	case '11':
		xlswritelabel($xlsRow, 2, $lang['import_phone_text']);
		break;

	case '2':
	case '3':
	case '8':
	case '9':
	case '10':
	case '12':
	default:
		xlswritelabel($xlsRow, 2, $lang['import_openText']);
		break;
	}
}

?>

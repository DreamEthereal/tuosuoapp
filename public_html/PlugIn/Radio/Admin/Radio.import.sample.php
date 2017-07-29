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
$theXlsCols = 2;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		xlswritelabel($xlsRow, $theXlsCols, $theQuestionArray['itemCode'] . ':' . qimportstring($theQuestionArray['optionName']));
	}
	else {
		xlswritelabel($xlsRow, $theXlsCols, $question_order_id . ':' . qimportstring($theQuestionArray['optionName']));
	}

	$question_order_id++;
	$theXlsCols++;
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	if ($theQtnArray['otherCode'] != 0) {
		xlswritelabel($xlsRow, $theXlsCols, $theQtnArray['otherCode'] . ':' . qimportstring($theQtnArray['otherText']));
	}
	else {
		xlswritelabel($xlsRow, $theXlsCols, '99:' . qimportstring($theQtnArray['otherText']));
	}

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

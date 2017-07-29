<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theCsvColNum++;
$xlsRow++;
xlswritelabel($xlsRow, 0, $theCsvColNum);
xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']));
$theXlsRow = $xlsRow;
$question_order_id = 1;
$theXlsCols = 2;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['itemCode'] != 0) {
		xlswritelabel($theXlsRow, $theXlsCols, $theQuestionArray['itemCode'] . ':' . qimportstring($theQuestionArray['optionName']));
	}
	else {
		xlswritelabel($theXlsRow, $theXlsCols, $question_order_id . ':' . qimportstring($theQuestionArray['optionName']));
	}

	$question_order_id++;
	$theXlsCols++;

	if ($theQuestionArray['isHaveText'] == 1) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - Text');

		switch ($theQuestionArray['isCheckType']) {
		case '1':
			xlswritelabel($xlsRow, 2, $lang['import_email_text']);
			break;

		case '4':
			xlswritelabel($xlsRow, 2, $lang['import_number'] . $theQuestionArray['minOption'] . '-' . $theQuestionArray['maxOption']);
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
}

?>

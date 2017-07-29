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

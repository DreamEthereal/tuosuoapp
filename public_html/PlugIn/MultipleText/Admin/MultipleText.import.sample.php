<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - ' . qimportstring($theLabelArray['optionLabel']));

		switch ($theLabelArray['isCheckType']) {
		case '1':
			xlswritelabel($xlsRow, 2, $lang['import_email_text']);
			break;

		case '4':
			xlswritelabel($xlsRow, 2, $lang['import_number'] . $theLabelArray['minOption'] . '-' . $theLabelArray['maxOption']);
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

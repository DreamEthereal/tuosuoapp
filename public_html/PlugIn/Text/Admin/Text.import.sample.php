<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theCsvColNum++;
$xlsRow++;
xlswritelabel($xlsRow, 0, $theCsvColNum);
xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']));

switch ($QtnListArray[$questionID]['isCheckType']) {
case '1':
	xlswritelabel($xlsRow, 2, $lang['import_email_text']);
	break;

case '4':
	xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['minOption'] . '-' . $QtnListArray[$questionID]['maxOption']);
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

case '13':
	xlswritelabel($xlsRow, 2, $lang['import_time_text']);
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

if ($QtnListArray[$questionID]['isHaveUnkown'] == 2) {
	xlswritelabel($xlsRow, 3, '99999:' . $lang['rating_unknow']);
}

?>

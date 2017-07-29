<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

switch ($QtnListArray[$questionID]['isSelect']) {
case '1':
	$tmp = 0;
	$lastOptionId = count($RankListArray[$questionID]) - 1;

	foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
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

		if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}

		$tmp++;
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

	$tmp = 0;
	$lastOptionId = count($RankListArray[$questionID]) - 1;

	foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
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

		if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}

		$tmp++;
	}

	break;

case '2':
	$tmp = 0;
	$lastOptionId = count($RankListArray[$questionID]) - 1;

	foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
		$theCsvColNum++;
		$xlsRow++;
		xlswritelabel($xlsRow, 0, $theCsvColNum);
		xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']));
		xlswritelabel($xlsRow, 2, $lang['import_number'] . $QtnListArray[$questionID]['startScale'] . '-' . $QtnListArray[$questionID]['endScale']);
		if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$theCsvColNum++;
			$xlsRow++;
			xlswritelabel($xlsRow, 0, $theCsvColNum);
			xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']) . ' - ' . qimportstring($theQuestionArray['optionName']) . ' - Text');
			xlswritelabel($xlsRow, 2, $lang['import_openText']);
		}

		$tmp++;
	}

	break;
}

?>

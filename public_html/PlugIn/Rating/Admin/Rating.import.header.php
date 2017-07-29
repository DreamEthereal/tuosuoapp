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
		$this_fields_list .= $theCsvColNum . '#15#' . $questionID . '#option_' . $questionID . '_' . $question_rankID . '#1#' . $QtnListArray[$questionID]['startScale'] . '#' . $QtnListArray[$questionID]['endScale'] . '|';

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#15#0#TextOtherValue_' . $questionID . '_' . $question_rankID . '#1|';
		}

		if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#15#0#TextOtherValue_' . $questionID . '#1#2#' . $theQtnArray['isHaveOther'] . '|';
		}

		$tmp++;
	}

	break;

case '0':
	$theRankNumber = '';
	$i = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $i; $i--) {
		$theRankNumber .= ($QtnListArray[$questionID]['weight'] * $i) . '*';
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$theRankNumber .= '99*';
	}

	$tmp = 0;
	$lastOptionId = count($RankListArray[$questionID]) - 1;

	foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#15#' . $questionID . '#option_' . $questionID . '_' . $question_rankID . '#0#' . substr($theRankNumber, 0, -1) . '#' . $QtnListArray[$questionID]['weight'] . '|';

		if ($theQtnArray['isHaveOther'] == '1') {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#15#0#TextOtherValue_' . $questionID . '_' . $question_rankID . '#0|';
		}

		if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#15#0#TextOtherValue_' . $questionID . '#0#2#' . $theQtnArray['isHaveOther'] . '|';
		}

		$tmp++;
	}

	break;

case '2':
	$tmp = 0;
	$lastOptionId = count($RankListArray[$questionID]) - 1;

	foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#15#' . $questionID . '#option_' . $questionID . '_' . $question_rankID . '#2#' . $QtnListArray[$questionID]['startScale'] . '#' . $QtnListArray[$questionID]['endScale'] . '|';
		if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$theCsvColNum++;
			$this_fields_list .= $theCsvColNum . '#15#0#TextOtherValue_' . $questionID . '#2#2#0|';
		}

		$tmp++;
	}

	break;
}

?>

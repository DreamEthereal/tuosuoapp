<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$tmp = 0;
$lastOptionId = count($RankListArray[$questionID]) - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#16#' . $questionID . '#option_' . $questionID . '_' . $question_rankID . '|';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$theCsvColNum++;
		$this_fields_list .= $theCsvColNum . '#16#0#TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

?>

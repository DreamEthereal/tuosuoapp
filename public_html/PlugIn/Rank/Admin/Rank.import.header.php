<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theRankNumber = count($RankListArray[$questionID]);

if ($theQtnArray['isHaveOther'] == '1') {
	$theRankNumber++;
}

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#10#' . $questionID . '#option_' . $questionID . '_' . $question_rankID . '#' . $theRankNumber . '|';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#10#' . $questionID . '#option_' . $questionID . '_0#' . $theRankNumber . '|';
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#10#0#TextOtherValue_' . $questionID . '#0|';
}

if ($theQtnArray['isHaveWhy'] == '1') {
	$theCsvColNum++;
	$this_fields_list .= $theCsvColNum . '#10#0#TextWhyValue_' . $questionID . '#1|';
}

?>

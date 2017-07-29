<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_weight'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";
$optionTotalNum = count($RankListArray[$questionID]);
$tmp = 0;
$lastOptionId = $optionTotalNum - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	if ($theQtnArray['isHaveOther'] != 1) {
		$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
	}
	else if ($tmp != $lastOptionId) {
		$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
	}
	else {
		$content .= $lang['txt_other_1'] . qshowquotechar($theQuestionArray['optionName']) . "\r\n";
	}

	$tmp++;
}

?>

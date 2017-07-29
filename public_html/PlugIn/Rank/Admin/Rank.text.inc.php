<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$content .= $lang['txt_rank'] . qshowquotechar($theQtnArray['questionName']) . "\r\n";

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$content .= qshowquotechar($theQuestionArray['optionName']) . "\r\n";
}

if ($theQtnArray['isHaveOther'] == 1) {
	$content .= $lang['txt_other_1'] . qshowquotechar($theQtnArray['otherText']) . "\r\n";
}

?>

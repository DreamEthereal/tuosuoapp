<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$tmp = 0;
$lastOptionId = count($RankListArray[$questionID]) - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '15#' . $theQtnArray['weight'] . '#option_' . $questionID . '_' . $question_rankID . '|';

	if ($theQtnArray['isHaveOther'] == '1') {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - ' . $lang['why_your_rating'] . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_rankID . '|';
	}

	if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
		$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . ' - Text' . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

?>

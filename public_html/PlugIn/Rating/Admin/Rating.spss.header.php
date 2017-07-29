<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$i = 0;
$tmp = 0;
$lastOptionId = count($RankListArray[$questionID]) - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$i++;

	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_' . $i . '"';
	}

	$this_fields_list .= '15#' . $theQtnArray['weight'] . '#option_' . $questionID . '_' . $question_rankID . '|';

	if ($theQtnArray['isHaveOther'] == '1') {
		if ($theQtnArray['alias'] != '') {
			$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '_why_text' . '"';
		}
		else {
			$header .= ',"' . 'VAR' . $questionID . '_' . $i . '_why_text' . '"';
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_rankID . '|';
	}

	if (($theQtnArray['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
		if ($theQtnArray['alias'] != '') {
			$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '_text' . '"';
		}
		else {
			$header .= ',"' . 'VAR' . $questionID . '_' . $i . '_text' . '"';
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

?>

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

	$this_fields_list .= '16#option_' . $questionID . '_' . $question_rankID . '|';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
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

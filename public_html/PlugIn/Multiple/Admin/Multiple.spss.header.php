<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$varName = $theQtnArray['alias'];
}
else {
	$varName = 'VAR' . $questionID;
}

$i = 0;
$tmp = 0;
$lastOptionId = count($OptionListArray[$questionID]) - 1;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$i++;
	$j = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;

		if ($theAnswerArray['itemCode'] != 0) {
			$header .= ',"' . $varName . '_' . $i . '_' . $theAnswerArray['itemCode'] . '"';
		}
		else {
			$header .= ',"' . $varName . '_' . $i . '_' . $j . '"';
		}

		$this_fields_list .= '7#option_' . $questionID . '_' . $question_range_optionID . '#' . $question_range_answerID . '|';
	}

	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$header .= ',"' . $varName . '_' . $i . '_text' . '"';
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	}

	$tmp++;
}

?>

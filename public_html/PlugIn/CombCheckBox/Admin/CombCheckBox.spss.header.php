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

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$i++;

	if ($theQuestionArray['itemCode'] != 0) {
		$header .= ',"' . $varName . '_' . $theQuestionArray['itemCode'] . '"';
	}
	else {
		$header .= ',"' . $varName . '_' . $i . '"';
	}

	$this_fields_list .= '25#option_' . $questionID . '#' . $question_checkboxID . '|';

	if ($theQuestionArray['isHaveText'] == 1) {
		if ($theQuestionArray['itemCode'] != 0) {
			$header .= ',"' . $varName . '_' . $theQuestionArray['itemCode'] . '_text' . '"';
		}
		else {
			$header .= ',"' . $varName . '_' . $i . '_text' . '"';
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_checkboxID . '|';
	}
}

?>

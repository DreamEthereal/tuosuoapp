<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$i = 0;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$i++;

	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_' . $i . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_' . $i . '"';
	}

	$this_fields_list .= '10#option_' . $questionID . '_' . $question_rankID . '|';
}

if ($theQtnArray['isHaveOther'] == '1') {
	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_99' . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_99' . '"';
	}

	$this_fields_list .= '10#option_' . $questionID . '_0' . '|';

	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_99_text' . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_99_text' . '"';
	}

	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

if ($theQtnArray['isHaveWhy'] == '1') {
	if ($theQtnArray['alias'] != '') {
		$header .= ',"' . $theQtnArray['alias'] . '_why_text' . '"';
	}
	else {
		$header .= ',"' . 'VAR' . $questionID . '_why_text' . '"';
	}

	$this_fields_list .= 'TextWhyValue_' . $questionID . '|';
}

?>

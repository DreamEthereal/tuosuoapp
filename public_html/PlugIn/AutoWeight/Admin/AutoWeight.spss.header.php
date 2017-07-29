<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$i = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$i++;

	if ($theQuestionArray['itemCode'] != 0) {
		$header .= ',"' . $VarName . '_' . $theQuestionArray['itemCode'] . '"';
	}
	else {
		$header .= ',"' . $VarName . '_' . $i . '"';
	}

	$this_fields_list .= '22#option_' . $questionID . '_' . $question_checkboxID . '|';
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	if ($theBaseQtnArray['otherCode'] != 0) {
		$header .= ',"' . $VarName . '_' . $theBaseQtnArray['otherCode'] . '"';
	}
	else {
		$header .= ',"' . $VarName . '_99' . '"';
	}

	$this_fields_list .= '22#option_' . $questionID . '_0' . '|';
}

?>

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
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theQuestionArray['itemCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
	}
	else {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	if ($theBaseQtnArray['otherCode'] != 0) {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $theBaseQtnArray['otherCode'] . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '\'.' . "\r\n" . '';
	}
	else {
		$content .= ' VARIABLE LABELS ' . $VarName . '_99 \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theBaseQtnArray['otherText']) . '\'.' . "\r\n" . '';
	}
}

?>

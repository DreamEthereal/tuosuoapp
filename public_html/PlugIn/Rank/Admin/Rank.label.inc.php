<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['alias'] != '') {
	$VarName = $theQtnArray['alias'];
}
else {
	$VarName = 'VAR' . $questionID;
}

$i = 0;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$i++;
	$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$content .= ' VARIABLE LABELS ' . $VarName . '_99 \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '\'.' . "\r\n" . '';
	$content .= ' VARIABLE LABELS ' . $VarName . '_99_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQtnArray['otherText']) . '-Text\'.' . "\r\n" . '';
}

if ($theQtnArray['isHaveWhy'] == '1') {
	$content .= ' VARIABLE LABELS ' . $VarName . '_why_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . $lang['why_your_order'] . '\'.' . "\r\n" . '';
}

?>

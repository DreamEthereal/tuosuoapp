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
$tmp = 0;
$lastOptionId = count($RankListArray[$questionID]) - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$i++;
	$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . ' \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '\'.' . "\r\n" . '';
	if (($theQtnArray['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$content .= ' VARIABLE LABELS ' . $VarName . '_' . $i . '_text \'' . qconverionlabel($theQtnArray['questionName']) . '-' . qconverionlabel($theQuestionArray['optionName']) . '-Text\'.' . "\r\n" . '';
	}

	$tmp++;
}

?>

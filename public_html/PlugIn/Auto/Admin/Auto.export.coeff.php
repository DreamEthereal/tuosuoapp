<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$allNoneNum = 0;

if ($theQtnArray['isCheckType'] == '1') {
	if ($theQtnArray['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$allNoneNum = $OptionCountRow['optionResponseNum'];
}

$thisCountNum = $thisOptionResponseNum - $allNoneNum;

if ($theQtnArray['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND a.question_checkboxID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_checkboxID'];
	$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
}

if ($theQtnArray['isSelect'] == 1) {
	$total_optionCoeffNum = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		if (in_array($question_checkboxID, $allResponseOptionID)) {
			$optionCoeffNum = $theQuestionArray['itemCode'] * $allOptionResponseNum[$question_checkboxID];
			$total_optionCoeffNum += $optionCoeffNum;
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$optionCoeffNum = $theBaseQtnArray['otherCode'] * $OptionCountRow['optionResponseNum'];
		$total_optionCoeffNum += $optionCoeffNum;
	}

	$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $thisCountNum);
	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$content .= ',"' . $total_optionCoeffAvg . '"';
	$content .= "\r\n";
}
else {
	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($theQuestionArray['optionName']) . '"';

		if (in_array($question_checkboxID, $allResponseOptionID)) {
			$optionCoeffNum = $theQuestionArray['itemCode'] * $allOptionResponseNum[$question_checkboxID];
			$optionCoeffAvg = meanaverage($optionCoeffNum, $thisCountNum);
			$content .= ',"' . $optionCoeffAvg . '"';
			$content .= "\r\n";
		}
		else {
			$content .= ',"0"';
			$content .= "\r\n";
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($theBaseQtnArray['otherText']) . '"';
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$optionCoeffNum = $theBaseQtnArray['otherCode'] * $OptionCountRow['optionResponseNum'];
		$optionCoeffAvg = meanaverage($optionCoeffNum, $thisCountNum);
		$content .= ',"' . $optionCoeffAvg . '"';
		$content .= "\r\n";
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qshowexportquotechar($theQtnArray['allowType']));
		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . $negText . '"';
		$content .= ',"0"';
		$content .= "\r\n";
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

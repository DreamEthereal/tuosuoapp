<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isHaveOther'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' AND b.TextOtherValue_' . $questionID . ' = \'\' and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$allNoneNum = 0;

if ($theQtnArray['isNeg'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$allNoneNum = $OptionCountRow['optionResponseNum'];
}

$thisCountNum = $thisOptionResponseNum - $allNoneNum;
$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_checkboxID'];
	$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
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

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($theQtnArray['otherText']) . '"';
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ')  AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$optionCoeffNum = $theQtnArray['otherCode'] * $OptionCountRow['optionResponseNum'];
	$optionCoeffAvg = meanaverage($optionCoeffNum, $thisCountNum);
	$content .= ',"' . $optionCoeffAvg . '"';
	$content .= "\r\n";
}

if ($theQtnArray['isNeg'] == '1') {
	$negText = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : qshowexportquotechar($theQtnArray['allowType']));
	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . $negText . '"';
	$content .= ',"0"';
	$content .= "\r\n";
}

?>

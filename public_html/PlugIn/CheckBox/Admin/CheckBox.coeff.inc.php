<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$minOption = $maxOption = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';

	if ($theQtnArray['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $theQtnArray['minOption'] . $lang['option'] . ']';
	}

	if ($theQtnArray['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $theQtnArray['maxOption'] . $lang['option'] . ']';
	}
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_3'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$EnableQCoreClass->replace('questionName', $questionName);

if ($theQtnArray['isHaveOther'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' AND b.TextOtherValue_' . $questionID . ' = \'\' and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
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
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

	if (in_array($question_checkboxID, $allResponseOptionID)) {
		$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_checkboxID]);
		$optionCoeffNum = $theQuestionArray['itemCode'] * $allOptionResponseNum[$question_checkboxID];
		$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 2));
		$optionCoeffAvg = meanaverage($optionCoeffNum, $thisCountNum);
		$EnableQCoreClass->replace('optionCoeffAvg', $optionCoeffAvg);
	}
	else {
		$EnableQCoreClass->replace('answerNum', 0);
		$EnableQCoreClass->replace('optionCoeffNum', 0);
		$EnableQCoreClass->replace('optionCoeffAvg', 0);
	}

	$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['itemCode']);
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ')  AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('other_answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('other_optionCoeff', $theQtnArray['otherCode']);
	$optionCoeffNum = $theQtnArray['otherCode'] * $OptionCountRow['optionResponseNum'];
	$EnableQCoreClass->replace('other_optionCoeffNum', round($optionCoeffNum, 2));
	$optionCoeffAvg = meanaverage($optionCoeffNum, $thisCountNum);
	$EnableQCoreClass->replace('other_optionCoeffAvg', $optionCoeffAvg);
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('other_optionName', '');
	$EnableQCoreClass->replace('other_answerNum', '');
	$EnableQCoreClass->replace('other_optionCoeff', '');
	$EnableQCoreClass->replace('other_optionCoeffNum', '');
	$EnableQCoreClass->replace('other_optionCoeffAvg', '');
}

if ($theQtnArray['isNeg'] == '1') {
	$EnableQCoreClass->replace('isHaveNeg', '');
	$EnableQCoreClass->replace('neg_optionName', $theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
	$EnableQCoreClass->replace('neg_answerNum', $allNoneNum);
	$EnableQCoreClass->replace('neg_optionCoeff', $theQtnArray['negCode']);
	$EnableQCoreClass->replace('neg_optionCoeffNum', '0');
	$EnableQCoreClass->replace('neg_optionCoeffAvg', '0');
}
else {
	$EnableQCoreClass->replace('isHaveNeg', 'none');
	$EnableQCoreClass->replace('neg_optionName', '');
	$EnableQCoreClass->replace('neg_answerNum', '');
	$EnableQCoreClass->replace('neg_optionCoeff', '');
	$EnableQCoreClass->replace('neg_optionCoeffNum', '');
	$EnableQCoreClass->replace('neg_optionCoeffAvg', '');
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

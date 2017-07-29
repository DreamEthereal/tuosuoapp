<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RadioCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_2'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($theQtnArray['isHaveOther'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' = \'\' and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$other_answerNum = $OptionCountRow['optionResponseNum'];
}

$isUnkown = array();

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if ($theQuestionArray['isUnkown'] == 1) {
		$isUnkown[] = $question_radioID;
	}
}

if (count($isUnkown) == 0) {
	$unkownNum = 0;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' IN (' . implode(',', $isUnkown) . ') and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$unkownNum = $OptionCountRow['optionResponseNum'];
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1') && ($theQtnArray['isUnkown'] == 1)) {
	$unkownNum += $other_answerNum;
}

$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$validNum = $thisOptionResponseNum - $unkownNum;
$OptionSQL = ' SELECT a.question_radioID,count(*) as optionResponseNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_radioID = b.option_' . $questionID . ' and ' . $dataSource;
$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_radioID'];
	$allOptionResponseNum[$OptionRow['question_radioID']] = $OptionRow['optionResponseNum'];
}

$total_optionCoeffNum = 0;

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));
	$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['itemCode']);

	if (in_array($question_radioID, $allResponseOptionID)) {
		$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_radioID]);

		if (in_array($question_radioID, $isUnkown)) {
			$EnableQCoreClass->replace('optionCoeffNum', 0);
			$EnableQCoreClass->replace('optionCoeffAvg', 0);
		}
		else {
			$optionCoeffNum = $theQuestionArray['itemCode'] * $allOptionResponseNum[$question_radioID];
			$total_optionCoeffNum += $optionCoeffNum;
			$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 2));
			$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
			$EnableQCoreClass->replace('optionCoeffAvg', $optionCoeffAvg);
		}
	}
	else {
		$EnableQCoreClass->replace('answerNum', 0);
		$EnableQCoreClass->replace('optionCoeffNum', 0);
		$EnableQCoreClass->replace('optionCoeffAvg', 0);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
	$EnableQCoreClass->replace('other_answerNum', $other_answerNum);
	$EnableQCoreClass->replace('other_optionCoeff', $theQtnArray['otherCode']);

	if ($theQtnArray['isUnkown'] == 1) {
		$EnableQCoreClass->replace('other_optionCoeffNum', 0);
		$EnableQCoreClass->replace('other_optionCoeffAvg', 0);
	}
	else {
		$optionCoeffNum = $theQtnArray['otherCode'] * $other_answerNum;
		$total_optionCoeffNum += $optionCoeffNum;
		$EnableQCoreClass->replace('other_optionCoeffNum', round($optionCoeffNum, 2));
		$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
		$EnableQCoreClass->replace('other_optionCoeffAvg', $optionCoeffAvg);
	}
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('other_optionName', '');
	$EnableQCoreClass->replace('other_answerNum', '');
	$EnableQCoreClass->replace('other_optionCoeff', '');
	$EnableQCoreClass->replace('other_optionCoeffNum', '');
	$EnableQCoreClass->replace('other_optionCoeffAvg', '');
}

$EnableQCoreClass->replace('total_optionCoeffNum', $total_optionCoeffNum);
$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
$EnableQCoreClass->replace('total_optionCoeffAvg', $total_optionCoeffAvg);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

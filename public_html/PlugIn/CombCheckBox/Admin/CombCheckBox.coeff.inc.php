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
$questionName .= '[' . $lang['question_type_25'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$EnableQCoreClass->replace('questionName', $questionName);
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$isUnkown = array();

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	if ($theQuestionArray['isExclusive'] == 1) {
		$isUnkown[] = $question_checkboxID;
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

$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$validNum = $thisOptionResponseNum - $unkownNum;
$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_checkboxID'];
	$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
}

$total_optionCoeffNum = 0;

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

	if (in_array($question_checkboxID, $allResponseOptionID)) {
		$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_checkboxID]);

		if (in_array($question_checkboxID, $isUnkown)) {
			$EnableQCoreClass->replace('optionCoeffNum', 0);
			$EnableQCoreClass->replace('optionCoeffAvg', 0);
		}
		else {
			$optionCoeffNum = $theQuestionArray['itemCode'] * $allOptionResponseNum[$question_checkboxID];
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

	$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['itemCode']);
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$EnableQCoreClass->replace('isHaveOther', 'none');
$EnableQCoreClass->replace('other_optionName', '');
$EnableQCoreClass->replace('other_answerNum', '');
$EnableQCoreClass->replace('other_optionCoeff', '');
$EnableQCoreClass->replace('other_optionCoeffNum', '');
$EnableQCoreClass->replace('other_optionCoeffAvg', '');
$EnableQCoreClass->replace('isHaveNeg', 'none');
$EnableQCoreClass->replace('neg_optionName', '');
$EnableQCoreClass->replace('neg_answerNum', '');
$EnableQCoreClass->replace('neg_optionCoeff', '');
$EnableQCoreClass->replace('neg_optionCoeffNum', '');
$EnableQCoreClass->replace('neg_optionCoeffAvg', '');
$EnableQCoreClass->replace('total_optionCoeffNum', $total_optionCoeffNum);
$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
$EnableQCoreClass->replace('total_optionCoeffAvg', $total_optionCoeffAvg);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

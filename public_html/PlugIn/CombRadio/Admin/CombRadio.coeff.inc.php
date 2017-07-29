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
$questionName .= '[' . $lang['question_type_24'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
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
$EnableQCoreClass->replace('isHaveOther', 'none');
$EnableQCoreClass->replace('other_optionName', '');
$EnableQCoreClass->replace('other_answerNum', '');
$EnableQCoreClass->replace('other_optionCoeff', '');
$EnableQCoreClass->replace('other_optionCoeffNum', '');
$EnableQCoreClass->replace('other_optionCoeffAvg', '');
$EnableQCoreClass->replace('total_optionCoeffNum', $total_optionCoeffNum);
$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
$EnableQCoreClass->replace('total_optionCoeffAvg', $total_optionCoeffAvg);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

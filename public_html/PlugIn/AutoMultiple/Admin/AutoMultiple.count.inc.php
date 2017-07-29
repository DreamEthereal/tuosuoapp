<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
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
$questionName .= '[' . $lang['question_type_28'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$EnableQCoreClass->replace('questionName', $questionName);
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
}

$TableFields = '';

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('subQuestionName', $optionName);
	$TableFields .= ' AND option_' . $questionID . '_' . $question_checkboxID . ' = \'\' ';
	$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' =\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['skipAnswerNum']);
	$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
	$EnableQCoreClass->replace('skip_optionPercent', countpercent($skipAnswerNum, $totalResponseNum));
	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
	$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_range_answerID,b.option_' . $questionID . '_' . $question_checkboxID . ') and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY a.question_range_answerID ORDER BY optionResponseNum DESC';
	$OptionCountResult = $DB->query($OptionCountSQL);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
		$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
	}

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($theAnswerArray['optionAnswer']));

		if (in_array($question_range_answerID, $allResponseOptionID)) {
			$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_range_answerID]);
			$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$question_range_answerID], $totalResponseNum));
			$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$question_range_answerID], $thisOptionResponseNum));
		}
		else {
			$EnableQCoreClass->replace('answerNum', 0);
			$EnableQCoreClass->replace('optionPercent', 0);
			$EnableQCoreClass->replace('optionValidPercent', 0);
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 ' . $TableFields . ' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('all_skip_answerNum', $OptionCountRow['optionResponseNum']);
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('all_skip_Percent', $optionPercent);
unset($Answer);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

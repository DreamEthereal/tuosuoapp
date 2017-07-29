<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_1'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$optionPercent = countpercent($thisOptionResponseNum, $totalResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', $optionPercent);
$OptionSQL = ' SELECT a.question_yesnoID,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_yesnoID'];
	$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
}

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', $theQuestionArray['optionName']);

	if (in_array($question_yesnoID, $allResponseOptionID)) {
		$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_yesnoID]);
		$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$question_yesnoID], $totalResponseNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$question_yesnoID], $thisOptionResponseNum));
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
$EnableQCoreClass->replace('surveyID', $surveyID);
$EnableQCoreClass->replace('questionID', $questionID);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

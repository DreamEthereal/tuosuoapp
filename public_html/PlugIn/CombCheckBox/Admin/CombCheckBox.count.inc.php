<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($isViewResult == 1) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombCheckBoxCombView.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombCheckBoxView.html');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
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
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$optionPercent = countpercent($thisOptionResponseNum, $totalResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', $optionPercent);
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
		$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$question_checkboxID], $totalResponseNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$question_checkboxID], $thisOptionResponseNum));
	}
	else {
		$EnableQCoreClass->replace('answerNum', 0);
		$EnableQCoreClass->replace('optionPercent', 0);
		$EnableQCoreClass->replace('optionValidPercent', 0);
	}

	if ($theQuestionArray['isHaveText'] == 1) {
		$EnableQCoreClass->replace('isHaveText', '');
	}
	else {
		$EnableQCoreClass->replace('isHaveText', 'none');
	}

	$EnableQCoreClass->replace('optionID', $question_checkboxID);
	$EnableQCoreClass->replace('surveyID', $surveyID);
	$EnableQCoreClass->replace('questionID', $questionID);
	$EnableQCoreClass->replace('surveyName', urlencode($_GET['surveyTitle']));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

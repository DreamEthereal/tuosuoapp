<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RadioView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_2'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($theQtnArray['isHaveOther'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' = \'\' and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$EnableQCoreClass->replace('skip_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
$CombSQL = ' SELECT combNameID,combName FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\'  ';
$CombResult = $DB->query($CombSQL);
$TempArray = array();

while ($CombRow = $DB->queryArray($CombResult)) {
	$EnableQCoreClass->replace('optionName', $CombRow['combName']);
	$CombOptionSQL = ' SELECT optionID FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\' AND combNameID =\'' . $CombRow['combNameID'] . '\' ';
	$CombOptionResult = $DB->query($CombOptionSQL);
	$CombOptionIDArray = array();

	while ($CombOptionRow = $DB->queryArray($CombOptionResult)) {
		$CombOptionIDArray[] = $CombOptionRow['optionID'];
		$TempArray[] = $CombOptionRow['optionID'];
	}

	if (!empty($CombOptionIDArray)) {
		$CombOptionIDList = implode(',', $CombOptionIDArray);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' IN (' . $CombOptionIDList . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	if (!empty($TempArray) && !in_array($question_radioID, $TempArray)) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' =  \'' . $question_radioID . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}
}

if (($theQtnArray['isSelect'] != '1') && ($theQtnArray['isHaveOther'] == '1')) {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('other_answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('other_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
	$EnableQCoreClass->replace('other_optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->replace('surveyName', urlencode($_GET['surveyTitle']));
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('other_optionName', '');
	$EnableQCoreClass->replace('other_answerNum', '');
	$EnableQCoreClass->replace('other_optionPercent', '');
	$EnableQCoreClass->replace('other_optionValidPercent', '');
	$EnableQCoreClass->replace('surveyName', '');
}

$EnableQCoreClass->replace('surveyID', $surveyID);
$EnableQCoreClass->replace('questionID', $questionID);
unset($TempArray);
unset($CombOptionIDArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

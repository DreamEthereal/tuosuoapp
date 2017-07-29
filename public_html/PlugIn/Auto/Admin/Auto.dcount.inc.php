<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCountND.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']));
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skipAnswerNum', $OptionCountRow['skipAnswerNum']);
$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));

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

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

	if (in_array($question_checkboxID, $allResponseOptionID)) {
		$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_checkboxID]);
		$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$question_checkboxID], $totalRepAnswerNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$question_checkboxID], $thisOptionResponseNum));
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

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('other_optionName', $theBaseQtnArray['otherText']);

	if ($theQtnArray['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('other_answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('other_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalRepAnswerNum));
	$EnableQCoreClass->replace('other_optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
}

if ($theQtnArray['isCheckType'] == '1') {
	$EnableQCoreClass->replace('neg_optionName', $theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));

	if ($theQtnArray['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('neg_answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('neg_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalRepAnswerNum));
	$EnableQCoreClass->replace('neg_optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
}

$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

if ($theBaseQtnArray['isHaveOther'] != '1') {
	$DataCrossHTML = preg_replace('/<!-- BEGIN OTHER -->(.*)<!-- END OTHER -->/s', '', $DataCrossHTML);
}

if ($theQtnArray['isCheckType'] != '1') {
	$DataCrossHTML = preg_replace('/<!-- BEGIN NEG -->(.*)<!-- END NEG -->/s', '', $DataCrossHTML);
}

?>

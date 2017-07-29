<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CondView.html');
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
$questionName .= '[' . $lang['question_type_18'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$EnableQCoreClass->replace('questionName', $questionName);
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$EnableQCoreClass->replace('skip_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
$CombSQL = ' SELECT combNameID,combName FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\' ';
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
		if ($theQtnArray['isSelect'] == '0') {
			$CombOptionIDList = implode(',', $CombOptionIDArray);
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' IN (' . $CombOptionIDList . ') and ' . $dataSource;
		}
		else {
			$Comb_SQL = '';

			foreach ($CombOptionIDArray as $CombOptionID) {
				$Comb_SQL .= ' FIND_IN_SET(' . $CombOptionID . ',b.option_' . $questionID . ') OR ';
			}

			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE (' . $Comb_SQL . ' 1=0 ) and ' . $dataSource;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

if ($theQtnArray['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;

	if (!empty($TempArray)) {
		$CombOptionIDList = implode(',', $TempArray);
		$OptionSQL .= ' AND question_yesnoID NOT IN (' . $CombOptionIDList . ') ';
	}

	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;

	if (!empty($TempArray)) {
		$CombOptionIDList = implode(',', $TempArray);
		$OptionSQL .= ' AND question_yesnoID NOT IN (' . $CombOptionIDList . ') ';
	}

	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
}

$OptionResult = $DB->query($OptionSQL);

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($OptionRow['optionName']));
	$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
	$EnableQCoreClass->replace('optionPercent', countpercent($OptionRow['optionResponseNum'], $totalResponseNum));
	$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

unset($TempArray);
unset($CombOptionIDArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

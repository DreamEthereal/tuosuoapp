<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'AutoView.html');
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
$questionName .= '[' . $lang['question_type_17'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$EnableQCoreClass->replace('questionName', $questionName);
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = \'\' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
$allSkipNum = $OptionCountRow['optionResponseNum'];
$EnableQCoreClass->replace('skip_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
$thisOptionResponseNum = $totalResponseNum - $allSkipNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
$CombSQL = ' SELECT combNameID,combName FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $theBaseID . '\'  ';
$CombResult = $DB->query($CombSQL);
$TempArray = array();

while ($CombRow = $DB->queryArray($CombResult)) {
	$EnableQCoreClass->replace('optionName', $CombRow['combName']);
	$CombOptionSQL = ' SELECT optionID FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $theBaseID . '\' AND combNameID =\'' . $CombRow['combNameID'] . '\' ';
	$CombOptionResult = $DB->query($CombOptionSQL);
	$CombOptionIDArray = array();

	while ($CombOptionRow = $DB->queryArray($CombOptionResult)) {
		$CombOptionIDArray[] = $CombOptionRow['optionID'];
		$TempArray[] = $CombOptionRow['optionID'];
	}

	if (!empty($CombOptionIDArray)) {
		if ($theQtnArray['isSelect'] == '1') {
			$CombOptionIDList = implode(',', $CombOptionIDArray);
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' IN (' . $CombOptionIDList . ') and ' . $dataSource;
		}
		else {
			$Comb_SQL = '';

			foreach ($CombOptionIDArray as $CombOptionID) {
				$Comb_SQL .= ' FIND_IN_SET(' . $CombOptionID . ',option_' . $questionID . ') OR ';
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

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	if (!empty($TempArray) && !in_array($question_checkboxID, $TempArray)) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

		if ($theQtnArray['isSelect'] == '1') {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' =  \'' . $question_checkboxID . '\' and ' . $dataSource;
		}
		else {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE FIND_IN_SET(' . $question_checkboxID . ',b.option_' . $questionID . ') and ' . $dataSource;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}
}

if ($theBaseQtnArray['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('other_optionName', qnospecialchar($theBaseQtnArray['otherText']));

	if ($theQtnArray['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . ' = \'0\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('other_answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('other_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
	$EnableQCoreClass->replace('other_optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
	$EnableQCoreClass->replace('surveyName', $_GET['surveyTitle']);
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

if ($theQtnArray['isCheckType'] == '1') {
	$EnableQCoreClass->replace('isHaveNeg', '');
	$EnableQCoreClass->replace('neg_optionName', $theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));

	if ($theQtnArray['isSelect'] == 1) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('neg_answerNum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->replace('neg_optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
	$EnableQCoreClass->replace('neg_optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
}
else {
	$EnableQCoreClass->replace('isHaveNeg', 'none');
	$EnableQCoreClass->replace('neg_optionName', '');
	$EnableQCoreClass->replace('neg_answerNum', '');
	$EnableQCoreClass->replace('neg_optionPercent', '');
	$EnableQCoreClass->replace('neg_optionValidPercent', '');
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

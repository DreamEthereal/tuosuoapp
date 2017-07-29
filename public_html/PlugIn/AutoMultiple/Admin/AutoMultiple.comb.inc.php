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
$questionName .= '[' . $lang['question_type_28'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$EnableQCoreClass->replace('questionName', $questionName);
$CombSQL = ' SELECT combNameID,combName FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\' ORDER BY combNameID ASC ';
$CombResult = $DB->query($CombSQL);
$CombArray = array();
$CombOptionIDArray = array();
$TempArray = array();

while ($CombRow = $DB->queryArray($CombResult)) {
	$CombArray[$CombRow['combNameID']] = $CombRow['combName'];
	$CombOptionSQL = ' SELECT optionID FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\' AND combNameID =\'' . $CombRow['combNameID'] . '\' ';
	$CombOptionResult = $DB->query($CombOptionSQL);

	while ($CombOptionRow = $DB->queryArray($CombOptionResult)) {
		$CombOptionIDArray[$CombRow['combNameID']][] = $CombOptionRow['optionID'];
		$TempArray[] = $CombOptionRow['optionID'];
	}
}

$NoCombArray = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	if (!empty($TempArray) && !in_array($question_range_answerID, $TempArray)) {
		$NoCombArray[$question_range_answerID] = qnospecialchar($theAnswerArray['optionAnswer']);
	}
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();
$TableFields = '';

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$TableFields .= ' AND option_' . $questionID . '_' . $question_checkboxID . ' = \'\' ';
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$TableFields .= ' AND option_' . $questionID . '_0 = \'\' ';
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('subQuestionName', $optionName);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' =\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
	$skipNum = $OptionCountRow['optionResponseNum'];
	$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
	$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
	$rep_answerNum = $thisOptionResponseNum = $totalResponseNum - $skipNum;
	$EnableQCoreClass->replace('rep_answerNum', $rep_answerNum);
	$optionPercent = countpercent($rep_answerNum, $totalResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', $optionPercent);

	if (!empty($CombArray)) {
		foreach ($CombArray as $combID => $combName) {
			if (!empty($CombOptionIDArray[$combID])) {
				$EnableQCoreClass->replace('optionName', qnospecialchar($combName));
				$Comb_SQL = '';

				foreach ($CombOptionIDArray[$combID] as $CombOptionID) {
					$Comb_SQL .= ' FIND_IN_SET(' . $CombOptionID . ',b.option_' . $questionID . '_' . $question_checkboxID . ') OR ';
				}

				$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE (' . $Comb_SQL . ' 1=0 ) and ' . $dataSource;
				$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
				$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
				$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
				$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
			}

			$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		}
	}

	if (!empty($NoCombArray)) {
		foreach ($NoCombArray as $no_comb_question_range_answerID => $optionAnswer) {
			$EnableQCoreClass->replace('optionName', $optionAnswer);
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE FIND_IN_SET( ' . $no_comb_question_range_answerID . ',b.option_' . $questionID . '_' . $question_checkboxID . ') and ' . $dataSource;
			$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
			$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
			$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
			$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
			$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		}
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 ' . $TableFields . ' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('all_skip_answerNum', $OptionCountRow['optionResponseNum']);
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('all_skip_Percent', $optionPercent);
unset($CombArray);
unset($CombOptionIDArray);
unset($NoCombArray);
unset($TempArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

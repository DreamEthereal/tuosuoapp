<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRangeView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_26'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$CombSQL = ' SELECT combNameID,combName FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\' ORDER BY combNameID ASC ';
$CombResult = $DB->query($CombSQL);
$CombArray = array();
$CombOptionIDArray = array();
$TempArray = array();

while ($CombRow = $DB->queryArray($CombResult)) {
	$CombArray[$CombRow['combNameID']] = qnospecialchar($CombRow['combName']);
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

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']));
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.' . $theOptionID . ' =0 and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['optionResponseNum']);
		$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
		$EnableQCoreClass->replace('skip_optionPercent', $optionPercent);
		$skipNum = $OptionCountRow['optionResponseNum'];
		$rep_answerNum = $thisOptionResponseNum = $totalResponseNum - $skipNum;
		$EnableQCoreClass->replace('rep_answerNum', $rep_answerNum);
		$optionPercent = countpercent($rep_answerNum, $totalResponseNum);
		$EnableQCoreClass->replace('rep_optionPercent', $optionPercent);

		if (!empty($CombArray)) {
			foreach ($CombArray as $combID => $combName) {
				$EnableQCoreClass->replace('optionName', $combName);

				if (!empty($CombOptionIDArray[$combID])) {
					$Comb_OptionIDList = implode(',', $CombOptionIDArray[$combID]);
					$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theOptionID . ' IN ( ' . $Comb_OptionIDList . ' ) and ' . $dataSource;
					$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
					$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
					$EnableQCoreClass->replace('optionPercent', countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum));
					$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionCountRow['optionResponseNum'], $thisOptionResponseNum));
				}

				$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
			}
		}

		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.' . $theOptionID . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.' . $theOptionID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);
		$allResponseOptionID = array();
		$allOptionResponseNum = array();

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		if (!empty($NoCombArray)) {
			foreach ($NoCombArray as $question_range_answerID => $optionAnswer) {
				$EnableQCoreClass->replace('optionName', $optionAnswer);

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
		}

		unset($allResponseOptionID);
		unset($allOptionResponseNum);
		$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
		$EnableQCoreClass->unreplace('option' . $questionID);
	}
}

unset($CombArray);
unset($CombOptionIDArray);
unset($NoCombArray);
unset($TempArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

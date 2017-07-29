<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRangeCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_26'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$isUnkown = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	if ($theAnswerArray['isUnkown'] == 1) {
		$isUnkown[] = $question_range_answerID;
	}
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']));
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;
		$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theOptionID . ' =0 and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['skipAnswerNum']);
		$skipAnswerNum = $OptionCountRow['skipAnswerNum'];

		if (count($isUnkown) == 0) {
			$unkownNum = 0;
		}
		else {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theOptionID . ' IN (' . implode(',', $isUnkown) . ') and ' . $dataSource;
			$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
			$unkownNum = $OptionCountRow['optionResponseNum'];
		}

		$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
		$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
		$validNum = $thisOptionResponseNum - $unkownNum;
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.' . $theOptionID . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.' . $theOptionID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);
		$allResponseOptionID = array();
		$allOptionResponseNum = array();

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		$total_optionCoeffNum = 0;

		foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
			$EnableQCoreClass->replace('optionName', qnospecialchar($theAnswerArray['optionAnswer']));
			$EnableQCoreClass->replace('optionCoeff', $theAnswerArray['itemCode']);

			if (in_array($question_range_answerID, $allResponseOptionID)) {
				$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_range_answerID]);

				if (in_array($question_range_answerID, $isUnkown)) {
					$EnableQCoreClass->replace('optionCoeffNum', 0);
					$EnableQCoreClass->replace('optionCoeffAvg', 0);
				}
				else {
					$optionCoeffNum = $theAnswerArray['itemCode'] * $allOptionResponseNum[$question_range_answerID];
					$total_optionCoeffNum += $optionCoeffNum;
					$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 2));
					$EnableQCoreClass->replace('optionCoeffAvg', meanaverage($optionCoeffNum, $validNum));
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
		$EnableQCoreClass->replace('total_optionCoeffNum', $total_optionCoeffNum);
		$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $validNum);
		$EnableQCoreClass->replace('total_optionCoeffAvg', $total_optionCoeffAvg);
		$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
		$EnableQCoreClass->unreplace('option' . $questionID);
	}
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

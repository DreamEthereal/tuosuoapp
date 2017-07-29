<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCount2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']));
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;
		$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theOptionID . ' =0 and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('skipAnswerNum', $OptionCountRow['skipAnswerNum']);
		$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
		$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
		$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
		$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
		$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.' . $theOptionID . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.' . $theOptionID . ' ORDER BY optionResponseNum DESC';
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
				$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$question_range_answerID], $totalRepAnswerNum));
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
}

$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

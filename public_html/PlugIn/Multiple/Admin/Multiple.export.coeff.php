<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_range_optionID . ' =\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$unkownNum = 0;
	if (($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['requiredMode'] != '2')) {
		$theLastArray = array_slice($AnswerListArray[$questionID], -1, 1);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_range_optionID . ' =\'' . $theLastArray[0]['question_range_answerID'] . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$unkownNum = $OptionCountRow['optionResponseNum'];
	}

	$validNum = $thisOptionResponseNum - $unkownNum;
	$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_range_answerID,b.option_' . $questionID . '_' . $question_range_optionID . ') and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY a.question_range_answerID ORDER BY optionResponseNum DESC';
	$OptionCountResult = $DB->query($OptionCountSQL);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
		$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
	}

	$k = 0;
	$lastAnswerId = count($AnswerListArray[$questionID]) - 1;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($theQuestionArray['optionName']) . '-' . qshowexportquotechar($theAnswerArray['optionAnswer']) . '"';

		if (in_array($question_range_answerID, $allResponseOptionID)) {
			if (($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['requiredMode'] != '2')) {
				if ($k != $lastAnswerId) {
					$optionCoeffNum = $theAnswerArray['itemCode'] * $allOptionResponseNum[$question_range_answerID];
					$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
					$content .= ',"' . $optionCoeffAvg . '"';
					$content .= "\r\n";
				}
				else {
					$content .= ',"0"';
					$content .= "\r\n";
				}
			}
			else {
				$optionCoeffNum = $theAnswerArray['itemCode'] * $allOptionResponseNum[$question_range_answerID];
				$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
				$content .= ',"' . $optionCoeffAvg . '"';
				$content .= "\r\n";
			}
		}
		else {
			$content .= ',"0"';
			$content .= "\r\n";
		}

		$k++;
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
}

?>

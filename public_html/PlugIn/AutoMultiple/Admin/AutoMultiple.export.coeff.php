<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qshowexportquotechar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' =\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
	$unkownNum = 0;

	if ($QtnListArray[$questionID]['isSelect'] == '1') {
		$theLastArray = array_slice($AnswerListArray[$questionID], -1, 1);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' =\'' . $theLastArray[0]['question_range_answerID'] . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$unkownNum = $OptionCountRow['optionResponseNum'];
	}

	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$validNum = $thisOptionResponseNum - $unkownNum;
	$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_range_answerID,b.option_' . $questionID . '_' . $question_checkboxID . ') and ' . $dataSource;
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
		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . $optionName . '-' . qshowexportquotechar($theAnswerArray['optionAnswer']) . '"';

		if (in_array($question_range_answerID, $allResponseOptionID)) {
			if ($QtnListArray[$questionID]['isSelect'] == '1') {
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

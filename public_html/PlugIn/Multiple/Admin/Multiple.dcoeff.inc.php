<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $theCoeffReportId . 'File', 'MultipleCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $theCoeffReportId . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_7'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_range_optionID . ' =\'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['skipAnswerNum']);
	$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
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
		$EnableQCoreClass->replace('optionName', qnospecialchar($theAnswerArray['optionAnswer']));
		$EnableQCoreClass->replace('optionCoeff', $theAnswerArray['itemCode']);

		if (in_array($question_range_answerID, $allResponseOptionID)) {
			$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_range_answerID]);
			if (($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['requiredMode'] != '2')) {
				if ($k != $lastAnswerId) {
					$optionCoeffNum = $theAnswerArray['itemCode'] * $allOptionResponseNum[$question_range_answerID];
					$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 2));
					$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
					$EnableQCoreClass->replace('optionCoeffAvg', $optionCoeffAvg);
				}
				else {
					$EnableQCoreClass->replace('optionCoeffNum', 0);
					$EnableQCoreClass->replace('optionCoeffAvg', 0);
				}
			}
			else {
				$optionCoeffNum = $theAnswerArray['itemCode'] * $allOptionResponseNum[$question_range_answerID];
				$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 2));
				$optionCoeffAvg = meanaverage($optionCoeffNum, $validNum);
				$EnableQCoreClass->replace('optionCoeffAvg', $optionCoeffAvg);
			}
		}
		else {
			$EnableQCoreClass->replace('answerNum', 0);
			$EnableQCoreClass->replace('optionCoeffNum', 0);
			$EnableQCoreClass->replace('optionCoeffAvg', 0);
		}

		$k++;
		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

$DataCrossHTML0 = $EnableQCoreClass->parse('ShowOption' . $theCoeffReportId, 'ShowOption' . $theCoeffReportId . 'File');
$DataCrossHTML = '<table width="100%">' . $DataCrossHTML0 . '</table>';

?>

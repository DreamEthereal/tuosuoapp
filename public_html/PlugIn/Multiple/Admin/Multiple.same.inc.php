<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DataMatchingSame2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'TIME', 'time' . $questionID);
$EnableQCoreClass->replace('time' . $questionID, '');
$theTimes = explode('^', trim($theTimeCharText));
$EnableQCoreClass->set_CycBlock('OPTION', 'DIMSUM', 'dimsum' . $questionID);
$EnableQCoreClass->replace('dimsum' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'DIM', 'dim' . $questionID);
$EnableQCoreClass->set_CycBlock('DIM', 'DIMNUM', 'dimnum' . $questionID);
$EnableQCoreClass->replace('dim' . $questionID, '');
$EnableQCoreClass->replace('dimnum' . $questionID, '');

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$theTotalResponseNum = array();

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$EnableQCoreClass->replace('timeName', date('y-n-j', $theBeginTime) . ' - ' . date('y-n-j', $theEndTime));
		$EnableQCoreClass->parse('time' . $questionID, 'TIME', true);
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_range_answerID,b.option_' . $questionID . '_' . $question_range_optionID . ') AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY a.question_range_answerID ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[$theTime][] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$theTime][$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . '_' . $question_range_optionID . ' !=\'\' AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$theTotalResponseNum[$theTime] = $OptionCountRow['optionResponseNum'];
		$EnableQCoreClass->replace('dimSum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->parse('dimsum' . $questionID, 'DIMSUM', true);
	}

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('dimName', qnospecialchar($theAnswerArray['optionAnswer']));

		foreach ($theTimes as $theTime) {
			if (in_array($question_range_answerID, $allResponseOptionID[$theTime])) {
				$EnableQCoreClass->replace('dimNum', $allOptionResponseNum[$theTime][$question_range_answerID]);
				$EnableQCoreClass->replace('dimPercent', countpercent($allOptionResponseNum[$theTime][$question_range_answerID], $theTotalResponseNum[$theTime]));
			}
			else {
				$EnableQCoreClass->replace('dimNum', 0);
				$EnableQCoreClass->replace('dimPercent', 0);
			}

			$EnableQCoreClass->parse('dimnum' . $questionID, 'DIMNUM', true);
		}

		$EnableQCoreClass->parse('dim' . $questionID, 'DIM', true);
		$EnableQCoreClass->unreplace('dimnum' . $questionID);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('dim' . $questionID);
	$EnableQCoreClass->unreplace('time' . $questionID);
	$EnableQCoreClass->unreplace('dimsum' . $questionID);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
unset($theTotalResponseNum);
$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $theDefineReportText . 'File', 'DeCross2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'OPTION', 'option' . $theDefineReportText);
$EnableQCoreClass->replace('option' . $theDefineReportText, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'ROWS', 'rows' . $theDefineReportText);
$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $theDefineReportText);
$EnableQCoreClass->replace('rows' . $theDefineReportText, '');
$EnableQCoreClass->replace('cell' . $theDefineReportText, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'COLS', 'cols' . $theDefineReportText);
$EnableQCoreClass->replace('cols' . $theDefineReportText, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'TOTAL', 'total' . $theDefineReportText);
$EnableQCoreClass->replace('total' . $theDefineReportText, '');
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('questionName', $optionName);
	$ObsFreq = array();
	$theColTotal = array();

	foreach ($theXName as $k => $thisXName) {
		$EnableQCoreClass->replace('colName', $thisXName);
		$EnableQCoreClass->parse('cols' . $theDefineReportText, 'COLS', true);
		$l = 0;
		$allResponseOptionID = array();
		$allOptionResponseNum = array();
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.option_' . $questionID . '_' . $question_checkboxID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_checkboxID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
			if (in_array($question_range_answerID, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$question_range_answerID];
				$theColTotal[$k] += $ObsFreq[$l][$k];
			}
			else {
				$ObsFreq[$l][$k] = 0;
			}

			$l++;
		}
	}

	$repTotalAnswerNum = array_sum($theColTotal);
	$EnableQCoreClass->replace('repTotalAnswerNum', $repTotalAnswerNum);
	$m = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('rowName', qnospecialchar($theAnswerArray['optionAnswer']));
		$rowTotalNum = 0;

		foreach ($theXName as $k => $thisXName) {
			$EnableQCoreClass->replace('cellNum', $ObsFreq[$m][$k]);
			$rowTotalNum += $ObsFreq[$m][$k];
			$EnableQCoreClass->replace('cellPercent', countpercent($ObsFreq[$m][$k], $theColTotal[$k]));
			$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
		}

		$EnableQCoreClass->replace('rowTotalNum', $rowTotalNum);
		$EnableQCoreClass->replace('rowTotalPercent', countpercent($rowTotalNum, $repTotalAnswerNum));
		$m++;
		$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
		$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
	}

	foreach ($theXName as $p => $thisXName) {
		$colTotalNum = ($theColTotal[$p] == '' ? 0 : $theColTotal[$p]);
		$EnableQCoreClass->replace('colTotalNum', $colTotalNum);
		$EnableQCoreClass->replace('colTotalPercent', $colTotalNum == 0 ? '0%' : '100%');
		$EnableQCoreClass->parse('total' . $theDefineReportText, 'TOTAL', true);
	}

	unset($theColTotal);
	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$EnableQCoreClass->parse('option' . $theDefineReportText, 'OPTION', true);
	$EnableQCoreClass->unreplace('rows' . $theDefineReportText);
	$EnableQCoreClass->unreplace('cols' . $theDefineReportText);
	$EnableQCoreClass->unreplace('total' . $theDefineReportText);
}

unset($optionArray);
unset($ObsFreq);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $theDefineReportText, 'ShowOption' . $theDefineReportText . 'File');

?>

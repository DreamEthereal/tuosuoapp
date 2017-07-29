<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $theDefineReportText . 'File', 'DeCross1D.html');
$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']));
$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'ROWS', 'rows' . $theDefineReportText);
$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $theDefineReportText);
$EnableQCoreClass->replace('rows' . $theDefineReportText, '');
$EnableQCoreClass->replace('cell' . $theDefineReportText, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'COLS', 'cols' . $theDefineReportText);
$EnableQCoreClass->replace('cols' . $theDefineReportText, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $theDefineReportText . 'File', 'TOTAL', 'total' . $theDefineReportText);
$EnableQCoreClass->replace('total' . $theDefineReportText, '');
$ObsFreq = array();
$theColTotal = array();

foreach ($theXName as $k => $thisXName) {
	$EnableQCoreClass->replace('colName', $thisXName);
	$EnableQCoreClass->parse('cols' . $theDefineReportText, 'COLS', true);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$l = 0;
	$OptionSQL = ' SELECT a.question_radioID,count(*) as optionResponseNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_radioID = b.option_' . $questionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_radioID'];
		$allOptionResponseNum[$OptionRow['question_radioID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
		if (in_array($question_radioID, $allResponseOptionID)) {
			$ObsFreq[$l][$k] = $allOptionResponseNum[$question_radioID];
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

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$EnableQCoreClass->replace('rowName', qnospecialchar($theQuestionArray['optionName']));
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
unset($ObsFreq);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $theDefineReportText, 'ShowOption' . $theDefineReportText . 'File');

?>

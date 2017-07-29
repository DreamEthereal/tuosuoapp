<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'ChiCross1D.html');
$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']));
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ROWS', 'rows' . $questionID);
$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $questionID);
$EnableQCoreClass->replace('rows' . $questionID, '');
$EnableQCoreClass->replace('cell' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'COLS', 'cols' . $questionID);
$EnableQCoreClass->replace('cols' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'TOTAL', 'total' . $questionID);
$EnableQCoreClass->replace('total' . $questionID, '');
$ObsFreq = array();
$theColTotal = array();

foreach ($theXName as $k => $thisXName) {
	$EnableQCoreClass->replace('colName', $thisXName);
	$EnableQCoreClass->parse('cols' . $questionID, 'COLS', true);
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

	if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[$l][$k] = $OptionCountRow['optionResponseNum'];
		$theColTotal[$k] += $ObsFreq[$l][$k];
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
		$EnableQCoreClass->parse('cell' . $questionID, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowTotalNum', $rowTotalNum);
	$EnableQCoreClass->replace('rowTotalPercent', countpercent($rowTotalNum, $repTotalAnswerNum));
	$m++;
	$EnableQCoreClass->parse('rows' . $questionID, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $questionID);
}

if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['otherText']));
	$rowTotalNum = 0;

	foreach ($theXName as $k => $thisXName) {
		$EnableQCoreClass->replace('cellNum', $ObsFreq[$m][$k]);
		$rowTotalNum += $ObsFreq[$m][$k];
		$EnableQCoreClass->replace('cellPercent', countpercent($ObsFreq[$m][$k], $theColTotal[$k]));
		$EnableQCoreClass->parse('cell' . $questionID, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowTotalNum', $rowTotalNum);
	$EnableQCoreClass->replace('rowTotalPercent', countpercent($rowTotalNum, $repTotalAnswerNum));
	$EnableQCoreClass->parse('rows' . $questionID, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $questionID);
}

foreach ($theXName as $p => $thisXName) {
	$colTotalNum = ($theColTotal[$p] == '' ? 0 : $theColTotal[$p]);
	$EnableQCoreClass->replace('colTotalNum', $colTotalNum);
	$EnableQCoreClass->replace('colTotalPercent', $colTotalNum == 0 ? '0%' : '100%');
	$EnableQCoreClass->parse('total' . $questionID, 'TOTAL', true);
}

unset($theColTotal);
unset($allResponseOptionID);
unset($allOptionResponseNum);
define('PHP_MATH', ROOT_PATH . 'PDL/');
require_once PHP_MATH . 'ChiSquare2D.php';
$Alpha = 0.05;
$Chi = new ChiSquare2D($ObsFreq, $Alpha);
$format = '%01.2f';
$ChiSqObt = sprintf($format, $Chi->ChiSqObt);
$ChiSqProb = sprintf($format, $Chi->ChiSqProb);
$ChiSqCrit = sprintf($format, $Chi->ChiSqCrit);
$EnableQCoreClass->replace('ChiSqObt', $ChiSqObt);
$EnableQCoreClass->replace('ChiSqProb', $ChiSqProb);
$EnableQCoreClass->replace('ChiSqCrit', $ChiSqCrit);
$EnableQCoreClass->replace('dfValue', $Chi->DF);
unset($ObsFreq);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

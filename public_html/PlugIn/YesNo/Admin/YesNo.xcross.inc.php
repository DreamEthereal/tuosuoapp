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
	$l = 0;

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $question_yesnoID . '\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[$l][$k] = $OptionCountRow['optionResponseNum'];
		$theColTotal[$k] += $OptionCountRow['optionResponseNum'];
		$l++;
	}
}

$repTotalAnswerNum = array_sum($theColTotal);
$EnableQCoreClass->replace('repTotalAnswerNum', $repTotalAnswerNum);
$m = 0;

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$EnableQCoreClass->replace('rowName', $theQuestionArray['optionName']);
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

foreach ($theXName as $p => $thisXName) {
	$colTotalNum = ($theColTotal[$p] == '' ? 0 : $theColTotal[$p]);
	$EnableQCoreClass->replace('colTotalNum', $colTotalNum);
	$EnableQCoreClass->replace('colTotalPercent', $colTotalNum == 0 ? '0%' : '100%');
	$EnableQCoreClass->parse('total' . $questionID, 'TOTAL', true);
}

unset($theColTotal);
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

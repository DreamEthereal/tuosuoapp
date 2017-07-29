<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'ChiCross2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'ROWS', 'rows' . $questionID);
$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $questionID);
$EnableQCoreClass->replace('rows' . $questionID, '');
$EnableQCoreClass->replace('cell' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'COLS', 'cols' . $questionID);
$EnableQCoreClass->replace('cols' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'TOTAL', 'total' . $questionID);
$EnableQCoreClass->replace('total' . $questionID, '');
$optionOrderNum = count($RankListArray[$questionID]);

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$optionOrderNum++;
	$theRankListArray = $RankListArray[$questionID];
	$theRankListArray[0] = array();
}
else {
	$theRankListArray = $RankListArray[$questionID];
}

foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
	if ($question_rankID == 0) {
		$optionName = qcrossqtnname($QtnListArray[$questionID]['questionName']) . ' - ' . qcrossqtnname($QtnListArray[$questionID]['otherText']);
		$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']));
	}
	else {
		$optionName = qcrossqtnname($QtnListArray[$questionID]['questionName']) . ' - ' . qcrossqtnname($theQuestionArray['optionName']);
		$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
	}

	$ObsFreq = array();
	$theColTotal = array();

	foreach ($theXName as $k => $thisXName) {
		$EnableQCoreClass->replace('colName', $thisXName);
		$EnableQCoreClass->parse('cols' . $questionID, 'COLS', true);
		$l = 0;
		$allResponseOptionID = array();
		$allOptionResponseNum = array();
		$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
		$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
			$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
		}

		$m = 1;

		for (; $m <= $optionOrderNum; $m++) {
			if (in_array($m, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$m];
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
	$p = 1;

	for (; $p <= $optionOrderNum; $p++) {
		$EnableQCoreClass->replace('rowName', $p);
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
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('rows' . $questionID);
	$EnableQCoreClass->unreplace('cols' . $questionID);
	$EnableQCoreClass->unreplace('total' . $questionID);
}

unset($ObsFreq);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

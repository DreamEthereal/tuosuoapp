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
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$u = 1;

for (; $u <= $QtnListArray[$questionID]['maxSize']; $u++) {
	$EnableQCoreClass->replace('rows' . $questionID, '');
	$tmp = $u - 1;
	$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]));
	$ObsFreq = array();
	$theColTotal = array();
	$theExistArray = array();

	foreach ($theXName as $k => $thisXName) {
		$EnableQCoreClass->replace('colName', $thisXName);
		$EnableQCoreClass->parse('cols' . $questionID, 'COLS', true);
		$allResponseOptionID = array();
		$allOptionResponseNum = array();
		$l = 0;
		$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $u . ' and a.level = \'' . $u . '\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $u . ' ORDER BY nodeID DESC';
		$OptionResult = $DB->query($OptionSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$theExistArray[$OptionRow['nodeID']] = $OptionRow['nodeName'];
			$allResponseOptionID[] = $OptionRow['nodeID'];
			$allOptionResponseNum[$OptionRow['nodeID']] = $OptionRow['optionResponseNum'];
		}

		foreach ($theExistArray as $nodeID => $nodeName) {
			if (in_array($nodeID, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$nodeID];
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

	foreach ($theExistArray as $nodeID => $nodeName) {
		$EnableQCoreClass->replace('rowName', qnospecialchar($nodeName));
		$rowTotalNum = 0;

		foreach ($theXName as $k => $thisXName) {
			$cellNum = (isset($ObsFreq[$m][$k]) ? $ObsFreq[$m][$k] : 0);
			$EnableQCoreClass->replace('cellNum', $cellNum);
			$rowTotalNum += $cellNum;
			$EnableQCoreClass->replace('cellPercent', countpercent($cellNum, $theColTotal[$k]));
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

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	if ($theBaseQtnArray['isHaveOther'] == 1) {
		$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$optionArray[99999] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']);
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
		$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND a.question_checkboxID = b.option_' . $questionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
		$OptionResult = $DB->query($OptionSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$allResponseOptionID[] = $OptionRow['question_checkboxID'];
			$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
		}

		if ($theBaseQtnArray['isHaveOther'] == 1) {
			$OptionSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
			$OptionRow = $DB->queryFirstRow($OptionSQL);
			$allResponseOptionID[] = 0;
			$allOptionResponseNum[0] = $OptionRow['optionResponseNum'];
		}

		if ($QtnListArray[$questionID]['isCheckType'] == '1') {
			$OptionSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
			$OptionRow = $DB->queryFirstRow($OptionSQL);
			$allResponseOptionID[] = 99999;
			$allOptionResponseNum[99999] = $OptionRow['optionResponseNum'];
		}

		foreach ($optionArray as $question_checkboxID => $optionName) {
			if (in_array($question_checkboxID, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$question_checkboxID];
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

	foreach ($optionArray as $question_checkboxID => $optionName) {
		$EnableQCoreClass->replace('rowName', $optionName);
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
	unset($ObsFreq);
	unset($optionArray);
	$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
}
else {
	if ($theBaseQtnArray['isHaveOther'] == 1) {
		$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$optionArray[99999] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']);
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
		$OptionCountSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
		$OptionResult = $DB->query($OptionCountSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$allResponseOptionID[] = $OptionRow['question_checkboxID'];
			$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
		}

		if ($theBaseQtnArray['isHaveOther'] == 1) {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') AND ' . $theXCond[$k] . ' and ' . $dataSource;
			$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
			$allResponseOptionID[] = 0;
			$allOptionResponseNum[0] = $OptionCountRow['optionResponseNum'];
		}

		if ($QtnListArray[$questionID]['isCheckType'] == 1) {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') AND ' . $theXCond[$k] . ' and ' . $dataSource;
			$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
			$allResponseOptionID[] = 99999;
			$allOptionResponseNum[99999] = $OptionCountRow['optionResponseNum'];
		}

		foreach ($optionArray as $question_checkboxID => $optionName) {
			if (in_array($question_checkboxID, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$question_checkboxID];
			}
			else {
				$ObsFreq[$l][$k] = 0;
			}

			$l++;
		}

		$OptionSQL = ' SELECT count(*) as theColTotal FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionRow = $DB->queryFirstRow($OptionSQL);
		$theColTotal[$k] = $OptionRow['theColTotal'];
	}

	$repTotalAnswerNum = array_sum($theColTotal);
	$EnableQCoreClass->replace('repTotalAnswerNum', $repTotalAnswerNum);
	$m = 0;
	$theColPercentTotal = array();

	foreach ($optionArray as $question_checkboxID => $optionName) {
		$EnableQCoreClass->replace('rowName', $optionName);
		$rowTotalNum = 0;

		foreach ($theXName as $k => $thisXName) {
			$EnableQCoreClass->replace('cellNum', $ObsFreq[$m][$k]);
			$rowTotalNum += $ObsFreq[$m][$k];
			$cellPercent = countpercent($ObsFreq[$m][$k], $theColTotal[$k]);
			$EnableQCoreClass->replace('cellPercent', $cellPercent);
			$theColPercentTotal[$k] += $cellPercent;
			$EnableQCoreClass->parse('cell' . $questionID, 'CELL', true);
		}

		$EnableQCoreClass->replace('rowTotalNum', $rowTotalNum);
		$rowTotalPercent = countpercent($rowTotalNum, $repTotalAnswerNum);
		$EnableQCoreClass->replace('rowTotalPercent', $rowTotalPercent);
		$theColPercentTotal['total'] += $rowTotalPercent;
		$m++;
		$EnableQCoreClass->parse('rows' . $questionID, 'ROWS', true);
		$EnableQCoreClass->unreplace('cell' . $questionID);
	}

	foreach ($theXName as $p => $thisXName) {
		$colTotalNum = ($theColTotal[$p] == '' ? 0 : $theColTotal[$p]);
		$EnableQCoreClass->replace('colTotalNum', $colTotalNum);
		$EnableQCoreClass->replace('colTotalPercent', $theColPercentTotal[$p] . '%');
		$EnableQCoreClass->parse('total' . $questionID, 'TOTAL', true);
	}

	$theTotalPercent = $theColPercentTotal['total'];
	unset($theColTotal);
	unset($theColPercentTotal);
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
	unset($optionArray);
	$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
	$DataCrossHTML = str_replace('<b id="totalPercent">100%</b>', '<b>' . $theTotalPercent . '%</b>', $DataCrossHTML);
}

?>

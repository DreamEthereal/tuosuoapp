<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($QtnListArray[$questionID]['isCheckType'] == '4') {
	$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']));
	$rowValidN = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$theOptionID = 'option_' . $questionID;
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theOptionID . ' != \'\' ';
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
		$OptionCountSQL = ' SELECT SUM(' . $theOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theOptionID . ' !=\'\' and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$this_sum_answerNum = $OptionCountRow['item_sum_answerNum'];
		$rowValidN += $thisOptionResponseNum;
		$rowSum += $this_sum_answerNum;
		$EnableQCoreClass->replace('validN', $thisOptionResponseNum);
		$EnableQCoreClass->replace('mean', meanaverage($this_sum_answerNum, $thisOptionResponseNum));
		$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowValidN', $rowValidN);
	$EnableQCoreClass->replace('rowMean', meanaverage($rowSum, $rowValidN));
	$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
}

?>

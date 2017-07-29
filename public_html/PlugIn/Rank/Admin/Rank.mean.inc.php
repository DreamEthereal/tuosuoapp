<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$theRankListArray = $RankListArray[$questionID];
	$theRankListArray[0] = array();
}
else {
	$theRankListArray = $RankListArray[$questionID];
}

foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
	if ($question_rankID == 0) {
		$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']));
	}
	else {
		$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
	}

	$rowValidN = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' AND b.' . $theRankOptionID . ' != 0 ';
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
		$OptionCountSQL = ' SELECT SUM(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 and ' . $theXCond[$k] . ' and ' . $dataSource . ' ';
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

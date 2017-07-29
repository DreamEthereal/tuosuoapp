<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($QtnListArray[$questionID]['isSelect'] == 1) {
}
else {
	$isUnkown = array();
	$OptSQL = ' SELECT question_yesnoID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $questionID . '\' AND isUnkown =1 ORDER BY question_yesnoID ';
	$OptResult = $DB->query($OptSQL);

	while ($OptRow = $DB->queryArray($OptResult)) {
		$isUnkown[] = $OptRow['question_yesnoID'];
	}

	$EnableQCoreClass->replace('rowName', qnospecialchar($QtnListArray[$questionID]['questionName']));
	$rowValidN = $rowValidNum = $rowSum = 0;

	foreach ($theXName as $k => $thisXName) {
		$allOptionResponseNum = array();
		$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
		$OptionResult = $DB->query($OptionSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
		}

		$this_sum_answerNum = 0;
		$thisOptionResponseNum = 0;
		$unkownNum = 0;

		foreach ($allOptionResponseNum as $question_yesnoID => $optionResponseNum) {
			if (!in_array($question_yesnoID, $isUnkown)) {
				$this_sum_answerNum += $YesNoListArray[$questionID][$question_yesnoID]['itemCode'] * $optionResponseNum;
			}
			else {
				$unkownNum += $optionResponseNum;
			}

			$thisOptionResponseNum += $optionResponseNum;
		}

		$rowValidN += $thisOptionResponseNum;
		$rowValidNum += $thisOptionResponseNum - $unkownNum;
		$rowSum += $this_sum_answerNum;
		$EnableQCoreClass->replace('validN', $thisOptionResponseNum);
		$EnableQCoreClass->replace('mean', meanaverage($this_sum_answerNum, $thisOptionResponseNum - $unkownNum));
		$EnableQCoreClass->parse('cell' . $theDefineReportText, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowValidN', $rowValidN);
	$EnableQCoreClass->replace('rowMean', meanaverage($rowSum, $rowValidNum));
	$EnableQCoreClass->parse('rows' . $theDefineReportText, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $theDefineReportText);
}

?>

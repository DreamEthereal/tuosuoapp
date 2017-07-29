<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$allResponseOptionID = array();
$allOptionResponseNum = array();

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$totalValue = $theTotalResponseNum;
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_yesnoID'];
		$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$Headings[] = qcrossqtnname($theQuestionArray['optionName']);

		if (in_array($question_yesnoID, $allResponseOptionID)) {
			$ObsFreq[] = $allOptionResponseNum[$question_yesnoID];
		}
		else {
			$ObsFreq[] = 0;
		}
	}
}
else {
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_yesnoID'];
		$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$Headings[] = qcrossqtnname($theQuestionArray['optionName']);

		if (in_array($question_yesnoID, $allResponseOptionID)) {
			$ObsFreq[] = $allOptionResponseNum[$question_yesnoID];
		}
		else {
			$ObsFreq[] = 0;
		}
	}

	$totalValue = array_sum($ObsFreq);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

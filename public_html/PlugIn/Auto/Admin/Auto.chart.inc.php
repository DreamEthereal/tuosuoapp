<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$allResponseOptionID = array();
$allOptionResponseNum = array();

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND a.question_checkboxID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_checkboxID'];
		$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$Headings[] = qcrossqtnname($theQuestionArray['optionName']);

		if (in_array($question_checkboxID, $allResponseOptionID)) {
			$ObsFreq[] = $allOptionResponseNum[$question_checkboxID];
		}
		else {
			$ObsFreq[] = 0;
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$Headings[] = qcrossqtnname($theBaseQtnArray['otherText']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[] = $OptionCountRow['optionResponseNum'];
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$Headings[] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qcrossqtnname($QtnListArray[$questionID]['allowType']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[] = $OptionCountRow['optionResponseNum'];
	}

	$totalValue = array_sum($ObsFreq);
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$totalValue = $theTotalResponseNum;
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_checkboxID'];
		$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$Headings[] = qcrossqtnname($theQuestionArray['optionName']);

		if (in_array($question_checkboxID, $allResponseOptionID)) {
			$ObsFreq[] = $allOptionResponseNum[$question_checkboxID];
		}
		else {
			$ObsFreq[] = 0;
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$Headings[] = qcrossqtnname($theBaseQtnArray['otherText']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[] = $OptionCountRow['optionResponseNum'];
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$Headings[] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qcrossqtnname($QtnListArray[$questionID]['allowType']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[] = $OptionCountRow['optionResponseNum'];
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

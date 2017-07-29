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
$Headings = $ObsFreq = array();

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$theRadioType = true;
	$TitleName = qnospecialchar($QtnListArray[$questionID]['questionName']);
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND a.question_checkboxID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_checkboxID'];
		$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$Headings[] = qnospecialchar($theQuestionArray['optionName']);

		if (in_array($question_checkboxID, $allResponseOptionID)) {
			$ObsFreq[] = $allOptionResponseNum[$question_checkboxID];
		}
		else {
			$ObsFreq[] = 0;
		}
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$Headings[] = qnospecialchar($theBaseQtnArray['otherText']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'0\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[] = $OptionCountRow['optionResponseNum'];
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$Headings[] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' = \'99999\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[] = $OptionCountRow['optionResponseNum'];
	}
}
else {
	$theRadioType = false;
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $theBaseID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_checkboxID'];
		$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
	}

	$t = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$theTitleName[$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']);
		$Headings[$t][0] = $lang['checkbox_checked'];
		$Headings[$t][1] = $lang['checkbox_no_checked'];

		if (in_array($question_checkboxID, $allResponseOptionID)) {
			$ObsFreq[$t][0] = $allOptionResponseNum[$question_checkboxID];
			$ObsFreq[$t][1] = $theTotalResponseNum - $allOptionResponseNum[$question_checkboxID];
		}
		else {
			$ObsFreq[$t][0] = 0;
			$ObsFreq[$t][1] = $theTotalResponseNum;
		}

		$t++;
	}

	if ($theBaseQtnArray['isHaveOther'] == '1') {
		$theTitleName[$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$Headings[$t][0] = $lang['checkbox_checked'];
		$ObsFreq[$t][0] = $OptionCountRow['optionResponseNum'];
		$Headings[$t][1] = $lang['checkbox_no_checked'];
		$ObsFreq[$t][1] = $theTotalResponseNum - $OptionCountRow['optionResponseNum'];
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$t++;
		$theTitleName[$t] = $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']);
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$Headings[$t][0] = $lang['checkbox_checked'];
		$ObsFreq[$t][0] = $OptionCountRow['optionResponseNum'];
		$Headings[$t][1] = $lang['checkbox_no_checked'];
		$ObsFreq[$t][1] = $theTotalResponseNum - $OptionCountRow['optionResponseNum'];
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE (b.option_' . $questionID . ' != \'\') OR ( b.option_' . $questionID . ' = \'\' AND b.TextOtherValue_' . $questionID . ' != \'\') and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') and ' . $dataSource;
$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_checkboxID'];
	$allOptionResponseNum[$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
}

$t = 0;
$Headings = $ObsFreq = array();

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
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

unset($allResponseOptionID);
unset($allOptionResponseNum);
if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$theTitleName[$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($QtnListArray[$questionID]['otherText']);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$Headings[$t][0] = $lang['checkbox_checked'];
	$ObsFreq[$t][0] = $OptionCountRow['optionResponseNum'];
	$Headings[$t][1] = $lang['checkbox_no_checked'];
	$ObsFreq[$t][1] = $theTotalResponseNum - $OptionCountRow['optionResponseNum'];
}

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$t++;
	$negText = ($QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']));
	$theTitleName[$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . $negText;
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$Headings[$t][0] = $lang['checkbox_checked'];
	$ObsFreq[$t][0] = $OptionCountRow['optionResponseNum'];
	$Headings[$t][1] = $lang['checkbox_no_checked'];
	$ObsFreq[$t][1] = $theTotalResponseNum - $OptionCountRow['optionResponseNum'];
}

?>

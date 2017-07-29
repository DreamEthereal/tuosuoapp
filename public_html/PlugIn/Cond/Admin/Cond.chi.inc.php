<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$allResponseOptionID = array();
$allOptionResponseNum = array();
$Headings = $ObsFreq = array();

if ($QtnListArray[$questionID]['isSelect'] == 1) {
	$theRadioType = false;
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_yesnoID,b.option_' . $questionID . ') and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_yesnoID ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_yesnoID'];
		$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
	}

	$t = 0;

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$theTitleName[$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']);
		$Headings[$t][0] = $lang['checkbox_checked'];
		$Headings[$t][1] = $lang['checkbox_no_checked'];

		if (in_array($question_yesnoID, $allResponseOptionID)) {
			$ObsFreq[$t][0] = $allOptionResponseNum[$question_yesnoID];
			$ObsFreq[$t][1] = $theTotalResponseNum - $allOptionResponseNum[$question_yesnoID];
		}
		else {
			$ObsFreq[$t][0] = 0;
			$ObsFreq[$t][1] = $theTotalResponseNum;
		}

		$t++;
	}
}
else {
	$theRadioType = true;
	$TitleName = qnospecialchar($QtnListArray[$questionID]['questionName']);
	$OptionSQL = ' SELECT a.question_yesnoID,a.optionName,count(*) as optionResponseNum FROM ' . QUESTION_YESNO_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_yesnoID = b.option_' . $questionID . ' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[] = $OptionRow['question_yesnoID'];
		$allOptionResponseNum[$OptionRow['question_yesnoID']] = $OptionRow['optionResponseNum'];
	}

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$Headings[] = qnospecialchar($theQuestionArray['optionName']);

		if (in_array($question_yesnoID, $allResponseOptionID)) {
			$ObsFreq[] = $allOptionResponseNum[$question_yesnoID];
		}
		else {
			$ObsFreq[] = 0;
		}
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

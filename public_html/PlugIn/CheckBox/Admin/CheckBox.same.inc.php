<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DataMatchingSame1D.html');
$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']));
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'TIME', 'time' . $questionID);
$EnableQCoreClass->replace('time' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'DIMSUM', 'dimsum' . $questionID);
$EnableQCoreClass->replace('dimsum' . $questionID, '');
$theTimes = explode('^', trim($theTimeCharText));
$allResponseOptionID = array();
$allOptionResponseNum = array();
$theTotalResponseNum = array();

foreach ($theTimes as $theTime) {
	$theTimeArray = explode('*', $theTime);
	$theBeginTime = $theTimeArray[0];
	$theEndTime = $theTimeArray[1];
	$EnableQCoreClass->replace('timeName', date('y-n-j', $theBeginTime) . ' - ' . date('y-n-j', $theEndTime));
	$EnableQCoreClass->parse('time' . $questionID, 'TIME', true);
	$OptionSQL = ' SELECT a.question_checkboxID,count(*) as optionResponseNum FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND FIND_IN_SET(a.question_checkboxID,b.option_' . $questionID . ') AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY a.question_checkboxID ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$allResponseOptionID[$theTime][] = $OptionRow['question_checkboxID'];
		$allOptionResponseNum[$theTime][$OptionRow['question_checkboxID']] = $OptionRow['optionResponseNum'];
	}

	if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ((b.option_' . $questionID . ' != \'\') OR ( b.option_' . $questionID . ' = \'\' AND b.TextOtherValue_' . $questionID . ' != \'\')) AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' != \'\' AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum[$theTime] = $OptionCountRow['optionResponseNum'];
	$EnableQCoreClass->replace('dimSum', $OptionCountRow['optionResponseNum']);
	$EnableQCoreClass->parse('dimsum' . $questionID, 'DIMSUM', true);
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'DIM', 'dim' . $questionID);
$EnableQCoreClass->set_CycBlock('DIM', 'DIMNUM', 'dimnum' . $questionID);
$EnableQCoreClass->replace('dim' . $questionID, '');
$EnableQCoreClass->replace('dimnum' . $questionID, '');

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$EnableQCoreClass->replace('dimName', qnospecialchar($theQuestionArray['optionName']));

	foreach ($theTimes as $theTime) {
		if (in_array($question_checkboxID, $allResponseOptionID[$theTime])) {
			$EnableQCoreClass->replace('dimNum', $allOptionResponseNum[$theTime][$question_checkboxID]);
			$EnableQCoreClass->replace('dimPercent', countpercent($allOptionResponseNum[$theTime][$question_checkboxID], $theTotalResponseNum[$theTime]));
		}
		else {
			$EnableQCoreClass->replace('dimNum', 0);
			$EnableQCoreClass->replace('dimPercent', 0);
		}

		$EnableQCoreClass->parse('dimnum' . $questionID, 'DIMNUM', true);
	}

	$EnableQCoreClass->parse('dim' . $questionID, 'DIM', true);
	$EnableQCoreClass->unreplace('dimnum' . $questionID);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$EnableQCoreClass->replace('dimName', qnospecialchar($QtnListArray[$questionID]['otherText']));

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(0,b.option_' . $questionID . ') AND b.TextOtherValue_' . $questionID . ' != \'\' AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('dimNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('dimPercent', countpercent($OptionCountRow['optionResponseNum'], $theTotalResponseNum[$theTime]));
		$EnableQCoreClass->parse('dimnum' . $questionID, 'DIMNUM', true);
	}

	$EnableQCoreClass->parse('dim' . $questionID, 'DIM', true);
	$EnableQCoreClass->unreplace('dimnum' . $questionID);
}

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$EnableQCoreClass->replace('dimName', $QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']));

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE FIND_IN_SET(99999,b.option_' . $questionID . ') AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$EnableQCoreClass->replace('dimNum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->replace('dimPercent', countpercent($OptionCountRow['optionResponseNum'], $theTotalResponseNum[$theTime]));
		$EnableQCoreClass->parse('dimnum' . $questionID, 'DIMNUM', true);
	}

	$EnableQCoreClass->parse('dim' . $questionID, 'DIM', true);
	$EnableQCoreClass->unreplace('dimnum' . $questionID);
}

unset($theTotalResponseNum);
$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

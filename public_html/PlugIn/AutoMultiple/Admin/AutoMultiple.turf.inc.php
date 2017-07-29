<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'TURFAnalytics.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($theOptionID == 0) {
	$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']));
}
else {
	$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theCheckBoxListArray[$theOptionID]['optionName']));
}

$CountSQL = ' SELECT COUNT(*) AS totalRepAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($CountSQL);
$totalRepAnswerNum = $CountRow['totalRepAnswerNum'];
$EnableQCoreClass->replace('totalRepAnswerNum', $totalRepAnswerNum);
$skipSQL = ' b.option_' . $questionID . '_' . $theOptionID . ' != \'\' ';
$OptionCountSQL = ' SELECT COUNT(*) AS thisOptionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ' . $skipSQL . ' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$thisOptionResponseNum = $OptionCountRow['thisOptionResponseNum'];
$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));
$combArray = array();

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$combArray[] = $question_range_answerID;
}

$theCombArray = combination($combArray, $_POST['maxLevel']);
$theCombNum = count($theCombArray);

if ($Config['turf_comb_num'] < $theCombNum) {
	exit($lang['comb_num'] . $theCombNum . '<br/><b>' . $lang['too_many_comb'] . '</b>');
}

$theAfterTurfName = $theAfterTurfNum = array();
$tmp = 0;

foreach ($theCombArray as $thisComb) {
	$comboName = '';
	$comboSQL = ' ( ';
	$theComb = explode(',', $thisComb);

	foreach ($theComb as $question_range_answerID) {
		$comboName .= qnospecialchar($AnswerListArray[$questionID][$question_range_answerID]['optionAnswer']) . '<br/>&nbsp;';
		$comboSQL .= ' FIND_IN_SET(\'' . $question_range_answerID . '\',b.option_' . $questionID . '_' . $theOptionID . ') OR ';
	}

	$comboSQL = substr($comboSQL, 0, -4) . ' ) ';
	$theAfterTurfName[$tmp]['comboName'] = substr($comboName, 0, -11);
	$OptionSQL = ' SELECT count(*) as optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ' . $skipSQL . ' and ' . $comboSQL . ' and ' . $dataSource;
	$OptionRow = $DB->queryFirstRow($OptionSQL);
	$theAfterTurfNum[$tmp] = $OptionRow['optionResponseNum'];
	$tmp++;
}

arsort($theAfterTurfNum);
$orderNo = 0;

foreach ($theAfterTurfNum as $order => $optionResponseNum) {
	if ($_POST['showNum'] <= $orderNo) {
		break;
	}

	$orderNo++;
	$EnableQCoreClass->replace('orderNo', $orderNo);
	$EnableQCoreClass->replace('answerNum', $optionResponseNum);
	$EnableQCoreClass->replace('optionPercent', countpercent($optionResponseNum, $thisOptionResponseNum));
	$EnableQCoreClass->replace('comboName', $theAfterTurfName[$order]['comboName']);
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

unset($combArray);
unset($theCombArray);
unset($theAfterTurfName);
unset($theAfterTurfNum);
unset($theComb);
$TURFHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

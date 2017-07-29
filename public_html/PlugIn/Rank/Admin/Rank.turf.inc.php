<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'TURFAnalytics.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']));
$CountSQL = ' SELECT COUNT(*) AS totalRepAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($CountSQL);
$totalRepAnswerNum = $CountRow['totalRepAnswerNum'];
$EnableQCoreClass->replace('totalRepAnswerNum', $totalRepAnswerNum);
$skipSQL = '';
$combArray = array();

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$combArray[] = $question_rankID;
	$skipSQL .= ' b.option_' . $questionID . '_' . $question_rankID . ' !=0 AND ';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$combArray[] = 0;
	$skipSQL .= ' b.option_' . $questionID . '_0 !=0 AND ';
}

$skipSQL = substr($skipSQL, 0, -4);
$OptionCountSQL = ' SELECT COUNT(*) AS thisOptionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ' . $skipSQL . ' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$thisOptionResponseNum = $OptionCountRow['thisOptionResponseNum'];
$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));
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

	foreach ($theComb as $question_rankID) {
		if ($question_rankID == 0) {
			$comboName .= qnospecialchar($QtnListArray[$questionID]['otherText']) . '<br/>&nbsp;';
		}
		else {
			$comboName .= qnospecialchar($RankListArray[$questionID][$question_rankID]['optionName']) . '<br/>&nbsp;';
		}

		$comboSQL .= ' b.option_' . $questionID . '_' . $question_rankID . ' IN (' . $_POST['trueOptions'] . ') OR ';
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

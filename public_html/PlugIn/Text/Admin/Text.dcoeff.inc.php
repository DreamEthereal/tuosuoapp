<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $theCoeffReportId . 'File', 'TextCoeffView.html');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_4_4'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($theQtnArray['isHaveUnkown'] == 2) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ( (b.option_' . $questionID . ' != \'\' AND b.isHaveUnkown_' . $questionID . ' = 0 ) OR b.isHaveUnkown_' . $questionID . ' = 1 ) and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
$answerNumAvg = $answerNum = $thisOptionResponseNum;
$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
$thisSkipNum = $totalResponseNum - $thisOptionResponseNum;
$EnableQCoreClass->replace('skip_answerNum', $thisSkipNum);
$OptionValueSQL = ' SELECT SUM(option_' . $questionID . ') AS totalValueNum,MAX(option_' . $questionID . '+1) AS maxValueNum,MIN(option_' . $questionID . '+1) AS minValueNum, STDDEV(option_' . $questionID . ') as stdDev FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
$OptionValueRow = $DB->queryFirstRow($OptionValueSQL);

if ($OptionValueRow) {
	$EnableQCoreClass->replace('totalValueNum', $OptionValueRow['totalValueNum']);

	if ($OptionValueRow['maxValueNum'] == 0) {
		$EnableQCoreClass->replace('maxValueNum', 0);
	}
	else {
		$EnableQCoreClass->replace('maxValueNum', $OptionValueRow['maxValueNum'] - 1);
	}

	if ($OptionValueRow['minValueNum'] == 0) {
		$EnableQCoreClass->replace('minValueNum', 0);
	}
	else {
		$EnableQCoreClass->replace('minValueNum', $OptionValueRow['minValueNum'] - 1);
	}

	$EnableQCoreClass->replace('stdDev', @round($OptionValueRow['stdDev'], 5));
}
else {
	$EnableQCoreClass->replace('totalValueNum', 0);
	$EnableQCoreClass->replace('maxValueNum', 0);
	$EnableQCoreClass->replace('minValueNum', 0);
	$EnableQCoreClass->replace('stdDev', '');
}

$OptionValueSQL = ' SELECT option_' . $questionID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
$OptionValueSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY count DESC ';
$ReValueRow = $DB->queryFirstRow($OptionValueSQL);

if ($ReValueRow) {
	$EnableQCoreClass->replace('reValueNum', $ReValueRow['option_' . $questionID]);
}
else {
	$EnableQCoreClass->replace('reValueNum', 0);
}

if ($answerNum == 0) {
	$EnableQCoreClass->replace('avgValueNum', 0);
	$EnableQCoreClass->replace('unKnowNum', 0);
}
else {
	if ($theQtnArray['isHaveUnkown'] == 2) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$answerNumAvg = $OptionCountRow['optionResponseNum'];
		$EnableQCoreClass->replace('unKnowNum', $answerNum - $answerNumAvg);
	}
	else {
		$EnableQCoreClass->replace('unKnowNum', 0);
	}

	$avgValueNum = @round($OptionValueRow['totalValueNum'] / $answerNumAvg, 5);
	$EnableQCoreClass->replace('avgValueNum', $avgValueNum);
}

$DataCrossHTML0 = $EnableQCoreClass->parse('ShowOption' . $theCoeffReportId, 'ShowOption' . $theCoeffReportId . 'File');
$DataCrossHTML = '<table width="100%">' . $DataCrossHTML0 . '</table>';

?>

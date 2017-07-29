<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $theCoeffReportId . 'File', 'CombTextCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $theCoeffReportId . 'File', 'LIST', 'list' . $theCoeffReportId);
$EnableQCoreClass->replace('list' . $theCoeffReportId, '');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_23'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	if ($theQuestionArray['isCheckType'] == '4') {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));

		if ($theQtnArray['isHaveUnkown'] == 2) {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ( (b.option_' . $questionID . '_' . $question_yesnoID . ' != \'\' AND b.isHaveUnkown_' . $questionID . '_' . $question_yesnoID . ' = 0 ) OR b.isHaveUnkown_' . $questionID . '_' . $question_yesnoID . ' = 1 ) and ' . $dataSource;
		}
		else {
			$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_yesnoID . ' != \'\' and ' . $dataSource;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$answerNum = $answerNumAvg = $OptionCountRow['optionResponseNum'];
		$EnableQCoreClass->replace('answerNum', $OptionCountRow['optionResponseNum']);
		$skip_answerNum = $totalResponseNum - $answerNum;
		$EnableQCoreClass->replace('skip_answerNum', $skip_answerNum);
		$OptionValueSQL = ' SELECT SUM(option_' . $questionID . '_' . $question_yesnoID . ') AS totalValueNum,MAX(option_' . $questionID . '_' . $question_yesnoID . '+1) AS maxValueNum,MIN(option_' . $questionID . '_' . $question_yesnoID . '+1) AS minValueNum, STDDEV(option_' . $questionID . '_' . $question_yesnoID . ') as stdDev FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_yesnoID . ' != \'\' and ' . $dataSource;
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

		$OptionValueSQL = ' SELECT option_' . $questionID . '_' . $question_yesnoID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
		$OptionValueSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_yesnoID . ' ORDER BY count DESC ';
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
				$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_yesnoID . ' != \'\' and ' . $dataSource;
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

		$EnableQCoreClass->parse('list' . $theCoeffReportId, 'LIST', true);
	}
}

$DataCrossHTML0 = $EnableQCoreClass->parse('ShowOption' . $theCoeffReportId, 'ShowOption' . $theCoeffReportId . 'File');
$DataCrossHTML = '<table width="100%">' . $DataCrossHTML0 . '</table>';

?>

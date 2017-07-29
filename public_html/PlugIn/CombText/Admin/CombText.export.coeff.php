<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	if ($theQuestionArray['isCheckType'] == '4') {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_yesnoID . ' != \'\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$answerNum = $OptionCountRow['optionResponseNum'];
		$OptionValueSQL = ' SELECT SUM(option_' . $questionID . '_' . $question_yesnoID . ') AS totalValueNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_yesnoID . ' != \'\' and ' . $dataSource;
		$OptionValueRow = $DB->queryFirstRow($OptionValueSQL);

		if ($answerNum == 0) {
			$avgValueNum = 0;
		}
		else {
			$avgValueNum = @round($OptionValueRow['totalValueNum'] / $answerNum, 5);
		}

		$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
		$content .= ',"' . $avgValueNum . '"';
		$content .= "\r\n";
	}
}

?>

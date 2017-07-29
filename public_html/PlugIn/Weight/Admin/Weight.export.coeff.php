<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
	$OptionCountSQL = ' SELECT Sum(option_' . $questionID . '_' . $question_rankID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$item_avg_answerNum = 0;

	if ($thisOptionResponseNum != 0) {
		$item_avg_answerNum = round($OptionCountRow['item_sum_answerNum'] / $thisOptionResponseNum, 5);
	}

	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$content .= ',"' . $item_avg_answerNum . '"';
	$content .= "\r\n";
}

?>

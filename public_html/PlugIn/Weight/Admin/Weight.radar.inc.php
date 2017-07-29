<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$t = 0;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$Headings[$t] = qcrossqtnname($theQuestionArray['optionName']);
	$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_rankID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != 0 AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[$t] = number_format($OptionCountRow['item_avg_answerNum'], 2);
	$t++;
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$t = 0;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$Headings[$t] = qcrossqtnname($theQuestionArray['optionName']);
	$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_rankID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[$t] = number_format($OptionCountRow['item_avg_answerNum'], 2);
	$t++;
}

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$Headings[$t] = qcrossqtnname($QtnListArray[$questionID]['otherText']);
	$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_0) as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_0 != \'0\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[$t] = number_format($OptionCountRow['item_avg_answerNum'], 2);
}

?>

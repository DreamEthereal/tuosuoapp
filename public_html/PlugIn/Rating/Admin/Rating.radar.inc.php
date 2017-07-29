<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$t = 0;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$Headings[$t] = qcrossqtnname($theQuestionArray['optionName']);

	switch ($QtnListArray[$questionID]['isSelect']) {
	case '0':
		$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_rankID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'99\' and ' . $dataSource;
		break;

	case '1':
		$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_rankID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
		break;

	case '2':
		$OptionCountSQL = ' SELECT AVG(option_' . $questionID . '_' . $question_rankID . ') as item_avg_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
		break;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[$t] = number_format($OptionCountRow['item_avg_answerNum'] * $QtnListArray[$questionID]['weight'], 2);
	$t++;
}

?>

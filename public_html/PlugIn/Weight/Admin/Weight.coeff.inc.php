<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'WeightCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_16'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$optionTotalNum = count($RankListArray[$questionID]);
$tmp = 0;
$lastOptionId = $optionTotalNum - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	if ($theQtnArray['isHaveOther'] != '1') {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	}
	else if ($tmp != $lastOptionId) {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	}
	else {
		$URL = 'ShowUserDefine.php?type=weight_text&surveyID=' . $surveyID . '&questionID=' . $questionID . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isComb=2&pageNum=' . $_GET['pageID'] . '&dataSourceId=' . $dataSourceId . '&optionName=' . str_replace('+', '%2B', base64_encode(qnospecialchar($theQuestionArray['optionName'])));
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']) . '&nbsp;&nbsp;<a href="' . $URL . '">显示回复文本</a>');
	}

	$tmp++;
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$thisOptionResponseNum = $OptionCountRow['optionResponseNum'];
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$thisSkipNum = $totalResponseNum - $thisOptionResponseNum;
	$EnableQCoreClass->replace('skip_answerNum', $thisSkipNum);
	$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_rankID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_rankID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_rankID . ') as item_sum_answerNum,STDDEV(option_' . $questionID . '_' . $question_rankID . ') as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

	if ($OptionCountRow) {
		$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum']);
		$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum']);
		$EnableQCoreClass->replace('item_tal_answerNum', $OptionCountRow['item_sum_answerNum']);
		$EnableQCoreClass->replace('item_std_answerNum', @round($OptionCountRow['item_std_answerNum'], 5));
	}
	else {
		$EnableQCoreClass->replace('item_min_answerNum', 0);
		$EnableQCoreClass->replace('item_max_answerNum', 0);
		$EnableQCoreClass->replace('item_tal_answerNum', 0);
		$EnableQCoreClass->replace('item_std_answerNum', 0);
	}

	if ($thisOptionResponseNum != 0) {
		$EnableQCoreClass->replace('item_avg_answerNum', round($OptionCountRow['item_sum_answerNum'] / $thisOptionResponseNum, 5));
	}
	else {
		$EnableQCoreClass->replace('item_avg_answerNum', 0);
	}

	$OptionCountSQL = ' SELECT option_' . $questionID . '_' . $question_rankID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_rankID . ' ORDER BY count DESC LIMIT 1 ';
	$ReValueRow = $DB->queryFirstRow($OptionCountSQL);

	if ($ReValueRow) {
		$EnableQCoreClass->replace('item_re_answerNum', $ReValueRow['option_' . $questionID . '_' . $question_rankID]);
	}
	else {
		$EnableQCoreClass->replace('item_re_answerNum', 0);
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

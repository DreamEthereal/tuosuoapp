<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $theCoeffReportId . 'File', 'RankCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $theCoeffReportId . 'File', 'LIST', 'list' . $theCoeffReportId);
$EnableQCoreClass->replace('list' . $theCoeffReportId, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $theCoeffReportId);
$EnableQCoreClass->replace('option' . $theCoeffReportId, '');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_10'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$optionOrderNum = count($RankListArray[$questionID]);

if ($theQtnArray['isHaveOther'] == '1') {
	$optionOrderNum++;
	$theRankListArray = $RankListArray[$questionID];
	$theRankListArray[0] = array();
}
else {
	$theRankListArray = $RankListArray[$questionID];
}

foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
	if ($question_rankID == 0) {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($QtnListArray[$questionID]['otherText']));
	}
	else {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	}

	$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY b.' . $theRankOptionID . ' ';
	$OptionCountResult = $DB->query($OptionCountSQL);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
		$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
	}

	if ($allOptionResponseNum[0] != '') {
		$EnableQCoreClass->replace('skip_answerNum', $allOptionResponseNum[0]);
		$skipAnswerNum = $allOptionResponseNum[0];
	}
	else {
		$EnableQCoreClass->replace('skip_answerNum', 0);
		$skipAnswerNum = 0;
	}

	$rep_answerNum = $thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$OptionCountSQL = ' SELECT Min(' . $theRankOptionID . ') as item_min_answerNum, Max(' . $theRankOptionID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_rankID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theRankOptionID . ' != \'0\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

	if ($OptionCountRow) {
		$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum']);
		$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum']);
	}
	else {
		$EnableQCoreClass->replace('item_min_answerNum', 0);
		$EnableQCoreClass->replace('item_max_answerNum', 0);
	}

	if ($rep_answerNum != 0) {
		$EnableQCoreClass->replace('item_avg_answerNum', round($OptionCountRow['item_sum_answerNum'] / $rep_answerNum, 5));
	}
	else {
		$EnableQCoreClass->replace('item_avg_answerNum', 0);
	}

	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theRankOptionID . ' != \'0\' and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY b.' . $theRankOptionID . ' ORDER BY count DESC LIMIT 1 ';
	$ReValueRow = $DB->queryFirstRow($OptionCountSQL);

	if ($ReValueRow) {
		$EnableQCoreClass->replace('item_re_answerNum', $ReValueRow['option_' . $questionID . '_' . $question_rankID]);
	}
	else {
		$EnableQCoreClass->replace('item_re_answerNum', 0);
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$EnableQCoreClass->parse('list' . $theCoeffReportId, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $theCoeffReportId);
}

$DataCrossHTML0 = $EnableQCoreClass->parse('ShowOption' . $theCoeffReportId, 'ShowOption' . $theCoeffReportId . 'File');
$DataCrossHTML = '<table width="100%">' . $DataCrossHTML0 . '</table>';

?>

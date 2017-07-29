<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RankCoeffView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$minOption = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';

	if ($theQtnArray['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $theQtnArray['minOption'] . $lang['option'] . ']';
	}
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_20'] . ']';
$questionName .= $minOption;
$EnableQCoreClass->replace('questionName', $questionName);
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();
$optionOrderNum = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
	$optionOrderNum++;
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
	$optionOrderNum++;
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('subQuestionName', $optionName);
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
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
	$OptionCountSQL = ' SELECT Min(' . $theRankOptionID . ') as item_min_answerNum, Max(' . $theRankOptionID . ') as item_max_answerNum,Sum(' . $theRankOptionID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.' . $theRankOptionID . ' != \'0\' and ' . $dataSource;
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
		$EnableQCoreClass->replace('item_re_answerNum', $ReValueRow[$theRankOptionID]);
	}
	else {
		$EnableQCoreClass->replace('item_re_answerNum', 0);
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

unset($optionArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

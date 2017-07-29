<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RankView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';
$minOption = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';

	if ($theQtnArray['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $theQtnArray['minOption'] . $lang['option'] . ']';
	}
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_10'] . ']';
$questionName .= $minOption;
$EnableQCoreClass->replace('questionName', $questionName);
$optionOrderNum = count($RankListArray[$questionID]);

if ($theQtnArray['isHaveOther'] == '1') {
	$optionOrderNum++;
}

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('surveyID', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('surveyName', '');
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
		$EnableQCoreClass->replace('skip_optionPercent', countpercent($skipAnswerNum, $totalResponseNum));
	}
	else {
		$EnableQCoreClass->replace('skip_answerNum', 0);
		$skipAnswerNum = 0;
		$EnableQCoreClass->replace('skip_optionPercent', 0);
	}

	$rep_answerNum = $thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
	$i = 1;

	for (; $i <= $optionOrderNum; $i++) {
		$EnableQCoreClass->replace('optionName', $i);

		if (in_array($i, $allResponseOptionID)) {
			$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$i]);
			$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$i], $totalResponseNum));
			$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$i], $thisOptionResponseNum));
		}
		else {
			$EnableQCoreClass->replace('answerNum', 0);
			$EnableQCoreClass->replace('optionPercent', 0);
			$EnableQCoreClass->replace('optionValidPercent', 0);
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_rankID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_rankID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_rankID . ') as item_sum_answerNum,STDDEV(option_' . $questionID . '_' . $question_rankID . ') as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

	if ($OptionCountRow) {
		$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum']);
		$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum']);
		$EnableQCoreClass->replace('item_std_answerNum', @round($OptionCountRow['item_std_answerNum'], 2));
	}
	else {
		$EnableQCoreClass->replace('item_min_answerNum', 0);
		$EnableQCoreClass->replace('item_max_answerNum', 0);
		$EnableQCoreClass->replace('item_std_answerNum', 0);
	}

	if ($rep_answerNum != 0) {
		$EnableQCoreClass->replace('item_avg_answerNum', round($OptionCountRow['item_sum_answerNum'] / $rep_answerNum, 2));
	}
	else {
		$EnableQCoreClass->replace('item_avg_answerNum', 0);
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

if ($theQtnArray['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQtnArray['otherText']));
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('surveyID', $surveyID);
	$EnableQCoreClass->replace('questionID', $questionID);
	$EnableQCoreClass->replace('surveyName', urlencode($_GET['surveyTitle']));
	$theRankOptionID = 'option_' . $questionID . '_0';
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY ' . $theRankOptionID . ' ';
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
		$EnableQCoreClass->replace('skip_optionPercent', countpercent($skipAnswerNum, $totalResponseNum));
	}
	else {
		$EnableQCoreClass->replace('skip_answerNum', 0);
		$skipAnswerNum = 0;
		$EnableQCoreClass->replace('skip_optionPercent', 0);
	}

	$rep_answerNum = $thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
	$i = 1;

	for (; $i <= $optionOrderNum; $i++) {
		$EnableQCoreClass->replace('optionName', $i);

		if (in_array($i, $allResponseOptionID)) {
			$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$i]);
			$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$i], $totalResponseNum));
			$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$i], $thisOptionResponseNum));
		}
		else {
			$EnableQCoreClass->replace('answerNum', 0);
			$EnableQCoreClass->replace('optionPercent', 0);
			$EnableQCoreClass->replace('optionValidPercent', 0);
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_0) as item_min_answerNum, Max(option_' . $questionID . '_0) as item_max_answerNum,Sum(option_' . $questionID . '_0) as item_sum_answerNum,STDDEV(option_' . $questionID . '_0) as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_0 != \'0\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

	if ($OptionCountRow) {
		$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum']);
		$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum']);
		$EnableQCoreClass->replace('item_std_answerNum', @round($OptionCountRow['item_std_answerNum'], 2));
	}
	else {
		$EnableQCoreClass->replace('item_min_answerNum', 0);
		$EnableQCoreClass->replace('item_max_answerNum', 0);
		$EnableQCoreClass->replace('item_std_answerNum', 0);
	}

	if ($rep_answerNum != 0) {
		$EnableQCoreClass->replace('item_avg_answerNum', round($OptionCountRow['item_sum_answerNum'] / $rep_answerNum, 2));
	}
	else {
		$EnableQCoreClass->replace('item_avg_answerNum', 0);
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

if ($theQtnArray['isHaveWhy'] == '1') {
	$EnableQCoreClass->replace('isHaveWhy', '');
}
else {
	$EnableQCoreClass->replace('isHaveWhy', 'none');
}

$EnableQCoreClass->replace('surveyID', $surveyID);
$EnableQCoreClass->replace('questionID', $questionID);
$EnableQCoreClass->replace('surveyName', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

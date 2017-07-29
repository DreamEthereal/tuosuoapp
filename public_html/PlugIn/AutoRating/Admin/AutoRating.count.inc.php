<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] != 0) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RatingOpenView.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
	$EnableQCoreClass->replace('list' . $questionID, '');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RatingView.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
	$EnableQCoreClass->replace('list' . $questionID, '');
	$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');
}

$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_21'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($theQtnArray['isSelect'] == 0) {
	$i = $theQtnArray['endScale'];

	for (; $theQtnArray['startScale'] <= $i; $i--) {
		$RatingWeight = $theQtnArray['weight'] * $i;
		$EnableQCoreClass->replace('orderNum', $RatingWeight);
		$EnableQCoreClass->parse('order' . $questionID, 'ORDER', true);
	}
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('subQuestionName', $optionName);
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY b.' . $theRankOptionID . ' ';
	$OptionCountResult = $DB->query($OptionCountSQL);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
		$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
	}

	if ($theQtnArray['isSelect'] == 1) {
		$skipNum = $allOptionResponseNum['0.00'] + $allOptionResponseNum[0];
	}
	else {
		$skipNum = $allOptionResponseNum[0];
	}

	if ($skipNum != '') {
		$EnableQCoreClass->replace('skip_answerNum', $skipNum);
		$skipAnswerNum = $skipNum;
		$EnableQCoreClass->replace('skip_optionPercent', countpercent($skipAnswerNum, $totalResponseNum));
	}
	else {
		$EnableQCoreClass->replace('skip_answerNum', 0);
		$skipAnswerNum = 0;
		$EnableQCoreClass->replace('skip_optionPercent', 0);
	}

	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
	$unKnowNum = 0;

	if ($theQtnArray['isSelect'] == 0) {
		$i = $theQtnArray['endScale'];

		for (; $theQtnArray['startScale'] <= $i; $i--) {
			$RatingWeight = $theQtnArray['weight'] * $i;
			$EnableQCoreClass->replace('optionName', $RatingWeight);

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

		if ($theQtnArray['isHaveUnkown'] == 1) {
			$EnableQCoreClass->replace('optionName', $lang['rating_unknow']);

			if (in_array(99, $allResponseOptionID)) {
				$unKnowNum = $allOptionResponseNum[99];
				$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[99]);
				$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[99], $totalResponseNum));
				$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[99], $thisOptionResponseNum));
			}
			else {
				$EnableQCoreClass->replace('answerNum', 0);
				$EnableQCoreClass->replace('optionPercent', 0);
				$EnableQCoreClass->replace('optionValidPercent', 0);
			}

			$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		}
	}

	unset($allResponseOptionID);
	unset($allOptionResponseNum);

	switch ($QtnListArray[$questionID]['isSelect']) {
	case '0':
		$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_checkboxID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_checkboxID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum,STDDEV(option_' . $questionID . '_' . $question_checkboxID . ') as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'99\' and ' . $dataSource;
		break;

	case '1':
		$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_checkboxID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_checkboxID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum,STDDEV(option_' . $questionID . '_' . $question_checkboxID . ') as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
		break;

	case '2':
		$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_checkboxID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_checkboxID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum,STDDEV(option_' . $questionID . '_' . $question_checkboxID . ') as item_std_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' and ' . $dataSource;
		break;
	}

	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

	if ($OptionCountRow) {
		$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum'] * $theQtnArray['weight']);
		$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum'] * $theQtnArray['weight']);
		$EnableQCoreClass->replace('item_tal_answerNum', $OptionCountRow['item_sum_answerNum'] * $theQtnArray['weight']);
		$EnableQCoreClass->replace('item_std_answerNum', @round($OptionCountRow['item_std_answerNum'] * $theQtnArray['weight'], 2));
	}
	else {
		$EnableQCoreClass->replace('item_min_answerNum', 0);
		$EnableQCoreClass->replace('item_max_answerNum', 0);
		$EnableQCoreClass->replace('item_tal_answerNum', 0);
		$EnableQCoreClass->replace('item_std_answerNum', 0);
	}

	$thisAnswerNum = $thisOptionResponseNum - $unKnowNum;

	if ($thisAnswerNum != 0) {
		$EnableQCoreClass->replace('item_avg_answerNum', round(($OptionCountRow['item_sum_answerNum'] * $theQtnArray['weight']) / $thisAnswerNum, 2));
	}
	else {
		$EnableQCoreClass->replace('item_avg_answerNum', 0);
	}

	if ($theQtnArray['isSelect'] != 0) {
		$OptionCountSQL = ' SELECT option_' . $questionID . '_' . $question_checkboxID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_checkboxID . ' ORDER BY count DESC LIMIT 1 ';
		$ReValueRow = $DB->queryFirstRow($OptionCountSQL);

		if ($ReValueRow) {
			$EnableQCoreClass->replace('item_re_answerNum', $ReValueRow['option_' . $questionID . '_' . $question_checkboxID]);
		}
		else {
			$EnableQCoreClass->replace('item_re_answerNum', 0);
		}
	}

	$EnableQCoreClass->replace('type', 2);

	if ($theQtnArray['isHaveOther'] == '1') {
		$EnableQCoreClass->replace('haveWhy', '');
		$EnableQCoreClass->replace('surveyID', $surveyID);
		$EnableQCoreClass->replace('questionID', $questionID);
		$EnableQCoreClass->replace('optionID', $question_checkboxID);
		$EnableQCoreClass->replace('surveyName', $_GET['surveyTitle']);
	}
	else {
		$EnableQCoreClass->replace('haveWhy', 'none');
		$EnableQCoreClass->replace('surveyID', $surveyID);
		$EnableQCoreClass->replace('questionID', $questionID);
		$EnableQCoreClass->replace('optionID', '');
		$EnableQCoreClass->replace('surveyName', '');
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

unset($optionArray);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

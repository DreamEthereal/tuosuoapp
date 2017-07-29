<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 0) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RatingCoeffView.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
	$EnableQCoreClass->replace('list' . $questionID, '');
	$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RatingOpenCoeffView.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
	$EnableQCoreClass->replace('list' . $questionID, '');
}

$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_15'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$optionTotalNum = count($RankListArray[$questionID]);
$tmp = 0;
$lastOptionId = $optionTotalNum - 1;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	if ($theQtnArray['isCheckType'] != '1') {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	}
	else if ($tmp != $lastOptionId) {
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']));
	}
	else {
		$URL = 'ShowUserDefine.php?type=rating_text&surveyID=' . $surveyID . '&questionID=' . $questionID . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isComb=2&pageNum=' . $_GET['pageID'] . '&dataSourceId=' . $dataSourceId . '&optionName=' . str_replace('+', '%2B', base64_encode(qnospecialchar($theQuestionArray['optionName'])));
		$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theQuestionArray['optionName']) . '&nbsp;&nbsp;<a href="' . $URL . '">显示回复文本</a>');
	}

	$tmp++;
	$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
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
	}
	else {
		$EnableQCoreClass->replace('skip_answerNum', 0);
		$skipAnswerNum = 0;
	}

	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);

	if ($theQtnArray['isSelect'] == 0) {
		$unKnowNum = 0;
		if (($theQtnArray['isHaveUnkown'] == 1) && in_array(99, $allResponseOptionID)) {
			$unKnowNum = $allOptionResponseNum[99];
		}

		$thisAnswerNum = $thisOptionResponseNum - $unKnowNum;
		$total_optionCoeffNum = 0;
		$i = $theQtnArray['endScale'];

		for (; $theQtnArray['startScale'] <= $i; $i--) {
			$RatingWeight = $theQtnArray['weight'] * $i;
			$EnableQCoreClass->replace('optionName', $RatingWeight);
			$EnableQCoreClass->replace('optionCoeff', $RatingWeight);

			if (in_array($i, $allResponseOptionID)) {
				$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$i]);
				$optionCoeffNum = $RatingWeight * $allOptionResponseNum[$i];
				$total_optionCoeffNum += $optionCoeffNum;
				$EnableQCoreClass->replace('optionCoeffNum', round($optionCoeffNum, 5));
				$optionCoeffAvg = meanaverage($optionCoeffNum, $thisAnswerNum);
				$EnableQCoreClass->replace('optionCoeffAvg', $optionCoeffAvg);
			}
			else {
				$EnableQCoreClass->replace('answerNum', 0);
				$EnableQCoreClass->replace('optionCoeffNum', 0);
				$EnableQCoreClass->replace('optionCoeffAvg', 0);
			}

			$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		}

		if ($theQtnArray['isHaveUnkown'] == 1) {
			$EnableQCoreClass->replace('optionName', $lang['rating_unknow']);
			$EnableQCoreClass->replace('optionCoeff', $theQtnArray['negCode']);

			if (in_array(99, $allResponseOptionID)) {
				$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[99]);
			}
			else {
				$EnableQCoreClass->replace('answerNum', 0);
			}

			$EnableQCoreClass->replace('optionCoeffNum', 0);
			$EnableQCoreClass->replace('optionCoeffAvg', 0);
			$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		}

		unset($allResponseOptionID);
		unset($allOptionResponseNum);
		$EnableQCoreClass->replace('total_optionCoeffNum', $total_optionCoeffNum);
		$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $thisAnswerNum);
		$EnableQCoreClass->replace('total_optionCoeffAvg', $total_optionCoeffAvg);
	}
	else {
		if ($theQtnArray['isSelect'] == 1) {
			$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_rankID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_rankID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_rankID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
		}
		else {
			$OptionCountSQL = ' SELECT Min(option_' . $questionID . '_' . $question_rankID . ') as item_min_answerNum, Max(option_' . $questionID . '_' . $question_rankID . ') as item_max_answerNum,Sum(option_' . $questionID . '_' . $question_rankID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

		if ($OptionCountRow) {
			$EnableQCoreClass->replace('item_min_answerNum', $OptionCountRow['item_min_answerNum'] * $theQtnArray['weight']);
			$EnableQCoreClass->replace('item_max_answerNum', $OptionCountRow['item_max_answerNum'] * $theQtnArray['weight']);
			$EnableQCoreClass->replace('item_tal_answerNum', $OptionCountRow['item_sum_answerNum'] * $theQtnArray['weight']);
		}
		else {
			$EnableQCoreClass->replace('item_min_answerNum', 0);
			$EnableQCoreClass->replace('item_max_answerNum', 0);
			$EnableQCoreClass->replace('item_tal_answerNum', 0);
		}

		if ($thisOptionResponseNum != 0) {
			$EnableQCoreClass->replace('item_avg_answerNum', round(($OptionCountRow['item_sum_answerNum'] * $theQtnArray['weight']) / $thisOptionResponseNum, 5));
		}
		else {
			$EnableQCoreClass->replace('item_avg_answerNum', 0);
		}

		if ($theQtnArray['isSelect'] == 1) {
			$OptionCountSQL = ' SELECT option_' . $questionID . '_' . $question_rankID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0.00\' AND b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
		}
		else {
			$OptionCountSQL = ' SELECT option_' . $questionID . '_' . $question_rankID . ',count(*) AS count FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_rankID . ' != \'0\' and ' . $dataSource;
		}

		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_rankID . ' ORDER BY count DESC LIMIT 1 ';
		$ReValueRow = $DB->queryFirstRow($OptionCountSQL);

		if ($ReValueRow) {
			$EnableQCoreClass->replace('item_re_answerNum', $ReValueRow['option_' . $questionID . '_' . $question_rankID]);
		}
		else {
			$EnableQCoreClass->replace('item_re_answerNum', 0);
		}
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

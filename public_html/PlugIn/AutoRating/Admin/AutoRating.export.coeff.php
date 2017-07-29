<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qshowexportquotechar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qshowexportquotechar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '-' . $optionName . '"';
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE ' . $dataSource;
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
		$skipAnswerNum = $skipNum;
	}
	else {
		$skipAnswerNum = 0;
	}

	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;

	if ($theQtnArray['isSelect'] == 0) {
		$total_optionCoeffNum = 0;
		$i = $theQtnArray['endScale'];

		for (; $theQtnArray['startScale'] <= $i; $i--) {
			$RatingWeight = $theQtnArray['weight'] * $i;

			if (in_array($i, $allResponseOptionID)) {
				$optionCoeffNum = $RatingWeight * $allOptionResponseNum[$i];
				$total_optionCoeffNum += $optionCoeffNum;
			}
		}

		$unKnowNum = 0;
		if (($theQtnArray['isHaveUnkown'] == 1) && in_array(99, $allResponseOptionID)) {
			$unKnowNum = $allOptionResponseNum[99];
		}

		unset($allResponseOptionID);
		unset($allOptionResponseNum);
		$thisAnswerNum = $thisOptionResponseNum - $unKnowNum;
		$total_optionCoeffAvg = meanaverage($total_optionCoeffNum, $thisAnswerNum);
		$content .= ',"' . $total_optionCoeffAvg . '"';
		$content .= "\r\n";
	}
	else {
		if ($theQtnArray['isSelect'] == 1) {
			$OptionCountSQL = ' SELECT Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' AND b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0.00\' and ' . $dataSource;
		}
		else {
			$OptionCountSQL = ' SELECT Sum(option_' . $questionID . '_' . $question_checkboxID . ') as item_sum_answerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $question_checkboxID . ' != \'0\' and ' . $dataSource;
		}

		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

		if ($thisOptionResponseNum != 0) {
			$content .= ',"' . round(($OptionCountRow['item_sum_answerNum'] * $theQtnArray['weight']) / $thisOptionResponseNum, 5) . '"';
			$content .= "\r\n";
		}
		else {
			$content .= ',"0"';
			$content .= "\r\n";
		}
	}
}

unset($optionArray);

?>

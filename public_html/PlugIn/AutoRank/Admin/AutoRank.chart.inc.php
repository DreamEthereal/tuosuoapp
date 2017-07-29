<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionOrderNum = count($theCheckBoxListArray);

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionOrderNum++;
}

$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qcrossqtnname($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qcrossqtnname($theBaseQtnArray['otherText']);
}

$t = 0;

foreach ($optionArray as $question_checkboxID => $optionName) {
	$Headings[$t] = $optionName;
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ORDER BY optionResponseNum DESC ';
	$OptionCountResult = $DB->query($OptionCountSQL);

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
		$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
	}

	$k = 0;
	$l = 1;

	for (; $l <= $optionOrderNum; $l++) {
		if (in_array($l, $allResponseOptionID)) {
			$ObsFreq[$t][$k] = $allOptionResponseNum[$l];
		}
		else {
			$ObsFreq[$t][$k] = 0;
		}

		$k++;
	}

	$totalValue[$t] = array_sum($ObsFreq[$t]);
	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

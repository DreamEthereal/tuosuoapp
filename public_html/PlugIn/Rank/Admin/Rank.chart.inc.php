<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionOrderNum = count($RankListArray[$questionID]);

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$optionOrderNum++;
}

$t = 0;

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$Headings[$t] = qcrossqtnname($theQuestionArray['optionName']);
	$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ';
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

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$Headings[$t] = qcrossqtnname($QtnListArray[$questionID]['otherText']);
	$theRankOptionID = 'option_' . $questionID . '_0';
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ';
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
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

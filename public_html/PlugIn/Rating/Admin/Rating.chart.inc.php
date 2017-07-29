<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
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
	$l = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $l; $l--) {
		if (in_array($l, $allResponseOptionID)) {
			$ObsFreq[$t][$k] = $allOptionResponseNum[$l];
		}
		else {
			$ObsFreq[$t][$k] = 0;
		}

		$k++;
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		if (in_array(99, $allResponseOptionID)) {
			$ObsFreq[$t][$k] = $allOptionResponseNum[99];
		}
		else {
			$ObsFreq[$t][$k] = 0;
		}
	}

	$totalValue[$t] = array_sum($ObsFreq[$t]);
	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

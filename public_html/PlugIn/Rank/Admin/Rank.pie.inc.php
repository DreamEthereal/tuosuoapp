<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionOrderNum = count($RankListArray[$questionID]);

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$optionOrderNum++;
}

$theRankOptionID = 'option_' . $questionID . '_' . $optionID;
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
	$Headings[$k] = $l;

	if (in_array($l, $allResponseOptionID)) {
		$ObsFreq[$k] = $allOptionResponseNum[$l];
	}
	else {
		$ObsFreq[$k] = 0;
	}

	$k++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

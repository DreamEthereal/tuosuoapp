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

$allResponseOptionID = array();
$allOptionResponseNum = array();
$theRankOptionID = 'option_' . $questionID . '_' . $optionID;
$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ORDER BY optionResponseNum DESC ';
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

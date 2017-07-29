<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
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
$l = $QtnListArray[$questionID]['endScale'];

for (; $QtnListArray[$questionID]['startScale'] <= $l; $l--) {
	$Headings[$k] = $QtnListArray[$questionID]['weight'] * $l;

	if (in_array($l, $allResponseOptionID)) {
		$ObsFreq[$k] = $allOptionResponseNum[$l];
	}
	else {
		$ObsFreq[$k] = 0;
	}

	$k++;
}

if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
	$Headings[$k] = $lang['rating_unknow'];

	if (in_array(99, $allResponseOptionID)) {
		$ObsFreq[$k] = $allOptionResponseNum[99];
	}
	else {
		$ObsFreq[$k] = 0;
	}
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

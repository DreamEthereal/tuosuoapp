<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']);
}

$t = 0;
$Headings = $ObsFreq = array();

foreach ($optionArray as $question_checkboxID => $optionName) {
	$theTitleName[$t] = $optionName;
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ';
	$OptionCountResult = $DB->query($OptionCountSQL);

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
		$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
	}

	$k = 0;
	$l = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $l; $l--) {
		$Headings[$t][$k] = $QtnListArray[$questionID]['weight'] * $l;

		if (in_array($l, $allResponseOptionID)) {
			$ObsFreq[$t][$k] = $allOptionResponseNum[$l];
		}
		else {
			$ObsFreq[$t][$k] = 0;
		}

		$k++;
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$Headings[$t][$k] = $lang['rating_unknow'];

		if (in_array(99, $allResponseOptionID)) {
			$ObsFreq[$t][$k] = $allOptionResponseNum[99];
		}
		else {
			$ObsFreq[$t][$k] = 0;
		}
	}

	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>
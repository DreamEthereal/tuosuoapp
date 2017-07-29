<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theDim[$theDimLabel]['dimSName'] = $theSRow['surveyTitle'];
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

foreach ($optionArray as $question_checkboxID => $optionName) {
	$theDim[$theDimLabel]['dimQtnName'][$t] = $optionName;
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$theDim[$theDimLabel]['dimSum'][$t] = $OptionCountRow['optionResponseNum'];
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
		$theDim[$theDimLabel]['dimName'][$t][$k] = $QtnListArray[$questionID]['weight'] * $l;

		if (in_array($l, $allResponseOptionID)) {
			$theDim[$theDimLabel]['dimNum'][$t][$k] = $allOptionResponseNum[$l];
			$theDim[$theDimLabel]['dimPercent'][$t][$k] = countpercent($allOptionResponseNum[$l], $theTotalResponseNum);
		}
		else {
			$theDim[$theDimLabel]['dimNum'][$t][$k] = 0;
			$theDim[$theDimLabel]['dimPercent'][$t][$k] = 0;
		}

		$k++;
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$theDim[$theDimLabel]['dimName'][$t][$k] = $lang['rating_unknow'];

		if (in_array(99, $allResponseOptionID)) {
			$theDim[$theDimLabel]['dimNum'][$t][$k] = $allOptionResponseNum[99];
			$theDim[$theDimLabel]['dimPercent'][$t][$k] = countpercent($allOptionResponseNum[99], $theTotalResponseNum);
		}
		else {
			$theDim[$theDimLabel]['dimNum'][$t][$k] = 0;
			$theDim[$theDimLabel]['dimPercent'][$t][$k] = 0;
		}
	}

	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

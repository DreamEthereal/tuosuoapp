<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DataMatchingSame2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'TIME', 'time' . $questionID);
$EnableQCoreClass->replace('time' . $questionID, '');
$theTimes = explode('^', trim($theTimeCharText));
$EnableQCoreClass->set_CycBlock('OPTION', 'DIMSUM', 'dimsum' . $questionID);
$EnableQCoreClass->replace('dimsum' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'DIM', 'dim' . $questionID);
$EnableQCoreClass->set_CycBlock('DIM', 'DIMNUM', 'dimnum' . $questionID);
$EnableQCoreClass->replace('dim' . $questionID, '');
$EnableQCoreClass->replace('dimnum' . $questionID, '');
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionOrderNum = count($theCheckBoxListArray);

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionOrderNum++;
}

$optionArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']);
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('questionName', $optionName);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$theTotalResponseNum = array();

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$EnableQCoreClass->replace('timeName', date('y-n-j', $theBeginTime) . ' - ' . date('y-n-j', $theEndTime));
		$EnableQCoreClass->parse('time' . $questionID, 'TIME', true);
		$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
		$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ORDER BY optionResponseNum DESC ';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[$theTime][] = $OptionCountRow[$theRankOptionID];
			$allOptionResponseNum[$theTime][$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
		}

		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$theTotalResponseNum[$theTime] = $OptionCountRow['optionResponseNum'];
		$EnableQCoreClass->replace('dimSum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->parse('dimsum' . $questionID, 'DIMSUM', true);
	}

	$k = 1;

	for (; $k <= $optionOrderNum; $k++) {
		$EnableQCoreClass->replace('dimName', $k);

		foreach ($theTimes as $theTime) {
			if (in_array($k, $allResponseOptionID[$theTime])) {
				$EnableQCoreClass->replace('dimNum', $allOptionResponseNum[$theTime][$k]);
				$EnableQCoreClass->replace('dimPercent', countpercent($allOptionResponseNum[$theTime][$k], $theTotalResponseNum[$theTime]));
			}
			else {
				$EnableQCoreClass->replace('dimNum', 0);
				$EnableQCoreClass->replace('dimPercent', 0);
			}

			$EnableQCoreClass->parse('dimnum' . $questionID, 'DIMNUM', true);
		}

		$EnableQCoreClass->parse('dim' . $questionID, 'DIM', true);
		$EnableQCoreClass->unreplace('dimnum' . $questionID);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('dim' . $questionID);
	$EnableQCoreClass->unreplace('time' . $questionID);
	$EnableQCoreClass->unreplace('dimsum' . $questionID);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
unset($optionArray);
unset($theTotalResponseNum);
$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

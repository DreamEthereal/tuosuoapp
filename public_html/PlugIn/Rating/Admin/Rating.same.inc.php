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

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$theTotalResponseNum = array();

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$EnableQCoreClass->replace('timeName', date('y-n-j', $theBeginTime) . ' - ' . date('y-n-j', $theEndTime));
		$EnableQCoreClass->parse('time' . $questionID, 'TIME', true);
		$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
		$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource . ' GROUP BY b.' . $theRankOptionID . ' ';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[$theTime][] = $OptionCountRow[$theRankOptionID];
			$allOptionResponseNum[$theTime][$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
		}

		$OptionCountSQL = ' SELECT COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.' . $theRankOptionID . ' !=0 AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$theTotalResponseNum[$theTime] = $OptionCountRow['optionResponseNum'];
		$EnableQCoreClass->replace('dimSum', $OptionCountRow['optionResponseNum']);
		$EnableQCoreClass->parse('dimsum' . $questionID, 'DIMSUM', true);
	}

	$k = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $k; $k--) {
		$EnableQCoreClass->replace('dimName', $QtnListArray[$questionID]['weight'] * $k);

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

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$EnableQCoreClass->replace('dimName', $lang['rating_unknow']);

		foreach ($theTimes as $theTime) {
			if (in_array(99, $allResponseOptionID[$theTime])) {
				$EnableQCoreClass->replace('dimNum', $allOptionResponseNum[$theTime][99]);
				$EnableQCoreClass->replace('dimPercent', countpercent($allOptionResponseNum[$theTime][99], $theTotalResponseNum[$theTime]));
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
unset($theTotalResponseNum);
$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

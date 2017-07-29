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
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$u = 1;

for (; $u <= $QtnListArray[$questionID]['maxSize']; $u++) {
	$EnableQCoreClass->replace('dim' . $questionID, '');
	$tmp = $u - 1;
	$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]));
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$theTotalResponseNum = array();
	$theExistArray = array();

	foreach ($theTimes as $theTime) {
		$theTimeArray = explode('*', $theTime);
		$theBeginTime = $theTimeArray[0];
		$theEndTime = $theTimeArray[1];
		$EnableQCoreClass->replace('timeName', date('y-n-j', $theBeginTime) . ' - ' . date('y-n-j', $theEndTime));
		$EnableQCoreClass->parse('time' . $questionID, 'TIME', true);
		$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $u . ' and a.level = \'' . $u . '\' AND b.joinTime>= \'' . $theBeginTime . '\' AND b.joinTime<=\'' . $theEndTime . '\' and ' . $dataSource;
		$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $u . ' ORDER BY nodeID DESC';
		$OptionCountResult = $DB->query($OptionSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$theExistArray[$OptionCountRow['nodeID']] = $OptionCountRow['nodeName'];
			$allResponseOptionID[$theTime][] = $OptionCountRow['nodeID'];
			$allOptionResponseNum[$theTime][$OptionCountRow['nodeID']] = $OptionCountRow['optionResponseNum'];
			$theTotalResponseNum[$theTime] += $OptionCountRow['optionResponseNum'];
		}

		$EnableQCoreClass->replace('dimSum', isset($theTotalResponseNum[$theTime]) ? $theTotalResponseNum[$theTime] : 0);
		$EnableQCoreClass->parse('dimsum' . $questionID, 'DIMSUM', true);
	}

	foreach ($theExistArray as $nodeID => $nodeName) {
		$EnableQCoreClass->replace('dimName', qnospecialchar($nodeName));

		foreach ($theTimes as $theTime) {
			$EnableQCoreClass->replace('dimNum', isset($allOptionResponseNum[$theTime][$nodeID]) ? $allOptionResponseNum[$theTime][$nodeID] : 0);
			$EnableQCoreClass->replace('dimPercent', countpercent($allOptionResponseNum[$theTime][$nodeID], $theTotalResponseNum[$theTime]));
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

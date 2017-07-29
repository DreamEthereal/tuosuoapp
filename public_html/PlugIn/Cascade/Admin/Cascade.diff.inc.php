<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theDim[$theDimLabel]['dimSName'] = $theSRow['surveyTitle'];
$t = 0;
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$u = 1;

for (; $u <= $QtnListArray[$questionID]['maxSize']; $u++) {
	$tmp = $u - 1;
	$theDim[$theDimLabel]['dimQtnName'][$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . '_' . $u . ' !=0 and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$theTotalResponseNum = $OptionCountRow['optionResponseNum'];
	$theDim[$theDimLabel]['dimSum'][$t] = $OptionCountRow['optionResponseNum'];
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $u . ' and a.level = \'' . $u . '\' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $u . ' ORDER BY nodeID DESC';
	$OptionCountResult = $DB->query($OptionSQL);

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow['nodeID'];
		$allOptionResponseNum[$OptionCountRow['nodeID']] = $OptionCountRow['optionResponseNum'];
	}

	$k = 0;

	foreach ($CascadeArray[$questionID] as $nodeID => $theQuestionArray) {
		if ($theQuestionArray['level'] == $u) {
			$theDim[$theDimLabel]['dimName'][$t][$k] = qnospecialchar($theQuestionArray['nodeName']);

			if (in_array($nodeID, $allResponseOptionID)) {
				$theDim[$theDimLabel]['dimNum'][$t][$k] = isset($allOptionResponseNum[$nodeID]) ? $allOptionResponseNum[$nodeID] : 0;
				$theDim[$theDimLabel]['dimPercent'][$t][$k] = countpercent($theDim[$theDimLabel]['dimNum'][$t][$k], $theTotalResponseNum);
			}
			else {
				$theDim[$theDimLabel]['dimNum'][$t][$k] = 0;
				$theDim[$theDimLabel]['dimPercent'][$t][$k] = 0;
			}

			$k++;
		}
	}

	$t++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);

?>

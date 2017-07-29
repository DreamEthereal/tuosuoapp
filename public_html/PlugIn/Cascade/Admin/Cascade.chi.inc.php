<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$t = 0;
$Headings = $ObsFreq = array();
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $QtnListArray[$questionID]['maxSize']; $i++) {
	$tmp = $i - 1;
	$theTitleName[$t] = qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();
	$k = 0;
	$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $i . ' and a.level = \'' . $i . '\' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $i . ' ORDER BY optionResponseNum DESC';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$Headings[$t][$k] = qnospecialchar($OptionRow['nodeName']);
		$ObsFreq[$t][$k] = $OptionRow['optionResponseNum'];
		$k++;
	}

	$t++;
}

?>

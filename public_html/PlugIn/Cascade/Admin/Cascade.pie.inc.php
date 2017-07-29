<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$allResponseOptionID = array();
$allOptionResponseNum = array();
$theExistArray = array();
$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $optionID . ' and a.level = \'' . $optionID . '\' and ' . $dataSource;
$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $optionID . ' ORDER BY nodeID DESC';
$OptionResult = $DB->query($OptionSQL);

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$theExistArray[$OptionRow['nodeID']] = $OptionRow['nodeName'];
	$allResponseOptionID[] = $OptionRow['nodeID'];
	$allOptionResponseNum[$OptionRow['nodeID']] = $OptionRow['optionResponseNum'];
}

$k = 0;

foreach ($theExistArray as $nodeID => $nodeName) {
	$Headings[$k] = qcrossqtnname($nodeName);
	$ObsFreq[$k] = $allOptionResponseNum[$nodeID];
	$k++;
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
unset($theExistArray);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $QutRow['questionID'] . '\' ORDER BY cascadeID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$InSQL = ' INSERT INTO ' . CASCADE_TABLE . ' SET surveyID =\'' . $theNewSurveyID . '\',questionID=\'' . $newQuestionID . '\',nodeID = \'' . $Row['nodeID'] . '\',nodeName = \'' . qaddslashes($Row['nodeName'], 1) . '\',nodeFatherID = \'' . $Row['nodeFatherID'] . '\',level = \'' . $Row['level'] . '\',flag = \'0\' ';
	$DB->query($InSQL);
}

?>

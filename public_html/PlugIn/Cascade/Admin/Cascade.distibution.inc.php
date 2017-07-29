<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($CascadeArray[$questionID] as $nodeID => $Row) {
	$Row = qbr2nl(qquoteconvertstring($Row));
	$Row = qaddslashes($Row, 1);
	$InSQL = ' INSERT INTO ' . CASCADE_TABLE . ' SET surveyID =\'' . $theNewSurveyID . '\',questionID=\'' . $newQuestionID . '\',nodeID = \'' . $Row['nodeID'] . '\',nodeName = \'' . $Row['nodeName'] . '\',nodeFatherID = \'' . $Row['nodeFatherID'] . '\',level = \'' . $Row['level'] . '\',flag = \'0\' ';
	$DB->query($InSQL);
}

?>

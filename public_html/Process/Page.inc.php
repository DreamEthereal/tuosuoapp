<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT questionID,orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $thePageSurveyID . '\' AND isPublic=\'1\' AND questionType=8 ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);
$pageBreak = array();
$i = 0;

while ($Row = $DB->queryArray($Result)) {
	$pageBreak[$i] = $Row['orderByID'];
	$i++;
}

$theLastArray = array_slice($QtnListArray, -1, 1);
$lastOrderByID = $theLastArray[0]['orderByID'];
if (empty($pageBreak) || ($pageBreak == '')) {
	$pageBreak[0] = $lastOrderByID + 1;
}
else {
	$pageBreak[$i] = $lastOrderByID + 1;
}

$pageQtnList = array();
$i = 0;

for (; $i < count($pageBreak); $i++) {
	foreach ($QtnListArray as $theQtnID => $theQtnArray) {
		if ($i == 0) {
			if ($theQtnArray['orderByID'] <= $pageBreak[0]) {
				$pageQtnList[$i][] = $theQtnID;
			}
		}
		else {
			$j = $i - 1;
			if (($pageBreak[$j] < $theQtnArray['orderByID']) && ($theQtnArray['orderByID'] <= $pageBreak[$i])) {
				$pageQtnList[$i][] = $theQtnID;
			}
		}
	}
}

?>

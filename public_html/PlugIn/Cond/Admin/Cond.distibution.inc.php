<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET baseID =\'' . $newQtnArray[$QutRow['baseID']] . '\' WHERE questionID=\'' . $newQuestionID . '\'';
$DB->query($SQL);

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $Row) {
	$Row = qbr2nl(qquoteconvertstring($Row));
	$Row = qaddslashes($Row, 1);
	$InSQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID =\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName = \'' . $Row['optionName'] . '\',optionCoeff = \'' . $Row['optionCoeff'] . '\',optionValue = \'' . $Row['optionValue'] . '\',itemCode = \'' . $Row['itemCode'] . '\',isUnkown = \'' . $Row['isUnkown'] . '\',isNA = \'' . $Row['isNA'] . '\',orderByID = \'' . $Row['orderByID'] . '\' ';
	$DB->query($InSQL);
	$yesNoArray[$question_yesnoID] = $DB->_GetInsertID();
}

foreach ($CondRadioListArray[$questionID] as $fatherID => $SonRow) {
	if ($fatherID != 0) {
		$theNewFatherID = $radioArray[$fatherID];
	}
	else {
		$theNewFatherID = 0;
	}

	foreach ($SonRow as $sonID) {
		$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theNewFatherID . '\',sonID=\'' . $yesNoArray[$sonID] . '\',questionID=\'' . $newQuestionID . '\' ';
		$DB->query($SQL);
	}
}

?>

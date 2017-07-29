<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['new_a_qtn'] . $lang['question_type_18'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'18\',orderByID=\'' . $theNewOrderByID . '\',baseID=\'' . $_POST['baseID'] . '\' ';
$DB->query($SQL);
$lastQuestionID = $DB->_GetInsertID();
$SQL = ' SELECT questionType,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['baseID'] . '\' ';
$BaseHaveRow = $DB->queryFirstRow($SQL);
$OptionSQL = ' SELECT question_radioID as optionID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ORDER BY optionOptionID ASC ';
$OptionResult = $DB->query($OptionSQL);
$optionAutoArray = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$optionAutoArray[] = $OptionRow['optionID'];
}

if ($BaseHaveRow['isHaveOther'] == 1) {
	$optionAutoArray[] = 0;
}

$k = 1;

foreach ($optionAutoArray as $optionID) {
	$i = 1;

	for (; $i <= 4; $i++) {
		$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['new_answer_name'] . '_' . $k . '_' . $i . '\' ';
		$DB->query($SQL);
		$lastOptionID = $DB->_GetInsertID();
		$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $optionID . '\',sonID=\'' . $lastOptionID . '\',questionID=\'' . $lastQuestionID . '\' ';
		$DB->query($SQL);
	}

	$k++;
}

?>

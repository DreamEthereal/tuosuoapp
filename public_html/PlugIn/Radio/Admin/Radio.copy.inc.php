<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY optionOptionID ASC ';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionName=\'' . qaddslashes($OptRow['optionName'], 1) . '\',createDate=\'' . $OptRow['createDate'] . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',isUnkown=\'' . $OptRow['isUnkown'] . '\',isNA =\'' . $OptRow['isNA'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',optionMargin=\'' . $OptRow['optionMargin'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';

	if ($OptRow['optionNameFile'] != '') {
		$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $OptRow['createDate']) . '/' . date('d', $OptRow['createDate']) . '/';
		$newFileNameList = explode('.', $OptRow['optionNameFile']);
		$newFileName = $newFileNameList[0] . $newQuestionID . '.' . $newFileNameList[1];

		if (file_exists($optionPicPath . $OptRow['optionNameFile'])) {
			@copy($optionPicPath . $OptRow['optionNameFile'], $optionPicPath . $newFileName);
		}

		if (file_exists($optionPicPath . 's_' . $OptRow['optionNameFile'])) {
			@copy($optionPicPath . 's_' . $OptRow['optionNameFile'], $optionPicPath . 's_' . $newFileName);
		}

		$SQL .= ' ,optionNameFile=\'' . $newFileName . '\' ';
	}

	$DB->query($SQL);
	$radioArray[$OptRow['question_radioID']] = $DB->_GetInsertID();
	updateorderid('question_radio');
}

?>

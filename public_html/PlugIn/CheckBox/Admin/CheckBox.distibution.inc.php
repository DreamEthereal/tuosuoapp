<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $OptRow) {
	$OptRow = qbr2nl(qquoteconvertstring($OptRow));
	$OptRow = qaddslashes($OptRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $theNewSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $OptRow['optionOptionID'] . '\',optionName=\'' . $OptRow['optionName'] . '\',createDate=\'' . $OptRow['createDate'] . '\',optionCoeff=\'' . $OptRow['optionCoeff'] . '\',optionValue=\'' . $OptRow['optionValue'] . '\',itemCode=\'' . $OptRow['itemCode'] . '\',optionMargin=\'' . $OptRow['optionMargin'] . '\',isLogicAnd=\'' . $OptRow['isLogicAnd'] . '\' ';

	if ($OptRow['optionNameFile'] != '') {
		$destination = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $OptRow['createDate']) . '/' . date('d', $OptRow['createDate']) . '/';
		createdir($destination);
		$optionPicPath = $tmpDataFilePath;
		$newFileNameList = explode('.', $OptRow['optionNameFile']);
		$newFileName = $newFileNameList[0] . $newQuestionID . '.' . $newFileNameList[1];

		if (file_exists($optionPicPath . $OptRow['optionNameFile'])) {
			@copy($optionPicPath . $OptRow['optionNameFile'], $destination . $newFileName);
			@unlink($optionPicPath . $OptRow['optionNameFile']);
		}

		if (file_exists($optionPicPath . 's_' . $OptRow['optionNameFile'])) {
			@copy($optionPicPath . 's_' . $OptRow['optionNameFile'], $destination . 's_' . $newFileName);
			@unlink($optionPicPath . 's_' . $OptRow['optionNameFile']);
		}

		$SQL .= ' ,optionNameFile=\'' . $newFileName . '\' ';
	}

	$DB->query($SQL);
	$checkboxArray[$OptRow['question_checkboxID']] = $DB->_GetInsertID();
	updateorderid('question_checkbox');
}

?>

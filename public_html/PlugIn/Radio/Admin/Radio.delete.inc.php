<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT optionNameFile,createDate FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $questionID . '\'';
$OptResult = $DB->query($SQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$picFilePath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $OptRow['createDate']) . '/' . date('d', $OptRow['createDate']) . '/';

	if (file_exists($picFilePath . $OptRow['optionNameFile'])) {
		@unlink($picFilePath . $OptRow['optionNameFile']);
	}

	if (file_exists($picFilePath . 's_' . $OptRow['optionNameFile'])) {
		@unlink($picFilePath . 's_' . $OptRow['optionNameFile']);
	}
}

$SQL = ' DELETE FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $questionID . '\'';
$DB->query($SQL);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($QutRow['isSelect'] == 1) {
	$SQL = ' SELECT * FROM ' . TEXT_OPTION_TABLE . ' WHERE questionID=\'' . $QutRow['questionID'] . '\' ORDER BY optionID ASC ';
	$OptResult = $DB->query($SQL);

	while ($OptRow = $DB->queryArray($OptResult)) {
		$OptRow = qaddslashes($OptRow, 1);
		$SQL = ' INSERT INTO ' . TEXT_OPTION_TABLE . ' SET questionID=\'' . $newQuestionID . '\',optionText=\'' . $OptRow['optionText'] . '\' ';
		$DB->query($SQL);
	}
}

?>

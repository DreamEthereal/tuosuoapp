<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($QutRow['isSelect'] == 1) {
	$OptRow = explode('######', qbr2nl(qquoteconvertstring($TextCheckArray[$questionID])));
	$OptRow = qaddslashes($OptRow, 1);

	foreach ($OptRow as $optionText) {
		$SQL = ' INSERT INTO ' . TEXT_OPTION_TABLE . ' SET questionID=\'' . $newQuestionID . '\',optionText=\'' . $optionText . '\' ';
		$DB->query($SQL);
	}
}

?>

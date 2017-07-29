<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isCheckType'] == 4) {
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$answerNum = $OptionCountRow['optionResponseNum'];
	$OptionValueSQL = ' SELECT SUM(option_' . $questionID . ') AS totalValueNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != \'\' and ' . $dataSource;
	$OptionValueRow = $DB->queryFirstRow($OptionValueSQL);

	if ($answerNum == 0) {
		$avgValueNum = 0;
	}
	else {
		$avgValueNum = @round($OptionValueRow['totalValueNum'] / $answerNum, 5);
	}

	$content .= '"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$content .= ',"' . $avgValueNum . '"';
	$content .= "\r\n";
}

?>

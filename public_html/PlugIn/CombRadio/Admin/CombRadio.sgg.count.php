<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$OptionCountSQL = ' SELECT COUNT(*) AS thisOptionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != 0 and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$thisOptionResponseNum = $OptionCountRow['thisOptionResponseNum'];
if (isset($_POST['optionID' . $tmp]) && ($_POST['optionID' . $tmp] != '')) {
	$OptionCountSQL = ' SELECT COUNT(*) AS buyNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' IN (' . $_POST['optionID' . $tmp] . ') and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$buyNum = $OptionCountRow['buyNum'];
}
else {
	$buyNum = 0;
}

$thePriceRate[$tmp] = countpercent($buyNum, $thisOptionResponseNum);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$OptionCountSQL = ' SELECT COUNT(*) AS thisOptionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE (b.option_' . $questionID . ' != 0 OR (b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\')) and ' . $dataSource;
}
else {
	$OptionCountSQL = ' SELECT COUNT(*) AS thisOptionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' != 0 and ' . $dataSource;
}

$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$thisOptionResponseNum = $OptionCountRow['thisOptionResponseNum'];
if (isset($_POST['optionID' . $tmp]) && ($_POST['optionID' . $tmp] != '')) {
	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$theOptionArray = explode(',', $_POST['optionID' . $tmp]);

		if (in_array('0', $theOptionArray)) {
			$buyNum = 0;

			foreach ($theOptionArray as $theOptionID) {
				switch ($theOptionID) {
				case '0':
					$OptionCountSQL = ' SELECT COUNT(*) AS buyNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 AND b.TextOtherValue_' . $questionID . ' != \'\' and ' . $dataSource;
					$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
					$buyNum += $OptionCountRow['buyNum'];
					break;

				default:
					$OptionCountSQL = ' SELECT COUNT(*) AS buyNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = ' . $theOptionID . ' and ' . $dataSource;
					$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
					$buyNum += $OptionCountRow['buyNum'];
					break;
				}
			}
		}
		else {
			$OptionCountSQL = ' SELECT COUNT(*) AS buyNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' IN (' . $_POST['optionID' . $tmp] . ') and ' . $dataSource;
			$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
			$buyNum = $OptionCountRow['buyNum'];
		}
	}
	else {
		$OptionCountSQL = ' SELECT COUNT(*) AS buyNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' IN (' . $_POST['optionID' . $tmp] . ') and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$buyNum = $OptionCountRow['buyNum'];
	}
}
else {
	$buyNum = 0;
}

$thePriceRate[$tmp] = countpercent($buyNum, $thisOptionResponseNum);

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$TitleName = qnospecialchar($QtnListArray[$questionID]['questionName']);
$Headings = $ObsFreq = array();

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$Headings[] = qnospecialchar($theQuestionArray['optionName']);
	$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $question_yesnoID . '\' and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$ObsFreq[] = $OptionCountRow['optionResponseNum'];
}

?>

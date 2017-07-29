<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'GGSimpleCond.html');
$EnableQCoreClass->replace('thisNo', $thisNo);
$valueList = '';

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$valueList .= '<option value=\'' . $question_yesnoID . '\'>' . $optionName . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('valueList', $valueList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

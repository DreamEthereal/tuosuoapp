<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'GGSimpleCond.html');
$EnableQCoreClass->replace('thisNo', $thisNo);
$valueList = '';

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$valueList .= '<option value=\'' . $question_radioID . '\'>' . $optionName . '</option>' . "\n" . '';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$valueList .= '<option value=\'0\'>' . qnospecialchar($theQtnArray['otherText']) . '</option>';
}

$EnableQCoreClass->replace('valueList', $valueList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

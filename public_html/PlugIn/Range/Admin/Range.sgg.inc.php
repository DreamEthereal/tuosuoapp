<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'GGSimpleCond.html');
$EnableQCoreClass->replace('thisNo', $thisNo);
$valueList = '';

foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
	$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
	$valueList .= '<option value=\'' . $question_range_answerID . '\'>' . $optionAnswer . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('valueList', $valueList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

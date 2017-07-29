<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" size=4><option value="1" selected>Ñ¡Ôñ</option><option value="2">Î´Ñ¡Ôñ</option></select>';
$optionList .= '<select name="queryValue_' . $questionID . '_' . $thisNo . '[]" id="queryValue_' . $questionID . '_' . $thisNo . '" size=4 multiple>';

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$optionList .= '<option value=\'' . $question_yesnoID . '\'>' . $theQuestionArray['optionName'] . '</option>' . "\n" . '';
}

$optionList .= '</select>';
echo $optionList;

?>

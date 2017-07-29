<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="option_' . $questionID . '_' . $thisNo . '" id="option_' . $questionID . '_' . $thisNo . '" align=absmiddle>';

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$optionList .= '<option value=\'' . $question_yesnoID . '\'>' . $optionName . '</option>';
}

$optionList .= '</select>';
$optionList .= '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '"><option value=1>等于 =</option><option value=6>不等于 !=</option><option value=7 selected>包含</option></select>';
$optionList .= '&nbsp;' . $lang['opt_text_char'] . '<input name="queryValue_' . $questionID . '_' . $thisNo . '" id="queryValue_' . $questionID . '_' . $thisNo . '" size=20>';
echo $optionList;

?>

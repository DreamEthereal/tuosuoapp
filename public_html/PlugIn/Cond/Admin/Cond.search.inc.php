<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 1) {
	$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" size=8><option value="1" selected>选择</option><option value="2">未选择</option></select>';
}
else {
	$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" size=8 onchange=javascript:disLogicMode("opertion_' . $questionID . '_' . $thisNo . '","cond_' . $questionID . '_' . $thisNo . '");><option value="1" selected>选择</option><option value="2">未选择</option></select>';
}

$optionList .= '<select name="queryValue_' . $questionID . '_' . $thisNo . '[]" id="queryValue_' . $questionID . '_' . $thisNo . '" size=8 multiple>';

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$optionList .= '<option value=\'' . $question_yesnoID . '\'>' . $optionName . '</option>';
}

$optionList .= '</select>';
$optionList .= '<input type=hidden value="' . $theQtnArray['isSelect'] . '" name="isRadio_' . $questionID . '_' . $thisNo . '" id="isRadio_' . $questionID . '_' . $thisNo . '">';

if ($theQtnArray['isSelect'] == 1) {
	$optionList .= '<br/>值间关系：<input type=checkbox value="1" name="cond_' . $questionID . '_' . $thisNo . '" id="cond_' . $questionID . '_' . $thisNo . '">' . $lang['cond_logic_or'];
}
else {
	$optionList .= '<br/>值间关系：<input disabled type=checkbox value="1" name="cond_' . $questionID . '_' . $thisNo . '" id="cond_' . $questionID . '_' . $thisNo . '">' . $lang['cond_logic_or'];
}

echo $optionList;

?>

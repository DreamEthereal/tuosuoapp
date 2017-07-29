<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="option_' . $questionID . '_' . $thisNo . '" id="option_' . $questionID . '_' . $thisNo . '" align=absmiddle>';
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $theQtnArray['maxSize']; $i++) {
	$tmp = $i - 1;
	$optionName = qnospecialchar($theUnitText[$tmp]);
	$optionList .= '<option value=\'' . $i . '\'>' . $optionName . '</option>';
}

$optionList .= '</select>';
$optionList .= '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" onchange=javascript:disLogicMode("opertion_' . $questionID . '_' . $thisNo . '","cond_' . $questionID . '_' . $thisNo . '");><option value="1" selected>选择</option><option value="2">未选择</option></select>';
$optionList .= '&nbsp;回复值集合为：<input name="queryValue_' . $questionID . '_' . $thisNo . '" id="queryValue_' . $questionID . '_' . $thisNo . '" size=40> (多值采用英文逗号分割)';
$optionList .= '<br/>值间关系：<input disabled type=checkbox value="1" name="cond_' . $questionID . '_' . $thisNo . '" id="cond_' . $questionID . '_' . $thisNo . '">' . $lang['cond_logic_or'];
echo $optionList;

?>

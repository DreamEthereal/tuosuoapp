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
$optionList .= '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" onchange=javascript:disLogicMode("opertion_' . $questionID . '_' . $thisNo . '","cond_' . $questionID . '_' . $thisNo . '");><option value="1" selected>ѡ��</option><option value="2">δѡ��</option></select>';
$optionList .= '&nbsp;�ظ�ֵ����Ϊ��<input name="queryValue_' . $questionID . '_' . $thisNo . '" id="queryValue_' . $questionID . '_' . $thisNo . '" size=40> (��ֵ����Ӣ�Ķ��ŷָ�)';
$optionList .= '<br/>ֵ���ϵ��<input disabled type=checkbox value="1" name="cond_' . $questionID . '_' . $thisNo . '" id="cond_' . $questionID . '_' . $thisNo . '">' . $lang['cond_logic_or'];
echo $optionList;

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$optionList = '<select name="opertion_' . $questionID . '_' . $thisNo . '" id="opertion_' . $questionID . '_' . $thisNo . '" size=8><option value="1" selected>ѡ��</option><option value="2">δѡ��</option></select>';
$optionList .= '<select name="queryValue_' . $questionID . '_' . $thisNo . '[]" id="queryValue_' . $questionID . '_' . $thisNo . '" size=8 multiple>';

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$optionList .= '<option value=\'' . $question_checkboxID . '\'>' . $optionName . '</option>';
}

$optionList .= '</select>';
$optionList .= '<br/>ֵ���ϵ��<input type=checkbox value="1" name="cond_' . $questionID . '_' . $thisNo . '">' . $lang['cond_logic_or'];
echo $optionList;

?>

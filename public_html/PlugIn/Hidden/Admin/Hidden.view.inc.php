<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CommonDetail.html');
$questionName = '';
$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_12'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($R_Row['option_' . $questionID] != '') {
	$EnableQCoreClass->replace('optionName', $R_Row['option_' . $questionID]);
}
else {
	$EnableQCoreClass->replace('optionName', $lang['no_value']);
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
$haveCondRel = getqtnconsucc($questionID);
if (($haveCondRel != '') && !runcode($haveCondRel)) {
	$check_survey_conditions_list .= '	$("#question_' . $questionID . '").hide();' . "\n" . '';
}

$EnableQCoreClass->replace('authList', '');

?>

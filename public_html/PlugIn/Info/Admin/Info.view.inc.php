<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'InfoDetail.html');
$questionName = qshowquotechar($theQtnArray['questionName']);
$EnableQCoreClass->replace('questionName', $questionName);
$EnableQCoreClass->replace('questionID', $questionID);
$EnableQCoreClass->replace('optionName', qshowquotechar($InfoListArray[$questionID]['optionName']));
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
$haveCondRel = getqtnconsucc($questionID);
if (($haveCondRel != '') && !runcode($haveCondRel)) {
	$check_survey_conditions_list .= '	$("#question_' . $questionID . '").hide();' . "\n" . '';
}

$EnableQCoreClass->replace('authList', '');

?>

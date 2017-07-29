<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'InfoView.html');
$questionName = qshowquotechar($theQtnArray['questionName']);
$EnableQCoreClass->replace('questionName', $questionName);
$EnableQCoreClass->replace('questionID', $questionID);
$EnableQCoreClass->replace('optionName', qshowquotechar($InfoListArray[$questionID]['optionName']));
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
$EnableQCoreClass->replace('authList', '');

?>

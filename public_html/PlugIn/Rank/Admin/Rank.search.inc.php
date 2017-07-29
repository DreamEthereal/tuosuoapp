<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RankCond.html');
$EnableQCoreClass->replace('questionID', $theQtnArray['questionID']);
$EnableQCoreClass->replace('thisNo', $thisNo);
$optionList = '';

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$optionName = qnospecialchar($theQuestionArray['optionName']);
	$optionList .= '<option value=\'' . $question_rankID . '\'>' . $optionName . '</option>' . "\n" . '';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$optionList .= '<option value=\'0\'>' . qnospecialchar($theQtnArray['otherText']) . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('optionList', $optionList);
$ShowOption = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
echo $ShowOption;

?>

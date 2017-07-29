<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCount1D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']));
$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . ' = 0 and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('skipAnswerNum', $OptionCountRow['skipAnswerNum']);
$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));
$OptionSQL = ' SELECT a.question_radioID,count(*) as optionResponseNum FROM ' . QUESTION_RADIO_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_radioID = b.option_' . $questionID . ' and ' . $dataSource;
$OptionSQL .= ' GROUP BY b.option_' . $questionID . ' ORDER BY optionResponseNum DESC';
$OptionResult = $DB->query($OptionSQL);
$allResponseOptionID = array();
$allOptionResponseNum = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$allResponseOptionID[] = $OptionRow['question_radioID'];
	$allOptionResponseNum[$OptionRow['question_radioID']] = $OptionRow['optionResponseNum'];
}

foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

	if (in_array($question_radioID, $allResponseOptionID)) {
		$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$question_radioID]);
		$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$question_radioID], $totalRepAnswerNum));
		$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$question_radioID], $thisOptionResponseNum));
	}
	else {
		$EnableQCoreClass->replace('answerNum', 0);
		$EnableQCoreClass->replace('optionPercent', 0);
		$EnableQCoreClass->replace('optionValidPercent', 0);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

unset($allResponseOptionID);
unset($allOptionResponseNum);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
$DataCrossHTML = preg_replace('/<!-- BEGIN OTHER -->(.*)<!-- END OTHER -->/s', '', $DataCrossHTML);

?>

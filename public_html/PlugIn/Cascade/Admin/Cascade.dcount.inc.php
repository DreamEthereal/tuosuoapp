<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCount2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $QtnListArray[$questionID]['maxSize']; $i++) {
	$EnableQCoreClass->replace('option' . $questionID, '');
	$tmp = $i - 1;
	$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theUnitText[$tmp]));
	$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $i . ' =0 and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('skipAnswerNum', $OptionCountRow['skipAnswerNum']);
	$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
	$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
	$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
	$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));
	$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $i . ' and a.level = \'' . $i . '\' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $i . ' ORDER BY optionResponseNum DESC LIMIT 200 ';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($OptionRow['nodeName']));
		$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
		$optionPercent = countpercent($OptionRow['optionResponseNum'], $totalRepAnswerNum);
		$EnableQCoreClass->replace('optionPercent', $optionPercent);
		$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionRow['optionResponseNum'], $thisOptionResponseNum));
		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

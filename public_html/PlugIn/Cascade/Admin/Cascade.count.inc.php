<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeView.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_31'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);
$EnableQCoreClass->replace('imagePath', ROOT_PATH);
$TableFields = '';
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$i = 1;

for (; $i <= $theQtnArray['maxSize']; $i++) {
	$EnableQCoreClass->replace('option' . $questionID, '');
	$tmp = $i - 1;
	$EnableQCoreClass->replace('subQuestionName', qnospecialchar($theUnitText[$tmp]));
	$TableFields .= ' AND option_' . $questionID . '_' . $i . ' = \'0\' ';
	$OptionCountSQL = ' SELECT COUNT(*) AS skipAnswerNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE b.option_' . $questionID . '_' . $i . ' =0 and ' . $dataSource;
	$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
	$EnableQCoreClass->replace('skip_answerNum', $OptionCountRow['skipAnswerNum']);
	$skipAnswerNum = $OptionCountRow['skipAnswerNum'];
	$EnableQCoreClass->replace('skip_optionPercent', countpercent($skipAnswerNum, $totalResponseNum));
	$thisOptionResponseNum = $totalResponseNum - $skipAnswerNum;
	$EnableQCoreClass->replace('rep_answerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('rep_optionPercent', countpercent($thisOptionResponseNum, $totalResponseNum));
	$OptionSQL = ' SELECT a.nodeID,a.nodeName,count(*) as optionResponseNum FROM ' . CASCADE_TABLE . ' a,' . $table_prefix . 'response_' . $surveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.nodeID = b.option_' . $questionID . '_' . $i . ' and a.level = \'' . $i . '\' and ' . $dataSource;
	$OptionSQL .= ' GROUP BY b.option_' . $questionID . '_' . $i . ' ORDER BY optionResponseNum DESC LIMIT 200 ';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($OptionRow['nodeName']));
		$EnableQCoreClass->replace('answerNum', $OptionRow['optionResponseNum']);
		$optionPercent = countpercent($OptionRow['optionResponseNum'], $totalResponseNum);
		$EnableQCoreClass->replace('optionPercent', $optionPercent);
		$EnableQCoreClass->replace('optionValidPercent', countpercent($OptionRow['optionResponseNum'], $thisOptionResponseNum));
		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 ' . $TableFields . ' and ' . $dataSource;
$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
$EnableQCoreClass->replace('all_skip_answerNum', $OptionCountRow['optionResponseNum']);
$optionPercent = countpercent($OptionCountRow['optionResponseNum'], $totalResponseNum);
$EnableQCoreClass->replace('all_skip_Percent', $optionPercent);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));

?>

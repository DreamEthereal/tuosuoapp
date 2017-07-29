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
$theBaseID = $theQtnArray['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionArray = array();
$optionOrderNum = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
	$optionOrderNum++;
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
	$optionOrderNum++;
}

foreach ($optionArray as $question_checkboxID => $optionName) {
	$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . ' - ' . $optionName);
	$theRankOptionID = 'option_' . $questionID . '_' . $question_checkboxID;
	$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
	$OptionCountSQL .= ' GROUP BY ' . $theRankOptionID . ' ';
	$OptionCountResult = $DB->query($OptionCountSQL);
	$allResponseOptionID = array();
	$allOptionResponseNum = array();

	while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
		$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
		$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
	}

	if ($allOptionResponseNum[0] != '') {
		$EnableQCoreClass->replace('skipAnswerNum', $allOptionResponseNum[0]);
		$skipAnswerNum = $allOptionResponseNum[0];
		$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
	}
	else {
		$EnableQCoreClass->replace('skipAnswerNum', 0);
		$skipAnswerNum = 0;
		$EnableQCoreClass->replace('skipAnswerPercent', 0);
	}

	$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
	$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
	$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));
	$k = 1;

	for (; $k <= $optionOrderNum; $k++) {
		$EnableQCoreClass->replace('optionName', $k);

		if (in_array($k, $allResponseOptionID)) {
			$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$k]);
			$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$k], $totalRepAnswerNum));
			$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$k], $thisOptionResponseNum));
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
	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
	$EnableQCoreClass->unreplace('option' . $questionID);
}

unset($optionArray);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>

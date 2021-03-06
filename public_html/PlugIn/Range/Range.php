<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uRangeNeg.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uRange.html');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->set_CycBlock('OPTION', 'SUBOPTION', 'suboption' . $questionID);
$EnableQCoreClass->replace('suboption' . $questionID, '');
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ANSWER', 'answer' . $questionID);
$EnableQCoreClass->replace('answer' . $questionID, '');
$check_survey_form_no_con_list = '';
$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_6'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$theRadioListArray = array();
	$optionTotalNum = count($OptionListArray[$questionID]);

	if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
		if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
			$theRandListArray = php_array_rand($OptionListArray[$questionID], $optionTotalNum);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
			}
		}
		else {
			$theRandIDArray = array_slice($OptionListArray[$questionID], 0, $optionTotalNum - 1);
			$theRandOptionIDArray = array();

			foreach ($theRandIDArray as $theRandID => $theOptionIDArray) {
				$theRandOptionIDArray[$theOptionIDArray['question_range_optionID']] = $theOptionIDArray['question_range_optionID'];
			}

			$theRandListArray = php_array_rand($theRandOptionIDArray, $optionTotalNum - 1);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
			}

			$theLastArray = array_slice($OptionListArray[$questionID], -1, 1);
			$theRadioListArray[$theLastArray[0]['question_range_optionID']] = $theLastArray[0];
		}
	}
	else if ($QtnListArray[$questionID]['isColArrange'] == '1') {
		$theRandListArray = php_array_rand($OptionListArray[$questionID], $QtnListArray[$questionID]['perRowCol']);

		foreach ($theRandListArray as $theRandRadioID) {
			$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
		}
	}
	else {
		$theRadioListArray = $OptionListArray[$questionID];
	}

	$remove_value_list = '';
	$Option = array();
	$theOptionIdArray = array();
	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;

	foreach ($theRadioListArray as $question_range_optionID => $theQuestionArray) {
		$theOptionIdArray[] = $question_range_optionID;

		if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else {
			$optionName = qshowquotechar($theQuestionArray['optionName']) . '</span>:&nbsp;&nbsp;<span style="vertical-align:middle"><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

			if ($isModiDataFlag == 1) {
				$optionName .= $R_Row['TextOtherValue_' . $questionID];
			}
			else {
				$optionName .= qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]);
			}

			$optionName .= '"></span>';
			$EnableQCoreClass->replace('optionName', $optionName);
		}

		$EnableQCoreClass->replace('optionNameID', $question_range_optionID);
		$Option[] = $question_range_optionID;
		$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
		$remove_value_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
		$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '|';
		$QtnAssCon = _getqtnasscond($questionID, $question_range_optionID);

		if ($QtnAssCon != '') {
			if ($QtnListArray[$questionID]['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
			}
		}
		else if ($QtnListArray[$questionID]['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
				$check_survey_form_no_con_list .= '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
			}
		}

		if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	if (getRadioCheckBoxValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ') != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
		}

		if ($QtnAssCon != '') {
			$colNum = (($tmp + 1) * 2) + 1;
			$colNumNext = $colNum + 1;
			$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#qtn_table_' . $questionID . '\').hideColumns([' . $colNum . ',' . $colNumNext . ']);' . "\n" . '';
			$check_survey_conditions_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
			if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#qtn_table_' . $questionID . '\').showColumns([' . $colNum . ',' . $colNumNext . ']);' . "\n" . '	} ' . "\n" . '';
		}

		$tmp++;
	}

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('suboption' . $questionID, '');
		$EnableQCoreClass->replace('answerName', qshowquotechar($theAnswerArray['optionAnswer']));
		$EnableQCoreClass->replace('optionAnswerID', $question_range_answerID);

		foreach ($Option as $question_range_optionID) {
			$EnableQCoreClass->replace('optionValue', $question_range_answerID);
			$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_range_optionID);
			$EnableQCoreClass->replace('optionNameID', $question_range_optionID);

			if ($isModiDataFlag == 1) {
				if (($R_Row['option_' . $questionID . '_' . $question_range_optionID] != '') && ($R_Row['option_' . $questionID . '_' . $question_range_optionID] == $question_range_answerID)) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}
			else {
				if (($_SESSION['option_' . $questionID . '_' . $question_range_optionID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_range_optionID] == $question_range_answerID)) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}

			$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		$EnableQCoreClass->unreplace('suboption' . $questionID);
		$OptAssCon = _getoptasscond($questionID, $question_range_answerID);

		if ($OptAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $OptAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_range_' . $question_range_answerID . '_container").hide();' . "\n" . '';

			foreach ($Option as $question_range_optionID) {
				$check_survey_conditions_list .= '		RadioItemUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ',' . $question_range_answerID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_range_' . $question_range_answerID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}
	}

	if ($QtnListArray[$questionID]['requiredMode'] == '2') {
		$check_survey_form_no_con_list .= '	if (!CheckRangeExclusive(' . $questionID . ',' . min($theOptionIdArray) . ',' . max($theOptionIdArray) . ',\'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
	}

	if (($QtnListArray[$questionID]['isContInvalid'] == '1') && ($QtnListArray[$questionID]['contInvalidValue'] != '0')) {
		$check_survey_form_no_con_list .= '	if (!CheckRangeIsContInvalid(' . $questionID . ',' . min($theOptionIdArray) . ',' . max($theOptionIdArray) . ',\'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['contInvalidValue'] . ')){return false;} ' . "\n" . '';
	}

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
		$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

	unset($Option);
	$check_survey_conditions_list .= '	changeMaskingOptBgColor(' . $questionID . ');' . "\n" . '';
}
else {
	$AnswerNum = count($AnswerListArray[$questionID]);
	$Answer = array();

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('answerName', qshowquotechar($theAnswerArray['optionAnswer']));
		$EnableQCoreClass->replace('optionAnswerID', $question_range_answerID);
		$Answer[] = $question_range_answerID;
		$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
	}

	$theRadioListArray = array();
	$optionTotalNum = count($OptionListArray[$questionID]);

	if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
		if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
			$theRandListArray = php_array_rand($OptionListArray[$questionID], $optionTotalNum);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
			}
		}
		else {
			$theRandIDArray = array_slice($OptionListArray[$questionID], 0, $optionTotalNum - 1);
			$theRandOptionIDArray = array();

			foreach ($theRandIDArray as $theRandID => $theOptionIDArray) {
				$theRandOptionIDArray[$theOptionIDArray['question_range_optionID']] = $theOptionIDArray['question_range_optionID'];
			}

			$theRandListArray = php_array_rand($theRandOptionIDArray, $optionTotalNum - 1);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
			}

			$theLastArray = array_slice($OptionListArray[$questionID], -1, 1);
			$theRadioListArray[$theLastArray[0]['question_range_optionID']] = $theLastArray[0];
		}
	}
	else if ($QtnListArray[$questionID]['isColArrange'] == '1') {
		$theRandListArray = php_array_rand($OptionListArray[$questionID], $QtnListArray[$questionID]['perRowCol']);

		foreach ($theRandListArray as $theRandRadioID) {
			$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
		}
	}
	else {
		$theRadioListArray = $OptionListArray[$questionID];
	}

	$remove_value_list = '';
	$theOptionIdArray = array();
	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;

	foreach ($theRadioListArray as $question_range_optionID => $theQuestionArray) {
		$theOptionIdArray[] = $question_range_optionID;
		$EnableQCoreClass->replace('suboption' . $questionID, '');
		$theNewOptionName = explode('|', qshowquotechar($theQuestionArray['optionName']));

		if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
			$EnableQCoreClass->replace('optionNameLeft', $theNewOptionName[0]);
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionNameLeft', $theNewOptionName[0]);
		}
		else {
			$optionNameLeft = $theNewOptionName[0] . '</span>:&nbsp;&nbsp;<span><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

			if ($isModiDataFlag == 1) {
				$optionNameLeft .= $R_Row['TextOtherValue_' . $questionID];
			}
			else {
				$optionNameLeft .= qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]);
			}

			$optionNameLeft .= '"></span>';
			$EnableQCoreClass->replace('optionNameLeft', $optionNameLeft);
		}

		$EnableQCoreClass->replace('optionNameRight', $theNewOptionName[1]);

		if (1 < count($theNewOptionName)) {
			$EnableQCoreClass->replace('alignDiff', 'right');
		}
		else {
			$EnableQCoreClass->replace('alignDiff', 'left');
		}

		$EnableQCoreClass->replace('optionNameID', $question_range_optionID);
		$remove_value_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
		$i = 0;

		for (; $i < $AnswerNum; $i++) {
			$EnableQCoreClass->replace('optionValue', $Answer[$i]);
			$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_range_optionID);

			if ($isModiDataFlag == 1) {
				if (($R_Row['option_' . $questionID . '_' . $question_range_optionID] != '') && ($R_Row['option_' . $questionID . '_' . $question_range_optionID] == $Answer[$i])) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}
			else {
				if (($_SESSION['option_' . $questionID . '_' . $question_range_optionID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_range_optionID] == $Answer[$i])) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}

			$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '|';
		$QtnAssCon = _getqtnasscond($questionID, $question_range_optionID);

		if ($QtnAssCon != '') {
			if ($QtnListArray[$questionID]['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
			}
		}
		else if ($QtnListArray[$questionID]['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
				$check_survey_form_no_con_list .= '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
			}
		}

		if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	if (getRadioCheckBoxValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ') != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		$EnableQCoreClass->unreplace('suboption' . $questionID);

		if ($QtnAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_range_' . $question_range_optionID . '_container").hide();' . "\n" . '';
			$check_survey_conditions_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
			if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_range_' . $question_range_optionID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}

		$tmp++;
	}

	if ($QtnListArray[$questionID]['requiredMode'] == '2') {
		$check_survey_form_no_con_list .= '	if (!CheckRangeExclusive(' . $questionID . ',' . min($theOptionIdArray) . ',' . max($theOptionIdArray) . ',\'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
	}

	if (($QtnListArray[$questionID]['isContInvalid'] == '1') && ($QtnListArray[$questionID]['contInvalidValue'] != '0')) {
		$check_survey_form_no_con_list .= '	if (!CheckRangeIsContInvalid(' . $questionID . ',' . min($theOptionIdArray) . ',' . max($theOptionIdArray) . ',\'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['contInvalidValue'] . ')){return false;} ' . "\n" . '';
	}

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
		$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

	unset($Answer);
	$j = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$j++;
		$OptAssCon = _getoptasscond($questionID, $question_range_answerID);

		if ($OptAssCon != '') {
			$colNum = ($j * 2) + 1;
			$colNumNext = $colNum + 1;
			$check_survey_conditions_list .= '	if(' . $OptAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#qtn_table_' . $questionID . '\').hideColumns([' . $colNum . ',' . $colNumNext . ']);' . "\n" . '';

			foreach ($theRadioListArray as $question_range_optionID => $theQuestionArray) {
				$check_survey_conditions_list .= '		RadioItemUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ',' . $question_range_answerID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#qtn_table_' . $questionID . '\').showColumns([' . $colNum . ',' . $colNumNext . ']);' . "\n" . '	} ' . "\n" . '';
		}
	}

	$check_survey_conditions_list .= '	changeMaskingQtnBgColor(' . $questionID . ');' . "\n" . '';
}

$QuestionCon = _getquestioncond($questionID, $surveyID);

if ($QuestionCon != '') {
	$check_survey_conditions_list .= '	if(' . $QuestionCon . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").show();' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").hide();' . "\n" . '	} ' . "\n" . '';
	$check_form_list = '	if(' . $QuestionCon . ')' . "\n" . '	{' . "\n" . '';
	$check_form_list .= $check_survey_form_no_con_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_form_list .= '	else{' . "\n" . '';
	$check_form_list .= $remove_value_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
}

$RelSurveyCon = _getsurveyvaluerelationcond($questionID, $surveyID, strtolower($language));

if ($RelSurveyCon != '') {
	$theRelSurveyCon = explode('$$$$$$', $RelSurveyCon);

	foreach ($theRelSurveyCon as $thisRelSurveyCon) {
		$tRelSurveyCon = explode('######', $thisRelSurveyCon);

		if ($tRelSurveyCon[0] == 2) {
			$survey_empty_list .= $tRelSurveyCon[1];
			$theEmptyList = explode('*', $tRelSurveyCon[1]);
			$theEmptyId = base64_decode($theEmptyList[7]);

			if (!issamepage($theEmptyId, $questionID)) {
				$this_fields_list .= 'option_' . $theEmptyId . '|';
				$theEmptyEndSurveyCon = _getsurveyquotacond($theEmptyId, $surveyID, strtolower($language));

				if ($theEmptyEndSurveyCon != '') {
					$survey_quota_list .= $theEmptyEndSurveyCon;
				}
			}

			unset($theEmptyList);
		}
		else {
			$survey_quota_list .= $tRelSurveyCon[1];
		}

		unset($tRelSurveyCon);
	}

	unset($theRelSurveyCon);
}

$EndSurveyCon = _getsurveyquotacond($questionID, $surveyID, strtolower($language));

if ($EndSurveyCon != '') {
	$survey_quota_list .= $EndSurveyCon;
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
if (($isAuthDataFlag == 1) || ($isAuthAppDataFlag == 1)) {
	if ($isAuthDataFlag == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND b.isAppData =0 ORDER BY b.traceTime DESC ';
	}

	if ($isAuthAppDataFlag == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.evidence,b.reason FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND b.isAppData !=0 ORDER BY b.traceTime DESC ';
	}

	$aResult = $DB->query($aSQL);
	$aRecNum = $DB->_getNumRows($aResult);

	if ($aRecNum == 0) {
		$EnableQCoreClass->replace('authList', '');
	}
	else {
		$EnableQCoreClass->setTemplateFile('ShowAuth' . $questionID . 'File', 'uAuthList.html');
		$EnableQCoreClass->set_CycBlock('ShowAuth' . $questionID . 'File', 'AUTHLIST', 'authList' . $questionID);
		$EnableQCoreClass->replace('authList' . $questionID, '');
		$tmp = 0;

		while ($aRow = $DB->queryArray($aResult)) {
			$tmp++;

			if ($aRow['isAppData'] != 1) {
				if ($aRow['isAdmin'] == '4') {
					$modiLang = '修改';
				}
				else {
					$modiLang = '审核';
				}
			}
			else {
				$modiLang = '申诉';
			}

			$authInfoList = '(' . $tmp . ')&nbsp;' . _getuserallname($aRow['nickName'], $aRow['userGroupID'], $aRow['groupType']);
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['traceTime']) . $modiLang;
			$theVarName = explode('_', $aRow['varName']);

			if ($theVarName[0] == 'option') {
				$authInfoList .= '<span class=red>[' . qnospecialchar($OptionListArray[$questionID][$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';

				switch ($aRow['oriValue']) {
				case '0':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$authInfoList .= qnospecialchar($AnswerListArray[$questionID][$aRow['oriValue']]['optionAnswer']);
					break;
				}

				$authInfoList .= ']</span>至<span class=red>[';

				switch ($aRow['updateValue']) {
				case '0':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$authInfoList .= qnospecialchar($AnswerListArray[$questionID][$aRow['updateValue']]['optionAnswer']);
					break;
				}

				$authInfoList .= ']</span>';
			}
			else {
				$optionTotalNum = count($OptionListArray[$questionID]);
				$tmp = 0;
				$theTextId = 0;

				foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
					$tmp++;

					if ($tmp != $optionTotalNum) {
						continue;
					}
					else {
						$theTextId = $question_range_optionID;
					}
				}

				$authInfoList .= '<span class=red>[' . qnospecialchar($OptionListArray[$questionID][$theTextId]['optionName']) . ']</span>自<span class=red>[';

				if ($aRow['oriValue'] == '') {
					$authInfoList .= '空]</span>至<span class=red>[';
				}
				else {
					$authInfoList .= qnospecialchar($aRow['oriValue']) . ']</span>至<span class=red>[';
				}

				if ($aRow['updateValue'] == '') {
					$authInfoList .= '空]</span>';
				}
				else {
					$authInfoList .= qnospecialchar($aRow['updateValue']) . ']</span>';
				}
			}

			if ($aRow['isAppData'] == 1) {
				$authInfoList .= '；理由为：<span class=red>[' . $aRow['reason'] . ']</span>';

				if ($aRow['evidence'] != '') {
					$authInfoList .= '；证据为：<a href=\'' . $evidencePhyPath . $aRow['evidence'] . '\' target=_blank><span class=red>[' . $aRow['evidence'] . ']</span></a>';
				}
			}

			$EnableQCoreClass->replace('authInfoList', $authInfoList);
			$EnableQCoreClass->parse('authList' . $questionID, 'AUTHLIST', true);
		}

		$EnableQCoreClass->replace('authList', $EnableQCoreClass->parse('ShowAuth' . $questionID, 'ShowAuth' . $questionID . 'File'));
	}
}

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uMultipleNeg.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uMultiple.html');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->set_CycBlock('OPTION', 'SUBOPTION', 'suboption' . $questionID);
$EnableQCoreClass->replace('suboption' . $questionID, '');
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ANSWER', 'answer' . $questionID);
$EnableQCoreClass->replace('answer' . $questionID, '');
$check_survey_form_no_con_list = '';
$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_7'] . ']';
}

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
	else {
		$theRadioListArray = $OptionListArray[$questionID];
	}

	$remove_value_list = '';
	$Option = array();
	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;

	foreach ($theRadioListArray as $question_range_optionID => $theQuestionArray) {
		if ($theQuestionArray['isRequired'] == '1') {
			$EnableQCoreClass->replace('questionRequire', '<span class=red>*</span>');
		}
		else {
			$EnableQCoreClass->replace('questionRequire', '');
		}

		$optionName = qshowquotechar($theQuestionArray['optionName']);

		if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
			$EnableQCoreClass->replace('optionName', $optionName);
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionName', $optionName);
		}
		else {
			$EnableQCoreClass->replace('questionRequire', '');
			$optionName .= '</span>:&nbsp;&nbsp;<span style="vertical-align:middle"><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

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
		$remove_value_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
		$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '|';
		$QtnAssCon = _getqtnasscond($questionID, $question_range_optionID);
		$theInfoOptName = qnoscriptstring($theQuestionArray['optionName']);
		$tString = '';

		if ($QtnAssCon != '') {
			$tString = '	';
		}

		$theCheckJS = $tString . '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '';

		if ($theQuestionArray['minOption'] != 0) {
			$theCheckJS .= $tString . '	if (!CheckCheckMinClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $theQuestionArray['minOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($theQuestionArray['maxOption'] != 0) {
			$theCheckJS .= $tString . '	if (!CheckCheckMaxClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $theQuestionArray['maxOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($QtnAssCon != '') {
			if ($theQuestionArray['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= $theCheckJS;
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= $theCheckJS;
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
				}
			}
		}
		else if ($theQuestionArray['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
				$check_survey_form_no_con_list .= $theCheckJS;
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= $theCheckJS;
			}
		}

		if (($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['requiredMode'] != '2')) {
			$theOptionNum = count($AnswerListArray[$questionID]) - 1;
			$check_survey_conditions_list .= '	theExcludeItem(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ',' . $theOptionNum . ',0);' . "\n" . '';
		}

		if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	if (getRadioCheckBoxValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ') != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '';
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
		$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
	}

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('suboption' . $questionID, '');
		$EnableQCoreClass->replace('answerName', qshowquotechar($theAnswerArray['optionAnswer']));
		$EnableQCoreClass->replace('optionAnswerID', $question_range_answerID);

		foreach ($Option as $question_range_optionID) {
			$EnableQCoreClass->replace('optionValue', $question_range_answerID);
			$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_range_optionID);
			$EnableQCoreClass->replace('optionNameID', $question_range_optionID);

			if (ischeckboxselect($question_range_answerID, $questionID . '_' . $question_range_optionID)) {
				$EnableQCoreClass->replace('isCheck', 'checked');
			}
			else {
				$EnableQCoreClass->replace('isCheck', '');
			}

			$tmp++;
			$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		$EnableQCoreClass->unreplace('suboption' . $questionID);
		$OptAssCon = _getoptasscond($questionID, $question_range_answerID);

		if ($OptAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $OptAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_range_' . $question_range_answerID . '_container").hide();' . "\n" . '';

			foreach ($Option as $question_range_optionID) {
				$check_survey_conditions_list .= '		$("input[id=\'option_' . $questionID . '_' . $question_range_optionID . '\'][value=\'' . $question_range_answerID . '\']").attr("checked",false);' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_range_' . $question_range_answerID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}
	}

	if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['requiredMode'] == '2')) {
		$theOptionNum = count($AnswerListArray[$questionID]) - 1;

		if ($QtnListArray[$questionID]['isCheckType'] == 1) {
			$check_survey_conditions_list .= '	theMaskingItemIsNeg(' . $questionID . ',\'' . implode(',', array_keys($theRadioListArray)) . '\',' . $theOptionNum . ',2);' . "\n" . '';
		}
		else {
			$check_survey_conditions_list .= '	theMaskingItem(' . $questionID . ',\'' . implode(',', array_keys($theRadioListArray)) . '\',' . $theOptionNum . ',2);' . "\n" . '';
		}
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
	else {
		$theRadioListArray = $OptionListArray[$questionID];
	}

	$remove_value_list = '';
	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;

	foreach ($theRadioListArray as $question_range_optionID => $theQuestionArray) {
		$EnableQCoreClass->replace('suboption' . $questionID, '');

		if ($theQuestionArray['isRequired'] == '1') {
			$EnableQCoreClass->replace('questionRequire', '<span class=red>*</span>');
		}
		else {
			$EnableQCoreClass->replace('questionRequire', '');
		}

		$optionName = qshowquotechar($theQuestionArray['optionName']);

		if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
			$EnableQCoreClass->replace('optionName', $optionName);
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionName', $optionName);
		}
		else {
			$EnableQCoreClass->replace('questionRequire', '');
			$optionName .= '</span>:&nbsp;&nbsp;<span><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

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
		$remove_value_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
		$i = 0;

		for (; $i < $AnswerNum; $i++) {
			$EnableQCoreClass->replace('optionValue', $Answer[$i]);
			$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_range_optionID);

			if (ischeckboxselect($Answer[$i], $questionID . '_' . $question_range_optionID)) {
				$EnableQCoreClass->replace('isCheck', 'checked');
			}
			else {
				$EnableQCoreClass->replace('isCheck', '');
			}

			$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $question_range_optionID . '|';
		$QtnAssCon = _getqtnasscond($questionID, $question_range_optionID);
		$theInfoOptName = qnoscriptstring($theQuestionArray['optionName']);
		$tString = '';

		if ($QtnAssCon != '') {
			$tString = '	';
		}

		$theCheckJS = $tString . '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '';

		if ($theQuestionArray['minOption'] != 0) {
			$theCheckJS .= $tString . '	if (!CheckCheckMinClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $theQuestionArray['minOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($theQuestionArray['maxOption'] != 0) {
			$theCheckJS .= $tString . '	if (!CheckCheckMaxClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . '_' . $question_range_optionID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $theQuestionArray['maxOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($QtnAssCon != '') {
			if ($theQuestionArray['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= $theCheckJS;
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= $theCheckJS;
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
				}
			}
		}
		else if ($theQuestionArray['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
				$check_survey_form_no_con_list .= $theCheckJS;
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= $theCheckJS;
			}
		}

		if (($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['requiredMode'] != '2')) {
			$theOptionNum = $AnswerNum - 1;
			$check_survey_conditions_list .= '	theExcludeItem(document.Survey_Form.option_' . $questionID . '_' . $question_range_optionID . ',' . $theOptionNum . ',0);' . "\n" . '';
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
				$check_survey_conditions_list .= '		$("input[id=\'option_' . $questionID . '_' . $question_range_optionID . '\'][value=\'' . $question_range_answerID . '\']").attr("checked",false);' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else{' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#qtn_table_' . $questionID . '\').showColumns([' . $colNum . ',' . $colNumNext . ']);' . "\n" . '	} ' . "\n" . '';
		}
	}

	if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['requiredMode'] == '2')) {
		$theOptionNum = $AnswerNum - 1;

		if ($QtnListArray[$questionID]['isCheckType'] == 1) {
			$check_survey_conditions_list .= '	theMaskingItemIsNeg(' . $questionID . ',\'' . implode(',', array_keys($theRadioListArray)) . '\',' . $theOptionNum . ',1);' . "\n" . '';
		}
		else {
			$check_survey_conditions_list .= '	theMaskingItem(' . $questionID . ',\'' . implode(',', array_keys($theRadioListArray)) . '\',' . $theOptionNum . ',1);' . "\n" . '';
		}
	}

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
		$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

	unset($Answer);
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

				if ($aRow['oriValue'] == '') {
					$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
				}
				else {
					$theOriValue = explode(',', $aRow['oriValue']);
					$theOriValueList = '';

					foreach ($theOriValue as $thisOriValue) {
						$theOriValueList .= qnospecialchar($AnswerListArray[$questionID][$thisOriValue]['optionAnswer']) . ',';
					}

					$authInfoList .= substr($theOriValueList, 0, -1) . ']</span>至<span class=red>[';
				}

				if ($aRow['updateValue'] == '') {
					$authInfoList .= $lang['skip_answer'] . ']</span>';
				}
				else {
					$theUpdateValue = explode(',', $aRow['updateValue']);
					$thisUpdateValueList = '';

					foreach ($theUpdateValue as $thisUpdateValue) {
						$thisUpdateValueList .= qnospecialchar($AnswerListArray[$questionID][$thisUpdateValue]['optionAnswer']) . ',';
					}

					$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
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

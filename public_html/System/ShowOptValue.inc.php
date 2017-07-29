<?php
//dezend by http://www.yunlu99.com/
function optvaluedatasubmit()
{
	global $_POST;
	global $DB;
	$this_fields_list = substr($_POST['this_fields_list'], 0, -1);
	$_obf_YgqkOqeBaE9fv9GdzdVdZnE_ = explode('|', $this_fields_list);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < count($_obf_YgqkOqeBaE9fv9GdzdVdZnE_); $_obf_7w__++) {
		$_obf_5KN9_q_EjRMs0zaCkXECafqz = explode('_', $_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]);

		switch ($_obf_5KN9_q_EjRMs0zaCkXECafqz['1']) {
		case '1':
		case '18':
			$_obf_xCnI = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE question_yesnoID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '2':
			if ($_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] == '0') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				$DB->query($_obf_xCnI);
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE question_radioID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
				$DB->query($_obf_xCnI);
			}

			break;

		case '24':
			$_obf_xCnI = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE question_radioID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '3':
			if ($_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] == '0') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				$DB->query($_obf_xCnI);
			}
			else if ($_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] == '99999') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET negValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				$DB->query($_obf_xCnI);
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE question_checkboxID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
				$DB->query($_obf_xCnI);
			}

			break;

		case '25':
			$_obf_xCnI = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE question_checkboxID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '15':
		case '21':
			$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET negValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '17':
			$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET negValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '6':
		case '19':
		case '26':
		case '7':
		case '28':
			$_obf_xCnI = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET optionValue=\'' . $_POST[$_obf_YgqkOqeBaE9fv9GdzdVdZnE_[$_obf_7w__]] . '\' WHERE question_range_answerID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			$DB->query($_obf_xCnI);
			break;
		}
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
$theWeightQtnTypeArray = array(1, 2, 3, 6, 7, 15, 17, 18, 19, 21, 24, 25, 26, 28);
$this_fields_list = '';
$check_form_list = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	$isCodeParse = true;

	if (in_array($theQtnArray['questionType'], $theWeightQtnTypeArray)) {
		$surveyID = $_GET['surveyID'];
		$EnableQCoreClass->replace('questionID', $questionID);

		switch ($theQtnArray['questionType']) {
		case '1':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . $theQuestionArray['optionName'] . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_yesnoID);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('optionValue', $theQuestionArray['optionValue']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '2':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RadioValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_2'] . ']');

			foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
				$this_fields_list .= 'option_2_' . $questionID . '_' . $question_radioID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_2_' . $questionID . '_' . $question_radioID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_radioID);
				$EnableQCoreClass->replace('optionValue', $theQuestionArray['optionValue']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			if ($theQtnArray['isHaveOther'] == '1') {
				$EnableQCoreClass->replace('isHaveOther', '');
				$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
				$EnableQCoreClass->replace('otherValue', $theQtnArray['optionValue']);
				$this_fields_list .= 'option_2_' . $questionID . '_0' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_2_' . $questionID . '_0,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQtnArray['otherText']) . '\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveOther', 'none');
				$EnableQCoreClass->replace('otherValue', 0);
				$EnableQCoreClass->replace('other_optionName', '');
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '3':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_3'] . ']');

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$this_fields_list .= 'option_3_' . $questionID . '_' . $question_checkboxID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_3_' . $questionID . '_' . $question_checkboxID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_checkboxID);
				$EnableQCoreClass->replace('optionValue', $theQuestionArray['optionValue']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			if ($theQtnArray['isHaveOther'] == '1') {
				$EnableQCoreClass->replace('isHaveOther', '');
				$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
				$EnableQCoreClass->replace('otherValue', $theQtnArray['optionValue']);
				$this_fields_list .= 'option_3_' . $questionID . '_0' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_3_' . $questionID . '_0,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQtnArray['otherText']) . '\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveOther', 'none');
				$EnableQCoreClass->replace('other_optionName', '');
				$EnableQCoreClass->replace('otherValue', 0);
			}

			if ($theQtnArray['isNeg'] == '1') {
				$EnableQCoreClass->replace('isHaveNeg', '');
				$neg_optionName = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : $theQtnArray['allowType']);
				$EnableQCoreClass->replace('neg_optionName', qnospecialchar($neg_optionName));
				$EnableQCoreClass->replace('negValue', $theQtnArray['negValue']);
				$this_fields_list .= 'option_3_' . $questionID . '_99999' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_3_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($neg_optionName) . '\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveNeg', 'none');
				$EnableQCoreClass->replace('neg_optionName', '');
				$EnableQCoreClass->replace('negValue', 0);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '15':
		case '21':
			if (($QtnListArray[$questionID]['isSelect'] == '0') && ($QtnListArray[$questionID]['isHaveUnkown'] == 1)) {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'UnknowValue.html');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');
				$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('isHaveUnknow', '');
				$EnableQCoreClass->replace('optionName', '不清楚');
				$EnableQCoreClass->replace('negValue', $theQtnArray['negValue']);
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 不清楚\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			}
			else {
				$isCodeParse = false;
			}

			break;

		case '17':
			if ($QtnListArray[$questionID]['isCheckType'] == 1) {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'AutoValue.html');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_17'] . ']');
				$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('isHaveUnknow', '');
				$neg_optionName = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : $theQtnArray['allowType']);
				$EnableQCoreClass->replace('optionName', qnospecialchar($neg_optionName));
				$EnableQCoreClass->replace('negValue', $theQtnArray['negValue']);
				$this_fields_list .= 'option_17_' . $questionID . '_99999' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($neg_optionName) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			}
			else {
				$isCodeParse = false;
			}

			break;

		case '18':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_yesnoID);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('optionValue', $theQuestionArray['optionValue']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '6':
		case '19':
		case '26':
		case '7':
		case '28':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . '|';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theAnswerArray['optionAnswer']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('optionValue', $theAnswerArray['optionValue']);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '24':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRadioValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_24'] . ']');

			foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
				$this_fields_list .= 'option_24_' . $questionID . '_' . $question_radioID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_24_' . $questionID . '_' . $question_radioID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_radioID);
				$EnableQCoreClass->replace('optionValue', $theQuestionArray['optionValue']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '25':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombCheckBoxValue.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_25'] . ']');

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$this_fields_list .= 'option_25_' . $questionID . '_' . $question_checkboxID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_25_' . $questionID . '_' . $question_checkboxID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_checkboxID);
				$EnableQCoreClass->replace('optionValue', $theQuestionArray['optionValue']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;
		}

		if ($isCodeParse == true) {
			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}
}

$EnableQCoreClass->replace('this_fields_list', $this_fields_list);
$EnableQCoreClass->replace('check_form_list', $check_form_list);

?>

<?php
//dezend by http://www.yunlu99.com/
function optweightdatasubmit()
{
	global $_POST;
	global $DB;
	$this_question_list = substr($_POST['questionIDList'], 0, -1);
	$_obf_qwhQZ7w5XO4_fvAOHJnGe6s_ = explode('|', $this_question_list);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < count($_obf_qwhQZ7w5XO4_fvAOHJnGe6s_); $_obf_7w__++) {
		$_obf_3MDEGZq_8RQ_ = $_obf_qwhQZ7w5XO4_fvAOHJnGe6s_[$_obf_7w__];
		if (($_obf_3MDEGZq_8RQ_ != '') && ($_obf_3MDEGZq_8RQ_ != 0)) {
			$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET coeffMode = \'' . $_POST['coeffMode_' . $_obf_3MDEGZq_8RQ_] . '\',coeffTotal = \'' . $_POST['coeffTotal_' . $_obf_3MDEGZq_8RQ_] . '\',coeffZeroMargin = \'' . $_POST['coeffZeroMargin_' . $_obf_3MDEGZq_8RQ_] . '\',coeffFullMargin = \'' . $_POST['coeffFullMargin_' . $_obf_3MDEGZq_8RQ_] . '\',skipMode = \'' . $_POST['skipMode_' . $_obf_3MDEGZq_8RQ_] . '\' WHERE questionID =\'' . $_obf_3MDEGZq_8RQ_ . '\' ';
			$DB->query($_obf_xCnI);
		}
	}

	$this_fields_list = substr($_POST['this_fields_list'], 0, -1);
	$_obf_5wnat10lwobpHHeFl_V10uE_ = explode('|', $this_fields_list);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < count($_obf_5wnat10lwobpHHeFl_V10uE_); $_obf_7w__++) {
		$_obf_5KN9_q_EjRMs0zaCkXECafqz = explode('_', $_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]);

		switch ($_obf_5KN9_q_EjRMs0zaCkXECafqz['1']) {
		case '1':
		case '18':
			if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_yesnoID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_yesnoID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}

			$DB->query($_obf_xCnI);
			break;

		case '2':
			if ($_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] == '0') {
				if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
					$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				}
				else {
					$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				}

				$DB->query($_obf_xCnI);
			}
			else {
				if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
					$_obf_xCnI = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_radioID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
				}
				else {
					$_obf_xCnI = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_radioID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
				}

				$DB->query($_obf_xCnI);
			}

			break;

		case '24':
			if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_radioID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_radioID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}

			$DB->query($_obf_xCnI);
			break;

		case '3':
			if ($_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] == '0') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				$DB->query($_obf_xCnI);
			}
			else if ($_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] == '99999') {
				if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
					$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET negCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				}
				else {
					$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
				}

				$DB->query($_obf_xCnI);
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_checkboxID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
				$DB->query($_obf_xCnI);
			}

			break;

		case '25':
			if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_checkboxID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_checkboxID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}

			$DB->query($_obf_xCnI);
			break;

		case '4':
		case '15':
		case '21':
			$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET negCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '17':
			if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET negCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE questionID = \'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['2'] . '\' ';
			}

			$DB->query($_obf_xCnI);
			break;

		case '6':
		case '19':
		case '26':
		case '7':
		case '28':
			if (trim($_obf_5KN9_q_EjRMs0zaCkXECafqz['0']) == 'option') {
				$_obf_xCnI = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET optionCoeff=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_range_answerID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}
			else {
				$_obf_xCnI = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET isNA=\'' . $_POST[$_obf_5wnat10lwobpHHeFl_V10uE_[$_obf_7w__]] . '\' WHERE question_range_answerID=\'' . $_obf_5KN9_q_EjRMs0zaCkXECafqz['3'] . '\' ';
			}

			$DB->query($_obf_xCnI);
			break;
		}
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
$theWeightQtnTypeArray = array(1, 2, 3, 4, 6, 7, 15, 17, 18, 19, 21, 24, 25, 26, 28);
$this_fields_list = '';
$check_form_list = '';
$questionIDList = '';
$init_func_list = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	$isCodeParse = true;

	if (in_array($theQtnArray['questionType'], $theWeightQtnTypeArray)) {
		$surveyID = $_GET['surveyID'];
		$EnableQCoreClass->replace('questionID', $questionID);

		switch ($theQtnArray['questionType']) {
		case '1':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . '|';
				$this_fields_list .= 'na_1_' . $questionID . '_' . $question_yesnoID . '|';
				$init_func_list .= '	checkNaChecked(1,' . $questionID . ',0,' . $question_yesnoID . ');' . "\n" . '';
				$EnableQCoreClass->replace('isHaveNA', '');
				$EnableQCoreClass->replace('isNA', $theQuestionArray['isNA'] == 1 ? 'checked' : '');
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . $theQuestionArray['optionName'] . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_yesnoID);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['optionCoeff']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '2':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RadioCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_2'] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
				$this_fields_list .= 'option_2_' . $questionID . '_' . $question_radioID . '|';
				$this_fields_list .= 'na_2_' . $questionID . '_' . $question_radioID . '|';
				$init_func_list .= '	checkNaChecked(2,' . $questionID . ',0,' . $question_radioID . ');' . "\n" . '';
				$EnableQCoreClass->replace('isNA', $theQuestionArray['isNA'] == 1 ? 'checked' : '');
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_2_' . $questionID . '_' . $question_radioID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_radioID);
				$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['optionCoeff']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			if ($theQtnArray['isHaveOther'] == '1') {
				$EnableQCoreClass->replace('isHaveOther', '');
				$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
				$EnableQCoreClass->replace('otherCoeff', $theQtnArray['optionCoeff']);
				$this_fields_list .= 'option_2_' . $questionID . '_0' . '|';
				$this_fields_list .= 'na_2_' . $questionID . '_0' . '|';
				$init_func_list .= '	checkNaChecked(2,' . $questionID . ',0,0);' . "\n" . '';
				$EnableQCoreClass->replace('isOtherNA', $theQtnArray['isNA'] == 1 ? 'checked' : '');
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_2_' . $questionID . '_0,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQtnArray['otherText']) . '\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveOther', 'none');
				$EnableQCoreClass->replace('otherCoeff', 0);
				$EnableQCoreClass->replace('other_optionName', '');
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '3':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_3'] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$this_fields_list .= 'option_3_' . $questionID . '_' . $question_checkboxID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_3_' . $questionID . '_' . $question_checkboxID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_checkboxID);
				$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['optionCoeff']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			if ($theQtnArray['isHaveOther'] == '1') {
				$EnableQCoreClass->replace('isHaveOther', '');
				$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
				$EnableQCoreClass->replace('otherCoeff', $theQtnArray['optionCoeff']);
				$this_fields_list .= 'option_3_' . $questionID . '_0' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_3_' . $questionID . '_0,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQtnArray['otherText']) . '\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveOther', 'none');
				$EnableQCoreClass->replace('other_optionName', '');
				$EnableQCoreClass->replace('otherCoeff', 0);
			}

			if ($theQtnArray['isNeg'] == '1') {
				$EnableQCoreClass->replace('isHaveNeg', '');
				$neg_optionName = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : $theQtnArray['allowType']);
				$EnableQCoreClass->replace('neg_optionName', qnospecialchar($neg_optionName));
				$EnableQCoreClass->replace('negCoeff', $theQtnArray['negCoeff'] == '0.00' ? '' : $theQtnArray['negCoeff']);
				$this_fields_list .= 'option_3_' . $questionID . '_99999' . '|';
				$this_fields_list .= 'na_3_' . $questionID . '_99999' . '|';
				$init_func_list .= '	checkNaChecked(3,' . $questionID . ',0,99999);' . "\n" . '';
				$EnableQCoreClass->replace('isNA', $theQtnArray['isNA'] == 1 ? 'checked' : '');
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_3_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($neg_optionName) . '\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveNeg', 'none');
				$EnableQCoreClass->replace('neg_optionName', '');
				$EnableQCoreClass->replace('negCoeff', 0);
				$EnableQCoreClass->replace('isNA', '');
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '4':
			if ($QtnListArray[$questionID]['isCheckType'] == '4') {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'UnknowCoeff.html');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_4'] . ']');

				switch ($theQtnArray['coeffMode']) {
				case 1:
					$EnableQCoreClass->replace('coeffMode_1', 'checked');
					$EnableQCoreClass->replace('coeffMode_2', '');
					break;

				case 2:
					$EnableQCoreClass->replace('coeffMode_1', '');
					$EnableQCoreClass->replace('coeffMode_2', 'checked');
					break;
				}

				$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
				$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
				$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

				switch ($theQtnArray['skipMode']) {
				case 1:
					$EnableQCoreClass->replace('skipMode_1', 'checked');
					$EnableQCoreClass->replace('skipMode_2', '');
					$EnableQCoreClass->replace('skipMode_3', '');
					break;

				case 2:
					$EnableQCoreClass->replace('skipMode_1', '');
					$EnableQCoreClass->replace('skipMode_2', 'checked');
					$EnableQCoreClass->replace('skipMode_3', '');
					break;

				case 3:
					$EnableQCoreClass->replace('skipMode_1', '');
					$EnableQCoreClass->replace('skipMode_2', '');
					$EnableQCoreClass->replace('skipMode_3', 'checked');
					break;
				}

				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);

				if ($QtnListArray[$questionID]['isHaveUnkown'] == 2) {
					$EnableQCoreClass->replace('isHaveUnknow', '');
					$EnableQCoreClass->replace('optionName', '不清楚');
					$EnableQCoreClass->replace('negCoeff', $theQtnArray['negCoeff'] == '0.00' ? '' : $theQtnArray['negCoeff']);
					$this_fields_list .= 'option_4_' . $questionID . '_99999' . '|';
					$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_4_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 不清楚\')){return false;} ' . "\n" . '';
				}
				else {
					$EnableQCoreClass->replace('isHaveUnknow', 'none');
					$EnableQCoreClass->replace('optionName', '');
					$EnableQCoreClass->replace('negCoeff', 0);
				}

				$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			}
			else {
				$isCodeParse = false;
				continue;
			}

			break;

		case '15':
		case '21':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'UnknowCoeff.html');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');
			$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';
			if (($QtnListArray[$questionID]['isSelect'] == '0') && ($QtnListArray[$questionID]['isHaveUnkown'] == 1)) {
				$EnableQCoreClass->replace('isHaveUnknow', '');
				$EnableQCoreClass->replace('optionName', '不清楚');
				$EnableQCoreClass->replace('negCoeff', $theQtnArray['negCoeff'] == '0.00' ? '' : $theQtnArray['negCoeff']);
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999' . '|';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 不清楚\')){return false;} ' . "\n" . '';
			}
			else {
				$EnableQCoreClass->replace('isHaveUnknow', 'none');
				$EnableQCoreClass->replace('optionName', '');
				$EnableQCoreClass->replace('negCoeff', 0);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '17':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'AutoCoeff.html');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_17'] . ']');
			$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			if ($QtnListArray[$questionID]['isCheckType'] == 1) {
				$EnableQCoreClass->replace('isHaveUnknow', '');
				$neg_optionName = ($theQtnArray['allowType'] == '' ? $lang['neg_text'] : $theQtnArray['allowType']);
				$EnableQCoreClass->replace('optionName', qnospecialchar($neg_optionName));
				$EnableQCoreClass->replace('negCoeff', $theQtnArray['negCoeff'] == '0.00' ? '' : $theQtnArray['negCoeff']);
				$this_fields_list .= 'option_17_' . $questionID . '_99999' . '|';
				$this_fields_list .= 'na_17_' . $questionID . '_99999' . '|';
				$init_func_list .= '	checkNaChecked(17,' . $questionID . ',0,99999);' . "\n" . '';
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999,\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($neg_optionName) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('isNA', $theQtnArray['isNA'] == 1 ? 'checked' : '');
			}
			else {
				$EnableQCoreClass->replace('isHaveUnknow', 'none');
				$EnableQCoreClass->replace('optionName', '');
				$EnableQCoreClass->replace('negCoeff', 0);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '18':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . '|';

				if ($QtnListArray[$questionID]['isSelect'] == '1') {
					$EnableQCoreClass->replace('isHaveNA', 'none');
				}
				else {
					$EnableQCoreClass->replace('isHaveNA', '');
					$EnableQCoreClass->replace('isNA', $theQuestionArray['isNA'] == 1 ? 'checked' : '');
					$this_fields_list .= 'na_18_' . $questionID . '_' . $question_yesnoID . '|';
					$init_func_list .= '	checkNaChecked(18,' . $questionID . ',0,' . $question_yesnoID . ');' . "\n" . '';
				}

				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_yesnoID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_yesnoID);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['optionCoeff']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '6':
		case '19':
		case '26':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . '|';
				$EnableQCoreClass->replace('isNA', $theAnswerArray['isNA'] == 1 ? 'checked' : '');
				$this_fields_list .= 'na_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . '|';
				$init_func_list .= '	checkNaChecked(' . $theQtnArray['questionType'] . ',' . $questionID . ',0,' . $question_range_answerID . ');' . "\n" . '';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theAnswerArray['optionAnswer']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('optionCoeff', $theAnswerArray['optionCoeff']);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '7':
		case '28':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'MultipleCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';
			$theAnswerNum = count($AnswerListArray[$questionID]) - 1;
			$tmp = 0;

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . '|';

				if ($theQtnArray['questionType'] == 7) {
					if (($tmp == $theAnswerNum) && ($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['requiredMode'] != '2')) {
						$EnableQCoreClass->replace('isHaveNA', '');
						$EnableQCoreClass->replace('isNA', $theAnswerArray['isNA'] == 1 ? 'checked' : '');
						$this_fields_list .= 'na_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . '|';
						$init_func_list .= '	checkNaChecked(' . $theQtnArray['questionType'] . ',' . $questionID . ',0,' . $question_range_answerID . ');' . "\n" . '';
					}
					else {
						$EnableQCoreClass->replace('isHaveNA', 'none');
					}
				}
				else {
					if (($tmp == $theAnswerNum) && ($QtnListArray[$questionID]['isSelect'] == '1')) {
						$EnableQCoreClass->replace('isHaveNA', '');
						$EnableQCoreClass->replace('isNA', $theAnswerArray['isNA'] == 1 ? 'checked' : '');
						$this_fields_list .= 'na_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . '|';
						$init_func_list .= '	checkNaChecked(' . $theQtnArray['questionType'] . ',' . $questionID . ',0,' . $question_range_answerID . ');' . "\n" . '';
					}
					else {
						$EnableQCoreClass->replace('isHaveNA', 'none');
					}
				}

				$tmp++;
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_' . $theQtnArray['questionType'] . '_' . $questionID . '_' . $question_range_answerID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theAnswerArray['optionAnswer']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('optionCoeff', $theAnswerArray['optionCoeff']);
				$EnableQCoreClass->replace('rangType', $theQtnArray['questionType']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '24':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRadioCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_24'] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
				$this_fields_list .= 'option_24_' . $questionID . '_' . $question_radioID . '|';
				$EnableQCoreClass->replace('isNA', $theQuestionArray['isNA'] == 1 ? 'checked' : '');
				$this_fields_list .= 'na_24_' . $questionID . '_' . $question_radioID . '|';
				$init_func_list .= '	checkNaChecked(24,' . $questionID . ',0,' . $question_radioID . ');' . "\n" . '';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_24_' . $questionID . '_' . $question_radioID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_radioID);
				$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['optionCoeff']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '25':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombCheckBoxCoeff.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_25'] . ']');

			switch ($theQtnArray['coeffMode']) {
			case 1:
				$EnableQCoreClass->replace('coeffMode_1', 'checked');
				$EnableQCoreClass->replace('coeffMode_2', '');
				break;

			case 2:
				$EnableQCoreClass->replace('coeffMode_1', '');
				$EnableQCoreClass->replace('coeffMode_2', 'checked');
				break;
			}

			$EnableQCoreClass->replace('coeffTotal', $theQtnArray['coeffTotal'] == '0.00' ? '' : $theQtnArray['coeffTotal']);
			$EnableQCoreClass->replace('coeffZeroMargin', $theQtnArray['coeffZeroMargin'] == 1 ? 'checked' : '');
			$EnableQCoreClass->replace('coeffFullMargin', $theQtnArray['coeffFullMargin'] == 1 ? 'checked' : '');

			switch ($theQtnArray['skipMode']) {
			case 1:
				$EnableQCoreClass->replace('skipMode_1', 'checked');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 2:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', 'checked');
				$EnableQCoreClass->replace('skipMode_3', '');
				break;

			case 3:
				$EnableQCoreClass->replace('skipMode_1', '');
				$EnableQCoreClass->replace('skipMode_2', '');
				$EnableQCoreClass->replace('skipMode_3', 'checked');
				break;
			}

			$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.coeffTotal_' . $questionID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - 满分\')){return false;} ' . "\n" . '';

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$this_fields_list .= 'option_25_' . $questionID . '_' . $question_checkboxID . '|';

				if ($theQuestionArray['isExclusive'] == 1) {
					$EnableQCoreClass->replace('isHaveNA', '');
					$EnableQCoreClass->replace('isNA', $theQuestionArray['isNA'] == 1 ? 'checked' : '');
					$this_fields_list .= 'na_25_' . $questionID . '_' . $question_checkboxID . '|';
					$init_func_list .= '	checkNaChecked(25,' . $questionID . ',0,' . $question_checkboxID . ');' . "\n" . '';
				}
				else {
					$EnableQCoreClass->replace('isHaveNA', 'none');
				}

				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$check_form_list .= 'if(!CheckNoFloat(document.Check_Form.option_25_' . $questionID . '_' . $question_checkboxID . ',\'' . qnoscriptstring($theQtnArray['questionName']) . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('optionID', $question_checkboxID);
				$EnableQCoreClass->replace('optionCoeff', $theQuestionArray['optionCoeff']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;
		}

		if ($isCodeParse == true) {
			$questionIDList .= $questionID . '|';
			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}
}

$EnableQCoreClass->replace('this_fields_list', $this_fields_list);
$EnableQCoreClass->replace('check_form_list', $check_form_list);
$EnableQCoreClass->replace('questionIDList', $questionIDList);
$EnableQCoreClass->replace('init_func_list', $init_func_list);

?>

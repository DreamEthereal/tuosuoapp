<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxEditSurveyCode.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyTitle,status,surveyID,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($_POST['Action'] == 'EditCodeSubmit') {
	$this_fields_list = substr($_POST['this_fields_list'], 0, -1);
	$code_fields_name = explode('|', $this_fields_list);
	$i = 0;

	for (; $i < count($code_fields_name); $i++) {
		$survey_fields_name = explode('_', $code_fields_name[$i]);

		switch ($survey_fields_name['1']) {
		case '1':
		case '18':
			if (trim($survey_fields_name['0']) == 'option') {
				$SQL = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE  question_yesnoID=\'' . $survey_fields_name['3'] . '\' ';
			}
			else {
				$SQL = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET isUnkown=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE  question_yesnoID=\'' . $survey_fields_name['3'] . '\' ';
			}

			$DB->query($SQL);
			break;

		case '2':
			if ($survey_fields_name['3'] == '0') {
				if (trim($survey_fields_name['0']) == 'option') {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET otherCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE questionID = \'' . $survey_fields_name['2'] . '\' ';
				}
				else {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isUnkown=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE questionID = \'' . $survey_fields_name['2'] . '\' ';
				}

				$DB->query($SQL);
			}
			else {
				if (trim($survey_fields_name['0']) == 'option') {
					$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_radioID=\'' . $survey_fields_name['3'] . '\' ';
				}
				else {
					$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET isUnkown=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_radioID=\'' . $survey_fields_name['3'] . '\' ';
				}

				$DB->query($SQL);
			}

			break;

		case '4':
		case '23':
		case '15':
		case '21':
		case '17':
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET negCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE questionID = \'' . $survey_fields_name['2'] . '\' ';
			$DB->query($SQL);
			break;

		case '24':
			if (trim($survey_fields_name['0']) == 'option') {
				$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_radioID=\'' . $survey_fields_name['3'] . '\' ';
			}
			else {
				$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET isUnkown=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_radioID=\'' . $survey_fields_name['3'] . '\' ';
			}

			$DB->query($SQL);
			break;

		case '3':
			if ($survey_fields_name['3'] == '0') {
				$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET otherCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE questionID = \'' . $survey_fields_name['2'] . '\' ';
				$DB->query($SQL);
			}
			else if ($survey_fields_name['3'] == '99999') {
				$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET negCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE questionID = \'' . $survey_fields_name['2'] . '\' ';
				$DB->query($SQL);
			}
			else {
				$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_checkboxID=\'' . $survey_fields_name['3'] . '\' ';
				$DB->query($SQL);
			}

			break;

		case '25':
			$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_checkboxID=\'' . $survey_fields_name['3'] . '\' ';
			$DB->query($SQL);
			break;

		case '6':
		case '19':
		case '26':
			if (trim($survey_fields_name['0']) == 'option') {
				$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_range_answerID=\'' . $survey_fields_name['3'] . '\' ';
			}
			else {
				$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET isUnkown=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_range_answerID=\'' . $survey_fields_name['3'] . '\' ';
			}

			$DB->query($SQL);
			break;

		case '7':
		case '28':
			$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET itemCode=\'' . $_POST[$code_fields_name[$i]] . '\' WHERE question_range_answerID=\'' . $survey_fields_name['3'] . '\' ';
			$DB->query($SQL);
			break;
		}
	}

	if ($Sur_G_Row['status'] != '0') {
		$theSID = $_POST['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	writetolog($lang['edit_survey_code'] . ':' . $Sur_G_Row['surveyTitle']);
	_showsucceed($lang['edit_survey_code'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('EditSurveyCode', 'SurveyEditCode.html');
$EnableQCoreClass->set_CycBlock('EditSurveyCode', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');

if ($Sur_G_Row['status'] != '0') {
	$EnableQCoreClass->setTemplateFile('SurveyActionFile', 'DeployActionList.html');
	$EnableQCoreClass->replace('navActionList', $EnableQCoreClass->parse('SurveyActionPage', 'SurveyActionFile'));
	if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
}
else {
	$EnableQCoreClass->setTemplateFile('SurveyActionFile', 'DesignActionList.html');
	$tmp = 1;

	for (; $tmp <= 4; $tmp++) {
		$EnableQCoreClass->replace('cur_' . $tmp, '');
	}

	$EnableQCoreClass->replace('cur_2', ' class="cur"');
	$EnableQCoreClass->replace('navActionList', $EnableQCoreClass->parse('SurveyActionPage', 'SurveyActionFile'));
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
}

$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyID', $Sur_G_Row['surveyID']);
if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
	$EnableQCoreClass->replace('isViewUserAction', 'disabled');
}
else {
	$EnableQCoreClass->replace('isViewUserAction', '');
}

$theQtnTypeArray = array(1, 2, 3, 4, 6, 7, 15, 17, 18, 19, 21, 23, 24, 25, 26, 28);
$this_fields_list = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	$isCodeParse = true;

	if (in_array($theQtnArray['questionType'], $theQtnTypeArray)) {
		$surveyID = $_GET['surveyID'];

		switch ($theQtnArray['questionType']) {
		case '1':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_1'] . ']');

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				$this_fields_list .= 'option_1_' . $questionID . '_' . $question_yesnoID . '|';
				$this_fields_list .= 'unkown_1_' . $questionID . '_' . $question_yesnoID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_yesnoID);
				$EnableQCoreClass->replace('rangType', 1);
				$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
				$EnableQCoreClass->replace('isUnkown', $theQuestionArray['isUnkown'] == 1 ? 'checked' : '');
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '2':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RadioItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_2'] . ']');

			foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
				$this_fields_list .= 'option_2_' . $questionID . '_' . $question_radioID . '|';
				$this_fields_list .= 'unkown_2_' . $questionID . '_' . $question_radioID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_radioID);
				$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
				$EnableQCoreClass->replace('isUnkown', $theQuestionArray['isUnkown'] == 1 ? 'checked' : '');
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			if ($theQtnArray['isHaveOther'] == '1') {
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('isHaveOther', '');
				$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
				$EnableQCoreClass->replace('otherCode', $theQtnArray['otherCode'] == 0 ? '' : $theQtnArray['otherCode']);
				$this_fields_list .= 'option_2_' . $questionID . '_0' . '|';
				$this_fields_list .= 'unkown_2_' . $questionID . '_0' . '|';
				$EnableQCoreClass->replace('isUnkown0', $theQtnArray['isUnkown'] == 1 ? 'checked' : '');
			}
			else {
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('isHaveOther', 'none');
				$EnableQCoreClass->replace('otherCode', 0);
				$EnableQCoreClass->replace('other_optionName', '');
				$EnableQCoreClass->replace('isUnkown0', '');
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '3':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_3'] . ']');

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$this_fields_list .= 'option_3_' . $questionID . '_' . $question_checkboxID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_checkboxID);
				$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionID', $questionID);

			if ($theQtnArray['isHaveOther'] == '1') {
				$EnableQCoreClass->replace('isHaveOther', '');
				$EnableQCoreClass->replace('other_optionName', qnospecialchar($theQtnArray['otherText']));
				$EnableQCoreClass->replace('otherCode', $theQtnArray['otherCode'] == 0 ? '' : $theQtnArray['otherCode']);
				$this_fields_list .= 'option_3_' . $questionID . '_0' . '|';
			}
			else {
				$EnableQCoreClass->replace('isHaveOther', 'none');
				$EnableQCoreClass->replace('otherCode', 0);
				$EnableQCoreClass->replace('other_optionName', '');
			}

			if ($theQtnArray['isNeg'] == '1') {
				$EnableQCoreClass->replace('isHaveNeg', '');
				$EnableQCoreClass->replace('neg_optionName', $theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
				$EnableQCoreClass->replace('negCode', $theQtnArray['negCode'] == 0 ? '' : $theQtnArray['negCode']);
				$this_fields_list .= 'option_3_' . $questionID . '_99999' . '|';
			}
			else {
				$EnableQCoreClass->replace('isHaveNeg', 'none');
				$EnableQCoreClass->replace('negCode', 0);
				$EnableQCoreClass->replace('neg_optionName', '');
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '4':
		case '23':
			if ($QtnListArray[$questionID]['isHaveUnkown'] == 2) {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'UnknowItemCode.html');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');
				$EnableQCoreClass->replace('optionName', '不清楚');
				$EnableQCoreClass->replace('negCode', $theQtnArray['negCode'] == 0 ? '' : $theQtnArray['negCode']);
				$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('questionID', $questionID);
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999' . '|';
				$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			}
			else {
				$isCodeParse = false;
				continue;
			}

			break;

		case '15':
		case '21':
			if (($QtnListArray[$questionID]['isSelect'] == '0') && ($QtnListArray[$questionID]['isHaveUnkown'] == 1)) {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'UnknowItemCode.html');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');
				$EnableQCoreClass->replace('optionName', '不清楚');
				$EnableQCoreClass->replace('negCode', $theQtnArray['negCode'] == 0 ? '' : $theQtnArray['negCode']);
				$EnableQCoreClass->replace('questionType', $theQtnArray['questionType']);
				$EnableQCoreClass->replace('questionID', $questionID);
				$this_fields_list .= 'option_' . $theQtnArray['questionType'] . '_' . $questionID . '_99999' . '|';
				$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			}
			else {
				$isCodeParse = false;
				continue;
			}

			break;

		case '17':
			if ($QtnListArray[$questionID]['isCheckType'] == 1) {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'UnknowItemCode.html');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_17'] . ']');
				$EnableQCoreClass->replace('optionName', $theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']));
				$EnableQCoreClass->replace('negCode', $theQtnArray['negCode'] == 0 ? '' : $theQtnArray['negCode']);
				$EnableQCoreClass->replace('questionType', '17');
				$EnableQCoreClass->replace('questionID', $questionID);
				$this_fields_list .= 'option_17_' . $questionID . '_99999' . '|';
				$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			}
			else {
				$isCodeParse = false;
				continue;
			}

			break;

		case '6':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_6'] . ']');

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_6_' . $questionID . '_' . $question_range_answerID . '|';
				$this_fields_list .= 'unkown_6_' . $questionID . '_' . $question_range_answerID . '|';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('itemCode', $theAnswerArray['itemCode'] == 0 ? '' : $theAnswerArray['itemCode']);
				$EnableQCoreClass->replace('isUnkown', $theAnswerArray['isUnkown'] == 1 ? 'checked' : '');
				$EnableQCoreClass->replace('rangType', 6);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '7':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'MultipleItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_7'] . ']');

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_7_' . $questionID . '_' . $question_range_answerID . '|';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('itemCode', $theAnswerArray['itemCode'] == 0 ? '' : $theAnswerArray['itemCode']);
				$EnableQCoreClass->replace('rangType', 7);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '18':
			if ($QtnListArray[$questionID]['isSelect'] == '1') {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CondItemCode.html');
				$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
				$EnableQCoreClass->replace('option', '');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_18'] . ']');

				foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
					$this_fields_list .= 'option_18_' . $questionID . '_' . $question_yesnoID . '|';
					$optionName = qnospecialchar($theQuestionArray['optionName']);
					$EnableQCoreClass->replace('optionName', $optionName);
					$EnableQCoreClass->replace('questionID', $questionID);
					$EnableQCoreClass->replace('optionID', $question_yesnoID);
					$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
					$EnableQCoreClass->replace('rangType', 18);
					$EnableQCoreClass->parse('option', 'OPTION', true);
				}
			}
			else {
				$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'YesNoItemCode.html');
				$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
				$EnableQCoreClass->replace('option', '');
				$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_18'] . ']');

				foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
					$this_fields_list .= 'option_18_' . $questionID . '_' . $question_yesnoID . '|';
					$this_fields_list .= 'unkown_18_' . $questionID . '_' . $question_yesnoID . '|';
					$optionName = qnospecialchar($theQuestionArray['optionName']);
					$EnableQCoreClass->replace('optionName', $optionName);
					$EnableQCoreClass->replace('questionID', $questionID);
					$EnableQCoreClass->replace('optionID', $question_yesnoID);
					$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
					$EnableQCoreClass->replace('isUnkown', $theQuestionArray['isUnkown'] == 1 ? 'checked' : '');
					$EnableQCoreClass->replace('rangType', 18);
					$EnableQCoreClass->parse('option', 'OPTION', true);
				}
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '19':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_19'] . ']');

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_19_' . $questionID . '_' . $question_range_answerID . '|';
				$this_fields_list .= 'unkown_19_' . $questionID . '_' . $question_range_answerID . '|';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('itemCode', $theAnswerArray['itemCode'] == 0 ? '' : $theAnswerArray['itemCode']);
				$EnableQCoreClass->replace('isUnkown', $theAnswerArray['isUnkown'] == 1 ? 'checked' : '');
				$EnableQCoreClass->replace('rangType', 19);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '24':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRadioItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_24'] . ']');

			foreach ($RadioListArray[$questionID] as $question_radioID => $theQuestionArray) {
				$this_fields_list .= 'option_24_' . $questionID . '_' . $question_radioID . '|';
				$this_fields_list .= 'unkown_24_' . $questionID . '_' . $question_radioID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_radioID);
				$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
				$EnableQCoreClass->replace('isUnkown', $theQuestionArray['isUnkown'] == 1 ? 'checked' : '');
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '25':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombCheckBoxItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_25'] . ']');

			foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
				$this_fields_list .= 'option_25_' . $questionID . '_' . $question_checkboxID . '|';
				$optionName = qnospecialchar($theQuestionArray['optionName']);
				$EnableQCoreClass->replace('optionName', $optionName);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_checkboxID);
				$EnableQCoreClass->replace('itemCode', $theQuestionArray['itemCode'] == 0 ? '' : $theQuestionArray['itemCode']);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '26':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_26'] . ']');

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_26_' . $questionID . '_' . $question_range_answerID . '|';
				$this_fields_list .= 'unkown_26_' . $questionID . '_' . $question_range_answerID . '|';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('itemCode', $theAnswerArray['itemCode'] == 0 ? '' : $theAnswerArray['itemCode']);
				$EnableQCoreClass->replace('isUnkown', $theAnswerArray['isUnkown'] == 1 ? 'checked' : '');
				$EnableQCoreClass->replace('rangType', 26);
				$EnableQCoreClass->parse('option', 'OPTION', true);
			}

			$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
			break;

		case '28':
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'MultipleItemCode.html');
			$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_28'] . ']');

			foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
				$this_fields_list .= 'option_28_' . $questionID . '_' . $question_range_answerID . '|';
				$optionAnswer = qnospecialchar($theAnswerArray['optionAnswer']);
				$EnableQCoreClass->replace('optionName', $optionAnswer);
				$EnableQCoreClass->replace('questionID', $questionID);
				$EnableQCoreClass->replace('optionID', $question_range_answerID);
				$EnableQCoreClass->replace('itemCode', $theAnswerArray['itemCode'] == 0 ? '' : $theAnswerArray['itemCode']);
				$EnableQCoreClass->replace('rangType', 28);
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
$EnableQCoreClass->parse('EditSurveyAlias', 'EditSurveyCode');
$EnableQCoreClass->output('EditSurveyAlias');

?>

<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);

if ($_GET['isPrint'] == '1') {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCombRangePrint.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCombRange.html');
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
	$questionNotes = '[' . $lang['question_type_26'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$Label = array();

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	$EnableQCoreClass->replace('optionLabelName', qshowquotechar($theLabelArray['optionLabel']));
	$EnableQCoreClass->replace('optionLabelID', $question_range_labelID);
	$Label[$question_range_labelID] = $theLabelArray['optionLabel'];
	$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
}

$theRadioListArray = array();
$optionTotalNum = count($OptionListArray[$questionID]);

if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
	$theRandListArray = php_array_rand($OptionListArray[$questionID], $optionTotalNum);

	foreach ($theRandListArray as $theRandRadioID) {
		$theRadioListArray[$theRandRadioID] = $OptionListArray[$questionID][$theRandRadioID];
	}
}
else {
	$theRadioListArray = $OptionListArray[$questionID];
}

$remove_value_list = '';

foreach ($theRadioListArray as $question_range_optionID => $theQuestionArray) {
	$EnableQCoreClass->replace('suboption' . $questionID, '');
	$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
	$EnableQCoreClass->replace('optionNameID', $question_range_optionID);
	$QtnAssCon = _getqtnasscond($questionID, $question_range_optionID);
	$remove_value_list_qtn = '';

	foreach ($Label as $question_range_labelID => $optionLabel) {
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;
		$EnableQCoreClass->replace('optionID', $theOptionID);
		$optionList = '';

		foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
			$optionAnswer = str_replace('&amp;quot;', '"', qnohtmltag($theAnswerArray['optionAnswer'], 1));

			if ($_GET['isPrint'] == '1') {
				$optionList .= '<input name=' . $theOptionID . ' id=' . $theOptionID . ' type="radio">' . $optionAnswer . '<br/>';
			}
			else if ($isModiDataFlag == 1) {
				if (($R_Row[$theOptionID] != '0') && ($R_Row[$theOptionID] == $question_range_answerID)) {
					$optionList .= '<option value=\'' . $question_range_answerID . '\' selected>' . $optionAnswer . '</option>' . "\n" . '';
				}
				else {
					$optionList .= '<option value=\'' . $question_range_answerID . '\'>' . $optionAnswer . '</option>' . "\n" . '';
				}
			}
			else {
				if (($_SESSION[$theOptionID] != '') && ($_SESSION[$theOptionID] == $question_range_answerID)) {
					$optionList .= '<option value=\'' . $question_range_answerID . '\' selected>' . $optionAnswer . '</option>' . "\n" . '';
				}
				else {
					$optionList .= '<option value=\'' . $question_range_answerID . '\'>' . $optionAnswer . '</option>' . "\n" . '';
				}
			}
		}

		$EnableQCoreClass->replace('optionList', $optionList);
		$this_fields_list .= $theOptionID . '|';

		if ($QtnAssCon != '') {
			if ($QtnListArray[$questionID]['isRequired'] == '1') {
				$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
				$check_survey_form_no_con_list .= '		ListUnSelect(document.Survey_Form.' . $theOptionID . ');' . "\n" . '';
				$check_survey_form_no_con_list .= '	} ' . "\n" . '';
				$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
				$check_survey_form_no_con_list .= '		if (!CheckListNoSelect(document.Survey_Form.' . $theOptionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . ' - ' . qnoscriptstring($optionLabel) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
			}
		}
		else if ($QtnListArray[$questionID]['isRequired'] == '1') {
			$check_survey_form_no_con_list .= '	if (!CheckListNoSelect(document.Survey_Form.' . $theOptionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . ' - ' . qnoscriptstring($optionLabel) . '\')){return false;} ' . "\n" . '';
		}

		$remove_value_list .= '	ListUnSelect(document.Survey_Form.' . $theOptionID . ');' . "\n" . '';

		if ($QtnAssCon != '') {
			$remove_value_list_qtn .= '		ListUnSelect(document.Survey_Form.' . $theOptionID . ');' . "\n" . '';
		}

		$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('suboption' . $questionID);

	if ($QtnAssCon != '') {
		$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_combrange_' . $question_range_optionID . '_container").hide();' . "\n" . '';
		$check_survey_conditions_list .= $remove_value_list_qtn;
		$check_survey_conditions_list .= '	} ' . "\n" . '';
		$check_survey_conditions_list .= '	else { ' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_combrange_' . $question_range_optionID . '_container").show();' . "\n" . '	} ' . "\n" . '';
	}
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

unset($Label);
$check_survey_conditions_list .= '	changeMaskingQtnBgColor(' . $questionID . ');' . "\n" . '';
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
			$authInfoList .= '<span class=red>[' . qnospecialchar($OptionListArray[$questionID][$theVarName[2]]['optionName']) . ' - ' . qnospecialchar($LabelListArray[$questionID][$theVarName[3]]['optionLabel']) . '</span>自<span class=red>[';

			if ($aRow['oriValue'] == '0') {
				$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
			}
			else {
				$authInfoList .= qnospecialchar($AnswerListArray[$questionID][$aRow['oriValue']]['optionAnswer']) . ']</span>至<span class=red>[';
			}

			if ($aRow['updateValue'] == '0') {
				$authInfoList .= $lang['skip_answer'] . ']</span>';
			}
			else {
				$authInfoList .= qnospecialchar($AnswerListArray[$questionID][$aRow['updateValue']]['optionAnswer']) . ']</span>';
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

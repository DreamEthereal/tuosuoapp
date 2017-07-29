<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uMultipleText.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->set_CycBlock('OPTION', 'SUBOPTION', 'suboption' . $questionID);
$EnableQCoreClass->replace('suboption' . $questionID, '');
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ANSWER', 'answer' . $questionID);
$EnableQCoreClass->replace('answer' . $questionID, '');
$check_survey_form_no_con_list = '';
$questionName = '';
$questionNotes = '';
$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_27'] . ']';
}

$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$theFirstArray = array_slice($OptionListArray[$questionID], 0, 1);
$theMinOptionID = $theFirstArray[0]['question_range_optionID'];
$theLastArray = array_slice($OptionListArray[$questionID], -1, 1);
$theMaxOptionID = $theLastArray[0]['question_range_optionID'];

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	if ($theLabelArray['isRequired'] == '1') {
		$EnableQCoreClass->replace('questionRequire', '<span class=red>*</span>');
	}
	else {
		$EnableQCoreClass->replace('questionRequire', '');
	}

	$EnableQCoreClass->replace('optionLabelName', qshowquotechar($theLabelArray['optionLabel']));
	$EnableQCoreClass->replace('optionLabelID', $question_range_labelID);
	if (($theLabelArray['isRequired'] == 1) && ($QtnListArray[$questionID]['requiredMode'] == 1)) {
		$theOptionJsName = $theInfoQtnName . ' - ' . qnoscriptstring($theLabelArray['optionLabel']);
		$check_survey_form_no_con_list .= '	if (!CheckMultipleText(' . $questionID . ',' . $question_range_labelID . ',' . $theMinOptionID . ',' . $theMaxOptionID . ',\'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
	}

	$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
}

$remove_value_list = '';
$datePicker = 0;

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$EnableQCoreClass->replace('suboption' . $questionID, '');
	$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
	$EnableQCoreClass->replace('optionNameID', $question_range_optionID);
	$QtnAssCon = _getqtnasscond($questionID, $question_range_optionID);
	$remove_value_list_qtn = '';

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;
		$EnableQCoreClass->replace('optionID', $theOptionID);

		if ($isMobile) {
			$EnableQCoreClass->replace('length', '10');

			switch ($theLabelArray['isCheckType']) {
			case 4:
			case 8:
			case 9:
				$EnableQCoreClass->replace('inputPrompt', 'number');
				break;

			case 5:
			case 11:
				$EnableQCoreClass->replace('inputPrompt', 'tel');
				break;

			default:
				$EnableQCoreClass->replace('inputPrompt', 'text');
				break;
			}

			$EnableQCoreClass->replace('dateTextAction', '');
		}
		else {
			$EnableQCoreClass->replace('length', $theLabelArray['optionSize']);
			$EnableQCoreClass->replace('inputPrompt', 'text');

			switch ($theLabelArray['isCheckType']) {
			case '6':
				if ($theHaveDatePicker == false) {
					$datePicker = 1;
					$theHaveDatePicker = true;
				}

				$EnableQCoreClass->replace('dateTextAction', 'onfocus="if(this.value==\'0000-00-00\') this.value=\'\'" onclick="javascript:SelectDate(this,\'yyyy-MM-dd\')"');
				break;

			default:
				$EnableQCoreClass->replace('dateTextAction', '');
				break;
			}
		}

		if ($isModiDataFlag == 1) {
			if ($R_Row[$theOptionID] != '') {
				$EnableQCoreClass->replace('value', $R_Row[$theOptionID]);
			}
			else {
				$EnableQCoreClass->replace('value', '');
			}
		}
		else if ($_SESSION[$theOptionID] != '') {
			$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION[$theOptionID]));
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}

		$this_fields_list .= $theOptionID . '|';
		$theOptionJsName = $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . ' - ' . qnoscriptstring($theLabelArray['optionLabel']);

		if ($QtnAssCon != '') {
			if (($theLabelArray['isRequired'] == 1) && ($QtnListArray[$questionID]['requiredMode'] == 2)) {
				$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
				$check_survey_form_no_con_list .= '		TextUnInput(document.Survey_Form.' . $theOptionID . ');' . "\n" . '';
				$check_survey_form_no_con_list .= '	} ' . "\n" . '';
				$check_survey_form_no_con_list .= '	else{ ' . "\n" . '';
				$check_survey_form_no_con_list .= '		if(!CheckNotNull(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
			}
		}
		else {
			if (($theLabelArray['isRequired'] == 1) && ($QtnListArray[$questionID]['requiredMode'] == 2)) {
				$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			}
		}

		switch ($theLabelArray['isCheckType']) {
		case '1':
			$check_survey_form_no_con_list .= '	if (!CheckEmail(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '2':
			$check_survey_form_no_con_list .= '	if (!CheckStringLength(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\',' . $theLabelArray['minOption'] . ',' . $theLabelArray['maxOption'] . ')){return false;}' . "\n" . '';
			break;

		case '3':
			$check_survey_form_no_con_list .= '	if (!CheckNoChinese(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '4':
			$check_survey_form_no_con_list .= '	if (!CheckIsValue(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\',' . $theLabelArray['minOption'] . ',' . $theLabelArray['maxOption'] . ')){return false;}' . "\n" . '';
			break;

		case '5':
			$check_survey_form_no_con_list .= '	if (!CheckPhone(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '6':
			$check_survey_form_no_con_list .= '	if (!CheckDate(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '7':
			$check_survey_form_no_con_list .= '	if (!CheckIDCardNo(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '8':
			$check_survey_form_no_con_list .= '	if (!CheckCorpCode(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '9':
			$check_survey_form_no_con_list .= '	if (!CheckPostalCode(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '10':
			$check_survey_form_no_con_list .= '	if (!CheckURL(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '11':
			$check_survey_form_no_con_list .= '	if (!CheckMobile(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;

		case '12':
			$check_survey_form_no_con_list .= '	if (!CheckChinese(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			break;
		}

		$remove_value_list .= '	TextUnInput(document.Survey_Form.' . $theOptionID . ');' . "\n" . '';

		if ($QtnAssCon != '') {
			$remove_value_list_qtn .= '		TextUnInput(document.Survey_Form.' . $theOptionID . ');' . "\n" . '';
		}

		$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('suboption' . $questionID);

	if ($QtnAssCon != '') {
		$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_multitext_' . $question_range_optionID . '_container").hide();' . "\n" . '';
		$check_survey_conditions_list .= $remove_value_list_qtn;
		$check_survey_conditions_list .= '	} ' . "\n" . '';
		$check_survey_conditions_list .= '	else { ' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_multitext_' . $question_range_optionID . '_container").show();' . "\n" . '	} ' . "\n" . '';
	}
}

if ($datePicker == 1) {
	if (strtolower($language) == 'cn') {
		$EnableQCoreClass->replace('dateIncludeFile', '<script type="text/javascript" src="JS/Calendar.js.php"></script>' . "\n" . '');
	}
	else {
		$EnableQCoreClass->replace('dateIncludeFile', '<script type="text/javascript" src="JS/Calendar.en.php"></script>' . "\n" . '');
	}
}
else {
	$EnableQCoreClass->replace('dateIncludeFile', '');
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

$check_survey_conditions_list .= '	changeMaskingQtnBgColor(' . $questionID . ');' . "\n" . '';
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

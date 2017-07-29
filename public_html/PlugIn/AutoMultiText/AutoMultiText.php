<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAutoMultiText.html');
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
	$questionNotes = '[' . $lang['question_type_29'] . ']';
}

$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
if (empty($theBaseQtnArray) && ($QtnListArray[$questionID]['isRequired'] == '1')) {
	$check_survey_form_no_con_list .= '	$.notification(\'' . $theInfoQtnName . $lang['base_qtn_no_exist'] . '\');return false;' . "\n" . '';
}

$optionAutoArray = array();
$optionMinMaxArray = array();
$i = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	if ($theBaseQtnArray['questionType'] == 3) {
		$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
	}

	if ($theBaseQtnArray['questionType'] == 25) {
		if (!issamepage($questionID, $theBaseID) && ($theQuestionArray['isHaveText'] == 1)) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
			}
			else if ($isModiDataFlag == 1) {
				$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . $R_Row['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID] . ')';
			}
			else {
				$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID]) . ')';
			}
		}
		else {
			$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
		}
	}

	$optionMinMaxArray[$i] = $question_checkboxID;
	$i++;
}

$theFirstID = ($optionMinMaxArray[0] != '' ? $optionMinMaxArray[0] : 0);
$theLastID = ($optionMinMaxArray[count($optionMinMaxArray) - 1] != '' ? $optionMinMaxArray[count($optionMinMaxArray) - 1] : 0);

if ($theBaseQtnArray['isHaveOther'] == 1) {
	if (!issamepage($questionID, $theBaseID)) {
		if ($QtnListArray[$questionID]['isNeg'] == 1) {
			$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
		}
		else if ($isModiDataFlag == 1) {
			$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . $R_Row['TextOtherValue_' . $theBaseID] . ')';
		}
		else {
			$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID]) . ')';
		}
	}
	else {
		$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
	}
}

$isHaveOther = ($theBaseQtnArray['isHaveOther'] != '' ? $theBaseQtnArray['isHaveOther'] : 0);
$theBaseValueList = $theOriValue = '';
if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $theBaseID] != '') {
			if (is_array($R_Row['option_' . $theBaseID])) {
				$theOriValue = implode(',', $R_Row['option_' . $theBaseID]);
			}
			else {
				$theOriValue = $R_Row['option_' . $theBaseID];
			}
		}
	}
	else if ($_SESSION['option_' . $theBaseID] != '') {
		if (is_array($_SESSION['option_' . $theBaseID])) {
			$theOriValue = implode(',', $_SESSION['option_' . $theBaseID]);
		}
		else {
			$theOriValue = $_SESSION['option_' . $theBaseID];
		}
	}
}

$theBaseValueList = $theOriValue;
$theMinOptionID = $theFirstID;
$theMaxOptionID = $theLastID;
$Label = array();
$j = 0;

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	if ($theLabelArray['isRequired'] == 1) {
		$EnableQCoreClass->replace('questionRequire', '<span class=red>*</span>');
	}
	else {
		$EnableQCoreClass->replace('questionRequire', '');
	}

	$EnableQCoreClass->replace('optionLabelName', qshowquotechar($theLabelArray['optionLabel']));
	$EnableQCoreClass->replace('optionLabelID', $question_range_labelID);
	$Label[$j] = $question_range_labelID;
	$j++;
	if (($theLabelArray['isRequired'] == 1) && ($QtnListArray[$questionID]['requiredMode'] == 1)) {
		$theOptionJsName = $theInfoQtnName . ' - ' . qnoscriptstring($theLabelArray['optionLabel']);
		if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$check_survey_form_no_con_list .= '	if (!CheckAutoMultipleTextNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOriValue . '\',' . $isHaveOther . ',' . $questionID . ',' . $question_range_labelID . ',' . $theMinOptionID . ',' . $theMaxOptionID . ',\'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			}
			else {
				$check_survey_form_no_con_list .= '	if (!CheckAutoMultipleTextNoSamePage(' . $theBaseID . ',\'' . $theOriValue . '\',' . $isHaveOther . ',' . $questionID . ',' . $question_range_labelID . ',' . $theMinOptionID . ',' . $theMaxOptionID . ',\'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			}
		}
		else {
			$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$check_survey_form_no_con_list .= '	if (!CheckAutoMultipleTextIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $isHaveOther . ',' . $questionID . ',' . $question_range_labelID . ',' . $theMinOptionID . ',' . $theMaxOptionID . ',\'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			}
			else {
				$check_survey_form_no_con_list .= '	if (!CheckAutoMultipleText(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $isHaveOther . ',' . $questionID . ',' . $question_range_labelID . ',' . $theMinOptionID . ',' . $theMaxOptionID . ',\'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			}
		}
	}

	$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
}

$startScale = ($Label[0] != '' ? $Label[0] : 0);
$endScale = ($Label[count($Label) - 1] != '' ? $Label[count($Label) - 1] : 0);
$remove_value_list = '';
$EnableQCoreClass->replace('baseID', $theBaseID);
$theOptionAutoIDList = '';
$datePicker = 0;

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$theOptionAutoIDList .= $optionAutoID . ',';
	$EnableQCoreClass->replace('optionName', $optionAutoName);
	$EnableQCoreClass->replace('optionNameID', $optionAutoID);

	foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
		$theOptionID = 'option_' . $questionID . '_' . $optionAutoID . '_' . $question_range_labelID;
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

		$EnableQCoreClass->replace('optionValue', $question_range_labelID);

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
		$theOptionJsName = $theInfoQtnName . ' - ' . qnoscriptstring($optionAutoName) . ' - ' . qnoscriptstring($theLabelArray['optionLabel']);
		if (($theLabelArray['isRequired'] == 1) && ($QtnListArray[$questionID]['requiredMode'] == 2)) {
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'multitext_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . $theOptionID . ', \'' . $theOptionJsName . '\')){return false;}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
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
		$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('suboption' . $questionID);
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
$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);
$theIsSamePage = (issamepage($questionID, $theBaseID) ? 1 : 0);
$getCheckBoxNoneofAbove = '_getCheckBoxNoneofAbove(' . $theBaseQtnArray['questionType'] . ',' . $theBaseID . ',' . $theBaseIsSelect . ',' . $theIsSamePage . ',' . $QtnListArray[$questionID]['isNeg'] . ',\'' . $theBaseValueList . '\')';

if ($QuestionCon != '') {
	$check_survey_conditions_list .= '	if(' . $QuestionCon . ' && ' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").show();' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").hide();' . "\n" . '	} ' . "\n" . '';
	$check_form_list = '	if(' . $QuestionCon . ' && ' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
	$check_form_list .= $check_survey_form_no_con_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_form_list .= '	else{' . "\n" . '';
	$check_form_list .= $remove_value_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_conditions_list .= '	if(' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").show();' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").hide();' . "\n" . '	} ' . "\n" . '';

	if ($check_survey_form_no_con_list != '') {
		$check_form_list = '	if(' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
		$check_form_list .= $check_survey_form_no_con_list;
		$check_form_list .= '	}' . "\n" . '';
		$check_survey_form_list .= $check_form_list;
	}
}

if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
	$theOptionAutoIDList = substr($theOptionAutoIDList, 0, -1);
	$theOriValue = ',' . $theOriValue . ',';

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoMultiTextNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $startScale . ',' . $endScale . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoMultiTextNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $startScale . ',' . $endScale . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
}
else {
	$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoMultiTextSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $startScale . ',' . $endScale . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoMultiTextSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $startScale . ',' . $endScale . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
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
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['traceTime']) . $modiLang . '<span class=red>[';
			$theVarName = explode('_', $aRow['varName']);

			if ($theVarName[2] == 0) {
				$authInfoList .= qnospecialchar($theBaseQtnArray['otherText']);
			}
			else {
				$authInfoList .= qnospecialchar($theCheckBoxListArray[$theVarName[2]]['optionName']);
			}

			$authInfoList .= ' - ' . qnospecialchar($LabelListArray[$questionID][$theVarName[3]]['optionLabel']);
			$authInfoList .= ']</span>自<span class=red>[';

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

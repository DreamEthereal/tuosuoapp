<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$this_fields_list .= 'option_' . $questionID . '|';

if ($isMobile) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'mCombCheckBox.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCombCheckBox.html');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionRequire = '';
$questionName = '';
$questionNotes = '';
$minOption = $maxOption = '';
$check_survey_form_no_con_list = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';

	if ($QtnListArray[$questionID]['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['minOption'] . $lang['option'] . ']';
	}

	if ($QtnListArray[$questionID]['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $QtnListArray[$questionID]['maxOption'] . $lang['option'] . ']';
	}

	$check_survey_form_no_con_list .= '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';

	if ($QtnListArray[$questionID]['minOption'] != 0) {
		$check_survey_form_no_con_list .= '	if (!CheckCheckMinClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ')){return false;} ' . "\n" . '';
	}

	if ($QtnListArray[$questionID]['maxOption'] != 0) {
		$check_survey_form_no_con_list .= '	if (!CheckCheckMaxClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes .= '[' . $lang['question_type_25'] . ']';
}

$questionNotes .= $minOption;
$questionNotes .= $maxOption;
$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$maxOptionNum = count($CheckBoxListArray[$questionID]);
$theCheckBoxListArray = array();
$theOptionOrderID = 0;
$remove_value_list = '';

if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
	$theRetainArray = array();
	$theRandArray = array();

	foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
		if ($theQuestionArray['isRetain'] == 1) {
			$theRetainArray[] = $question_checkboxID;
		}
		else {
			$theRandArray[] = $question_checkboxID;
		}
	}

	if (count($theRandArray) != 0) {
		$theRandListArray = php_array_rand($theRandArray, count($theRandArray));

		foreach ($theRandListArray as $randNum) {
			$theRandCheckBoxID = $theRandArray[$randNum];
			$theCheckBoxListArray[$theRandCheckBoxID] = $CheckBoxListArray[$questionID][$theRandCheckBoxID];
		}
	}

	foreach ($theRetainArray as $question_checkboxID) {
		$theCheckBoxListArray[$question_checkboxID] = $CheckBoxListArray[$questionID][$question_checkboxID];
	}
}
else {
	$theCheckBoxListArray = $CheckBoxListArray[$questionID];
}

$theGroupOption = array();
$tmp = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$theGroupOption[$theQuestionArray['groupNum']][] = $tmp;
	$tmp++;
}

$theOptionOdNum = 0;
$datePicker = 0;
$check_survey_conditions_list_group = '';

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$EnableQCoreClass->replace('theOptionOdNum', $theOptionOdNum);
	$theOptionOdNum++;
	$EnableQCoreClass->replace('optionID', $question_checkboxID);
	$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
	$theTextID = 'TextOtherValue_' . $questionID . '_' . $question_checkboxID;

	if ($theQuestionArray['isExclusive'] == 1) {
		if ($theQuestionArray['groupNum'] == 0) {
			$check_survey_conditions_list .= '	isExcludeItem(document.Survey_Form.option_' . $questionID . ',' . $theOptionOrderID . ',' . $maxOptionNum . ');' . "\n" . '';
		}
		else {
			$check_survey_conditions_list_group .= '	isExcludeGroupItem(document.Survey_Form.option_' . $questionID . ',' . $theOptionOrderID . ',\'' . implode(',', $theGroupOption[$theQuestionArray['groupNum']]) . '\');' . "\n" . '';
		}
	}

	if ($theQuestionArray['isHaveText'] == 1) {
		$this_fields_list .= $theTextID . '|';
		$EnableQCoreClass->replace('isHaveText', '');

		switch ($theQuestionArray['isCheckType']) {
		case '4':
			$EnableQCoreClass->replace('unitText', trim($theQuestionArray['unitText']));
			break;

		default:
			$EnableQCoreClass->replace('unitText', '');
			break;
		}

		if ($isMobile) {
			switch ($theQuestionArray['isCheckType']) {
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
		}
		else {
			switch ($theQuestionArray['isCheckType']) {
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
			if ($R_Row[$theTextID] != '') {
				$EnableQCoreClass->replace('value', $R_Row[$theTextID]);
			}
			else {
				$EnableQCoreClass->replace('value', '');
			}
		}
		else if ($_SESSION[$theTextID] != '') {
			$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION[$theTextID]));
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}

		$remove_value_list .= '	TextUnInput(document.Survey_Form.' . $theTextID . ');' . "\n" . '';
	}
	else {
		$EnableQCoreClass->replace('isHaveText', 'none');
		$EnableQCoreClass->replace('unitText', '');
		$EnableQCoreClass->replace('dateTextAction', '');
	}

	if ($isMobile) {
		$EnableQCoreClass->replace('length', '20');
	}
	else {
		$EnableQCoreClass->replace('length', $theQuestionArray['optionSize']);
	}

	if (ischeckboxselect($question_checkboxID, $questionID)) {
		$EnableQCoreClass->replace('isCheck', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isCheck', '');
	}

	$check_survey_form_no_con_list .= '	if ( (document.Survey_Form.' . 'option_' . $questionID . '.length != null && document.Survey_Form.' . 'option_' . $questionID . '[' . $theOptionOrderID . '].checked) || (document.Survey_Form.' . 'option_' . $questionID . '.length == null && document.Survey_Form.' . 'option_' . $questionID . '.checked) )' . "\n" . '	{' . "\n" . ' ';

	if ($theQuestionArray['isRequired'] == 1) {
		$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '';
	}

	switch ($theQuestionArray['isCheckType']) {
	case '1':
		$check_survey_form_no_con_list .= '		if (!CheckEmail(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '2':
		$check_survey_form_no_con_list .= '		if (!CheckStringLength(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\',' . $theQuestionArray['minOption'] . ',' . $theQuestionArray['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '3':
		$check_survey_form_no_con_list .= '		if (!CheckNoChinese(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '4':
		$check_survey_form_no_con_list .= '		if (!CheckIsValue(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\',' . $theQuestionArray['minOption'] . ',' . $theQuestionArray['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '5':
		$check_survey_form_no_con_list .= '		if (!CheckPhone(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '6':
		$check_survey_form_no_con_list .= '		if (!CheckDate(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '7':
		$check_survey_form_no_con_list .= '		if (!CheckIDCardNo(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '8':
		$check_survey_form_no_con_list .= '		if (!CheckCorpCode(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '9':
		$check_survey_form_no_con_list .= '		if (!CheckPostalCode(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '10':
		$check_survey_form_no_con_list .= '		if (!CheckURL(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '11':
		$check_survey_form_no_con_list .= '		if (!CheckMobile(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;

	case '12':
		$check_survey_form_no_con_list .= '		if (!CheckChinese(document.Survey_Form.' . $theTextID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
		break;
	}

	$check_survey_form_no_con_list .= '	}' . "\n" . '';
	$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.' . $theTextID . ');' . "\n" . '	}' . "\n" . '';
	$theOptionOrderID++;
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$OptAssCon = _getoptasscond($questionID, $question_checkboxID);

	if ($OptAssCon != '') {
		$check_survey_conditions_list .= '	if(' . $OptAssCon . ')' . "\n" . '	{' . "\n" . '';
		$check_survey_conditions_list .= '		$("#option_checkbox_' . $question_checkboxID . '_container").hide();' . "\n" . '';
		$check_survey_conditions_list .= '		$("input[id=\'option_' . $questionID . '\'][value=\'' . $question_checkboxID . '\']").attr("checked",false);' . "\n" . '';
		$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.' . $theTextID . ');';
		$check_survey_conditions_list .= '' . "\n" . '	} ' . "\n" . '';
		$check_survey_conditions_list .= '	else { ' . "\n" . '';
		$check_survey_conditions_list .= '		$("#option_checkbox_' . $question_checkboxID . '_container").show();' . "\n" . '	} ' . "\n" . '';
	}
}

$check_survey_conditions_list .= $check_survey_conditions_list_group;

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

if ($isMobile) {
	$check_survey_conditions_list .= '	changeMaskingSingleBgColor(' . $questionID . ');' . "\n" . '';
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
	$check_form_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
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
				$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

				if ($aRow['oriValue'] == '') {
					$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
				}
				else {
					$theOriValue = explode(',', $aRow['oriValue']);
					$theOriValueList = '';

					foreach ($theOriValue as $thisOriValue) {
						$theOriValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisOriValue]['optionName']) . ',';
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
						$thisUpdateValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisUpdateValue]['optionName']) . ',';
					}

					$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
				}
			}
			else {
				$authInfoList .= '<span class=red>[' . qnospecialchar($CheckBoxListArray[$questionID][$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';

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

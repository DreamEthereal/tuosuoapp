<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);

if ($isMobile) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'mText.html');

	switch ($QtnListArray[$questionID]['isCheckType']) {
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
	if (($QtnListArray[$questionID]['isCheckType'] != '6') || ($_GET['isPrint'] == '1')) {
		$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uText.html');
	}
	else {
		if ($theHaveDatePicker == false) {
			if (strtolower($language) == 'cn') {
				$EnableQCoreClass->replace('dateIncludeFile', '<script type="text/javascript" src="JS/Calendar.js.php"></script>' . "\n" . '');
			}
			else {
				$EnableQCoreClass->replace('dateIncludeFile', '<script type="text/javascript" src="JS/Calendar.en.php"></script>' . "\n" . '');
			}

			$theHaveDatePicker = true;
		}
		else {
			$EnableQCoreClass->replace('dateIncludeFile', '');
		}

		$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uTextDate.html');
	}
}

$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	switch ($QtnListArray[$questionID]['isCheckType']) {
	case '4':
		$questionNotes = '[' . $lang['question_type_4_4'] . ']';
		break;

	case '6':
		$questionNotes = '[' . $lang['question_type_4_6'] . ']';
		break;

	default:
		$questionNotes = '[' . $lang['question_type_4'] . ']';
		break;
	}
}

switch ($QtnListArray[$questionID]['isCheckType']) {
case '4':
	$EnableQCoreClass->replace('unitText', trim($QtnListArray[$questionID]['unitText']));
	break;

case '6':
	$EnableQCoreClass->replace('unitText', '');
	break;

default:
	$EnableQCoreClass->replace('unitText', '');
	break;
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$EnableQCoreClass->replace('optionID', 'option_' . $questionID);

if ($isMobile) {
	$EnableQCoreClass->replace('length', '20');
}
else {
	$EnableQCoreClass->replace('length', $QtnListArray[$questionID]['length']);
}

if ($QtnListArray[$questionID]['isHaveUnkown'] == 2) {
	$EnableQCoreClass->replace('isHaveUnkown', '');
	$this_fields_list .= 'option_' . $questionID . '|';
	$this_fields_list .= 'isHaveUnkown_' . $questionID . '|';

	if ($isModiDataFlag == 1) {
		if ($R_Row['isHaveUnkown_' . $questionID] == 1) {
			$EnableQCoreClass->replace('isHaveUnkown0', '');
			$EnableQCoreClass->replace('isHaveUnkown1', 'checked');
			$EnableQCoreClass->replace('value', '');
		}
		else {
			$EnableQCoreClass->replace('isHaveUnkown0', 'checked');
			$EnableQCoreClass->replace('isHaveUnkown1', '');

			if ($R_Row['option_' . $questionID] != '') {
				$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID]);
			}
			else {
				$EnableQCoreClass->replace('value', '');
			}
		}
	}
	else if ($_SESSION['isHaveUnkown_' . $questionID] == 1) {
		$EnableQCoreClass->replace('isHaveUnkown0', '');
		$EnableQCoreClass->replace('isHaveUnkown1', 'checked');
		$EnableQCoreClass->replace('value', '');
	}
	else {
		$EnableQCoreClass->replace('isHaveUnkown0', 'checked');
		$EnableQCoreClass->replace('isHaveUnkown1', '');

		if ($_SESSION['option_' . $questionID] != '') {
			$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION['option_' . $questionID]));
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}

	if (($QtnListArray[$questionID]['isNeg'] == 1) || ($QtnListArray[$questionID]['isSelect'] == 1)) {
		$this_check_list .= '4*option_' . $questionID . '|';
	}

	$check_survey_conditions_list .= '	if (document.Survey_Form.' . 'isHaveUnkown_' . $questionID . '[1].checked )' . "\n" . '	{' . "\n" . ' ';
	$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.' . 'option_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
	$check_survey_form_no_con_list = '';
	$check_survey_form_no_con_list .= '	if (!CheckRadioNoClick(document.Survey_Form.' . 'isHaveUnkown_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
	$check_survey_form_no_con_list .= '	if (document.Survey_Form.' . 'isHaveUnkown_' . $questionID . '[0].checked )' . "\n" . '	{' . "\n" . ' ';

	if ($QtnListArray[$questionID]['isRequired'] == '1') {
		$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
	}

	switch ($QtnListArray[$questionID]['isCheckType']) {
	case '1':
		$check_survey_form_no_con_list .= '		if (!CheckEmail(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '2':
		$check_survey_form_no_con_list .= '		if (!CheckStringLength(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '3':
		$check_survey_form_no_con_list .= '		if (!CheckNoChinese(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '4':
		$check_survey_form_no_con_list .= '		if (!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '5':
		$check_survey_form_no_con_list .= '		if (!CheckPhone(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '6':
		$check_survey_form_no_con_list .= '		if (!CheckDate(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '7':
		$check_survey_form_no_con_list .= '		if (!CheckIDCardNo(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '8':
		$check_survey_form_no_con_list .= '		if (!CheckCorpCode(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '9':
		$check_survey_form_no_con_list .= '		if (!CheckPostalCode(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '10':
		$check_survey_form_no_con_list .= '		if (!CheckURL(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '11':
		$check_survey_form_no_con_list .= '		if (!CheckMobile(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '12':
		$check_survey_form_no_con_list .= '		if (!CheckChinese(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '13':
		$check_survey_form_no_con_list .= '		if (!CheckTime(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;
	}

	$check_survey_form_no_con_list .= '	}' . "\n" . '';
	$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.' . 'option_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
	$dataInputAction = ' onblur="Check_Survey_Conditions();"';
	$EnableQCoreClass->replace('dataInputAction', $dataInputAction);
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
		$check_form_list .= '	RadioUnClick(document.Survey_Form.isHaveUnkown_' . $questionID . ');' . "\n" . '';
		$check_form_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
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
}
else {
	$EnableQCoreClass->replace('isHaveUnkown', 'none');
	$EnableQCoreClass->replace('isHaveUnkown0', '');
	$EnableQCoreClass->replace('isHaveUnkown1', '');
	$this_fields_list .= 'option_' . $questionID . '|';

	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $questionID] != '') {
			$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}
	else if ($_SESSION['option_' . $questionID] != '') {
		$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION['option_' . $questionID]));
	}
	else {
		$EnableQCoreClass->replace('value', '');
	}

	if (($QtnListArray[$questionID]['isNeg'] == 1) || ($QtnListArray[$questionID]['isSelect'] == 1)) {
		$this_check_list .= '4*option_' . $questionID . '|';
	}

	$check_survey_form_no_con_list = '';

	if ($QtnListArray[$questionID]['isRequired'] == '1') {
		$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
	}

	switch ($QtnListArray[$questionID]['isCheckType']) {
	case '1':
		$check_survey_form_no_con_list .= '	if (!CheckEmail(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '2':
		$check_survey_form_no_con_list .= '	if (!CheckStringLength(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '3':
		$check_survey_form_no_con_list .= '	if (!CheckNoChinese(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '4':
		$check_survey_form_no_con_list .= '	if (!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '5':
		$check_survey_form_no_con_list .= '	if (!CheckPhone(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '6':
		$check_survey_form_no_con_list .= '	if (!CheckDate(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '7':
		$check_survey_form_no_con_list .= '	if (!CheckIDCardNo(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '8':
		$check_survey_form_no_con_list .= '	if (!CheckCorpCode(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '9':
		$check_survey_form_no_con_list .= '	if (!CheckPostalCode(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '10':
		$check_survey_form_no_con_list .= '	if (!CheckURL(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '11':
		$check_survey_form_no_con_list .= '	if (!CheckMobile(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '12':
		$check_survey_form_no_con_list .= '	if (!CheckChinese(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;

	case '13':
		$check_survey_form_no_con_list .= '		if (!CheckTime(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
		break;
	}

	$dataInputAction = ' onblur="Check_Survey_Conditions();"';
	$EnableQCoreClass->replace('dataInputAction', $dataInputAction);
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
		$check_form_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
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
			$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

			if ($QtnListArray[$questionID]['isHaveUnkown'] == 2) {
				$theVarName = explode('_', $aRow['varName']);

				if ($theVarName[0] == 'option') {
					if ($aRow['oriValue'] == '') {
						$theNextTraceID = $aRow['traceID'] + 1;
						$hSQL = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $theNextTraceID . '\' ';
						$hRow = $DB->queryFirstRow($hSQL);

						if ($hRow['varName'] == 'isHaveUnkown_' . $questionID) {
							$authInfoList .= '不清楚]</span>至<span class=red>[';
						}
						else {
							$authInfoList .= '空]</span>至<span class=red>[';
						}
					}
					else {
						$authInfoList .= qnospecialchar($aRow['oriValue']) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '') {
						$theNextTraceID = $aRow['traceID'] + 1;
						$hSQL = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $theNextTraceID . '\' ';
						$hRow = $DB->queryFirstRow($hSQL);

						if ($hRow['varName'] == 'isHaveUnkown_' . $questionID) {
							$authInfoList .= '不清楚]</span>';
						}
						else {
							$authInfoList .= '空]</span>';
						}
					}
					else {
						$authInfoList .= qnospecialchar($aRow['updateValue']) . ']</span>';
					}
				}
				else {
					$theLastTraceID = $aRow['traceID'] - 1;
					$hSQL = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $theLastTraceID . '\' ';
					$hRow = $DB->queryFirstRow($hSQL);

					if ($hRow['varName'] == 'option_' . $questionID) {
						continue;
					}
					else {
						$authInfoList .= '空]</span>至<span class=red>[';
						$authInfoList .= '不清楚]</span>';
					}
				}
			}
			else {
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

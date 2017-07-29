<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAutoWeight.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'WEIGHT', 'weight' . $questionID);
$EnableQCoreClass->replace('weight' . $questionID, '');
$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_22'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$check_survey_form_no_con_list = '';
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
$EnableQCoreClass->replace('baseID', $theBaseID);
$theOptionAutoIDList = '';
$remove_value_list = '';
$isHaveSessionValue = false;
$totalValue = 0;

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$theOptionAutoIDList .= $optionAutoID . ',';
	$EnableQCoreClass->replace('optionName', $optionAutoName);
	$EnableQCoreClass->replace('optionNameID', $optionAutoID);
	$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $optionAutoID);
	$EnableQCoreClass->replace('maxSize', $QtnListArray[$questionID]['maxSize']);
	$EnableQCoreClass->replace('minOptionNum', $theFirstID);
	$EnableQCoreClass->replace('maxOptionNum', $theLastID);
	$EnableQCoreClass->replace('isHaveOther', $isHaveOther);
	$remove_value_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '';
	$theInfoAutoName = qnoscriptstring($optionAutoName);
	$check_survey_form_no_con_list .= '		if(!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\',0,' . $QtnListArray[$questionID]['maxSize'] . ')){return false;} ' . "\n" . '';

	if ($isModiDataFlag == 1) {
		if (($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0') && ($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0.00')) {
			$isHaveSessionValue = true;
			$totalValue += $R_Row['option_' . $questionID . '_' . $optionAutoID];
			$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID . '_' . $optionAutoID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}
	else {
		if (($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '0') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '0.00')) {
			$isHaveSessionValue = true;
			$totalValue += $_SESSION['option_' . $questionID . '_' . $optionAutoID];
			$EnableQCoreClass->replace('value', $_SESSION['option_' . $questionID . '_' . $optionAutoID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}

	$this_fields_list .= 'option_' . $questionID . '_' . $optionAutoID . '|';
	$EnableQCoreClass->parse('weight' . $questionID, 'WEIGHT', true);
}

if ($isHaveSessionValue == false) {
	$EnableQCoreClass->replace('maxSizeValue', $QtnListArray[$questionID]['maxSize']);
}
else {
	$EnableQCoreClass->replace('maxSizeValue', $QtnListArray[$questionID]['maxSize'] - $totalValue);
}

$check_survey_form_no_con_list .= '		if (!CheckWeight(' . $questionID . ',' . $theBaseID . ',' . $theFirstID . ',' . $theLastID . ',' . $QtnListArray[$questionID]['maxSize'] . ',' . $isHaveOther . ',' . $QtnListArray[$questionID]['isRequired'] . ',\'' . $theInfoQtnName . '\')){return false;}' . "\n" . '';
$QuestionCon = _getquestioncond($questionID, $surveyID);
$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);
$theIsSamePage = (issamepage($questionID, $theBaseID) ? 1 : 0);
$theOriValue = '';
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

$getCheckBoxNoneofAbove = '_getCheckBoxNoneofAbove(' . $theBaseQtnArray['questionType'] . ',' . $theBaseID . ',' . $theBaseIsSelect . ',' . $theIsSamePage . ',' . $QtnListArray[$questionID]['isNeg'] . ',\'' . $theOriValue . '\')';

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
		$check_survey_conditions_list .= '	getAutoWeightNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoWeightNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
}
else {
	$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoWeightSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoWeightSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ');' . "\n" . '';
	}
}

if ($isMobile) {
	$EnableQCoreClass->replace('inputPrompt', 'number');
	$check_survey_conditions_list .= '	changeMaskingSingleQtnBgColor(' . $questionID . ');' . "\n" . '';
}
else {
	$EnableQCoreClass->replace('inputPrompt', 'text');
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

			if ($theVarName[2] == 0) {
				$authInfoList .= '<span class=red>[' . qnospecialchar($theBaseQtnArray['otherText']) . ']</span>自<span class=red>[';
			}
			else {
				$authInfoList .= '<span class=red>[' . qnospecialchar($theCheckBoxListArray[$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';
			}

			if (($aRow['oriValue'] == '0') || ($aRow['oriValue'] == '0.00')) {
				$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
			}
			else {
				$authInfoList .= $aRow['oriValue'] . ']</span>至<span class=red>[';
			}

			if (($aRow['updateValue'] == '0') || ($aRow['updateValue'] == '0.00')) {
				$authInfoList .= $lang['skip_answer'] . ']</span>';
			}
			else {
				$authInfoList .= $aRow['updateValue'] . ']</span>';
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

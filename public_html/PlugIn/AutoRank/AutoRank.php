<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAutoRank.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionRequire = '';
$questionName = '';
$questionNotes = '';
$minOption = $maxOption = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';

	if ($QtnListArray[$questionID]['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['minOption'] . $lang['option'] . ']';
	}

	if ($QtnListArray[$questionID]['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $QtnListArray[$questionID]['maxOption'] . $lang['option'] . ']';
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes .= '[' . $lang['question_type_20'] . ']';
}

$questionNotes .= $minOption;
$questionNotes .= $maxOption;
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

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$theOptionAutoIDList .= $optionAutoID . ',';
	$EnableQCoreClass->replace('optionName', $optionAutoName);
	$EnableQCoreClass->replace('optionNameID', $optionAutoID);
	$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $optionAutoID);
	$this_fields_list .= 'option_' . $questionID . '_' . $optionAutoID . '|';

	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0') {
			$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID . '_' . $optionAutoID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}
	else {
		if (($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '0')) {
			$EnableQCoreClass->replace('value', $_SESSION['option_' . $questionID . '_' . $optionAutoID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$theBaseValueList = '';
if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $theBaseID] != '') {
			if (is_array($R_Row['option_' . $theBaseID])) {
				$theOriValue = implode(',', $R_Row['option_' . $theBaseID]);
				$theSelectNum = count($R_Row['option_' . $theBaseID]);
			}
			else {
				$theOriValue = $R_Row['option_' . $theBaseID];
				$theSelectArray = explode(',', $R_Row['option_' . $theBaseID]);
				$theSelectNum = count($theSelectArray);
			}
		}
	}
	else {
		$theSelectNum = 0;

		if ($_SESSION['option_' . $theBaseID] != '') {
			if (is_array($_SESSION['option_' . $theBaseID])) {
				$theOriValue = implode(',', $_SESSION['option_' . $theBaseID]);
				$theSelectNum = count($_SESSION['option_' . $theBaseID]);
			}
			else {
				$theOriValue = $_SESSION['option_' . $theBaseID];
				$theSelectArray = explode(',', $_SESSION['option_' . $theBaseID]);
				$theSelectNum = count($theSelectArray);
			}
		}
	}

	$theOptionAutoIDList = substr($theOptionAutoIDList, 0, -1);
	$theBaseValueList = $theOriValue;
	$theOriValue = ',' . $theOriValue . ',';

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoRankNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoRankNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ');' . "\n" . '';
	}

	$isSamePage = 0;
	$theBaseIsSelect = 0;
}
else {
	$isSamePage = 1;
	$theSelectNum = 0;
	$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoRankSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoRankSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $theFirstID . ',' . $theLastID . ');' . "\n" . '';
	}
}

$check_survey_form_no_con_list .= '	if (!CheckAutoRank(' . $questionID . ',\'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['isRequired'] . ',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;}' . "\n" . ' ';
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
	$check_form_list .= '		AutoRankUnInput(' . $questionID . ');' . "\n" . '';
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

if ($isMobile) {
	$check_survey_conditions_list .= '	changeMaskingSingleQtnBgColor(' . $questionID . ');' . "\n" . '';
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

			if ($aRow['oriValue'] == '0') {
				$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
			}
			else {
				$authInfoList .= $aRow['oriValue'] . ']</span>至<span class=red>[';
			}

			if ($aRow['updateValue'] == '0') {
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

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
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'mAuto.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAuto.html');
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
	$check_survey_form_no_con_list .= '	if (!CheckAutoNoClick(document.Survey_Form.' . 'option_' . $questionID . ',' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';

	if ($QtnListArray[$questionID]['isSelect'] == '0') {
		if ($QtnListArray[$questionID]['minOption'] != 0) {
			$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['minOption'] . $lang['option'] . ']';
			$check_survey_form_no_con_list .= '	if (!CheckAutoMinClick(document.Survey_Form.' . 'option_' . $questionID . ',' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['maxOption'] != 0) {
			$maxOption = '[' . $lang['maxOption'] . $QtnListArray[$questionID]['maxOption'] . $lang['option'] . ']';
			$check_survey_form_no_con_list .= '	if (!CheckAutoMaxClick(document.Survey_Form.' . 'option_' . $questionID . ',' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		}
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes .= '[' . $lang['question_type_17'] . ']';
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
if (empty($theBaseQtnArray) && ($QtnListArray[$questionID]['isRequired'] == '1')) {
	$check_survey_form_no_con_list .= '	$.notification(\'' . $theInfoQtnName . $lang['base_qtn_no_exist'] . '\');return false;' . "\n" . '';
}

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
	$check_form_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
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

$optionAutoArray = array();

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
}

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

if ($QtnListArray[$questionID]['isCheckType'] == 1) {
	$optionAutoArray[99999] = qshowquotechar($QtnListArray[$questionID]['allowType']);
}

$theOptionAutoIDList = '';
$theOptionOdNum = 0;

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$theOptionAutoIDList .= $optionAutoID . ',';
	$EnableQCoreClass->replace('baseID', $theBaseID);
	$EnableQCoreClass->replace('questionID', $questionID);
	$EnableQCoreClass->replace('optionValue', $optionAutoID);
	$EnableQCoreClass->replace('theOptionOdNum', $theOptionOdNum);
	$theOptionOdNum++;

	if ($QtnListArray[$questionID]['isSelect'] == 1) {
		$EnableQCoreClass->replace('isRadio', 'radio');
		$EnableQCoreClass->replace('selEventName', 'selRadioCheckRows');
		$EnableQCoreClass->replace('optionName', 'option_' . $questionID);

		if (isradioselect($optionAutoID, $questionID)) {
			$EnableQCoreClass->replace('isCheck', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isCheck', '');
		}

		$EnableQCoreClass->replace('onChangeEvent', 'onclick="Check_Survey_Conditions();"');
	}
	else {
		$EnableQCoreClass->replace('isRadio', 'checkbox');
		$EnableQCoreClass->replace('selEventName', 'selCheckBoxCheckRows');
		$EnableQCoreClass->replace('optionName', 'option_' . $questionID . '[]');

		if (ischeckboxselect($optionAutoID, $questionID)) {
			$EnableQCoreClass->replace('isCheck', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isCheck', '');
		}

		$EnableQCoreClass->replace('onChangeEvent', 'onclick="Check_Survey_Conditions();"');
	}

	$EnableQCoreClass->replace('optionText', $optionAutoName);
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
	$theOptionAutoIDList = substr($theOptionAutoIDList, 0, -1);
	$theOriValue = ',' . $theOriValue . ',';

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoOptionNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $QtnListArray[$questionID]['isCheckType'] . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoOptionNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $QtnListArray[$questionID]['isCheckType'] . ');' . "\n" . '';
	}
}
else {
	$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$check_survey_conditions_list .= '	getAutoOptionSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $QtnListArray[$questionID]['isCheckType'] . ',' . $theBaseQtnArray['isHaveOther'] . ');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getAutoOptionSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $QtnListArray[$questionID]['isCheckType'] . ');' . "\n" . '';
	}
}

if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($QtnListArray[$questionID]['isSelect'] != 1)) {
	$theOptionOrderID = count($optionAutoArray) - 1;
	$check_survey_conditions_list .= '	theExcludeItem(document.Survey_Form.option_' . $questionID . ',' . $theOptionOrderID . ',0);' . "\n" . '';
}

if ($isMobile) {
	$check_survey_conditions_list .= '	changeMaskingAutoBgColor(' . $questionID . ');' . "\n" . '';
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
			$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

			if ($QtnListArray[$questionID]['isSelect'] == 1) {
				switch ($aRow['oriValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				case '0':
					$authInfoList .= qnospecialchar($theBaseQtnArray['otherText']);
					break;

				case '99999':
					$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']);
					break;

				default:
					$authInfoList .= qnospecialchar($theCheckBoxListArray[$aRow['oriValue']]['optionName']);
					break;
				}

				$authInfoList .= ']</span>至<span class=red>[';

				switch ($aRow['updateValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				case '0':
					$authInfoList .= qnospecialchar($theBaseQtnArray['otherText']);
					break;

				case '99999':
					$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']);
					break;

				default:
					$authInfoList .= qnospecialchar($theCheckBoxListArray[$aRow['updateValue']]['optionName']);
					break;
				}

				$authInfoList .= ']</span>';
			}
			else {
				switch ($aRow['oriValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				case '99999':
					$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']);
					break;

				default:
					$theOriValue = explode(',', $aRow['oriValue']);
					$theOriValueList = '';

					foreach ($theOriValue as $thisOriValue) {
						if ($thisOriValue == '0') {
							$theOriValueList .= qnospecialchar($theBaseQtnArray['otherText']) . ',';
						}
						else {
							$theOriValueList .= qnospecialchar($theCheckBoxListArray[$thisOriValue]['optionName']) . ',';
						}
					}

					$authInfoList .= substr($theOriValueList, 0, -1);
					break;
				}

				$authInfoList .= ']</span>至<span class=red>[';

				switch ($aRow['updateValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				case '99999':
					$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']);
					break;

				default:
					$theUpdateValue = explode(',', $aRow['updateValue']);
					$thisUpdateValueList = '';

					foreach ($theUpdateValue as $thisUpdateValue) {
						if ($thisUpdateValue == '0') {
							$thisUpdateValueList .= qnospecialchar($theBaseQtnArray['otherText']) . ',';
						}
						else {
							$thisUpdateValueList .= qnospecialchar($theCheckBoxListArray[$thisUpdateValue]['optionName']) . ',';
						}
					}

					$authInfoList .= substr($thisUpdateValueList, 0, -1);
					break;
				}

				$authInfoList .= ']</span>';
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

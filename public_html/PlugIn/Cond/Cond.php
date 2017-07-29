<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$this_fields_list .= 'option_' . $questionID . '|';
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCond.html');
$questionRequire = '';
$questionName = '';
$questionNotes = '';
$minOption = $maxOption = '';
$check_survey_form_no_con_list = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
	$check_survey_form_no_con_list .= '	if (!CheckListNoSelect(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';

	if ($QtnListArray[$questionID]['isSelect'] == '1') {
		if ($QtnListArray[$questionID]['minOption'] != 0) {
			$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['minOption'] . $lang['option'] . ']';
		}

		if ($QtnListArray[$questionID]['maxOption'] != 0) {
			$maxOption = '[' . $lang['maxOption'] . $QtnListArray[$questionID]['maxOption'] . $lang['option'] . ']';
		}

		if ($QtnListArray[$questionID]['minOption'] != 0) {
			$check_survey_form_no_con_list .= '	if (!CheckListMinSelect(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['maxOption'] != 0) {
			$check_survey_form_no_con_list .= '	if (!CheckListMaxSelect(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		}
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes .= '[' . $lang['question_type_18'] . ']';
}

$questionNotes .= $minOption;
$questionNotes .= $maxOption;
$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
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
	$check_form_list .= '	ListUnSelect(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
}

if ($QtnListArray[$questionID]['isSelect'] == '1') {
	$optionSelect = '<select name="option_' . $questionID . '[]" id="option_' . $questionID . '" size=8 multiple></select>';
}
else {
	$optionSelect = '<select name="option_' . $questionID . '" id="option_' . $questionID . '" onchange="Check_Survey_Conditions();"></select>';
}

$EnableQCoreClass->replace('optionSelect', $optionSelect);
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theRadioListArray = $RadioListArray[$theBaseQtnArray['questionID']];
if (empty($theBaseQtnArray) && ($QtnListArray[$questionID]['isRequired'] == '1')) {
	$check_survey_form_no_con_list .= '	$.notification(\'' . $theInfoQtnName . $lang['base_qtn_no_exist'] . '\');return false;' . "\n" . '';
}

$optionAutoArray = array();

foreach ($theRadioListArray as $question_radioID => $theQuestionArray) {
	$optionAutoArray[$question_radioID] = $theQuestionArray['optionName'];
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionAutoArray[0] = $theBaseQtnArray['otherText'];
}

$theOptionList = '';

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$theOptionList .= $optionAutoID . '||||';
	$RelSQL = ' SELECT a.sonID,b.optionName FROM ' . CONDREL_TABLE . ' a,' . QUESTION_YESNO_TABLE . ' b WHERE a.fatherID =\'' . $optionAutoID . '\' AND a.questionID=\'' . $questionID . '\' AND a.sonID = b.question_yesnoID ORDER BY a.sonID ASC ';
	$RelResult = $DB->query($RelSQL);
	$theOptionIDName = array();

	while ($RelRow = $DB->queryArray($RelResult)) {
		$theOptionIDName[$RelRow['sonID']] = addslashes($RelRow['optionName']);
	}

	$theOptionIDList = '';

	foreach ($theOptionIDName as $theOptionID => $theOptionName) {
		$theOptionIDList .= $theOptionID . ',';
	}

	$theOptionList .= substr($theOptionIDList, 0, -1) . '&&&&';
	$theOptionNameList = '';

	foreach ($theOptionIDName as $theOptionID => $theOptionName) {
		$theOptionNameList .= $theOptionName . '####';
	}

	$theOptionList .= substr($theOptionNameList, 0, -4) . '****';
}

$theOptionList = substr($theOptionList, 0, -4);
$theOriValue = '';

if ($isModiDataFlag == 1) {
	if ($R_Row['option_' . $questionID] != '') {
		if (is_array($R_Row['option_' . $questionID])) {
			$theOriValue = implode(',', $R_Row['option_' . $questionID]);
		}
		else {
			$theOriValue = $R_Row['option_' . $questionID];
		}
	}
}
else if ($_SESSION['option_' . $questionID] != '') {
	if (is_array($_SESSION['option_' . $questionID])) {
		$theOriValue = implode(',', $_SESSION['option_' . $questionID]);
	}
	else {
		$theOriValue = $_SESSION['option_' . $questionID];
	}
}

if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
	if ($isModiDataFlag == 1) {
		$check_survey_conditions_list .= '	getCondOptionNoSamePage(\'' . $R_Row['option_' . $theBaseID] . '\',' . $questionID . ',\'' . $theOriValue . '\',\'' . $theOptionList . '\');' . "\n" . '';
	}
	else {
		$check_survey_conditions_list .= '	getCondOptionNoSamePage(\'' . $_SESSION['option_' . $theBaseID] . '\',' . $questionID . ',\'' . $theOriValue . '\',\'' . $theOptionList . '\');' . "\n" . '';
	}
}
else {
	$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);
	$check_survey_conditions_list .= '	getCondOptionSamePage(' . $theBaseID . ',' . $questionID . ',' . $theBaseIsSelect . ',\'' . $theOriValue . '\',\'' . $theOptionList . '\');' . "\n" . '';
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

			if ($QtnListArray[$questionID]['isSelect'] == 0) {
				switch ($aRow['oriValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$authInfoList .= qnospecialchar($YesNoListArray[$questionID][$aRow['oriValue']]['optionName']);
					break;
				}

				$authInfoList .= ']</span>至<span class=red>[';

				switch ($aRow['updateValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$authInfoList .= qnospecialchar($YesNoListArray[$questionID][$aRow['updateValue']]['optionName']);
					break;
				}

				$authInfoList .= ']</span>';
			}
			else {
				switch ($aRow['oriValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$theOriValue = explode(',', $aRow['oriValue']);
					$theOriValueList = '';

					foreach ($theOriValue as $thisOriValue) {
						$theOriValueList .= qnospecialchar($YesNoListArray[$questionID][$thisOriValue]['optionName']) . ',';
					}

					$authInfoList .= substr($theOriValueList, 0, -1);
					break;
				}

				$authInfoList .= ']</span>至<span class=red>[';

				switch ($aRow['updateValue']) {
				case '':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$theUpdateValue = explode(',', $aRow['updateValue']);
					$thisUpdateValueList = '';

					foreach ($theUpdateValue as $thisUpdateValue) {
						$thisUpdateValueList .= qnospecialchar($YesNoListArray[$questionID][$thisUpdateValue]['optionName']) . ',';
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

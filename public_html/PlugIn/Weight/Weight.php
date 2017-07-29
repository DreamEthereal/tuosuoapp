<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uWeight.html');
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
	$questionNotes = '[' . $lang['question_type_16'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$check_survey_form_no_con_list = '';

if ($QtnListArray[$questionID]['isSelect'] == 2) {
	$theBaseID = $QtnListArray[$questionID]['baseID'];
	$theBaseQtnArray = $QtnListArray[$theBaseID];
	if (empty($theBaseQtnArray) && ($QtnListArray[$questionID]['isRequired'] == '1')) {
		$check_survey_form_no_con_list .= '	$.notification(\'' . $theInfoQtnName . $lang['base_qtn_no_exist'] . '\');return false;' . "\n" . '';
	}
}

$theFirstArray = array_slice($RankListArray[$questionID], 0, 1);
$minOptionNum = $theFirstArray[0]['question_rankID'];
$theLastArray = array_slice($RankListArray[$questionID], -1, 1);
$maxOptionNum = $theLastArray[0]['question_rankID'];
$optionTotalNum = count($RankListArray[$questionID]);
$remove_value_list = '';
$isHaveSessionValue = false;
$totalValue = 0;
$theRankListArray = array();

if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
	if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
		$theRandListArray = php_array_rand($RankListArray[$questionID], $optionTotalNum);

		foreach ($theRandListArray as $theRandRankID) {
			$theRankListArray[$theRandRankID] = $RankListArray[$questionID][$theRandRankID];
		}
	}
	else {
		$theRandIDArray = array_slice($RankListArray[$questionID], 0, $optionTotalNum - 1);
		$theRandOptionIDArray = array();

		foreach ($theRandIDArray as $theRandID => $theOptionIDArray) {
			$theRandOptionIDArray[$theOptionIDArray['question_rankID']] = $theOptionIDArray['question_rankID'];
		}

		$theRandListArray = php_array_rand($theRandOptionIDArray, $optionTotalNum - 1);

		foreach ($theRandListArray as $theRandRadioID) {
			$theRankListArray[$theRandRadioID] = $RankListArray[$questionID][$theRandRadioID];
		}

		$theLastArray = array_slice($RankListArray[$questionID], -1, 1);
		$theRankListArray[$theLastArray[0]['question_rankID']] = $theLastArray[0];
	}
}
else {
	$theRankListArray = $RankListArray[$questionID];
}

if ($QtnListArray[$questionID]['isSelect'] == 2) {
	if (!issamepage($questionID, $QtnListArray[$questionID]['baseID'])) {
		if ($isModiDataFlag == 1) {
			if ($R_Row['option_' . $QtnListArray[$questionID]['baseID']] != '') {
				$maxSizeValue = ($R_Row['option_' . $QtnListArray[$questionID]['baseID']] == '' ? 0 : $R_Row['option_' . $QtnListArray[$questionID]['baseID']]);
			}
			else {
				$maxSizeValue = 0;
			}
		}
		else if ($_SESSION['option_' . $QtnListArray[$questionID]['baseID']] != '') {
			$maxSizeValue = $_SESSION['option_' . $QtnListArray[$questionID]['baseID']];
		}
		else {
			$maxSizeValue = 0;
		}
	}
	else {
		$maxSizeValue = 'document.Survey_Form.option_' . $QtnListArray[$questionID]['baseID'] . '.value';
	}
}
else {
	$maxSizeValue = $QtnListArray[$questionID]['maxSize'];
}

$tmp = 0;
$lastOptionId = $optionTotalNum - 1;

foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
	if ($QtnListArray[$questionID]['isHaveOther'] != '1') {
		$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
	}
	else if ($tmp != $lastOptionId) {
		$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
	}
	else {
		$optionName = qshowquotechar($theQuestionArray['optionName']) . '</span>:&nbsp;&nbsp;<span style="vertical-align:middle"><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

		if ($isModiDataFlag == 1) {
			$optionName .= $R_Row['TextOtherValue_' . $questionID];
		}
		else {
			$optionName .= qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]);
		}

		$optionName .= '"></span>';
		$EnableQCoreClass->replace('optionName', $optionName);
	}

	$EnableQCoreClass->replace('optionNameID', $question_rankID);
	$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_rankID);

	if ($QtnListArray[$questionID]['isSelect'] == 2) {
		if (!issamepage($questionID, $QtnListArray[$questionID]['baseID'])) {
			if ($isModiDataFlag == 1) {
				if ($R_Row['option_' . $QtnListArray[$questionID]['baseID']] != '') {
					$EnableQCoreClass->replace('maxSize', $R_Row['option_' . $QtnListArray[$questionID]['baseID']]);
				}
				else {
					$EnableQCoreClass->replace('maxSize', 0);
				}
			}
			else if ($_SESSION['option_' . $QtnListArray[$questionID]['baseID']] != '') {
				$EnableQCoreClass->replace('maxSize', $_SESSION['option_' . $QtnListArray[$questionID]['baseID']]);
			}
			else {
				$EnableQCoreClass->replace('maxSize', 0);
			}
		}
		else {
			$theTextValue = 'document.Survey_Form.option_' . $QtnListArray[$questionID]['baseID'] . '.value';
			$EnableQCoreClass->replace('maxSize', $theTextValue);
		}
	}
	else {
		$EnableQCoreClass->replace('maxSize', $QtnListArray[$questionID]['maxSize']);
	}

	$EnableQCoreClass->replace('minOptionNum', $minOptionNum);
	$EnableQCoreClass->replace('maxOptionNum', $maxOptionNum);
	$remove_value_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';

	if ($isModiDataFlag == 1) {
		if (($R_Row['option_' . $questionID . '_' . $question_rankID] != '0') && ($R_Row['option_' . $questionID . '_' . $question_rankID] != '0.00')) {
			$isHaveSessionValue = true;
			$totalValue += $R_Row['option_' . $questionID . '_' . $question_rankID];
			$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID . '_' . $question_rankID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}
	else {
		if (($_SESSION['option_' . $questionID . '_' . $question_rankID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] != '0') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] != '0.00')) {
			$isHaveSessionValue = true;
			$totalValue += $_SESSION['option_' . $questionID . '_' . $question_rankID];
			$EnableQCoreClass->replace('value', $_SESSION['option_' . $questionID . '_' . $question_rankID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}

	$this_fields_list .= 'option_' . $questionID . '_' . $question_rankID . '|';
	$theInfoOptName = qnoscriptstring($theQuestionArray['optionName']);
	$check_survey_form_no_con_list .= '	if (!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',0,' . $maxSizeValue . ')){return false;} ' . "\n" . '';
	if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
		$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
		$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '';
		$check_survey_form_no_con_list .= '	}' . "\n" . '';
		$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
	}

	$EnableQCoreClass->parse('weight' . $questionID, 'WEIGHT', true);
	$QtnAssCon = _getqtnasscond($questionID, $question_rankID);

	if ($QtnAssCon != '') {
		$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_weight_' . $question_rankID . '_container").hide();' . "\n" . '';
		$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
		if (($QtnListArray[$questionID]['isHaveOther'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
		}

		$check_survey_conditions_list .= '	} ' . "\n" . '';
		$check_survey_conditions_list .= '	else { ' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_weight_' . $question_rankID . '_container").show();' . "\n" . '	} ' . "\n" . '';
	}

	$tmp++;
}

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
	$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
}

$check_survey_conditions_list .= '	ReCalcSum(' . $questionID . ',' . $minOptionNum . ',' . $maxOptionNum . ',' . $maxSizeValue . '); ' . "\n" . '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$check_survey_form_no_con_list .= '	if (document.Survey_Form.option_' . $questionID . '_total.value != 0 ){' . "\n" . '';
	$check_survey_form_no_con_list .= '		$.notification(\'' . $theInfoQtnName . ' ' . $lang['no_all_weight'] . '\');' . "\n" . '';
	$check_survey_form_no_con_list .= '		return false;' . "\n" . '	}' . "\n" . '';
}
else {
	$check_survey_form_no_con_list .= '	var objValue = document.Survey_Form.option_' . $questionID . '_total.value;' . "\n" . '';
	$check_survey_form_no_con_list .= '	if (objValue != ' . $maxSizeValue . ' && objValue < 0){' . "\n" . '';
	$check_survey_form_no_con_list .= '		$.notification(\'' . $theInfoQtnName . ' ' . $lang['no_all_weight'] . '\');' . "\n" . '';
	$check_survey_form_no_con_list .= '		return false;' . "\n" . '	}' . "\n" . '';
}

if ($isMobile) {
	$EnableQCoreClass->replace('inputPrompt', 'number');
	$check_survey_conditions_list .= '	changeMaskingSingleBgColor(' . $questionID . ');' . "\n" . '';
}
else {
	$EnableQCoreClass->replace('inputPrompt', 'text');
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

			switch ($theVarName[0]) {
			case 'option':
				$authInfoList .= '<span class=red>[' . qnospecialchar($RankListArray[$questionID][$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';
				$flag = 1;
				break;

			case 'TextOtherValue':
				$optionTotalNum = count($RankListArray[$questionID]);
				$tmp = 0;
				$theTextId = 0;

				foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
					$tmp++;

					if ($tmp != $optionTotalNum) {
						continue;
					}
					else {
						$theTextId = $question_rankID;
					}
				}

				$authInfoList .= '<span class=red>[' . qnospecialchar($RankListArray[$questionID][$theTextId]['optionName']) . ']</span>自<span class=red>[';
				$flag = 2;
				break;
			}

			if ($flag == 1) {
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

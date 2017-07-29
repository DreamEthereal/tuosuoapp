<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RangeDetail.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$minOption = $maxOption = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';

	if ($theQtnArray['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $theQtnArray['minOption'] . $lang['option'] . ']';
	}

	if ($theQtnArray['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $theQtnArray['maxOption'] . $lang['option'] . ']';
	}
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_28'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
$optionAutoArray = array();

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

if ($theBaseQtnArray['isHaveOther'] == 1) {
	$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
}

$theTmpArray = $AnswerListArray[$questionID];
$theLastAnswerArray = array_pop($theTmpArray);
$theLastAnswerId = $theLastAnswerArray['question_range_answerID'];
unset($theLastAnswerArray);

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	if ($isHaveViewCoeff == 1) {
		$theOptionID = 'option_' . $questionID . '_' . $optionAutoID;
		$skipValue = 0;

		if ($R_Row[$theOptionID] == '') {
			switch ($QtnListArray[$questionID]['skipMode']) {
			case '1':
			default:
				$theQtnValue = $skipValue = 0;
				break;

			case '2':
				$theQtnValue = $skipValue = $QtnListArray[$questionID]['coeffTotal'];
				break;

			case '3':
				$theQtnValue = '-999';
				break;
			}
		}
		else if ($R_Row[$theOptionID] == $theLastAnswerId) {
			if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
				$theQtnValue = '-999';
			}
			else {
				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'];
					break;

				case '2':
					$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}
		}
		else {
			$theSurveyFields = explode(',', $R_Row[$theOptionID]);
			$thisQtnValue = 0;

			foreach ($theSurveyFields as $surveyQtnFields) {
				$thisQtnValue += $AnswerListArray[$questionID][$surveyQtnFields]['optionCoeff'];
			}

			switch ($QtnListArray[$questionID]['coeffMode']) {
			case '1':
			default:
				$theQtnValue = $thisQtnValue;
				break;

			case '2':
				$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
				break;
			}
		}

		if ($theQtnValue != '-999') {
			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			if ($Sur_G_Row['isRateIndex'] == 1) {
				$theQtnValueRate = number_format(($theQtnValue / $QtnListArray[$questionID]['coeffTotal']) * 100, 2) . '%';
				$EnableQCoreClass->replace('optionName', $optionAutoName . '&nbsp;<span class="answer">(合格率：<font color=red>' . $theQtnValueRate . '</font>)</span>');
			}
			else {
				$EnableQCoreClass->replace('optionName', $optionAutoName . '&nbsp;<span class="answer"><font color=red>(' . $theQtnValue . '分)</font></span>');
			}
		}
		else {
			$EnableQCoreClass->replace('optionName', $optionAutoName . '&nbsp;<span class="answer"><font color=red>(不适用)</font></span>');
		}
	}
	else {
		$EnableQCoreClass->replace('optionName', $optionAutoName);
	}

	$question_multi_answerID = $R_Row['option_' . $questionID . '_' . $optionAutoID];

	if ($question_multi_answerID != '') {
		if ($question_multi_answerID == $theLastAnswerId) {
			if ($isHaveViewCoeff == 1) {
				if ($AnswerListArray[$questionID][$question_multi_answerID]['isNA'] == 1) {
					$optionAnswer = qnospecialchar($AnswerListArray[$questionID][$question_multi_answerID]['optionAnswer']) . ' (不适用)';
				}
				else {
					$optionAnswer = qnospecialchar($AnswerListArray[$questionID][$question_multi_answerID]['optionAnswer']) . ' (' . $AnswerListArray[$questionID][$question_multi_answerID]['optionCoeff'] . '分)';
				}
			}
			else {
				$optionAnswer = qnospecialchar($AnswerListArray[$questionID][$question_multi_answerID]['optionAnswer']);
			}

			$EnableQCoreClass->replace('optionAnswer', $optionAnswer);
		}
		else {
			$option_value_array = explode(',', $question_multi_answerID);
			$checkbox_value_list = '';

			foreach ($option_value_array as $option_selected_id) {
				if ($isHaveViewCoeff == 1) {
					$checkbox_value_list .= qnospecialchar($AnswerListArray[$questionID][$option_selected_id]['optionAnswer']) . ' (' . $AnswerListArray[$questionID][$option_selected_id]['optionCoeff'] . '分) | ';
				}
				else {
					$checkbox_value_list .= qnospecialchar($AnswerListArray[$questionID][$option_selected_id]['optionAnswer']) . ' | ';
				}
			}

			$checkbox_value_list = substr($checkbox_value_list, 0, -2);
			$EnableQCoreClass->replace('optionAnswer', $checkbox_value_list);
		}
	}
	else if ($isHaveViewCoeff == 1) {
		if ($QtnListArray[$questionID]['skipMode'] == 3) {
			$EnableQCoreClass->replace('optionAnswer', $lang['skip_answer'] . ' (不适用)');
		}
		else {
			$EnableQCoreClass->replace('optionAnswer', $lang['skip_answer'] . ' (' . $skipValue . '分)');
		}
	}
	else {
		$EnableQCoreClass->replace('optionAnswer', $lang['skip_answer']);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$EnableQCoreClass->replace('questionName', $questionName);
$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
$haveCondRel = getqtnconsucc($questionID);
if (($haveCondRel != '') && !runcode($haveCondRel)) {
	$check_survey_conditions_list .= '	$("#question_' . $questionID . '").hide();' . "\n" . '';
}

if (($isViewAuthInfo == 1) || ($isAppSurveyData == 1)) {
	if ($isViewAuthInfo == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' ORDER BY b.traceTime DESC ';
	}

	if ($isAppSurveyData == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.evidence,b.reason FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND isAppData =1 ORDER BY b.traceTime DESC ';
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

			if ($aRow['oriValue'] == '') {
				$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
			}
			else {
				$theOriValue = explode(',', $aRow['oriValue']);
				$theOriValueList = '';

				foreach ($theOriValue as $thisOriValue) {
					$theOriValueList .= qnospecialchar($AnswerListArray[$questionID][$thisOriValue]['optionAnswer']) . ',';
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
					$thisUpdateValueList .= qnospecialchar($AnswerListArray[$questionID][$thisUpdateValue]['optionAnswer']) . ',';
				}

				$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
			}

			$authInfoList .= ']</span>';

			if ($isAppSurveyData == 1) {
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
else {
	$EnableQCoreClass->replace('authList', '');
}

?>

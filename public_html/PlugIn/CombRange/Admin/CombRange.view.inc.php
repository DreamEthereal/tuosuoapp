<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'MatrixDetail.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->set_CycBlock('OPTION', 'SUBOPTION', 'suboption' . $questionID);
$EnableQCoreClass->replace('suboption' . $questionID, '');
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ANSWER', 'answer' . $questionID);
$EnableQCoreClass->replace('answer' . $questionID, '');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_26'] . ']';
$Label = array();

foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
	$EnableQCoreClass->replace('optionLabelName', qnospecialchar($theLabelArray['optionLabel']));
	$Label[$question_range_labelID] = $theLabelArray['optionLabel'];
	$EnableQCoreClass->parse('answer' . $questionID, 'ANSWER', true);
}

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

	foreach ($Label as $question_range_labelID => $optionLabel) {
		$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;

		if ($R_Row[$theOptionID] != '0') {
			if ($isHaveViewCoeff == 1) {
				if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
					$EnableQCoreClass->replace('optionAnswer', qnospecialchar($AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionAnswer']) . ' (不适用)');
				}
				else {
					$EnableQCoreClass->replace('optionAnswer', qnospecialchar($AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionAnswer']) . ' (' . $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] . '分)');
				}
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', qnospecialchar($AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionAnswer']));
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

		$EnableQCoreClass->parse('suboption' . $questionID, 'SUBOPTION', true);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('suboption' . $questionID);
}

unset($Label);
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
			$authInfoList .= '<span class=red>[' . qnospecialchar($OptionListArray[$questionID][$theVarName[2]]['optionName']) . ' - ' . qnospecialchar($LabelListArray[$questionID][$theVarName[3]]['optionLabel']) . '</span>自<span class=red>[';

			if ($aRow['oriValue'] == '0') {
				$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
			}
			else {
				$authInfoList .= qnospecialchar($AnswerListArray[$questionID][$aRow['oriValue']]['optionAnswer']) . ']</span>至<span class=red>[';
			}

			if ($aRow['updateValue'] == '0') {
				$authInfoList .= $lang['skip_answer'] . ']</span>';
			}
			else {
				$authInfoList .= qnospecialchar($AnswerListArray[$questionID][$aRow['updateValue']]['optionAnswer']) . ']</span>';
			}

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

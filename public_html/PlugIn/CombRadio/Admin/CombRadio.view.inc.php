<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CombRadioDetail.html');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_24'] . ']';

if ($isHaveViewCoeff == 1) {
	$skipValue = 0;

	if ($R_Row['option_' . $questionID] == 0) {
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
	else if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
		$theQtnValue = '-999';
	}
	else {
		switch ($QtnListArray[$questionID]['coeffMode']) {
		case '1':
		default:
			$theQtnValue = $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'];
			break;

		case '2':
			$theQtnValue = $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
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
			$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;<span class="answer">(合格率：<font color=red>' . $theQtnValueRate . '</font>)</span>');
		}
		else {
			$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;<span class="answer"><font color=red>(' . $theQtnValue . '分)</font></span>');
		}
	}
	else {
		$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;<span class="answer"><font color=red>(不适用)</font></span>');
	}
}
else {
	$EnableQCoreClass->replace('questionName', $questionName);
}

if ($R_Row['option_' . $questionID] != '0') {
	if ($isHaveViewCoeff == 1) {
		if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
			$EnableQCoreClass->replace('optionName', qnospecialchar($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionName']) . ' (不适用)');
		}
		else {
			$EnableQCoreClass->replace('optionName', qnospecialchar($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionName']) . ' (' . $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] . '分)');
		}
	}
	else {
		$EnableQCoreClass->replace('optionName', qnospecialchar($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionName']));
	}
}
else if ($isHaveViewCoeff == 1) {
	if ($QtnListArray[$questionID]['skipMode'] == 3) {
		$EnableQCoreClass->replace('optionName', $lang['skip_answer'] . ' (不适用)');
	}
	else {
		$EnableQCoreClass->replace('optionName', $lang['skip_answer'] . ' (' . $skipValue . '分)');
	}
}
else {
	$EnableQCoreClass->replace('optionName', $lang['skip_answer']);
}

if ($R_Row['TextOtherValue_' . $questionID . '_' . $R_Row['option_' . $questionID]] != '') {
	$encode = mb_detect_encoding($R_Row['TextOtherValue_' . $questionID . '_' . $R_Row['option_' . $questionID]], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

	if ($encode == 'UTF-8') {
		$EnableQCoreClass->replace('textOtherValue', iconv('UTF-8', 'GBK', $R_Row['TextOtherValue_' . $questionID . '_' . $R_Row['option_' . $questionID]]));
	}
	else {
		$EnableQCoreClass->replace('textOtherValue', $R_Row['TextOtherValue_' . $questionID . '_' . $R_Row['option_' . $questionID]]);
	}
}
else {
	$EnableQCoreClass->replace('textOtherValue', $lang['skip_answer']);
}

if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isHaveText'] != 1) {
	$EnableQCoreClass->replace('isHaveText', 'none');
}
else {
	$EnableQCoreClass->replace('isHaveText', '');
}

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

			if ($theVarName[0] == 'option') {
				$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

				switch ($aRow['oriValue']) {
				case '0':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$authInfoList .= qnospecialchar($RadioListArray[$questionID][$aRow['oriValue']]['optionName']);
					break;
				}

				$authInfoList .= ']</span>至<span class=red>[';

				switch ($aRow['updateValue']) {
				case '0':
					$authInfoList .= $lang['skip_answer'];
					break;

				default:
					$authInfoList .= qnospecialchar($RadioListArray[$questionID][$aRow['updateValue']]['optionName']);
					break;
				}

				$authInfoList .= ']</span>';
			}
			else {
				$authInfoList .= '<span class=red>[' . qnospecialchar($RadioListArray[$questionID][$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';

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

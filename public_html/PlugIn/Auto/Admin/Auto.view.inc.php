<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 1) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'AutoDetail.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxDetail.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('otherText', '');
	$EnableQCoreClass->replace('textOtherValue', '');
}

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
$questionName .= '[' . $lang['question_type_17'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

if ($isHaveViewCoeff == 1) {
	$skipValue = 0;

	if ($R_Row['option_' . $questionID] == '') {
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
	else if ($QtnListArray[$questionID]['isSelect'] == 1) {
		if ($R_Row['option_' . $questionID] == '99999') {
			if ($QtnListArray[$questionID]['isNA'] == 1) {
				$theQtnValue = '-999';
			}
			else {
				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['negCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}
		}
		else {
			switch ($QtnListArray[$questionID]['coeffMode']) {
			case '1':
			default:
				switch ($R_Row['option_' . $questionID]) {
				case '0':
					$theQtnValue = $theBaseQtnArray['optionCoeff'];
					break;

				default:
					$theQtnValue = $theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionCoeff'];
					break;
				}

				break;

			case '2':
				switch ($R_Row['option_' . $questionID]) {
				case '0':
					$theQtnValue = $theBaseQtnArray['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;

				default:
					$theQtnValue = $theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}

				break;
			}
		}
	}
	else if ($R_Row['option_' . $questionID] == '99999') {
		if ($QtnListArray[$questionID]['isNA'] == 1) {
			$theQtnValue = '-999';
		}
		else {
			switch ($QtnListArray[$questionID]['coeffMode']) {
			case '1':
			default:
				$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
				break;

			case '2':
				$theQtnValue = $QtnListArray[$questionID]['negCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
				break;
			}
		}
	}
	else {
		$thisQtnValue = 0;
		$theSurveyFields = explode(',', $R_Row['option_' . $questionID]);

		foreach ($theSurveyFields as $surveyQtnFields) {
			switch ($surveyQtnFields) {
			case '0':
				$thisQtnValue += $theBaseQtnArray['optionCoeff'];
				break;

			default:
				$thisQtnValue += $theCheckBoxListArray[$surveyQtnFields]['optionCoeff'];
				break;
			}
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

if ($theQtnArray['isSelect'] == 1) {
	switch ($R_Row['option_' . $questionID]) {
	case '':
		if ($isHaveViewCoeff == 1) {
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

		break;

	case '0':
		if ($isHaveViewCoeff == 1) {
			$theOtherValue = qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $theBaseQtnArray['optionCoeff'] . '分) ';
		}
		else {
			$theOtherValue = qnospecialchar($theBaseQtnArray['otherText']);
		}

		if ($theQtnArray['isNeg'] == 1) {
		}
		else {
			$theOtherValue .= '(' . $R_Row['TextOtherValue_' . $theBaseQtnArray['questionID']] . ')';
		}

		$EnableQCoreClass->replace('optionName', $theOtherValue);
		break;

	case '99999':
		$negText = ($QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']));

		if ($isHaveViewCoeff == 1) {
			if ($QtnListArray[$questionID]['isNA'] == 1) {
				$EnableQCoreClass->replace('optionName', $negText . ' (不适用)');
			}
			else {
				$EnableQCoreClass->replace('optionName', $negText . ' (' . $theQtnArray['negCoeff'] . '分)');
			}
		}
		else {
			$EnableQCoreClass->replace('optionName', $negText);
		}

		break;

	default:
		if ($isHaveViewCoeff == 1) {
			$EnableQCoreClass->replace('optionName', qnospecialchar($theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionName']) . ' (' . $theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionCoeff'] . '分)');
		}
		else {
			$EnableQCoreClass->replace('optionName', qnospecialchar($theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionName']));
		}

		break;
	}
}
else {
	$option_value_array = explode(',', $R_Row['option_' . $questionID]);

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

		if (in_array($question_checkboxID, $option_value_array)) {
			if ($isHaveViewCoeff == 1) {
				$EnableQCoreClass->replace('optionAnswer', '1' . ' (' . $theQuestionArray['optionCoeff'] . '分)');
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', '1');
			}
		}
		else {
			$EnableQCoreClass->replace('optionAnswer', '0');
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	if ($theBaseQtnArray['isHaveOther'] == 1) {
		$EnableQCoreClass->replace('optionName', qnospecialchar($theBaseQtnArray['otherText']));

		if ($R_Row['option_' . $questionID] == '') {
			$EnableQCoreClass->replace('optionAnswer', '0');
		}
		else if (in_array(0, $option_value_array)) {
			if ($isHaveViewCoeff == 1) {
				$EnableQCoreClass->replace('optionAnswer', '1' . ' (' . $theBaseQtnArray['optionCoeff'] . '分)');
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', '1');
			}
		}
		else {
			$EnableQCoreClass->replace('optionAnswer', '0');
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	if ($theQtnArray['isCheckType'] == '1') {
		$negText = ($QtnListArray[$questionID]['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($QtnListArray[$questionID]['allowType']));
		$EnableQCoreClass->replace('optionName', $negText);

		if ($R_Row['option_' . $questionID] == '') {
			$EnableQCoreClass->replace('optionAnswer', '0');
		}
		else if (in_array(99999, $option_value_array)) {
			if ($isHaveViewCoeff == 1) {
				if ($QtnListArray[$questionID]['isNA'] == 1) {
					$EnableQCoreClass->replace('optionAnswer', '1 (不适用)');
				}
				else {
					$EnableQCoreClass->replace('optionAnswer', '1 (' . $theQtnArray['negCoeff'] . '分)');
				}
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', '1');
			}
		}
		else {
			$EnableQCoreClass->replace('optionAnswer', '0');
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}
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

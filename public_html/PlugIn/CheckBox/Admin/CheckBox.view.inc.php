<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CheckBoxDetail.html');
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
$questionName .= '[' . $lang['question_type_3'] . ']';
$questionName .= $minOption;
$questionName .= $maxOption;

if ($isHaveViewCoeff == 1) {
	$isSkip = false;

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		if (($R_Row['option_' . $questionID] == '') && ($R_Row['TextOtherValue_' . $questionID] == '')) {
			$isSkip = true;
		}
	}
	else if ($R_Row['option_' . $questionID] == '') {
		$isSkip = true;
	}

	if ($isSkip == true) {
		switch ($QtnListArray[$questionID]['skipMode']) {
		case '1':
		default:
			$theQtnValue = 0;
			break;

		case '2':
			$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			break;

		case '3':
			$theQtnValue = '-999';
			break;
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
				$thisQtnValue += $QtnListArray[$questionID]['optionCoeff'];
				break;

			default:
				$thisQtnValue += $CheckBoxListArray[$questionID][$surveyQtnFields]['optionCoeff'];
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

$optionAutoArray = array();

foreach ($CheckBoxListArray[$questionID] as $question_checkboxID => $theQuestionArray) {
	$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
}

$option_value_array = explode(',', $R_Row['option_' . $questionID]);

if ($theQtnArray['isHaveOther'] == 1) {
	$optionAutoArray[0] = qnospecialchar($theQtnArray['otherText']);

	if ($R_Row['TextOtherValue_' . $questionID] != '') {
		$EnableQCoreClass->replace('isHaveOther', '');
		$EnableQCoreClass->replace('otherText', qnospecialchar($theQtnArray['otherText']));
		$encode = mb_detect_encoding($R_Row['TextOtherValue_' . $questionID], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

		if ($encode == 'UTF-8') {
			$EnableQCoreClass->replace('textOtherValue', iconv('UTF-8', 'GBK', $R_Row['TextOtherValue_' . $questionID]));
		}
		else {
			$EnableQCoreClass->replace('textOtherValue', $R_Row['TextOtherValue_' . $questionID]);
		}
	}
	else {
		$EnableQCoreClass->replace('isHaveOther', 'none');
		$EnableQCoreClass->replace('otherText', '');
		$EnableQCoreClass->replace('textOtherValue', '');
	}
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('otherText', '');
	$EnableQCoreClass->replace('textOtherValue', '');
}

if ($theQtnArray['isNeg'] == 1) {
	$optionAutoArray[99999] = $theQtnArray['allowType'] == '' ? $lang['neg_text'] : qnospecialchar($theQtnArray['allowType']);
}

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$EnableQCoreClass->replace('optionName', $optionAutoName);

	if ($optionAutoID == 0) {
		if (($R_Row['TextOtherValue_' . $questionID] != '') && in_array($optionAutoID, $option_value_array)) {
			if ($isHaveViewCoeff == 1) {
				$EnableQCoreClass->replace('optionAnswer', '1 (' . $theQtnArray['optionCoeff'] . '分)');
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', '1');
			}
		}
		else {
			$EnableQCoreClass->replace('optionAnswer', '0');
		}
	}
	else if (in_array($optionAutoID, $option_value_array)) {
		if ($optionAutoID == '99999') {
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
		else if ($isHaveViewCoeff == 1) {
			$EnableQCoreClass->replace('optionAnswer', '1 (' . $CheckBoxListArray[$questionID][$optionAutoID]['optionCoeff'] . '分)');
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

				if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
					if ($aRow['oriValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else if ($aRow['oriValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>至<span class=red>[';
					}
					else {
						$theOriValue = explode(',', $aRow['oriValue']);
						$theOriValueList = '';

						foreach ($theOriValue as $thisOriValue) {
							switch ($thisOriValue) {
							case '0':
								$theOriValueList .= qnospecialchar($QtnListArray[$questionID]['otherText']) . ',';
								break;

							default:
								$theOriValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisOriValue]['optionName']) . ',';
								break;
							}
						}

						$authInfoList .= substr($theOriValueList, 0, -1) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else if ($aRow['updateValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>';
					}
					else {
						$theUpdateValue = explode(',', $aRow['updateValue']);
						$thisUpdateValueList = '';

						foreach ($theUpdateValue as $thisUpdateValue) {
							switch ($thisOriValue) {
							case '0':
								$thisUpdateValueList .= qnospecialchar($QtnListArray[$questionID]['otherText']) . ',';
								break;

							default:
								$thisUpdateValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisUpdateValue]['optionName']) . ',';
								break;
							}
						}

						$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
					}
				}
				else {
					if ($aRow['oriValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else if ($aRow['oriValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>至<span class=red>[';
					}
					else {
						$theOriValue = explode(',', $aRow['oriValue']);
						$theOriValueList = '';

						foreach ($theOriValue as $thisOriValue) {
							$theOriValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisOriValue]['optionName']) . ',';
						}

						$authInfoList .= substr($theOriValueList, 0, -1) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else if ($aRow['updateValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>';
					}
					else {
						$theUpdateValue = explode(',', $aRow['updateValue']);
						$thisUpdateValueList = '';

						foreach ($theUpdateValue as $thisUpdateValue) {
							$thisUpdateValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisUpdateValue]['optionName']) . ',';
						}

						$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
					}
				}
			}
			else {
				$authInfoList .= '<span class=red>[' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ']</span>自<span class=red>[';

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

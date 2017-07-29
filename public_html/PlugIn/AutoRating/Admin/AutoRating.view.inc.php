<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'ListDetail.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_21'] . ']';
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

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	if ($isHaveViewCoeff == 1) {
		$isSkip = false;
		$skipValue = 0;
		$theOptionID = 'option_' . $questionID . '_' . $optionAutoID;

		switch ($QtnListArray[$questionID]['isSelect']) {
		case '0':
		case '2':
			if ($R_Row[$theOptionID] == 0) {
				$isSkip = true;
			}

			break;

		case '1':
			if (($R_Row[$theOptionID] == 0) || ($R_Row[$theOptionID] == '0.00')) {
				$isSkip = true;
			}

			break;
		}

		if ($isSkip == true) {
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
		else {
			if (($QtnListArray[$questionID]['isSelect'] == 0) && ($R_Row[$theOptionID] == 99)) {
				$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
			}
			else {
				$theQtnValue = $QtnListArray[$questionID]['weight'] * $R_Row[$theOptionID];
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

	switch ($theQtnArray['isSelect']) {
	case '0':
		switch ($R_Row['option_' . $questionID . '_' . $optionAutoID]) {
		case '0':
			if ($isHaveViewCoeff == 1) {
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

			break;

		case '99':
			if ($isHaveViewCoeff == 1) {
				$EnableQCoreClass->replace('optionAnswer', $lang['rating_unknow'] . ' (' . $theQtnArray['negCoeff'] . '分)');
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', $lang['rating_unknow']);
			}

			break;

		default:
			$theWeight = $R_Row['option_' . $questionID . '_' . $optionAutoID] * $theQtnArray['weight'];

			if ($isHaveViewCoeff == 1) {
				$theWeight .= ' (' . $theWeight . '分) ';
			}

			if ($theWeight < $theQtnArray['maxOption']) {
				$encode = mb_detect_encoding($R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

				if ($encode == 'UTF-8') {
					$EnableQCoreClass->replace('optionAnswer', $theWeight . ' (理由：' . iconv('UTF-8', 'GBK', $R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID]) . ')');
				}
				else {
					$EnableQCoreClass->replace('optionAnswer', $theWeight . ' (理由：' . $R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID] . ')');
				}
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', $theWeight);
			}

			break;
		}

		break;

	case '1':
		if (($R_Row['option_' . $questionID . '_' . $optionAutoID] != 0) && ($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0.00')) {
			$theWeight = $R_Row['option_' . $questionID . '_' . $optionAutoID];

			if ($isHaveViewCoeff == 1) {
				$theWeight .= ' (' . $theWeight . '分) ';
			}

			if ($theWeight < $theQtnArray['maxOption']) {
				$encode = mb_detect_encoding($R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

				if ($encode == 'UTF-8') {
					$EnableQCoreClass->replace('optionAnswer', $theWeight . ' (理由：' . iconv('UTF-8', 'GBK', $R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID]) . ')');
				}
				else {
					$EnableQCoreClass->replace('optionAnswer', $theWeight . ' (理由：' . $R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID] . ')');
				}
			}
			else {
				$EnableQCoreClass->replace('optionAnswer', $theWeight);
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

		break;

	case '2':
		if ($R_Row['option_' . $questionID . '_' . $optionAutoID] != 0) {
			$theWeight = $R_Row['option_' . $questionID . '_' . $optionAutoID];

			if ($isHaveViewCoeff == 1) {
				$theWeight .= ' (' . $theWeight . '分) ';
			}

			$EnableQCoreClass->replace('optionAnswer', $theWeight);
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

		break;
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

			switch ($theVarName[0]) {
			case 'option':
				if ($theVarName[2] == 0) {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theBaseQtnArray['otherText']) . ']</span>自<span class=red>[';
				}
				else {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theCheckBoxListArray[$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';
				}

				$flag = 1;
				break;

			case 'TextOtherValue':
				if ($theVarName[2] == 0) {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theBaseQtnArray['otherText']) . ' - 理由]</span>自<span class=red>[';
				}
				else {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theCheckBoxListArray[$theVarName[2]]['optionName']) . ' - 理由]</span>自<span class=red>[';
				}

				$flag = 2;
				break;
			}

			if ($flag == 1) {
				switch ($QtnListArray[$questionID]['isSelect']) {
				case '0':
					if ($aRow['oriValue'] == '0') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else if ($aRow['oriValue'] == '99') {
						$authInfoList .= $lang['rating_unknow'] . ']</span>至<span class=red>[';
					}
					else {
						$authInfoList .= ($aRow['oriValue'] * $QtnListArray[$questionID]['weight']) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '0') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else if ($aRow['updateValue'] == '99') {
						$authInfoList .= $lang['rating_unknow'] . ']</span>';
					}
					else {
						$authInfoList .= ($aRow['updateValue'] * $QtnListArray[$questionID]['weight']) . ']</span>';
					}

					break;

				case '1':
					if (($aRow['oriValue'] == '0.00') || ($aRow['oriValue'] == '0')) {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else {
						$authInfoList .= $aRow['oriValue'] . ']</span>至<span class=red>[';
					}

					if (($aRow['updateValue'] == '0.00') || ($aRow['updateValue'] == '0')) {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else {
						$authInfoList .= $aRow['updateValue'] . ']</span>';
					}

					break;

				case '2':
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

					break;
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

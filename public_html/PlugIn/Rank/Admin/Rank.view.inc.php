<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RankDetail.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionName = '';
$minOption = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';

	if ($theQtnArray['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $theQtnArray['minOption'] . $lang['option'] . ']';
	}
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_10'] . ']';
$questionName .= $minOption;
$EnableQCoreClass->replace('questionName', $questionName);

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qnospecialchar($theQuestionArray['optionName']));

	if ($R_Row['option_' . $questionID . '_' . $question_rankID] != '0') {
		$EnableQCoreClass->replace('optionAnswer', $R_Row['option_' . $questionID . '_' . $question_rankID]);
	}
	else {
		$EnableQCoreClass->replace('optionAnswer', $lang['skip_answer']);
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

if ($theQtnArray['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('otherText', qnospecialchar($theQtnArray['otherText']));

	if ($R_Row['TextOtherValue_' . $questionID] != '') {
		$encode = mb_detect_encoding($R_Row['TextOtherValue_' . $questionID], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

		if ($encode == 'UTF-8') {
			$EnableQCoreClass->replace('textOtherValue', '(' . iconv('UTF-8', 'GBK', $R_Row['TextOtherValue_' . $questionID]) . ')');
		}
		else {
			$EnableQCoreClass->replace('textOtherValue', '(' . $R_Row['TextOtherValue_' . $questionID] . ')');
		}

		$EnableQCoreClass->replace('optionAnswer_0', $R_Row['option_' . $questionID . '_0']);
	}
	else {
		$EnableQCoreClass->replace('textOtherValue', '');
		$EnableQCoreClass->replace('optionAnswer_0', $lang['skip_answer']);
	}
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('otherText', '');
	$EnableQCoreClass->replace('textOtherValue', '');
	$EnableQCoreClass->replace('optionAnswer_0', '');
}

if ($theQtnArray['isHaveWhy'] == '1') {
	$EnableQCoreClass->replace('isHaveWhy', '');
	$encode = mb_detect_encoding($R_Row['TextWhyValue_' . $questionID], array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));

	if ($encode == 'UTF-8') {
		$EnableQCoreClass->replace('textWhyValue', iconv('UTF-8', 'GBK', $R_Row['TextWhyValue_' . $questionID]));
	}
	else {
		$EnableQCoreClass->replace('textWhyValue', $R_Row['TextWhyValue_' . $questionID]);
	}
}
else {
	$EnableQCoreClass->replace('isHaveWhy', 'none');
	$EnableQCoreClass->replace('textWhyValue', '');
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

			switch ($theVarName[0]) {
			case 'option':
				if ($theVarName[2] == 0) {
					$authInfoList .= '<span class=red>[' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ']</span>自<span class=red>[';
				}
				else {
					$authInfoList .= '<span class=red>[' . qnospecialchar($RankListArray[$questionID][$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';
				}

				$flag = 1;
				break;

			case 'TextWhyValue':
				$authInfoList .= '<span class=red>[该题 - 为什么这么排序]</span>自<span class=red>[';
				$flag = 2;
				break;

			case 'TextOtherValue':
				$authInfoList .= '<span class=red>[' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ']</span>自<span class=red>[';
				$flag = 2;
				break;
			}

			if ($flag == 1) {
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

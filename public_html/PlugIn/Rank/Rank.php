<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uRank.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('otherText', qshowquotechar($QtnListArray[$questionID]['otherText']));
	$EnableQCoreClass->replace('other_OptionID', 'option_' . $questionID . '_0');
	$EnableQCoreClass->replace('questionID', $questionID);
	$this_fields_list .= 'option_' . $questionID . '_0' . '|';
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';

	if ($isModiDataFlag == 1) {
		if ($R_Row['TextOtherValue_' . $questionID] != '') {
			$EnableQCoreClass->replace('TextOtherValue', $R_Row['TextOtherValue_' . $questionID]);
		}
		else {
			$EnableQCoreClass->replace('TextOtherValue', '');
		}

		if ($R_Row['option_' . $questionID . '_0'] != '0') {
			$EnableQCoreClass->replace('other_value', $R_Row['option_' . $questionID . '_0']);
		}
		else {
			$EnableQCoreClass->replace('other_value', '');
		}
	}
	else {
		if ($_SESSION['TextOtherValue_' . $questionID] != '') {
			$EnableQCoreClass->replace('TextOtherValue', qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]));
		}
		else {
			$EnableQCoreClass->replace('TextOtherValue', '');
		}

		if (($_SESSION['option_' . $questionID . '_0'] != '') && ($_SESSION['option_' . $questionID . '_0'] != '0')) {
			$EnableQCoreClass->replace('other_value', $_SESSION['option_' . $questionID . '_0']);
		}
		else {
			$EnableQCoreClass->replace('other_value', '');
		}
	}
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('otherText', qshowquotechar($QtnListArray[$questionID]['otherText']));
	$EnableQCoreClass->replace('other_OptionID', 'option_' . $questionID . '_0');
	$EnableQCoreClass->replace('questionID', $questionID);
	$EnableQCoreClass->replace('TextOtherValue', '');
	$EnableQCoreClass->replace('other_value', '');
	$EnableQCoreClass->replace('other_endScale', 1);
}

if ($QtnListArray[$questionID]['isHaveWhy'] == '1') {
	$EnableQCoreClass->replace('isHaveWhy', '');
	$this_fields_list .= 'TextWhyValue_' . $questionID . '|';

	if ($isModiDataFlag == 1) {
		if ($R_Row['TextWhyValue_' . $questionID] != '') {
			$EnableQCoreClass->replace('TextWhyValue', $R_Row['TextWhyValue_' . $questionID]);
		}
		else {
			$EnableQCoreClass->replace('TextWhyValue', '');
		}
	}
	else if ($_SESSION['TextWhyValue_' . $questionID] != '') {
		$EnableQCoreClass->replace('TextWhyValue', qhtmlspecialchars($_SESSION['TextWhyValue_' . $questionID]));
	}
	else {
		$EnableQCoreClass->replace('TextWhyValue', '');
	}
}
else {
	$EnableQCoreClass->replace('isHaveWhy', 'none');
	$EnableQCoreClass->replace('TextWhyValue', '');
}

$questionRequire = '';
$questionName = '';
$questionNotes = '';
$minOption = $maxOption = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';

	if ($QtnListArray[$questionID]['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['minOption'] . $lang['option'] . ']';
	}

	if ($QtnListArray[$questionID]['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $QtnListArray[$questionID]['maxOption'] . $lang['option'] . ']';
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes .= '[' . $lang['question_type_10'] . ']';
}

$questionNotes .= $minOption;
$questionNotes .= $maxOption;
$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$optionTotalNum = count($RankListArray[$questionID]);
$theRankListArray = array();

if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
	$theRandListArray = php_array_rand($RankListArray[$questionID], $optionTotalNum);

	foreach ($theRandListArray as $theRandRankID) {
		$theRankListArray[$theRandRankID] = $RankListArray[$questionID][$theRandRankID];
	}
}
else {
	$theRankListArray = $RankListArray[$questionID];
}

foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
	$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
	$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_rankID);
	$EnableQCoreClass->replace('rankID', $question_rankID);
	$this_fields_list .= 'option_' . $questionID . '_' . $question_rankID . '|';

	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $questionID . '_' . $question_rankID] != '0') {
			$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID . '_' . $question_rankID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}
	else {
		if (($_SESSION['option_' . $questionID . '_' . $question_rankID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] != '0')) {
			$EnableQCoreClass->replace('value', $_SESSION['option_' . $questionID . '_' . $question_rankID]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$QtnAssCon = _getqtnasscond($questionID, $question_rankID);

	if ($QtnAssCon != '') {
		$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_rank_' . $question_rankID . '_container").hide();' . "\n" . '';
		$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
		$check_survey_conditions_list .= '	} ' . "\n" . '';
		$check_survey_conditions_list .= '	else { ' . "\n" . '';
		$check_survey_conditions_list .= '		$("#range_rank_' . $question_rankID . '_container").show();' . "\n" . '	} ' . "\n" . '';
	}
}

$check_survey_form_no_con_list = '';
$check_survey_form_no_con_list .= '	if (!CheckRank(' . $questionID . ',\'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['isRequired'] . ',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;}' . "\n" . ' ';

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$check_survey_form_no_con_list .= '	if (document.Survey_Form.' . 'option_' . $questionID . '_0' . '.value != \'\' )' . "\n" . '	{' . "\n" . '		if(!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;}' . "\n" . '	}' . "\n" . '';
	$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
}

if (($QtnListArray[$questionID]['isRequired'] == '1') && ($QtnListArray[$questionID]['isHaveWhy'] == '1')) {
	$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.TextWhyValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . $lang['why_your_order'] . '\')){return false;} ' . "\n" . '';
}

if ($isMobile) {
	$check_survey_conditions_list .= '	changeMaskingSingleBgColor(' . $questionID . ');' . "\n" . '';
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
	$check_form_list .= '		RankUnInput(' . $questionID . ');' . "\n" . '';

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$check_form_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

	if ($QtnListArray[$questionID]['isHaveWhy'] == '1') {
		$check_form_list .= '	TextUnInput(document.Survey_Form.TextWhyValue_' . $questionID . ');' . "\n" . '';
	}

	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
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

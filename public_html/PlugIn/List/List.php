<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uList.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
$EnableQCoreClass->replace('list' . $questionID, '');
$questionRequire = '';
$questionName = '';
$questionNotes = '';
$minOption = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';

	if ($QtnListArray[$questionID]['maxSize'] != 0) {
		$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['maxSize'] . $lang['option'] . ']';
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_14'] . ']';
}

$questionNotes .= $minOption;
$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$check_survey_form_no_con_list = '';
$remove_value_list = '';
$theRowsNum = 1;
$i = 1;

for (; $i <= $QtnListArray[$questionID]['rows']; $i++) {
	$EnableQCoreClass->replace('listOrder', $i);

	if ($isMobile) {
		$EnableQCoreClass->replace('length', '20');
	}
	else {
		$EnableQCoreClass->replace('length', $QtnListArray[$questionID]['length']);
	}

	$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $i);

	if ($isMobile) {
		switch ($QtnListArray[$questionID]['isCheckType']) {
		case 4:
		case 8:
		case 9:
			$EnableQCoreClass->replace('inputPrompt', 'number');
			break;

		case 5:
		case 11:
			$EnableQCoreClass->replace('inputPrompt', 'tel');
			break;

		default:
			$EnableQCoreClass->replace('inputPrompt', 'text');
			break;
		}
	}
	else {
		$EnableQCoreClass->replace('inputPrompt', 'text');
	}

	if (($theRowsNum % 2) == 0) {
		$EnableQCoreClass->replace('bgcolor', '#ffffff');
	}
	else {
		$EnableQCoreClass->replace('bgcolor', '#f5f5f5');
	}

	$theRowsNum++;
	$remove_value_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $i . ');' . "\n" . '';

	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $questionID . '_' . $i] != '') {
			$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID . '_' . $i]);
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}
	}
	else if ($_SESSION['option_' . $questionID . '_' . $i] != '') {
		$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION['option_' . $questionID . '_' . $i]));
	}
	else {
		$EnableQCoreClass->replace('value', '');
	}

	$this_fields_list .= 'option_' . $questionID . '_' . $i . '|';

	switch ($QtnListArray[$questionID]['isCheckType']) {
	case '1':
		$check_survey_form_no_con_list .= '	if (!CheckURL(document.Survey_Form.' . 'option_' . $questionID . '_' . $i . ', \'' . $theInfoQtnName . ' - ' . $i . '\')){return false;} ' . "\n" . '';
		break;

	case '2':
		$check_survey_form_no_con_list .= '	if (!CheckStringLength(document.Survey_Form.' . 'option_' . $questionID . '_' . $i . ', \'' . $theInfoQtnName . ' - ' . $i . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		break;

	case '4':
		$check_survey_form_no_con_list .= '	if (!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $i . ', \'' . $theInfoQtnName . ' - ' . $i . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		break;
	}

	$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
}

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$check_survey_form_no_con_list .= '	var listNum =0;' . "\n" . ' ';
	$check_survey_form_no_con_list .= '	for( i= 1;i<=' . $QtnListArray[$questionID]['rows'] . ';i++){ ' . "\n" . ' ';
	$check_survey_form_no_con_list .= '		var obj = eval("document.Survey_Form.' . 'option_' . $questionID . '_' . '" + i );' . "\n" . '';
	$check_survey_form_no_con_list .= '		if(obj.value  != \'\' ){listNum++;} ' . "\n" . ' ';
	$check_survey_form_no_con_list .= '	}' . "\n" . ' ';

	if ($QtnListArray[$questionID]['maxSize'] == 0) {
		$check_survey_form_no_con_list .= '	if (listNum < 1){' . "\n" . ' ';
		$check_survey_form_no_con_list .= '		$.notification(\'' . $theInfoQtnName . $lang['no_less_list'] . '1' . $lang['option'] . '\');' . "\n" . ' ';
		$check_survey_form_no_con_list .= '		document.Survey_Form.' . 'option_' . $questionID . '_1' . '.focus();' . "\n" . ' 		return false;' . "\n" . '	}' . "\n" . '';
	}
	else {
		$check_survey_form_no_con_list .= '	if (listNum < ' . $QtnListArray[$questionID]['maxSize'] . '){' . "\n" . ' ';
		$check_survey_form_no_con_list .= '		$.notification(\'' . $theInfoQtnName . $lang['no_less_list'] . $QtnListArray[$questionID]['maxSize'] . $lang['option'] . '\');' . "\n" . ' ';
		$check_survey_form_no_con_list .= '		document.Survey_Form.' . 'option_' . $questionID . '_1' . '.focus();' . "\n" . ' 		return false;' . "\n" . '	}' . "\n" . '';
	}
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
			$authInfoList .= '<span class=red>[本题 - ' . $theVarName[2] . ']</span>自<span class=red>[';

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
